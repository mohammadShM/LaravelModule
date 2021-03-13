<div class="file-upload">
    <div class="i-file-upload">
        <span>{{ $placeholder }}</span>
        <input type="file" class="file-upload" id="files" name="{{ $name }}" {{ $attributes }}/>
    </div>
    <span class="filesize"></span>
    <br/>
    @if (isset($value))
        <div class="selectedFiles">
            <p> فرمت تصویر فعلی : <strong>{{ $value->filename }}</strong></p>
            <p> نام تصویر فعلی : <strong>{{ array_values($value->files)[0] }}</strong></p>
            <img src="{{ $value->thumb }}" alt="{{ $name }}" width="150" class="margin-15 mt-2">
        </div>
    @else
        <span class="selectedFiles">فایلی انتخاب نشده است</span>
    @endif
    <x-validation-error field="{{ $name }}"/>
</div>
