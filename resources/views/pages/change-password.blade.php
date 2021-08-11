@extends("layout")
@section("content")
    <section class="change-password-section">
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="text-center">
            <img src="/assets/uploads/dentacare-jaws-of-battle-logo.png" class="width-100 max-width-350" alt="Jaws of battle logo">
        </figure>
        <div class="form-container color-white padding-top-20 padding-bottom-100 max-width-600 margin-0-auto padding-left-15 padding-right-15">
            <form method="POST" class="change-password-form" action="{{ route('submit-change-password') }}">
                <h1 class="text-center fs-44 fs-xs-24 lato-black">PASSWORD RECOVER</h1>
                <div class="text-center fs-26 fs-xs-18 padding-bottom-25">Please enter your new password.</div>
                <div class="padding-bottom-15">
                    <input type="password" id="password" name="password" placeholder="Password:"/>
                </div>
                <div class="padding-bottom-35">
                    <input type="password" id="repeat-password" name="repeat-password" placeholder="Repeat password:"/>
                </div>
                <div class="text-center">
                    <button type="submit">
                        <input type="hidden" name="token" value="{{\Illuminate\Support\Facades\Input::get('token')}}"/>
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <img src="/assets/uploads/reset-pass-btn.png" alt="Button"/>
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection