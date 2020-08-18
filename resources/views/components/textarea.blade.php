<label for="1"></label>
<textarea id="1" placeholder="{{ $placeholder }}" name="{{ $name }}" class="text h">
        {!! isset($value) ? $value :  old($name) !!}
</textarea>
<x-validation-error field="body"/>
