@props(['posts'])
<div class="featured-posts flex flex-wrap md:flex-nowrap gap-6">

    @foreach ($posts as $post)
        @if ($loop->first)
            <div class="w-full lg:w-6/12">
                @include('partials.fetaured-big', ['post' => $post])
            </div>
            <div class="w-full lg:w-6/12 flex flex-col gap-6">
        @endif

        @if (!$loop->first)
            @include('partials.fetaured-small', ['post' => $post])
        @endif

        @if ($loop->last)
</div>
@endif
@endforeach
</div>
