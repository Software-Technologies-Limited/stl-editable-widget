<?php

declare(strict_types=1);

namespace Stl\Editable;

use Stl\Editable\Support\Html;

final class Editable implements \Stringable
{
    private const TYPES = [
        'address', 'checklist', 'combodate', 'date', 'datetime', 'email',
        'number', 'password', 'select', 'select2', 'tel', 'text', 'textarea',
        'time', 'url', 'wysihtml5',
    ];

    /**
     * @param scalar|array<array-key, scalar>|null $value
     * @param scalar|array<array-key, scalar>|null $primaryKey
     * @param array<string, mixed> $options X-Editable client options
     * @param array<string, scalar|null> $attributes HTML anchor attributes
     */
    public function __construct(
        private readonly string $name,
        private readonly string $url,
        private readonly string|int|float|bool|array|null $value = null,
        private readonly string $type = 'text',
        private readonly string $mode = 'inline',
        private readonly string $placement = 'top',
        private readonly string|int|float|bool|array|null $primaryKey = null,
        private readonly array $options = [],
        private readonly array $attributes = [],
        private readonly ?string $id = null,
        private readonly ?string $display = null,
    ) {
        if ($name === '' || $url === '') {
            throw new \InvalidArgumentException('Editable name and URL are required.');
        }
        if (!in_array($type, self::TYPES, true)) {
            throw new \InvalidArgumentException('Unsupported editable type: ' . $type);
        }
        if (!in_array($mode, ['inline', 'popup'], true)) {
            throw new \InvalidArgumentException('Editable mode must be inline or popup.');
        }
    }

    public function id(): string
    {
        if ($this->id !== null && $this->id !== '') {
            return $this->id;
        }

        $base = strtolower(trim((string) preg_replace('/[^A-Za-z0-9_-]+/', '-', $this->name), '-'));

        return ($base !== '' ? $base : 'stl') . '-editable';
    }

    public function type(): string
    {
        return $this->type;
    }

    public function render(): string
    {
        $attributes = array_merge($this->attributes, [
            'href' => '#',
            'id' => $this->id(),
            'data-stl-editable' => true,
            'role' => $this->attributes['role'] ?? 'button',
        ]);

        $display = $this->display;
        if ($display === null && is_scalar($this->value)) {
            $display = (string) $this->value;
        }

        return '<a' . Html::attributes($attributes) . '>' . Html::escape($display ?? '') . '</a>';
    }

    public function script(): string
    {
        $configuration = array_merge($this->options, [
            'url' => $this->url,
            'type' => $this->type,
            'mode' => $this->mode,
            'name' => $this->name,
            'placement' => $this->placement,
            'pk' => $this->primaryKey,
            'value' => $this->value,
        ]);
        $id = Html::json(['id' => $this->id()]);
        $options = Html::json($configuration);

        return '<script>(function(init){if(document.readyState==="loading")'
            . '{document.addEventListener("DOMContentLoaded",init);}else{init();}})(function(){var c=' . $id
            . ';var e=document.getElementById(c.id);if(e&&window.jQuery&&jQuery.fn.editable)'
            . '{jQuery(e).editable(' . $options . ');}});</script>';
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
