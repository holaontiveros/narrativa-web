<div class="flex gap-4">
    {{-- User profile picture --}}
    @if (get_option('show_avatars'))
        <div class="p-author h-card">
            {!! get_avatar(get_the_author_meta('ID'), 48, '', '', ['class' => '!rounded-full']) !!}
        </div>
    @endif

    <div class="text-small">
        <p>
            <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}"
                class="p-author h-card font-bold text-primary-500">
                {{ get_the_author() }}
            </a>
        </p>

        <time class="dt-published" datetime="{{ get_post_time('c', true) }}">
            {{ get_the_date() }}
        </time>

    </div>
</div>
