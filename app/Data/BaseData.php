<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use JsonSerializable;
use ReflectionClass;

/**
 * Base Data-Transfer Object.
 *
 * Extend with promoted-property constructor and optional rules().
 *
 * @implements Arrayable<string, mixed>
 */
abstract class BaseData implements Arrayable, JsonSerializable
{
    /**
     * Override in child to define validation rules.
     *
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [];
    }

    /**
     * Hydrate and validate data, then instantiate DTO.
     *
     * @param  array<string, mixed>  $attributes
     *
     * @throws InvalidArgumentException if a required key is missing
     * @throws \Illuminate\Validation\ValidationException on invalid data
     */
    public static function from(array $attributes): static
    {
        // Reflect constructor parameters
        $reflection = new ReflectionClass(static::class);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor?->getParameters() ?? [];

        // Fill in defaults and provided values
        $filled = [];
        foreach ($parameters as $param) {
            $name = $param->getName();

            if (array_key_exists($name, $attributes)) {
                $filled[$name] = $attributes[$name];
            } elseif ($param->isDefaultValueAvailable()) {
                $filled[$name] = $param->getDefaultValue();
            } else {
                throw new InvalidArgumentException(
                    "Missing required key '{$name}' for ".static::class
                );
            }
        }

        // Validate after defaults applied
        if (($rules = static::rules()) !== []) {
            /** @var array<string, mixed> $rules */
            $filled = Validator::validate($filled, $rules);
        }

        // Instantiate with ordered args
        $args = [];
        foreach ($parameters as $param) {
            $args[] = $filled[$param->getName()];
        }

        return $reflection->newInstanceArgs($args);
    }

    /**
     * Convert to array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * JSON serialization
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
