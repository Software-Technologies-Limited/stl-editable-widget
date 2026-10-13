<?php

declare(strict_types=1);

namespace Stl\Editable;

use Stl\Editable\Support\Html;

final class Assets
{
    public function __construct(
        private readonly string $baseUrl = '/assets/stl-editable',
        private readonly string $select2BaseUrl = 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist',
    ) {
    }

    /** @param list<string> $types */
    public function styles(array $types = []): string
    {
        $files = ['editable/css/bootstrap-editable.css'];
        if (in_array('address', $types, true)) {
            $files[] = 'address/bootstrap-editable-address.css';
        }
        if (in_array('date', $types, true)) {
            $files[] = 'datepicker/vendor/css/datepicker3.css';
        }
        if (in_array('datetime', $types, true)) {
            $files[] = 'datetimepicker/vendor/css/bootstrap-datetimepicker.css';
        }
        if (in_array('wysihtml5', $types, true)) {
            $files[] = 'wysihtml5/vendor/bootstrap3-wysihtml5.css';
        }

        $tags = array_map(
            fn (string $file): string => '<link rel="stylesheet" href="' . Html::escape($this->url($file)) . '">',
            $files,
        );
        if (in_array('select2', $types, true)) {
            $tags[] = '<link rel="stylesheet" href="'
                . Html::escape($this->select2BaseUrl . '/css/select2.min.css') . '">';
        }

        return implode("\n", array_unique($tags));
    }

    /** @param list<string> $types */
    public function scripts(array $types = [], ?string $language = null): string
    {
        $files = ['editable/js/bootstrap-editable.js'];
        foreach (array_unique($types) as $type) {
            array_push($files, ...$this->scriptsForType($type));
        }

        if ($language !== null && !preg_match('/^[A-Za-z][A-Za-z0-9-]*$/', $language)) {
            throw new \InvalidArgumentException('Language must be a valid locale identifier.');
        }
        if ($language !== null && in_array('wysihtml5', $types, true)) {
            $files[] = 'wysihtml5/vendor/locales/bootstrap-wysihtml5.' . $language . '.js';
        }
        if ($language !== null && in_array('date', $types, true)) {
            $files[] = 'datepicker/vendor/js/locales/bootstrap-datepicker.' . $language . '.js';
        }
        if ($language !== null && in_array('datetime', $types, true)) {
            $files[] = 'datetimepicker/vendor/js/locales/bootstrap-datetimepicker.' . $language . '.js';
        }

        $tags = [];
        if (in_array('select2', $types, true)) {
            $tags[] = '<script src="' . Html::escape($this->select2BaseUrl . '/js/select2.full.min.js') . '"></script>';
        }
        foreach (array_unique($files) as $file) {
            $tags[] = '<script src="' . Html::escape($this->url($file)) . '"></script>';
        }

        return implode("\n", $tags);
    }

    /** @return list<string> */
    private function scriptsForType(string $type): array
    {
        return match ($type) {
            'address' => ['address/bootstrap-editable-address.js'],
            'combodate' => [
                'combodate/vendor/moment-with-langs.min.js',
                'combodate/vendor/combodate.js',
                'combodate/bootstrap-editable-combodate.js',
            ],
            'date' => [
                'datepicker/vendor/js/bootstrap-datepicker.js',
                'datepicker/bootstrap-editable-datepicker.js',
            ],
            'datetime' => [
                'datetimepicker/vendor/js/bootstrap-datetimepicker.js',
                'datetimepicker/bootstrap-editable-datetimepicker.js',
            ],
            'select2' => ['select2/bootstrap-editable-select2.js'],
            'wysihtml5' => [
                'wysihtml5/vendor/wysihtml5-0.3.0.min.js',
                'wysihtml5/vendor/bootstrap3-wysihtml5.all.min.js',
                'wysihtml5/bootstrap-editable-wysihtml5.js',
            ],
            default => [],
        };
    }

    private function url(string $file): string
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($file, '/');
    }
}
