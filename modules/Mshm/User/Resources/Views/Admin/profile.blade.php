@extends('Dashboard::master')
@section('breadcrumb')
    <!--suppress CheckEmptyScriptTag -->
    <li><a href="{{ route('users.index') }}" title="پروفایلان">پروفایلان</a></li>
    <li><a href="#" title="ویرایش پروفایل">ویرایش پروفایل</a></li>
@endsection
@section('content')
    <div class="row no-gutters margin-bottom-20" style="align-content: center;justify-content: center;">
        <div class="col-10 bg-white">
            <p class="box__title">بروزرسانی پروفایل</p>
            <!--suppress HtmlUnknownTag -->
            <x-user-photo/>
            <form action="{{ route('users.profile') }}" class="padding-30" method="post">
                @csrf
                <x-input name="name" placeholder="نام کاربر" type="text"
                         value="{{ auth()->user()->name }}" class="text-left" required/>
                <x-input type="text" class="text-left" name="email" placeholder="ایمیل"
                         value="{{ auth()->user()->email }}" required/>
                <x-input type="text" class="text-left" name="mobile" placeholder="موبایل"
                         value="{{ auth()->user()->mobile }}"/>
                <x-input type="password" class="text-left" name="password" placeholder="پسورد جدید"/>
                <p class="rules">رمز عبور باید حداقل 6 کاراکتر و ترکیبی از حروف بزرگ ، کوچک ، و کاراکتر های
                    <strong>!@#$%^&amp;*()</strong>باشد.</p>
                @can(\Mshm\RolePermissions\Models\Permission::PERMISSION_TEACH)
                    <x-input type="text" class="text-left" name="card_number" placeholder="شماره کارت بانکی"
                             value="{{ auth()->user()->card_number }}"/>
                    <x-input type="text" class="text-left" name="shaba" placeholder="شماره شبا بانکی"
                             value="{{ auth()->user()->shaba }}"/>
                    <x-input type="text" class="text-left" name="username" placeholder="نام کاربری و آدرس پروفایل"
                             value="{{ auth()->user()->username }}"/>
                    <p class="input-help text-left margin-bottom-12" dir="ltr">
                        <a href="{{ auth()->user()->profilePath() }}">
                            {{ auth()->user()->profilePath() }}</a>
                    </p>
                    <x-input type="text" name="headline" placeholder="عنوان"
                             value="{{ auth()->user()->headline }}"/>
                    <x-textarea placeholder="بیو" name="bio" value="{{ auth()->user()->bio }}"/>
                @endcan
                <br>
                <button class="btn btn-webamooz_net">بروزرسانی پروفایل</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <!--suppress HtmlUnknownTarget -->
    <script src="/panel/js/tagsInput.js?v=14"></script>
    <script>
        @@include('Common::layouts.feedbacks')
    </script>
@endsection
