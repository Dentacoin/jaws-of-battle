@extends("layout")
@section("content")
    @if(!empty($page->html))
        {{(new \App\Http\Controllers\PagesController())->shortcodeExtractor(html_entity_decode($page->html))}}
    @endif
    <section class="section-intro">
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="frame-figure first-sprite">
            <img src="/assets/uploads/dentacare-game-home-page-card-arch.png" class="width-100 frame" alt="Frame">
            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="logo">
                <img src="/assets/uploads/dentacare-jaws-of-battle-logo.png" class="width-100 max-width-350" alt="Jaws of battle logo">
            </figure>
        </figure>
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="hidden-frame">
            <img src="/assets/uploads/dentacare-game-home-page-card-arch.png" class="width-100" alt="Frame">
        </figure>
        <div class="absolute-content">
            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="moving-image">
                <img src="/assets/uploads/dentacare-website-mooving-header-sprite.png" alt="Jaws of battle characters">
            </figure>
        </div>
        <div class="below-absolute-content behind"></div>
        <div class="below-absolute-content in-front-of text-center">
            <iframe src="https://www.youtube.com/embed/53604WPHUeY"></iframe>
            <h1 class="color-white fs-48 line-height-48 lato-black">FAMILY FUN FOR<br>ALL AGES</h1>
            <p class="color-white fs-18 max-width-600 margin-0-auto">Become the ultimate Dentawarrior in Dentacare: Jaws of Battle – the first and only educational trading card game that helps you develop healthy dental care habits! <br><br>Lock jaws and face your opponents in head to head combat to determine who can keep most of their teeth standing. Unlock new cards, build the ultimate deck and pave your way to the top. Do you have what it takes to become Arena Champion?</p>
            <div class="apps-btns">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block android-btn padding-right-15 padding-right-xs-0">
                    <a href="https://play.google.com/apps/testing/com.DentaCare.JawsOfBattle" target="_blank">
                        <img src="/assets/uploads/google-play-badge.svg" class="max-width-240 max-width-xs-210 width-100" alt="Google Play button"/>
                    </a>
                </figure>
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block ios-btn">
                    <a href="https://testflight.apple.com/join/hOg8An1t" target="_blank">
                        <img src="/assets/uploads/app-store.svg" class="max-width-240 max-width-xs-210 width-100" alt="App Store button"/>
                    </a>
                </figure>
            </div>
        </div>
    </section>
    <section class="section-how-to-play">
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="wave-separator">
            <img src="/assets/uploads/wave-below-intro-section.png" class="width-100" alt="Wave separating sections"/>
        </figure>
        <div class="white-background">
            <div class="container text-center padding-bottom-30">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="lato-black fs-48 fs-xs-32">HOW TO PLAY</h2>
                        <h3 class="padding-bottom-50 fs-32 fs-xs-22 padding-bottom-xs-20">Form oral hygiene habits while playing a fun card game</h3>
                        @include('partials.shortcode-card-types-slider')
                        <div class="line-separator"></div>
                        <div>
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block padding-left-20 padding-right-20">
                                <img src="/assets/uploads/toothx1.png" class="width-100 max-width-200" alt="Tooth x1"/>
                            </figure>
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block padding-left-20 padding-right-20">
                                <img src="/assets/uploads/toothx2.png" class="width-100 max-width-200" alt="Tooth x2"/>
                            </figure>
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block padding-left-20 padding-right-20">
                                <img src="/assets/uploads/toothx3.png" class="width-100 max-width-200" alt="Tooth x3"/>
                            </figure>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                        <h3 class="fs-30 lato-black padding-top-15 padding-bottom-10">Number of targets</h3>
                        <p class="fs-20">This indicates the number of targets affected by a card's effect. The higher the number the more teeth will be hit. Splash cards are a great way to turn the tides of the game.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-a-jaw-breaking-game-experience">
        <div class="container-fluid padding-bottom-50">
            <div class="row fs-0">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block-top col-xs-12 col-sm-8 col-sm-offset-2 col-lg-7 col-lg-offset-0 padding-top-100 padding-top-sm-50 padding-top-xs-0">
                    <img src="/assets/uploads/cards-hand.png" class="width-100" alt="Group of cards"/>
                </figure>
                <div class="inline-block-top color-white col-xs-12 col-sm-10 col-sm-offset-1 col-lg-4 col-lg-offset-0 padding-top-sm-50 padding-top-md-50 padding-top-250 padding-top-xs-70 fs-18 line-height-20">
                    <h2 class="fs-40 fs-xs-24 line-height-55 line-height-xs-34 lato-black">A JAW-BRAKING CARD GAME EXPERIENCE</h2><br>
                    <p>
                        Breathe some fresh air into what has always been a dull chore and start having fun doing it for once. Learn, as you play - every card mimics the real world effects it has on your teeth, so you will be an expert on dental health in no time!<br><br>Collect cards and battle your way to the top, by challenging fierce bosses. Learn more about dental care and develop healthy habits along the journey. <br><br>So what are you waiting for? Brush your teeth and prepare for battle!
                    </p>
                    <br>
                    <div class="row fs-0">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block android-btn col-xs-6 text-right">
                            <a href="https://play.google.com/apps/testing/com.DentaCare.JawsOfBattle" target="_blank">
                                <img src="/assets/uploads/google-play-badge.svg" class="width-100 max-width-200" alt="Google Play button"/>
                            </a>
                        </figure>
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block ios-btn col-xs-6 text-left">
                            <a href="https://testflight.apple.com/join/hOg8An1t" target="_blank">
                                <img src="/assets/uploads/app-store.svg" class="width-100 max-width-200" alt="App Store button"/>
                            </a>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <div class="container padding-bottom-50">
            <div class="row">
                <div class="col-xs-12">
                    @include('partials.shortcode-featured-cards-slider')
                </div>
            </div>
        </div>
    </section>
@endsection