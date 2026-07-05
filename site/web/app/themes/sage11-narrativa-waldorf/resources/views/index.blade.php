@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    <div class="container mb-12">
        <x-add-space :space-id="'cat_start'"/>
    </div>

    @if (!have_posts())
        <div class="container mx-auto flex flex-col items-center justify-center py-12">
            <h1 class="text-4xl font-bold mb-4">Sin contenido</h1>
            <p class="text-lg text-gray-600 mb-8">Lo sentimos, el recurso que buscas no existe o fue movido.</p>
            <a href="{{ home_url('/') }}" class="btn btn-primary">Volver al inicio</a>
        </div>

        {{-- {!! get_search_form(false) !!} --}}
    @endif

    <div class="container flex flex-wrap mx-auto">
        @php($postIndex = 0)
        @while (have_posts())
            @php(the_post())

            @if ($postIndex === 3)
                <div class="w-full my-8">
                    <x-add-space :space-id="'cat_content'"/>
                </div>
            @endif

            @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
            @php($postIndex++)
        @endwhile
    </div>


    <div class="container mx-auto mt-8">
        {!! get_the_posts_navigation([
            'prev_text' => __('Previous', 'sage'),
            'next_text' => __('Next', 'sage'),
        ]) !!}
    </div>

    <div class="container mt-12">
        <x-add-space :space-id="'footer'"/>
    </div>
@endsection

{{-- @section('sidebar')
    @include('sections.sidebar')
@endsection --}}
