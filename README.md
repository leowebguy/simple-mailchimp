Simple Mailchimp plugin for Craft
===

A minimal Craft plugin to connect forms to Mailchimp

---

## Composer | Important

Craft 5

```
"require": {
"leowebguy/simple-mailchimp": "^3.0",
}
```

Craft 4

```
"require": {
   "leowebguy/simple-mailchimp": "^2.0",
}
```

Craft 3

```
"require": {
   "leowebguy/simple-mailchimp": "^1.0.4",
}
```

---

## Installation

```bash
composer require leowebguy/simple-mailchimp
```

On your Control Panel, go to Settings → Plugins → "Simple Mailchimp" → Install

## Credentials

Gather the necessary info from Mailchimp

#### API Key _MC_API_KEY_

Go to https://admin.mailchimp.com/lists > Select Audience > Settings > Audience name and defaults > Audience ID

![Screenshot](resources/list_id.png)

#### Audience ID _MC_LIST_ID_

Go to https://admin.mailchimp.com/account/api/ > API Key

![Screenshot](resources/api_key.png)

Add the credentials to plugin settings

![Screenshot](resources/settings.png)

You may also use `.env` parameters like in the example above.

```dotenv
# Mailchimp
MC_API_KEY=xxx12345x1234x123xxx123xxxxx123xx-us14
MC_LIST_ID=xxx1234xx1234
```

## Usage

Your newsletter form template can look something like this:

```twig
<form method="post" id="mailchimp">
    {{ csrfInput() }}
    <input type="hidden" name="tags" value="Tag_1,Tag_2">
    <input type="text" name="name">
    <input type="email" name="email">
    <button type="submit">Submit</button>
</form>
```

_The only required field is `email`, all the rest is optional_

use the vanilla js example below:

```js
const form = document.getElementById('#mailchimp')
form.onsubmit = (e) => {
    e.preventDefault();
    fetch('/mailchimp/send', {
        method: 'post',
        body: new FormData(this)
    })
    .then((r) => r.json())
    .then((r) => {
        if (r.success) {
            alert(r.msg)
        } else {
            alert(r.msg)
        }
    })
    .catch((e) => {
        console.error(e);
    });
};
```

or jquery/ajax...

```js
$('form#mailchimp').submit((e) => {
    e.preventDefault();
    $.post({
        url: '/mailchimp/send',
        data: $(this).serialize(),
        success: (r) => {
            if (r.success) {
                alert(r.msg)
            } else {
                alert(r.msg)
            }
        },
        error: (e) => {
            console.error(e);
        }
    });
});
```

