<label for="1"></label>
<select id="1" name="{{ $name }}" {{ $attributes }}>
    {{ $slot }}
</select>
<x-validation-error field="{{ $name }}"/>
