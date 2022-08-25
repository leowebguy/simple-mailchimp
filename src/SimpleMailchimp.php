<?php
/**
 * A minimal Craft plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2021, leowebguy
 * @license    MIT
 */

namespace leowebguy\simplemailchimp;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use leowebguy\simplemailchimp\models\MailchimpModel;
use yii\base\Event;

/*
 * Class SimpleMailchimp
 */
class SimpleMailchimp extends Plugin
{
    // Properties
    // =========================================================================

    public static $plugin;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        if (!$this->isInstalled) {
            return;
        }

        // site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['mailchimp/send'] = 'simple-mailchimp/mailchimp/subscribe';
            }
        );

        // log info
        Craft::info(
            'Simple Mailchimp plugin loaded',
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel(): ?Model
    {
        return new MailchimpModel();
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('simple-mailchimp/settings', [
            'settings' => $this->getSettings(),
        ]);
    }
}
