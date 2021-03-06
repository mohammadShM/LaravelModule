@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('courses.details',$season->course_id) }}"
           title="{{ $season->course->title }}">{{ $season->course->title }}</a>
    </li>
    <li><a href="#" title="ویرایش سرفصل">ویرایش سرفصل</a></li>
@endsection
@section('content')
    <div class="row no-gutters  " style="align-content: center;justify-content: center;">
        <div class="col-10 bg-white">
            <p class="box__title">بروزرسانی سرفصل</p>
            <form action="{{ route('seasons.update' ,$season->id) }}"
                  class="padding-30" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <!--suppress HtmlFormInputWithoutLabel -->
                <x-input type="text" name="title" placeholder="عنوان سرفصل" class="text"
                         value="{{ $season->title }}" required/>
                <!--suppress HtmlFormInputWithoutLabel -->
                <x-input type="text" name="number" placeholder="شماره سرفصل" class="text"
                         value="{{ $season->number }}"/>
                <br>
                <button class="btn btn-webamooz_net">بروزرسانی سرفصل</button>
            </form>
        </div>
    </div>
@endsection
