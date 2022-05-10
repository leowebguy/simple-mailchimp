<?php
/**
 * A minimal Craft 3 plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2021, leowebguy
 * @license    MIT
 */

namespace leowebguy\simplemailchimp\controllers;

use craft\web\Controller;
use leowebguy\simplemailchimp\SimpleMailchimp;

class MailchimpController extends Controller
{
    // Protected Properties
    // =========================================================================

    protected int|bool|array $allowAnonymous = true;

    // Public Properties
    // =========================================================================

    public $enableCsrfValidation = true;

    // Public Methods
    // =========================================================================

    public function actionSubscribe()
    {
        return $this->asJson(SimpleMailchimp::getInstance()->smcService->subscribe($_REQUEST));
    }
}
