<?php
/**
 * A minimal Craft plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2024, leowebguy
 */

namespace leowebguy\simplemailchimp\models;

use craft\base\Model;

class MailchimpModel extends Model
{
    public string $mcApiKey = '$MC_API_KEY';
    public string $mcListID = '$MC_LIST_ID';
    public string $optIn = '0';
}
