@component('mail::message')
# کد فعال سازی حساب شما در سایت

این ایمیل به دلیل ثبت نام شما در سایت ما برای شما ارسال شده هست در صورتی که ثبت نامی توسط شما **انجام نشده است** این ایمیل رل نادیده بگیرید .

@component('mail::panel')
کد فعال سازی :  {{ $code }}
@endcomponent

با تشکر ,<br>
{{ config('app.name') }}
@endcomponent