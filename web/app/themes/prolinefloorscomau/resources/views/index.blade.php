@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    @if (!have_posts())
        <x-alert type="warning">
            {!! __('Sorry, no results were found.', 'sage') !!}
        </x-alert>
        {!! get_search_form(false) !!}
    @endif

    <div class="proline-section-dark-vanilla proline-section-no-margin proline-section-padding">
        <p class="uppercase">Learn</p>
        <h2 class="proline-section-jumbo-text">Blog</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @while (have_posts())
                @php(the_post())
                @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
            @endwhile
        </div>
    </div>
    {!! get_the_posts_navigation() !!}
@endsection

@section('sidebar')
    @include('sections.sidebar')
@endsection
