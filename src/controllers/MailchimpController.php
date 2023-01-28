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

use Craft;
use craft\web\Controller;
use leowebguy\simplemailchimp\SimpleMailchimp;
use yii\web\Response;

class MailchimpController extends Controller
{
    // Properties
    // =========================================================================

    protected int|bool|array $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    /**
     * @return Response
     */
    public function actionSubscribe(): Response
    {
        if (!Craft::$app->request->getIsPost()) {
            return $this->asJson(
                ['success' => false, 'msg' => 'Endpoint only accepts $_POST']
            );
        }

        return $this->asJson(
            SimpleMailchimp::$plugin->smcService->subscribe(
                Craft::$app->request->post()
            )
        );
    }
}
