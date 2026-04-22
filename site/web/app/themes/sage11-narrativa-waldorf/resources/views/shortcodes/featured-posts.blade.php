@props(['posts', 'mainPost'])

@if (count($posts))
    <div class="featured-posts flex flex-wrap md:flex-nowrap gap-6">
            <div class="w-full lg:w-6/12">
                @include('partials.fetaured-big', ['post' => $mainPost])
            </div>
            <div class="w-full lg:w-6/12 flex flex-col gap-6">
              @foreach ($posts as $post)
              @include('partials.fetaured-small', ['post' => $post])
              @endforeach
            </div>
</div>

@endif
