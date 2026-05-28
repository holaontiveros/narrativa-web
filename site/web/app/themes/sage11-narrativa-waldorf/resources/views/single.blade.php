@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php(the_post())
        <div class="container mx-auto">
            <div class=" mb-12">
                <x-add-space space-id="post_start" />
            </div>
            @includeFirst(['partials.content-single-' . get_post_type(), 'partials.content-single'])

            <div class=" mt-12 ">
                <x-add-space space-id="footer" />
            </div>
        </div>
    @endwhile
@endsection
