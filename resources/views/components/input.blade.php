<div class="w-100  mlg-15">
    <label for="1"></label>
    <input id="1" type="{{ $type }}" name="{{ $name }}"
           placeholder="{{ $placeholder }}" {{ $attributes->merge(['class'=>'text w-100']) }}
           value="{{ old($name) }}">
    <x-validation-error field="{{ $name }}"/>
</div>
