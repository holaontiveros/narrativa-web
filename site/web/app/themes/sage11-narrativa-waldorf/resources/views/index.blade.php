@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    @if (!have_posts())
        <div class="container mx-auto flex flex-col items-center justify-center py-12">
            <h1 class="text-4xl font-bold mb-4">Sin contenido</h1>
            <p class="text-lg text-gray-600 mb-8">Lo sentimos, el recurso que buscas no existe o fue movido.</p>
            <a href="{{ home_url('/') }}" class="btn btn-primary">Volver al inicio</a>
        </div>

        {{-- {!! get_search_form(false) !!} --}}
    @endif

    <div class="container flex flex-wrap md:flex-nowrap gap-6 mx-auto">
        @while (have_posts())
            @php(the_post())
            @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
        @endwhile
    </div>


    <div class="container mx-auto mt-8">
        {!! get_the_posts_navigation([
            'prev_text' => __('Previous', 'sage'),
            'next_text' => __('Next', 'sage'),
        ]) !!}
    </div>
@endsection

@section('sidebar')
    @include('sections.sidebar')
@endsection
