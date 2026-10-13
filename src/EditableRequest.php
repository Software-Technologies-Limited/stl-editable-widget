<?php

declare(strict_types=1);

namespace Stl\Editable;

final class EditableRequest
{
    /** @param scalar|array<array-key, mixed>|null $primaryKey */
    private function __construct(
        public readonly string|int|float|bool|array|null $primaryKey,
        public readonly string $name,
        public readonly mixed $value,
    ) {
    }

    /** @param array<string, mixed> $input */
    public static function fromArray(array $input): self
    {
        $name = $input['name'] ?? null;
        if (!is_string($name) || $name === '') {
            throw new \InvalidArgumentException("'name' must be a non-empty string.");
        }
        if (!array_key_exists('value', $input)) {
            throw new \InvalidArgumentException("'value' is required.");
        }

        $primaryKey = $input['pk'] ?? null;
        if (!is_scalar($primaryKey) && !is_array($primaryKey) && $primaryKey !== null) {
            throw new \InvalidArgumentException("'pk' must be a scalar, array, or null.");
        }

        return new self($primaryKey, $name, $input['value']);
    }
}
