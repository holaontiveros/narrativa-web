@props(['post'])

<div class="flex w-full gap-4" href="{{ get_permalink($post->ID) }}">
    {{-- featured image --}}
    <div class=" w-5/12 ">
        <a href="{{ get_permalink($post->ID) }}">
            {!! $getFeaturedImage($post, 'aspect-square object-cover') !!}
        </a>
    </div>
    <div class="flex flex-col justify-center gap-2 w-7/12">
        <span class="font-bold text-sm">{{ get_the_category($post->ID)[0]->name ?? '' }}</span>
        <h3 class="headline-5"><a href="{{ get_permalink($post->ID) }}">{{ $post->post_title }}</a></h3>
        @include('partials.entry-meta')

    </div>
</div>
