        {!! $getFeaturedImage($post, 'w-full h-75 object-cover mb-6') !!}

        <article @php(post_class('h-entry'))>
            <div class="prose mx-auto max-w-3xl">
                <header>
                    <h1 class="p-name">
                        {!! $title !!}
                    </h1>

                </header>

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
