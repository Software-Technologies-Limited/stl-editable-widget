# STL Editable Widget

STL Editable Widget provides framework-agnostic inline editing controls for PHP
applications. It can be used from raw PHP, Laravel Blade, Yii views, Symfony,
and other PHP rendering layers without requiring a framework package.

The browser layer is the customized Bootstrap 3 X-Editable distribution from
the original `2amigos/yii2-editable-widget` project. This continuation preserves
the original Git history and BSD-3-Clause license and is not affiliated with or
endorsed by the original maintainers.

## Requirements

- PHP 8.1 through 8.5
- jQuery and Bootstrap 3 in the browser

There are no required PHP framework dependencies.

## Installation

```bash
composer require software-technologies-limited/stl-editable-widget
```

## Publish the browser assets

Copy `vendor/software-technologies-limited/stl-editable-widget/src/assets` to a
publicly served directory such as `public/assets/stl-editable`.

The default `Assets` configuration expects `/assets/stl-editable`. You can pass
a different public URL when necessary:

```php
use Stl\Editable\Assets;

$assets = new Assets('/build/stl-editable');
```

Render the required tags once, after loading Bootstrap 3 CSS and jQuery:

```php
<?= $assets->styles(['date', 'select2']) ?>
<?= $assets->scripts(['date', 'select2']) ?>
```

Supported optional asset groups are `address`, `combodate`, `date`, `datetime`,
`select2`, and `wysihtml5`. Basic text, textarea, select, checklist, and HTML5
input types only need the base editable assets.

## Raw PHP

```php
<?php

use Stl\Editable\Editable;

$editable = new Editable(
    name: 'status',
    url: '/update-field.php',
    value: 'pending',
    display: 'Pending',
    type: 'select',
    mode: 'popup',
    primaryKey: 42,
    options: [
        'source' => [
            ['value' => 'pending', 'text' => 'Pending'],
            ['value' => 'complete', 'text' => 'Complete'],
        ],
    ],
    attributes: ['class' => 'editable-value'],
);
?>

<?= $editable->render() ?>
<?= $editable->script() ?>
```

Names, values, IDs, attributes, and JavaScript options are safely encoded.

## Handling updates

The neutral request object validates the X-Editable request shape without
performing framework-specific database operations:

```php
use Stl\Editable\EditableRequest;

$request = EditableRequest::fromArray($_POST);

// Authorize the current user and validate the allowed field before saving.
$record = findRecord($request->primaryKey);
$record->update([$request->name => $request->value]);
```

Always allow-list editable field names and perform authorization and validation
inside your application. Primary keys are sent as ordinary scalar or array
values; this package does not use PHP serialization.

## Laravel

Create `Editable` in a controller, component, or view model, then render it in
Blade as intentional component HTML:

```blade
{!! $editable->render() !!}

@push('scripts')
    {!! $editable->script() !!}
@endpush
```

In the update endpoint:

```php
$editableRequest = \Stl\Editable\EditableRequest::fromArray($request->all());
```

Use Laravel authorization, validation, and Eloquent to persist the value.

## Yii

The same renderer works directly in a Yii view:

```php
$editable = new \Stl\Editable\Editable(
    name: 'status',
    url: \yii\helpers\Url::to(['record/update-field']),
    value: (string) $model->status,
    primaryKey: $model->getPrimaryKey(),
);

echo $editable->render();
echo $editable->script();
```

Use `EditableRequest::fromArray(Yii::$app->request->post())` in the controller,
then apply Yii authorization and model validation. Yii is not installed or
required by this package.

## Development checks

```bash
composer validate --strict
if composer show --locked --name-only | grep -q .; then composer audit --locked; fi
find src -name '*.php' -print0 | xargs -0 -n1 php -l
```

GitHub Actions runs these checks on PHP 8.1 through 8.5.

## Credits

- Antonio Ramirez and 2amigOS! Consulting Group for the original widget and
  customized X-Editable assets
- Contributors to the original project
- Software Technologies Limited, current maintainer

## License

BSD-3-Clause. See [LICENSE.md](LICENSE.md). The original copyright and license
notice are retained as required by the license.
