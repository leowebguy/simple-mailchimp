<?php

/**
 * A minimal Craft plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2024, leowebguy
 */

namespace leowebguy\simplemailchimp\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Request;
use leowebguy\simplemailchimp\SimpleMailchimp;
use yii\web\Response;

class MailchimpController extends Controller
{
    /**
     * @var array<int|string>|bool|int
     */
    protected array|bool|int $allowAnonymous = true;

    public function actionSubscribe(): Response
    {
        $request = Craft::$app->getRequest();

        if (!$request instanceof Request || !$request->getIsPost()) {
            return $this->asJson([
                'success' => false,
                'msg' => 'Endpoint only accepts $_POST',
            ]);
        }

        $plugin = SimpleMailchimp::getInstance();
        if ($plugin === null) {
            return $this->asJson([
                'success' => false,
                'msg' => 'Simple Mailchimp plugin is not loaded',
            ]);
        }

        /** @var array<string, mixed> $bodyParams */
        $bodyParams = $request->getBodyParams();

        return $this->asJson(
            $plugin->smcService->subscribe($bodyParams)
        );
    }
}
