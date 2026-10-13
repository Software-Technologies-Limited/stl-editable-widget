<?php

declare(strict_types=1);

namespace Stl\Editable;

use Stl\Editable\Support\Html;

final class Assets
{
    public function __construct(private readonly string $baseUrl = '/vendor/stl-editable')
    {
    }

    public function styles(array $types = []): string
    {
        return '<link rel="stylesheet" href="'
            . Html::escape(rtrim($this->baseUrl, '/') . '/stl-editable.css') . '">';
    }

    public function scripts(array $types = [], ?string $language = null): string
    {
        return '<script src="' . Html::escape(rtrim($this->baseUrl, '/') . '/stl-editable.js')
            . '" defer></script>';
    }
}
