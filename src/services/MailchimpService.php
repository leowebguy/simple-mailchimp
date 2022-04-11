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

use Craft;
use craft\base\Component;
use craft\helpers\App;
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
                $NAME = array_pad(explode(" ", $_POST["name"], 2), 2, null);
                $dataMC = array_merge($dataMC, ['merge_fields' => ['FNAME' => $NAME[0], 'LNAME' => $NAME[1] ?: '']]);
            }

            if (isset($_POST["tags"])) {
                $dataMC = array_merge($dataMC, ['tags' => explode(',', $_POST["tags"])]);
            }

            try {
                $settings = Craft::$app->plugins->getPlugin('simple-mailchimp')->getSettings();

                $MailChimp = new MC(App::parseEnv($settings['mcApiKey']));

                $result = $MailChimp->post("lists/" . App::parseEnv($settings['mcListID']) . "/members", $dataMC);

                if ($result['status'] == 'subscribed') {
                    return ['success' => true, 'msg' => 'Email subscribed successfully', 'id' => $result['contact_id']];
                }
            } catch (\Exception $e) {
                return ['success' => false, 'msg' => $e->getMessage()];
            }
        }

        return ['success' => false, 'msg' => 'Direct access not allowed'];
    }
}
