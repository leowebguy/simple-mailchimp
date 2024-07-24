<?php
/**
 * A minimal Craft plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2024, leowebguy
 */

namespace leowebguy\simplemailchimp\services;

use Craft;
use craft\base\Component;
use craft\helpers\App;
use DrewM\MailChimp\MailChimp as MC;
use Exception;

class MailchimpService extends Component
{
    /**
     * @param $data
     * @return array
     */
    public function subscribe($data): array
    {
        $settings = Craft::$app->plugins->getPlugin('simple-mailchimp')->getSettings();

        if (empty($data["email"])) {
            return ['success' => false, 'msg' => 'Email can\'t be empty'];
        }

        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'msg' => 'Invalid email format'];
        }

        $dataMC = [
            'email_address' => $data["email"],
            'status' => $settings['optIn'] ? 'pending' : 'subscribed',
            'double_optin' => (bool)$settings['optIn']
        ];

        if (isset($data["name"])) {
            $name = array_pad(explode(" ", $data["name"], 2), 2, null);
            $dataMC = array_merge($dataMC, ['merge_fields' => ['FNAME' => $name[0], 'LNAME' => $name[1] ?? '']]);
        }

        if (isset($data["tags"])) {
            $dataMC = array_merge($dataMC, ['tags' => explode(',', $data["tags"])]);
        }

        try {
            $MailChimp = new MC(App::parseEnv($settings['mcApiKey'] ?? ''));
            $result = $MailChimp->post("lists/" . App::parseEnv($settings['mcListID'] ?? '') . "/members", $dataMC);
            if ($result['status'] == 'subscribed') {
                return ['success' => true, 'msg' => 'Email subscribed successfully', 'id' => $result['contact_id']];
            }
            return ['success' => false, 'msg' => 'Mailchimp error: ' . $result['title']];
        } catch (Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }
}
