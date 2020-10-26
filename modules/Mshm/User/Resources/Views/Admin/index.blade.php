@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="کاربران">کاربران</a></li>
@endsection
@section('content')
    <div class="row no-gutters  ">
        <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">کاربران</p>
            <div class="border-2"/>
            <div class="table__box">
                <table class="table h_line">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>ردیف</th>
                        <th>شناسه</th>
                        <th>نام و نام خانوادگی</th>
                        <th>ایمیل</th>
                        <th>شماره موبایل</th>
                        <th>سطح کاربری</th>
                        <th>تاریخ عضویت</th>
                        <th>آی پی</th>
                        <th>وضعیت حساب</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr role="row" class="">
                            <td>{{ $loop->index + 1 }}</td>
                            <td><a href="">{{ $user->id }}</a></td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>
                                <ul>
                                    @foreach($user->roles as $userRole)
                                        <li class="deletable-item-list">@Lang($userRole->name)<span> </span>
                                            {{-- ============================== for set multi roles for users ============================== --}}
                                            {{-- <a href=""--}}
                                            {{-- onclick="deleteItem(event ,--}}
                                            {{-- '{{ route('users.removeRole',--}}
                                            {{-- ["user"=>$user->id,"role"=>$userRole->name]) }}','li')"--}}
                                            {{-- class="item-delete mlg-15"--}}
                                            {{-- title="حذف"></a>--}}
                                        </li>
                                    @endforeach
                                    {{-- ============================== for set multi roles for users ============================== --}}
                                    {{-- <li class="deletable-item-add"><a href="#select-role" rel="modal:open"--}}
                                    {{--  onclick="setFormAction({{ $user->id }})">--}}
                                    {{--  افزودن--}}
                                    {{-- نقش کاربری</a></li>--}}
                                </ul>
                            </td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->ip ? $user->ip : 'ندارد' }}</td>
                            <td class="confirmation-status">{!! $user->hasVerifiedEmail()
                                    ? "<span class='text-success'>تایید شده</span>"
                                    : "<span class='text-error'>تایید نشده</span>" !!}</td>
                            <td>
                                <!--suppress JSDeprecatedSymbols -->
                                <a href=""
                                   onclick="deleteItem(event ,'{{ route('users.destroy',$user->id) }}')"
                                   class="item-delete mlg-15" title="حذف"></a>
                                <a href="{{ route('users.edit',$user->id) }}" class="item-edit mlg-15"
                                   title="ویرایش"></a>
                                <a href="" onclick="updateConfirmationStatus(event,
                                    '{{ route('users.manualVerify',$user->id) }}',
                                    'آیا از تایید این آیتم اطمینان دارید',
                                    'تایید شده')" class="item-confirm mlg-15"
                                   title="تایید"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{-- ============================== for set multi roles for users ============================== --}}
                {{--                <div id="select-role" class="modal">--}}
                {{--                    <form id="select-role-form" class="m-for-all"--}}
                {{--                          action="{{ route('users.addRole',0) }}" method="post">--}}
                {{--                        @csrf--}}
                {{--                        <label for="role" class="role-label-css">انتخاب نقش برای کاربر : </label>--}}
                {{--                        <select name="role" id="role">--}}
                {{--                            <option value="">یک نقش را انتخاب کنید</option>--}}
                {{--                            @foreach($roles as $role)--}}
                {{--                                <option value="{{ $role->name }}">{{ $role->name }}</option>--}}
                {{--                            @endforeach--}}
                {{--                        </select>--}}
                {{--                        <button class="btn btn-webamooz_net mt-2 ">افزودن</button>--}}
                {{--                    </form>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- jQuery Modal -->
    {{-- ============================== for set multi roles for users ============================== --}}
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>--}}
    <script>
        {{-- ============================== for set multi roles for users ============================== --}}
        {{--function setFormAction(userId) {--}}
        {{--    let action = '{{ route('users.addRole' , 0) }}';--}}
        {{--    $("#select-role-form").attr('action', action.replace('/0/', '/' + userId + '/'));--}}
        {{--}--}}
        @include('Common::layouts.feedbacks')
    </script>
@endsection
{{-- ============================== for set multi roles for users ============================== --}}
<!-- jQuery Modal -->
{{--@section('CssMe')--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css">--}}
{{--@endsection--}}
