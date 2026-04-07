@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    @if (!have_posts())
        <div class="container mx-auto flex flex-col items-center justify-center py-12">
            <h1 class="text-4xl font-bold mb-4">404 - Página no encontrada</h1>
            <p class="text-lg text-gray-600 mb-8">Lo sentimos, pero la página que buscas no existe.</p>
            <a href="{{ home_url('/') }}" class="btn btn-primary">Volver al inicio</a>
        </div>

        {{-- {!! get_search_form(false) !!} --}}
    @endif
@endsection
