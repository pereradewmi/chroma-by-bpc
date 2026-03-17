@if(session()->has('user_id') || auth()->check())
    @include('backend.layouts.navbars.navs.auth')
@else
    @include('backend.layouts.navbars.navs.guest')
@endif