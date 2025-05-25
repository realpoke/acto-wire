<?php

namespace App\Livewire\Forms;

use App\Data\BaseData;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Livewire\Form;
use LogicException;

/**
 * Base Livewire Form object.
 *
 * ┌────────────┐            ┌───────────────┐
 * │  Livewire  │  validate  │     DTO       │
 * │  Form obj  │───────────▶│  (domain)     │
 * └────────────┘            └───────────────┘
 *
 * @implements \Illuminate\Contracts\Support\Arrayable<string, mixed>
 */
abstract class BaseForm extends Form implements Arrayable
{
    /**
     * Child class **must** declare the DTO it should return.
     *
     *     protected static string $dtoClass = CreateOrderData::class;
     *
     * The DTO must extend App\Data\BaseData.
     */
    protected static string $dtoClass;

    /* ---------------------------------------------------------------------
     |  Validation
     * --------------------------------------------------------------------*/

    /**
     * If the child form doesn’t override `rules()`,
     * fall back to DTO::rules() so you keep rules in one place.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        if (! is_subclass_of(static::$dtoClass, BaseData::class)) {
            return [];
        }

        /** @var array<string, mixed> $rules */
        $rules = forward_static_call([static::$dtoClass, 'rules']);

        return $rules;
    }

    /* ---------------------------------------------------------------------
     |  DTO bridge
     * --------------------------------------------------------------------*/

    /**
     * Validate the form (using attributes or rules())
     * and return a typed DTO instance.
     */
    public function dto(): BaseData
    {
        if (! isset(static::$dtoClass) || ! is_subclass_of(static::$dtoClass, BaseData::class)) {
            throw new LogicException('BaseForm::$dtoClass must extend App\\Data\\BaseData');
        }

        $validated = $this->validate();   // Livewire 3 validate()

        /** @var BaseData $dto */
        $dto = forward_static_call([static::$dtoClass, 'from'], $validated);

        return $dto;
    }

    /* ---------------------------------------------------------------------
     |  Helpers
     * --------------------------------------------------------------------*/

    /**
     * Pre-fill the form from a Model, DTO, or plain array.
     *
     * @param  Model|BaseData|array<string, mixed>  $source
     */
    public function fillFrom(Model|BaseData|array $source): void
    {
        $data = match (true) {
            $source instanceof BaseData => $source->toArray(),
            $source instanceof Model => $source->toArray(),
            default => $source,
        };

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Satisfy Arrayable / jsonSerialize.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return collect(get_object_vars($this))
            ->except(['component'])   // Livewire’s internal reference
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
