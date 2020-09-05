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
                        <th>آی دی</th>
                        <th>نام</th>
                        <th>ایمیل</th>
                        <th>نقش کاربری</th>
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
                            <td><a href="">
                                    <ul>
                                        @foreach($user->roles as $userRole)
                                            <li>{{ $userRole->name }}</li>
                                        @endforeach
                                        <li><a href="#select-role" rel="modal:open"
                                               onclick="setFormAction({{ $user->id }})">افزودن نقش کاربری</a></li>
                                    </ul>
                                </a></td>
                            <td>
                                <!--suppress JSDeprecatedSymbols -->
                                <a href=""
                                   onclick="deleteItem(event ,'{{ route('users.destroy',$user->id) }}')"
                                   class="item-delete mlg-15" title="حذف"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div id="select-role" class="modal">
                    <form id="select-role-form" class="m-for-all"
                          action="{{ route('users.addRole',0) }}" method="post">
                        @csrf
                        <label for="role" class="role-label-css">انتخاب نقش برای کاربر : </label>
                        <select name="role" id="role">
                            <option value="">یک نقش را انتخاب کنید</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-webamooz_net mt-2 ">افزودن</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script>
        function setFormAction(userId) {
            let action = '{{ route('users.addRole' , 0) }}';
            $("#select-role-form").attr('action', action.replace('/0/', '/' + userId + '/'));
        }
        @include('Common::layouts.feedbacks')
    </script>
@endsection
<!-- jQuery Modal -->
@section('CssMe')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css">
@endsection
