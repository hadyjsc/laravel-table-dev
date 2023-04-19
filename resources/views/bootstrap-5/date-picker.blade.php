<div wire:key="{{ Str::of($filter->identifier)->snake('-')->slug() }}" class="ms-3 mt-2">
    <input type="text"
        wire:model="datePickerFilters"
        {{ $attributes->class(['custom-date-picker', ...$class]) }}
        autocomplete="off"
        placeholder="{{ $label }}"
        data-livewire="@this"
        onchange="this.dispatchEvent(new InputEvent('input'))"
    />
</div>
