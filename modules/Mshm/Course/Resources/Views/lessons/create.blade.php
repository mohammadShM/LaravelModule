@extends('Dashboard::master')
@section('breadcrumb')
    <!--suppress CheckEmptyScriptTag -->
    <li><a href="{{ route('courses.index') }}" title="درس ها">درس ها</a></li>
    <li><a href="{{ route('courses.details' , $course->id) }}" title="{{ $course->title }}">{{ $course->title }}</a>
    </li>
    <li><a href="#" title="ویرایش درس">ایجاد درس</a></li>
@endsection
@section('content')
    <div class="row no-gutters  " style="align-content: center;justify-content: center;">
        <div class="col-10 bg-white">
            <p class="box__title">ایجاد درس جدید</p>
            <form action="{{ route('lessons.store',$course->id) }}" class="padding-30" method="post"
                  enctype="multipart/form-data">
                @csrf
                <x-input name="title" placeholder="عنوان درس *" type="text" required/>
                <x-input type="text" class="text-left" name="slug" placeholder="نام انگلیسی درس اختیاری"
                         style="margin-bottom: 20px;"/>
                <x-input type="number" class="text-left" name="time" placeholder="مدت زمان جلسه *"
                         required style="margin-bottom: 20px;"/>
                <x-input type="number" class="text-left" name="number" placeholder="شماره جلسه"
                         style="margin-bottom: 20px;"/>
                @if (count($seasons))
                    <x-select name="season_id" required>
                        <option value="">انتخاب سرفصل درس *</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}"
                                    @if($season->id == old('season_id')) selected @endif >
                                {{ $season->title }}</option>
                        @endforeach
                    </x-select>
                @endif
                <p class="box__title" style="margin-top: 20px;">ایا این درس رایگان است ؟ *</p>
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
                <x-file placeholder="آپلود درس *" name="lesson_file" required/>
                <x-textarea placeholder="توضیحات درس" name="body"/>
                <button class="btn btn-webamooz_net">ایجاد درس</button>
            </form>
        </div>
    </div>
@endsection
