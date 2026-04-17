@if (class_exists('Elementor\Plugin') &&
        Elementor\Plugin::instance()->documents->get(get_the_ID())->is_built_with_elementor())
    @php(the_content())
@else
    <div class="prose mx-auto max-w-3xl">
        @php(the_content())
    </div>
@endif

@if ($pagination())
    <nav class="page-nav" aria-label="Page">
        {!! $pagination !!}
    </nav>
@endif
