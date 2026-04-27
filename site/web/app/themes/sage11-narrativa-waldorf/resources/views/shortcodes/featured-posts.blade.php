@props(['posts', 'mainPost'])

@if (count($posts) || $mainPost)

    <div class="featured-posts flex flex-wrap md:flex-nowrap gap-6">
        <div class="w-full lg:w-6/12">
            @include('partials.fetaured-big', ['post' => $mainPost])
        </div>
        <div class="w-full lg:w-6/12 flex flex-col gap-6">
            @foreach ($posts as $currentPost)
                @php
                    global $post;
                    $post = $currentPost;
                    setup_postdata($currentPost);
                @endphp
                @include('partials.fetaured-small', ['post' => $currentPost])
            @endforeach
        </div>
    </div>

    @php
        wp_reset_postdata();
    @endphp

@endif
