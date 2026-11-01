# Release Notes for Simple Mailchimp

All notable changes to this project will be documented in this file.

## 3.1.0 - 2026.11.01
### Added
- Added compatibility for PHP ^8.2 || ^8.3 || ^8.4 || ^8.5 and Craft CMS ^5.0.0.
- Added PHPStan strict static analysis at Level 8 (`phpstan.neon`).
- Added Laravel Pint code style configuration and verification scripts.
- Added explicit parameter, return, and property type declarations across all classes.
- Added support for custom `merge_fields` and array-based `tags` in subscribe payloads.
- Added detailed error reporting extracting title and details from Mailchimp API responses.

### Changed
- Modernized `composer.json` schema (`authors` array, `extra` metadata, dev dependencies, platform target).
- Improved environment variable parsing via `craft\helpers\App::parseEnv()` and boolean evaluation for settings.
- Strengthened type-safe handling of web requests in `MailchimpController`.

## 3.0.1 - 2024.03.27
- Bump version

## 3.0.0 - 2024.03.26
- Craft 5, PHP 8.2

## 2.0.2 - 2023.03.23
- Cleanup, PHP 8.2

## 2.0.1 - 2023.01.28
- + vanilla js example
- + better phpDoc
- + change $_POST to Craft::$app->request->post() < better

## 2.0.0 - 2022.08.26
- PHP 8.0 | Craft 4

## 1.0.4 - 2022.08.24
- Cleanup

## 1.0.2 - 2022.04.10
- Fix array issue

## 1.0.1 - 2022.02.20
- Add settings release
- Adding settings + autosuggestField

## 1.0.0 - 2022.02.11
- Initial release
- Craft 3 plugin initial release
