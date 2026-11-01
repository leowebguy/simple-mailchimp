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
use yii\base\Event;

/**
 * Simple Mailchimp plugin
 *
 * @method static SimpleMailchimp|null getInstance()
 * @property-read MailchimpService $smcService
 */
class SimpleMailchimp extends Plugin
{
    public static ?SimpleMailchimp $plugin = null;

    public bool $hasCpSection = false;

    public bool $hasCpSettings = true;

    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        if (!$this->isInstalled) {
            return;
        }

        $this->setComponents([
            'smcService' => MailchimpService::class,
        ]);

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            static function (RegisterUrlRulesEvent $event): void {
                $event->rules['mailchimp/send'] = 'simple-mailchimp/mailchimp/subscribe';
            }
        );

        Craft::info(
            'Simple Mailchimp plugin loaded',
            __METHOD__
        );
    }

    protected function createSettingsModel(): ?Model
    {
        return new MailchimpModel();
    }

    public function getSettings(): ?MailchimpModel
    {
        /** @var MailchimpModel|null $settings */
        $settings = parent::getSettings();

        return $settings;
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('simple-mailchimp/settings', [
            'settings' => $this->getSettings(),
        ]);
    }
}
