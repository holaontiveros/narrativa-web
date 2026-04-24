<form role="search" method="get" class="search-form w-full mx-auto" action="{{ home_url('/') }}">
  <div class="relative">
    <label>
      <span class="sr-only">
        {{ _x('Search for:', 'label', 'sage') }}
      </span>

      <input
        type="search"
        placeholder="{!! esc_attr_x('Search &hellip;', 'placeholder', 'sage') !!}"
        value="{{ get_search_query() }}"
        name="s"
        class="w-full pl-4 pr-12 py-3 text-sm bg-white border border-gray-200 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300"
      >
    </label>

    <button
      type="submit"
      class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-blue-500 focus:outline-none focus:text-blue-500 transition-colors duration-200"
      aria-label="{{ _x('Search', 'submit button', 'sage') }}"
    >
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
      </svg>
    </button>
  </div>
</form>
