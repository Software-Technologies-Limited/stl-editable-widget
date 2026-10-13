<?php

declare(strict_types=1);

namespace Stl\Editable;

use Stl\Editable\Support\Html;

final class Editable implements \Stringable
{
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
        if (!preg_match('/^[a-z][a-z0-9-]*$/', $type)) {
            throw new \InvalidArgumentException('Editable type must be a lowercase identifier.');
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
            'class' => trim(($this->attributes['class'] ?? '') . ' stl-editable-trigger'),
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
            . ';if(window.StlEditable){window.StlEditable.create(c.id,' . $options . ');}});</script>';
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
