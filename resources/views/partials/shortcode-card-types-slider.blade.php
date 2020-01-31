@php($slides = (new \App\Http\Controllers\Admin\CardTypesSlidesController())->getAllCardTypesSlides())
@if(!empty($slides))
    <div class="card-types-slider slider-arrows-type-one shortcode">
        @foreach($slides as $slide)
            @if((isset($mobile) && $mobile && $slide->mobile_visible) || (isset($mobile) && !$mobile && $slide->desktop_visible))
                <div class="single-slide text-center padding-left-25 padding-right-25">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img src="{{URL::asset('assets/uploads/'. $slide->media->name)}}" alt="{{$slide->media->alt}}" class="max-width-150 width-100 margin-0-auto"/>
                    </figure>
                    <h3 class="fs-30  lato-black">{{$slide->title}}</h3>
                    <p class="fs-20">{{$slide->text}}</p>
                </div>
            @endif
        @endforeach
    </div>
@endif