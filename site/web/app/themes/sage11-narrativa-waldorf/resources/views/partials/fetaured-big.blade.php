@props(['post'])

<div class="big">
    <a href="{{ get_permalink($post->ID) }}">
        {{-- featured image --}}
        <div class="mb-6">
            {!! $getFeaturedImage($post) !!}
        </div>
        <span class="font-bold text-sm mb-2">{{ get_the_category($post->ID)[0]->name ?? '' }}</span>
        <h3 class="headline-4 mb-2">{{ $post->post_title }}</h3>
        <p>{{ wp_trim_words($post->post_excerpt, 20) }}</p>
    </a>
</div>
