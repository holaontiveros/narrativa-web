@props([
    'main_title' => null,
    'query',
])

@if ($query->have_posts())
    <div class="posts-by-category">
        @if ($main_title)
            <h2 class="headline-4 mb-10">{{ $main_title }}</h2>
        @endif
        <div class="flex flex-wrap md:flex-nowrap gap-8">
            @while ($query->have_posts())
                @php
                    $query->the_post();
                    global $post;
                    setup_postdata($post);
                @endphp
                @include('partials.content')
            @endwhile

            @php
                wp_reset_postdata();
            @endphp
        </div>
    </div>
@endif
