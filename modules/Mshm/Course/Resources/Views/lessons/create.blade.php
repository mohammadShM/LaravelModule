@extends('Dashboard::master')
@section('breadcrumb')
    <!--suppress CheckEmptyScriptTag -->
    <li><a href="{{ route('courses.index') }}" title="درس ها">درس ها</a></li>
    <li><a href="#" title="ویرایش درس">ایجاد درس [ndn</a></li>
@endsection
@section('content')
    <div class="row no-gutters  " style="align-content: center;justify-content: center;">
        <div class="col-10 bg-white">
            <p class="box__title">بروزرسانی درس</p>
            <form action="{{ route('courses.store') }}" class="padding-30" method="post" enctype="multipart/form-data">
                @csrf
                <x-input name="title" placeholder="عنوان درس" type="text" required/>
                <x-input type="text" class="text-left" name="slug" placeholder="نام انگلیسی درس اختیاری"
                         required style="margin-bottom: 20px;"/>
                <x-select name="season_id" required>
                    <option value="">انتخاب سرفصل</option>
                    @foreach($seasons as $season)
                        <option value="{{ $season->id }}"
                                @if($season->id == old('season_id')) selected @endif >
                            {{ $season->title }}</option>
                    @endforeach
                </x-select>
                <p class="box__title" style="margin-top: 20px;">ایا این درس رایگان است ؟ </p>
                <div class="w-50" style="margin-bottom: 20px;">
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-1" name="free" value="0" type="radio" checked="">
                        <label for="lesson-upload-field-1">خیر</label>
                    </div>
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-2" name="free" value="1" type="radio">
                        <label for="lesson-upload-field-2">بله</label>
                    </div>
                </div>
                <x-file placeholder="آپلود درس" name="lesson_file" required/>
                <x-textarea placeholder="توضیحات درس" name="body"/>
                <button class="btn btn-webamooz_net">ایجاد درس</button>
            </form>
        </div>
    </div>
@endsection
