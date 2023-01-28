<?php
/**
 * A minimal Craft plugin to connect forms to Mailchimp
 *
 * @author     Leo Leoncio
 * @see        https://github.com/leowebguy
 * @copyright  Copyright (c) 2021, leowebguy
 * @license    MIT
 */

namespace leowebguy\simplemailchimp\models;

use craft\base\Model;

class MailchimpModel extends Model
{
    // Properties
    // =========================================================================

    public string $mcApiKey = '$MC_API_KEY';
    public string $mcListID = '$MC_LIST_ID';
}
