@extends('User::Front.master')

@section('content')
    <div class="account">
        <form action="{{ route('verification.verify') }}" class="form" method="post">
            @csrf
            <a class="account-logo" href="/">
                <img src="/img/weblogo.png" alt="">
            </a>
            <div class="card-header">
                <p class="activation-code-title">کد فرستاده شده به ایمیل <span>{{ auth()->user()->email }}</span> را
                    وارد کنید</p>
            </div>
            <div class="form-content form-content1">
                <input name="verify_code" class="activation-code-input @error('verify_code') is-invalid @enderror"
                       required placeholder="فعال سازی">
                @error('verify_code')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <br>
                <button class="btn i-t">تایید</button>
                <a href="#" onclick="event.preventDefault(); document.getElementById('resend-code').submit()"
                >ارسال مجدد کد فعالسازی</a>
            </div>
            <div class="form-footer">
                <a href="{{ route('register') }}">صفحه ثبت نام</a>
            </div>
        </form>
        <form id="resend-code" action="{{ route('verification.resend') }}" method="post">
            @csrf
        </form>
    </div>
@endsection
@section('js')
    <!--suppress VueDuplicateTag, HtmlUnknownTarget -->
    <script src="/js/jquery-3.4.1.min.js"></script>
    <!--suppress HtmlUnknownTarget, VueDuplicateTag -->
    <script src="/js/activation-code.js"></script>
@endsection
