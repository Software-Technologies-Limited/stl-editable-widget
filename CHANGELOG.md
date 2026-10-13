# Changelog

## 3.0.1

- Removed unused legacy X-Editable, Select2, date/time picker, and WYSIHTML5
  browser assets from the distribution.

## 3.0.0

- Replaced the retained jQuery/Bootstrap X-Editable runtime with an STL-owned
  vanilla JavaScript implementation.
- Added CSS design tokens, themes, localized labels, lifecycle events, custom
  editor type registration, responsive presentation, and fetch-based saving.
- Added optional Laravel configuration and asset publishing.

## 2.0.1

- Removed an unconditional browser-side reference to Yii's global CSRF helper.
- Added optional framework-neutral CSRF discovery through standard meta tags.

## Unreleased

- Adopted as `software-technologies-limited/stl-editable-widget`.
- Rebuilt the PHP layer as a framework-agnostic PHP 8.1+ library.
- Removed Yii and all other PHP frameworks from runtime dependencies.
- Added safe HTML and JSON rendering through the `Stl\Editable` namespace.
- Added separate machine values and human-readable labels for editable fields.
- Replaced serialized primary keys with normal scalar or array values.
- Added neutral request parsing for raw PHP, Laravel, Yii, Symfony, and others.
- Preserved the customized Bootstrap 3 X-Editable browser assets.
- Added framework-neutral asset tags with date and datetime locale support.
- Replaced legacy Travis and Scrutinizer configuration with GitHub quality checks.

## 1.x

Original releases were maintained by 2amigOS! Consulting Group. See the Git
history for details.
