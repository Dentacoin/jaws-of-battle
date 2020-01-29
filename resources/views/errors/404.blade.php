@extends("layout")
@section("content")
    <div class="container-404">
        <figure>
            <img src="{{URL::asset('assets/images/404-page.svg') }}"/>
        </figure>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div class="ops">Oops! We couldn't find this page.</div>
                    <div class="homepage-link"><a href="{{ route('home') }}" class="white-light-blue-btn">BACK TO HOME</a></div>
                </div>
            </div>
        </div>
    </div>
@endsection