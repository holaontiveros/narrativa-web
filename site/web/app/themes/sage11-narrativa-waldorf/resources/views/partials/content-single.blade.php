<article @php(post_class('h-entry'))>
    <div class="prose mx-auto max-w-3xl">
        @if ($categories = get_the_category())
            <nav class="mb-4 pt-6 not-prose" aria-label="Breadcrumb">
                <ul class="flex space-x-2 text-sm text-gray-500">
                    @foreach ($categories as $category)
                        <li class="flex items-center space-x-2">
                            <a href="{{ get_category_link($category) }}" @class(['hover:underline', 'text-primary-500' => $loop->last])>
                                {{ $category->name }}
                            </a>
                            @if (!$loop->last)
                                <span>/</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif

        <header>
            <h1 class="p-name">
                {!! $title !!}
            </h1>

            @include('partials.entry-meta')


        </header>

        {!! $getFeaturedImage($post, 'w-full md:w-[calc(100%+10rem)] md:-ml-20 md:max-w-none object-contain mb-6') !!}


        <div class="e-content">
            @php(the_content())
        </div>
    </div>

    @if ($pagination())
        <footer>
            <nav class="page-nav" aria-label="Page">
                {!! $pagination !!}
            </nav>
        </footer>
    @endif


    <div class="mx-auto max-w-3xl">
        @include('partials.entry-meta')
        @include('partials.share-buttons')

        @php(comments_template())

    </div>


</article>
