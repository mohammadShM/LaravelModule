@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="کاربران">کاربران</a></li>
    <li><a href="#" title="ویرایش کاربر">ویرایش کاربر</a></li>
@endsection
@section('content')
    <div class="row no-gutters  margin-bottom-20" style="align-content: center;justify-content: center;">
        <div class="col-10 bg-white">
            <p class="box__title">بروزرسانی کاربر</p>
            <form action="{{ route('users.update' ,$user->id) }}"
                  class="padding-30" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <x-input name="name" placeholder="نام کاربر" type="text" value="{{ $user->name }}" class="text-left"
                         required/>
                <x-input type="text" class="text-left" name="email" placeholder="ایمیل"
                         value="{{ $user->email }}" required/>
                <x-input type="text" class="text-left" name="mobile" placeholder="موبایل"
                         value="{{ $user->mobile }}"/>
                <x-input type="text" class="text-left" name="username" placeholder="نام کاربری"
                         value="{{ $user->username }}"/>
                <x-input type="text" class="text-left" name="headline" placeholder="عنوان"
                         value="{{ $user->headline }}"/>
                <x-input type="text" class="text-left" name="website" placeholder="وبسایت"
                         value="{{ $user->website }}"/>
                <x-input type="text" class="text-left" name="linkedin" placeholder="لینکدین"
                         value="{{ $user->linkedin }}"/>
                <x-input type="text" class="text-left" name="facebook" placeholder="فیس بوک"
                         value="{{ $user->facebook }}"/>
                <x-input type="text" class="text-left" name="twitter" placeholder="توویتر"
                         value="{{ $user->twitter }}"/>
                <x-input type="text" class="text-left" name="youtube" placeholder="یوتیوپ"
                         value="{{ $user->youtube }}"/>
                <x-input type="text" class="text-left" name="instagram" placeholder="اینستاگزام"
                         value="{{ $user->instagram }}"/>
                <x-input type="text" class="text-left" name="telegram" placeholder="تلگرام"
                         value="{{ $user->telegram }}"/>
                <x-select name="status" class="mt-2" required>
                    <option value="">وضعیت حساب کاربر</option>
                    @foreach(\Mshm\User\Models\User::$statuses as $status)
                        <option value="{{ $status }}"
                                @if($status == $user->status) selected @endif >
                            @lang($status)</option>
                    @endforeach
                </x-select>
                <x-select name="role">
                    <option value="">یک نقش کاربری انتخاب کنید</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}
                        >@Lang($role->name)</option>
                    @endforeach
                </x-select>
                <x-file placeholder="آپلود بنر کاربر" name="image" :value="$user->image"/>
                <x-input type="password" class="text-left" name="password" placeholder="پسورد جدید"/>
                <x-textarea placeholder="بیو" name="bio" value="{{ $user->bio }}"/>
                <button class="btn btn-webamooz_net">بروزرسانی</button>
            </form>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-6 margin-left-10 margin-bottom-20">
            <p class="box__title">درحال یادگیری</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>نام دوره</th>
                        <th>نام مدرس</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr role="row" class="">
                        <td><a href="">1</a></td>
                        <td><a href="">دوره لاراول</a></td>
                        <td><a href="">محمد شیخی</a></td>
                    </tr>
                    <tr role="row" class="">
                        <td><a href="">1</a></td>
                        <td><a href="">دوره لاراول</a></td>
                        <td><a href="">محمد شیخی</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-6 margin-bottom-20">
            <p class="box__title">دوره های مدرس</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>نام دوره</th>
                        <th>نام مدرس</th>
                    </tr>
                    </thead>
                    @if ($user->courses->count()<1)
                        <p class="text-danger center">
                            شما هیچ دوره ای برگزار نکرده اید .</p>
                    @endif
                    <tbody>
                    @foreach($user->courses as $course)
                        <tr role="row" class="">
                            <td><a href="">{{ $course->id }}</a></td>
                            <td><a href="">{{ $course->title }}</a></td>
                            <td><a href="">{{ $course->teacher->name }}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
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
