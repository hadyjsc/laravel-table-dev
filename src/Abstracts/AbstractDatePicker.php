<?php

namespace JscDev\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;

abstract class AbstractDatePicker
{
    public string $datePickerClass;

    public string $identifier;

    public string $modelKeyName;

    protected string|null $class;

    protected array $attributes;

    protected string $label;

    protected array $options;

    abstract protected function identifier(): string;

    protected function class(): array
    {
        return [];
    }

    protected function attributes(): array
    {
        return [
            'placeholder' => $this->label(),
            'aria-label' => $this->label(),
            ...config('laravel-table.html_select_components_attributes'),
        ];
    }

    abstract protected function label(): string;

    abstract protected function options(): array;

    abstract public function filter(Builder $query, mixed $selected): void;

    public function setup(string $modelKeyName): void
    {
        $this->datePickerClass = $this::class;
        $this->identifier = $this->identifier();
        $this->modelKeyName = $modelKeyName;
    }

    public static function retrieve(array $datePickerArray, string $identifier): array
    {
        return collect($datePickerArray)->firstOrFail('identifier', $identifier);
    }

    public static function make(array $datePickerArray): self
    {
        /** @var \JscDev\LaravelTable\Abstracts\AbstractDatePicker $datePickerInstance */
        $datePickerInstance = app($datePickerArray['datePickerClass'], $datePickerArray);
        $datePickerInstance->datePickerClass = $datePickerArray['datePickerClass'];
        $datePickerInstance->identifier = $datePickerArray['identifier'];
        $datePickerInstance->modelKeyName = $datePickerArray['modelKeyName'];

        return $datePickerInstance;
    }

    public function render(): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.date-picker', [
            'filter' => $this,
            'class' => $this->class(),
            'attributes' => (new ComponentAttributeBag($this->attributes())),
            'label' => $this->label(),
            'options' => $this->options(),
        ]);
    }
}
