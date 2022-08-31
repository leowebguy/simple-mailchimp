<?php
/**
 * A minimal Craft plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2021, leowebguy
 * @license    MIT
 */

namespace leowebguy\simplemailchimp\services;

use Craft;
use craft\base\Component;
use craft\helpers\App;
use DrewM\MailChimp\MailChimp as MC;

/*
 * Class MailchimpService
 */
class MailchimpService extends Component
{
    // Public Methods
    // =========================================================================

    public function subscribe($data): array
    {
        if (empty($data["email"])) {
            return ['success' => false, 'msg' => 'Email can\'t be empty'];
        }

        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'msg' => 'Invalid email format'];
        }

        $dataMC = [
            'email_address' => $data["email"],
            'status' => 'subscribed'
        ];

        if (isset($data["name"])) {
            $name = array_pad(explode(" ", $data["name"], 2), 2, null);
            $dataMC = array_merge($dataMC, ['merge_fields' => ['FNAME' => $name[0], 'LNAME' => $name[1] ?: '']]);
        }

        if (isset($data["tags"])) {
            $dataMC = array_merge($dataMC, ['tags' => explode(',', $data["tags"])]);
        }

        if(isset($data['email'])) {
           unset($data['email']); 
        }
        if(isset($data['name'])) {
           unset($data['name']); 
        }
        if(isset($data['tags'])) {
           unset($data['tags']); 
        }
        if(isset($data['CRAFT_CSRF_TOKEN'])) {
           unset($data['CRAFT_CSRF_TOKEN']); 
        }

        $address1 = '';
        if(isset($data['address1'])) {
            $address1 = $data['address1'];
            unset($data['address1']);
        }

        $address2 = '';
        if(isset($data['address2'])) {
            $address = $data['address2'];
            unset($data['address2']);
        }

        $city = '';
        if(isset($data['city'])) {
            $address = $data['city'];
            unset($data['city']);
        }

        $state = '';
        if(isset($data['state'])) {
            $address = $data['state'];
            unset($data['state']);
        }

        $zip = '';
        if(isset($data['zip'])) {
            $address = $data['zip'];
            unset($data['zip']);
        }

        $country = '';
        if(isset($data['country'])) {
            $address = $data['country'];
            unset($data['country']);
        }

        if(!empty($data)) {
            if(!isset($dataMC['merge_fields'])) {
                $dataMC['merge_fields'] = [];
            }
            $dataMC['merge_fields'] = $data;

            if(!empty($address1) || !empty($address2) || !empty($city) || !empty($state) || !empty($zip) || !empty($country)) {
                $dataMC['merge_fields']['ADDRESS'] = [
                    'addr1'   => $address1,
                    'addr2'   => $address2,
                    'city'    => $city,
                    'state'   => $state,
                    'zip'     => $zip,
                    'country' => $country
                ];
            }
        }

        try {
            $settings = Craft::$app->plugins->getPlugin('simple-mailchimp')->getSettings();

            $MailChimp = new MC(App::parseEnv($settings['mcApiKey'] ?: ''));

            $result = $MailChimp->post("lists/" . App::parseEnv($settings['mcListID'] ?: '') . "/members", $dataMC);

            if ($result['status'] == 'subscribed') {
                return ['success' => true, 'msg' => 'Email subscribed successfully', 'id' => $result['contact_id']];
            }

            return ['success' => false, 'msg' => 'Mailchimp error: ' . $result['title']];

        } catch (\Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }
}
