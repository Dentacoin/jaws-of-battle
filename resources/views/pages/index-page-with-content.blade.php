@extends("layout")
@section("content")
    @if(!empty($page->html))
        {{(new \App\Http\Controllers\PagesController())->shortcodeExtractor(html_entity_decode($page->html))}}
    @endif
@endsection
