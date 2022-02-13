<?php
/**
 * A minimal Craft 3 plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2021, leowebguy
 * @license    MIT
 */

namespace leowebguy\simplemailchimp\services;

use craft\helpers\App;
use craft\base\Component;
use DrewM\MailChimp\MailChimp as MC;

class MailchimpService extends Component
{
    // Public Methods
    // =========================================================================

    public function subscribe($data): array
    {
        if ($_POST) {
            if (empty($_POST["email"])) {
                return ['success' => false, 'msg' => 'Email can\'t be empty'];
            }

            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'msg' => 'Invalid email format'];
            }

            $dataMC = [
                'email_address' => $_POST["email"],
                'status' => 'subscribed'
            ];

            if (isset($_POST["name"])) {
                $dataMC = array_merge($dataMC, ['merge_fields' => ['FNAME' => $_POST["name"], 'LNAME' => '']]);
            }

            if (isset($_POST["tags"])) {
                $dataMC = array_merge($dataMC, ['tags' => explode(',', $_POST["tags"])]);
            }

            try {
                $MailChimp = new MC(App::env('MC_API_KEY'));

                $result = $MailChimp->post("lists/" . App::env('MC_LIST_ID') . "/members", $dataMC);

                if ($result['status'] == 'subscribed') {
                    return ['success' => true, 'msg' => 'Email subscribed successfully', 'id' => $result['contact_id']];
                }
            } catch (\Exception $e) {
                $result = ['success' => false, 'msg' => $e->getResponse()];
            }

            return ['success' => false, 'msg' => $result['title']];
        }

        return ['success' => false, 'msg' => 'Direct access not allowed'];
    }
}
