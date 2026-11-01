<?php

/**
 * A minimal Craft plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2024, leowebguy
 */

namespace leowebguy\simplemailchimp\services;

use craft\base\Component;
use craft\helpers\App;
use DrewM\MailChimp\MailChimp as MC;
use Exception;
use leowebguy\simplemailchimp\models\MailchimpModel;
use leowebguy\simplemailchimp\SimpleMailchimp;

class MailchimpService extends Component
{
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function subscribe(array $data): array
    {
        $settings = SimpleMailchimp::getInstance()?->getSettings();
        if (!$settings instanceof MailchimpModel) {
            return ['success' => false, 'msg' => 'Simple Mailchimp plugin settings not found'];
        }

        if (empty($data['email']) || !is_string($data['email'])) {
            return ['success' => false, 'msg' => "Email can't be empty"];
        }

        $email = trim($data['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'msg' => 'Invalid email format'];
        }

        $isOptIn = false;
        if (is_bool($settings->optIn)) {
            $isOptIn = $settings->optIn;
        } elseif (is_string($settings->optIn)) {
            $parsed = App::parseBooleanEnv($settings->optIn);
            $isOptIn = $parsed ?? (!in_array(strtolower(trim($settings->optIn)), ['0', 'false', 'no', 'off', ''], true));
        } elseif (is_int($settings->optIn)) {
            $isOptIn = $settings->optIn === 1;
        }

        /** @var array<string, mixed> $dataMC */
        $dataMC = [
            'email_address' => $email,
            'status' => $isOptIn ? 'pending' : 'subscribed',
            'double_optin' => $isOptIn,
        ];

        if (!empty($data['name']) && is_string($data['name'])) {
            $name = array_pad(explode(' ', trim($data['name']), 2), 2, '');
            $dataMC['merge_fields'] = [
                'FNAME' => $name[0],
                'LNAME' => $name[1],
            ];
        }

        if (isset($data['merge_fields']) && is_array($data['merge_fields'])) {
            $existing = isset($dataMC['merge_fields']) && is_array($dataMC['merge_fields']) ? $dataMC['merge_fields'] : [];
            $dataMC['merge_fields'] = array_merge($existing, $data['merge_fields']);
        }

        if (!empty($data['tags'])) {
            if (is_array($data['tags'])) {
                $tags = array_values(array_filter(array_map('strval', $data['tags'])));
            } elseif (is_string($data['tags'])) {
                $tags = array_values(array_filter(array_map('trim', explode(',', $data['tags']))));
            } else {
                $tags = [];
            }

            if (!empty($tags)) {
                $dataMC['tags'] = $tags;
            }
        }

        $apiKeyParsed = App::parseEnv($settings->mcApiKey);
        $apiKey = is_string($apiKeyParsed) ? trim($apiKeyParsed) : '';
        if ($apiKey === '') {
            return ['success' => false, 'msg' => 'Mailchimp API key is not configured'];
        }

        $listIdParsed = App::parseEnv($settings->mcListID);
        $listId = is_string($listIdParsed) ? trim($listIdParsed) : '';
        if ($listId === '') {
            return ['success' => false, 'msg' => 'Mailchimp List ID is not configured'];
        }

        try {
            $mailChimp = new MC($apiKey);
            /** @var array<string, mixed>|false $result */
            $result = $mailChimp->post("lists/{$listId}/members", $dataMC);

            if (is_array($result) && isset($result['status']) && is_string($result['status']) && in_array($result['status'], ['subscribed', 'pending'], true)) {
                return [
                    'success' => true,
                    'status' => $result['status'],
                    'msg' => 'Email subscribed successfully',
                    'id' => isset($result['contact_id']) && is_scalar($result['contact_id']) ? (string)$result['contact_id'] : null,
                ];
            }

            $errorMsg = 'Unknown Mailchimp error';
            if (is_array($result) && isset($result['title']) && is_string($result['title'])) {
                $errorMsg = 'Mailchimp error: ' . $result['title'];
                if (isset($result['detail']) && is_string($result['detail'])) {
                    $errorMsg .= ' (' . $result['detail'] . ')';
                }
            } else {
                $lastError = $mailChimp->getLastError();
                if (is_string($lastError) && $lastError !== '') {
                    $errorMsg = 'Mailchimp error: ' . $lastError;
                }
            }

            return [
                'success' => false,
                'msg' => $errorMsg,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
