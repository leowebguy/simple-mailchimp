<?php
/**
 * A minimal Craft plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2021, leowebguy
 * @license    MIT
 */

namespace leowebguy\simplemailchimp\controllers;

use craft\web\Controller;
use leowebguy\simplemailchimp\SimpleMailchimp;
use yii\web\Response;

/*
 * Class MailchimpController
 */
class MailchimpController extends Controller
{
    // Protected Properties
    // =========================================================================

    protected array|bool|int $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    public function actionSubscribe(): Response
    {
        if ($_POST) {
            return $this->asJson(SimpleMailchimp::getInstance()->smcService->subscribe($_POST));
        }

        return $this->asJson(['success' => false, 'msg' => 'Direct access not allowed']);
    }
}
