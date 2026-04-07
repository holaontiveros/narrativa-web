@props([
    'main_title' => null,
    'query',
])

<div class="posts-by-category">
    @if ($main_title)
        <h2 class="headline-4 mb-10">{{ $main_title }}</h2>
    @endif
    <div class="flex flex-wrap md:flex-nowrap gap-8">
        @while ($query->have_posts())
            @php($query->the_post())
            @include('partials.content')
        @endwhile
        @php(wp_reset_postdata())
    </div>
</div>
