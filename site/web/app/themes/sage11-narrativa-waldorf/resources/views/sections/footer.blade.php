<footer class="content-info bg-gray-100 py-8">
    <div class="container mx-auto flex flex-col items-center">
        <div class="flex flex-wrap justify-between items-center w-full">
            <div class="w-full lg:w-60 mb-4 lg:mb-0">
                {!! do_shortcode('[brand]') !!}


                <div class="flex">
                    @foreach ($getSocialLinks() as $social)
                        <a href="{{ $social['link'] }}" target="_blank" class="inline-block mr-4">
                            <div class="bg-primary-500 rounded-full p-1">
                                <img src="{{ $social['icon'] }}" alt="Social Icon" class="w-6 h-6 invert">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <nav class="flex space-x-4">
                @if (has_nav_menu('footer_navigation'))
                    {!! wp_nav_menu(['theme_location' => 'footer_navigation', 'menu_class' => 'flex space-x-4', 'echo' => false]) !!}
                @endif
            </nav>

        </div>

        <hr class="my-6 w-full border-t border-gray-300">

        © {{ date('Y') }} {{ get_bloginfo('name') }}. {{ __('All rights reserved.', 'sage') }}
    </div>
</footer>
