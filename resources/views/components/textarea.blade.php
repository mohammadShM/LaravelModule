<label for="1"></label>
<textarea id="1" placeholder="{{ $placeholder }}" name="{{ $name }}" style="color: #212121;" class="text h">
        {!! isset($value) ? $value :  old($name) !!}
</textarea>
<!--suppress CheckEmptyScriptTag, HtmlUnknownTag -->
<x-validation-error field="body"/>
