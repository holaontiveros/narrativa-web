@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php(the_post())
        @include('partials.page-header')
        @includeFirst(['partials.content-page', 'partials.content'])
        <div class="mt-12">
            <x-add-space space-id="footer" />
        </div>
    @endwhile
@endsection
