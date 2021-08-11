@extends("layout")
@section("content")
    <section class="change-password-section">
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-10">
            <img src="/assets/uploads/dentacare-jaws-of-battle-logo.png" class="width-100 max-width-350" alt="Jaws of battle logo">
        </figure>
        <div class="form-container color-white padding-top-50 padding-bottom-50 max-width-600 padding-left-15 padding-right-15">
            <form class="change-password-form" action="{{ route('submit-change-password') }}">
                <h1 class="fs-44 lato-black">PASSWORD RECOVER</h1>
                <div class="fs-26 padding-bottom-25">Please enter your new password.</div>
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