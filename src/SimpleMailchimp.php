<?php
/**
 * A minimal Craft plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2024, leowebguy
 */

namespace leowebguy\simplemailchimp;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use leowebguy\simplemailchimp\models\MailchimpModel;
use leowebguy\simplemailchimp\services\MailchimpService;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\Event;
use yii\base\Exception;

class SimpleMailchimp extends Plugin
{
    public bool $hasCpSection = false;

    public bool $hasCpSettings = true;

    public function init()
    {
        parent::init();

        if (!$this->isInstalled) {
            return;
        }

        $this->setComponents([
            'smcService' => MailchimpService::class
        ]);

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            static function(RegisterUrlRulesEvent $event) {
                $event->rules['mailchimp/send'] = 'simple-mailchimp/mailchimp/subscribe';
            }
        );

        // log info
        Craft::info(
            'Simple Mailchimp plugin loaded',
            __METHOD__
        );
    }

    /**
     * @return Model|null
     */
    protected function createSettingsModel(): ?Model
    {
        return new MailchimpModel;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     * @return string|null
     */
    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('simple-mailchimp/settings', [
            'settings' => $this->getSettings(),
        ]);
    }
}
