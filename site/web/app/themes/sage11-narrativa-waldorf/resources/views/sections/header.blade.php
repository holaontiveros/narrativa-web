<header class="banner relative top-0 z-900 w-full mb-11" @keydown.window.escape="$store.mobileMenu.open = false"
    @mousedown.outside="$store.mobileMenu.open = false">
    <div class="flex container justify-between lg:justify-center flex-wrap lg:flex-col items-center">
        <div class="flex justify-center max-w-50 lg:max-w-200 w-full lg:mt-12.5 lg:mb-8">
            {!! do_shortcode('[brand]') !!}
        </div>


        <div class="relative flex items-center justify-between gap-7 py-4 lg:py-0 z-10">
            @if (has_nav_menu('primary_navigation'))
                <nav class="nav-primary fixed lg:relative lg:h-auto lg:w-full "
                    aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
                    <div class="nav-container fixed z-20 bg-white inset-x-0 w-full bottom-0 top-0 flex flex-col gap-2 overflow-y-scroll  px-4 py-5 pb-5 lg:relative lg:top-0 lg:flex-row lg:items-center lg:overflow-y-visible lg:bg-transparent lg:bg-none lg:px-0 lg:py-0"
                        x-data="{ selected: null }" x-cloak :class="!$store.mobileMenu.open ? 'hidden lg:flex' : 'flex'"
                        x-id="['sub-menu-dropdown']">

                        <div class="relative z-20 flex items-center justify-between md:hidden">
                            {!! do_shortcode('[brand]') !!}

                            <button class="text-secondary-500 bg-transparent lg:hidden" type="button"
                                @click="$store.mobileMenu.toggle()">
                                <div x-show="!$store.mobileMenu.open" x-cloak>
                                    <span class="sr-only">Open menu</span>
                                    @svg('menu_alt', 'h-12 w-12 text-secondary-500', ['aria-hidden' => 'true'])
                                </div>
                                <div x-show="$store.mobileMenu.open" x-cloak>
                                    <span class="sr-only">Close menu</span>
                                    @svg('close', 'h-12 w-12 text-secondary-500', ['aria-hidden' => 'true'])
                                </div>
                            </button>

                        </div>

                        {!! wp_nav_menu([
                            'theme_location' => 'category_navigation',
                            'menu_class' =>
                                'menu-container justify-center w-full font-semibold z-10 nav flex flex-col lg:flex-row relative  px-4 lg:px-0 text-black text-sm',
                            'container' => '',
                            'echo' => false,
                            'walker' => new App\Support\TaleNavWalker(),
                        ]) !!}

                    </div>

                </nav>
            @endif

            <div class="flex shrink-0 lg:hidden">
                <button class="bg-transparent lg:hidden" type="button" @click="$store.mobileMenu.toggle()">
                    <div x-show="!$store.mobileMenu.open" x-cloak>
                        <span class="sr-only">menu</span>
                        @svg('menu_alt', 'h-12 w-12 text-secondary-500', ['aria-hidden' => 'true'])
                    </div>
                    <div x-show="$store.mobileMenu.open" x-cloak>
                        <span class="sr-only">Close menu</span>
                        @svg('close', 'h-12 w-12 text-secondary-500', ['aria-hidden' => 'true'])
                    </div>
                </button>
            </div>

        </div>

        <div class="w-full border-b-4 border-secondary-500 pb-6 relative -z-0">
            {!! get_search_form(false) !!}
        </div>
    </div>
</header>
