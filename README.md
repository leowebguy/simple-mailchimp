Simple Mailchimp plugin for Craft 3
===

A minimal Craft 3 plugin to connect forms to Mailchimp

## Installation

```bash
composer require leowebguy/simple-mailchimp
```

On your Control Panel, go to Settings → Plugins → "Simple Mailchimp" → Install

## Usage

Gather the necessary parameters from Mailchimp

#### API Key (MC_API_KEY)

Go to https://admin.mailchimp.com/lists > Select Audience > Settings > Audience name and defaults > Audience ID

![Screenshot](resources/list_id.png)

#### Audience ID (MC_LIST_ID)

Go to https://admin.mailchimp.com/account/api/ > API Key

![Screenshot](resources/api_key.png)

Add the credentials to your .env file

```dotenv
# Mailchimp
MC_API_KEY=xxx12345x1234x123xxx123xxxxx123xx-us14
MC_LIST_ID=xxx1234xx1234
```

Your newsletter form template can look something like this:

```html
<form method="post">
    {{ csrfInput() }}
    <input type="hidden" name="tags" value="Tag_1,Tag_2"> {# comma separated #}
    <input type="text" class="form-control" name="name">
    <input type="email" class="form-control" name="email">
    <button type="submit">Submit</button>
    <span class="notification" style="display: none"></span>
</form>
```

_The only required field is `email`. Everything else is optional._

You can use jQuery/Ajax to call plugin controller like the example below

```js
(function($) {
    $('form').submit(function(e) {
        e.preventDefault();
        $.post({
            url: '/mailchimp/send',
            data: $(this).serialize(),
            success: (r) => {
                if (r.success == true) {
                    $('.notification')
                        .text(r.msg)
                        .fadeIn()
                        .delay(6000).fadeOut();
                    $(this).trigger("reset");
                    // console.log(`%ccontact_id: ${r.id}`, 'color:#2c8');
                } else {
                    $('.notification')
                        .text(r.msg)
                        .fadeIn()
                        .delay(6000).fadeOut();
                }
            }
        });
    });
})(jQuery);
```
