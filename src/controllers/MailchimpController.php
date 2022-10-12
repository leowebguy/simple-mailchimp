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

    protected int|bool|array $allowAnonymous = true;

    protected const ALLOWED_METHODS = ['POST', 'PUT'];

    // Public Methods
    // =========================================================================

    public function actionSubscribe(): Response
    {
        if (in_array($this->request->method, self::ALLOWED_METHODS)) {
            $params = $this->request->getBodyParams();

            return $this->asJson(SimpleMailchimp::getInstance()->smcService->subscribe($params, $this->request->isPut));
        }

        $this->response->setStatusCode(405);
        return $this->asJson(['success' => false, 'msg' => 'Method not allowed']);
    }
}
