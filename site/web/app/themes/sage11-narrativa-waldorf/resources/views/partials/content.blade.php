<article @php(post_class('w-full lg:basis-1/3 py-3 px-4 flex flex-col gap-4'))>
    <header>
        <a href="{{ get_permalink() }}" class="block mb-6">
            {!! $getFeaturedImage(0, 'aspect-video object-cover') !!}
        </a>

        <div class="font-bold text-sm mb-2">{{ get_the_category()[0]->name ?? '' }}</div>

        <h2 class="entry-title headline-5">
            <a href="{{ get_permalink() }}">
                {!! $title !!}
            </a>
        </h2>

    </header>

    <div class="entry-summary">
        @php(the_excerpt())
    </div>

    @include('partials.entry-meta')

</article>
