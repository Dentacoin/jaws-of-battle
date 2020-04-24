<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="shortcut icon" href="{{URL::asset('assets/images/favicon.png') }}" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @if(!empty($meta_data))
        <title>{{$meta_data->title}}</title>
        <meta name="description" content="{{$meta_data->description}}" />
        <meta name="keywords" content="{{$meta_data->keywords}}" />
        <meta property="og:url" content="{{Request::url()}}"/>
        <meta property="og:title" content="{{$meta_data->social_title}}"/>
        <meta property="og:description" content="{{$meta_data->social_description}}"/>
        @if(!empty($meta_data->media))
            <meta property="og:image" content="{{URL::asset('assets/uploads/'.$meta_data->media->name)}}"/>
            <meta property="og:image:width" content="1200"/>
            <meta property="og:image:height" content="630"/>
        @endif
    @endif
    @if(!empty(Route::current()) && Route::current()->getName() == 'home')
        <link rel="canonical" href="{{route('home')}}" />
    @endif
    <style>

    </style>
    {{--<link rel="stylesheet" type="text/css" href="/dist/css/front-libs-style.css?v=1.0.3">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?v=1.0.3">--}}
    <script>
        var HOME_URL = '{{ route("home") }}';
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-108398439-6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        @if(empty($_COOKIE['performance_cookies']))
            gtag('config', 'UA-108398439-6', {'anonymize_ip': true});
        @else
            gtag('config', 'UA-108398439-6');
        @endif
    </script>
</head>
<body class="@if(!empty(Route::current())) {{Route::current()->getName()}} @else class-404 @endif">

<header @if(!empty(Route::current()) && Route::current()->getName() == 'home') class="home-header" @endif>

</header>
<main>@yield('content')</main>
<footer class="padding-bottom-30 padding-top-40 margin-top-20 container border-top">
    <div class="row fs-0 padding-bottom-15">
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block col-xs-6 text-right">
            <a href="https://play.google.com/apps/testing/com.DentaCare.JawsOfBattle" target="_blank">
                <img src="/assets/uploads/google-play-badge.svg" class="width-100 max-width-170" alt="Google Play button"/>
            </a>
        </figure>
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block col-xs-6 text-left">
            <a href="https://testflight.apple.com/join/hOg8An1t" target="_blank">
                <img src="/assets/uploads/app-store.svg" class="width-100 max-width-170" alt="App Store button"/>
            </a>
        </figure>
    </div>
    <div class="row fs-0 border-top padding-top-40">
        <div class="col-xs-12 col-md-3 inline-block text-center-xs text-center-sm padding-bottom-xs-20 padding-bottom-sm-20">
            <figure itemscope="" itemtype="http://schema.org/Organization">
                <a itemprop="url" href="//dentacoin.com" class="fs-14">
                    <img src="/assets/images/logo.svg" itemprop="logo" class="max-width-30" alt="Dentacoin logo"/>
                    <span class="color-main padding-left-10 inline-block">Powered by Dentacoin</span>
                </a>
            </figure>
        </div>
        <div class="col-xs-12 col-md-6 text-center inline-block padding-bottom-xs-20 padding-bottom-sm-20">
            @if(!empty(Route::current()))
                @php($footer_menu = \App\Http\Controllers\Controller::instance()->getMenu('footer'))
                @if(!empty($footer_menu) && sizeof($footer_menu) > 0)
                    <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement" class="fs-14 color-main">
                        @php($pass_first = false)
                        @foreach($footer_menu as $menu_el)
                            @if((isset($mobile) && $mobile && $menu_el->mobile_visible) || (isset($mobile) && !$mobile && $menu_el->desktop_visible))
                                @if($pass_first)
                                    <li class="inline-block-top separator">|</li>
                                @endif
                                <li class="inline-block-top"><a @if($menu_el->new_window) target="_blank" @endif itemprop="url" href="{{$menu_el->url}}" class="color-main {{$menu_el->class_attribute}}"><span itemprop="name">{!! $menu_el->name !!}</span></a></li>
                                @if(!$pass_first)
                                    @php($pass_first = true)
                                @endif
                            @endif
                        @endforeach
                    </ul>
                @endif
            @endif
        </div>
        <div class="col-xs-12 col-md-3 inline-block text-right socials text-center-xs text-center-sm" itemscope="" itemtype="http://schema.org/Organization">
            <link itemprop="url" href="{{ route('home') }}">
            <span class="padding-right-10 inline-block fs-14">Stay in the loop:</span>
            <ul class="inline-block">
                <li class="inline-block">
                    <a itemprop="sameAs" target="_blank" href="https://www.facebook.com/dentacare.jaws/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                </li>
                <li class="inline-block telegram">
                    <a itemprop="sameAs" target="_blank" href="https://t.me/dentacoin"><i class="fa fa-telegram"></i></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row color-main">
        <div class="col-xs-12 text-center fs-14 padding-top-20">
            © {{date('Y')}} Dentacoin Foundation. All rights reserved.
            <div><a href="//dentacoin.com/assets/uploads/dentacoin-foundation.pdf" class="text-decoration" target="_blank">Verify Dentacoin Foundation</a> | <a href="//dentacoin.com/privacy-policy" target="_blank" class="text-decoration">Privacy Policy</a></div>
        </div>
    </div>
</footer>

<div class="bottom-fixed-container">
    @if(empty($_COOKIE['performance_cookies']) && empty($_COOKIE['functionality_cookies']) && empty($_COOKIE['marketing_cookies']) && empty($_COOKIE['strictly_necessary_policy']))
        <div class="privacy-policy-cookie">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text inline-block">This site uses cookies. Find out more on how we use cookies in our <a href="https://dentacoin.com/privacy-policy" class="link" target="_blank">Privacy Policy</a>. | <a href="javascript:void(0);" class="link adjust-cookies">Adjust cookies</a></div>
                        <div class="button inline-block"><a href="javascript:void(0);" class="light-blue-white-btn white-border accept-all">Accept all cookies</a></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="/dist/js/front-libs-script.js?v=1.0.3"></script>
<script src="https://dentacoin.com/assets/js/basic.js?v=1.0.3"></script>
{{--<script src="/assets/js/index.js?v=1.0.3"></script>--}}
<script src="/dist/js/front-script.js?v=1.0.3"></script>

</body>
</html>