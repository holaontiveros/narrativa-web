@props(['bannerId'])

@if ($bannerId)
    <div class="mx-auto flex items-center justify-center">
      <div data-add-space class="relative inline-flex items-center justify-center">
        <div data-add-space-loader class="absolute inset-0 z-10 flex items-center justify-center">
          <span class="h-8 w-8 animate-spin rounded-full border-2 border-[#132A26]/20 border-t-[#132A26]" aria-hidden="true"></span>
          <span class="sr-only">Loading ad</span>
        </div>

        {!! wp_get_attachment_image($bannerId, 'full', false, [
            'class' => 'w-75 h-22.5 md:w-242 md:h-60 object-cover object-center opacity-0 transition-opacity duration-300',
            'loading' => 'lazy',
            'decoding' => 'async',
            'onload' => "this.style.opacity='1';this.closest('[data-add-space]')?.querySelector('[data-add-space-loader]')?.remove();",
            'onerror' => "this.closest('[data-add-space]')?.querySelector('[data-add-space-loader]')?.remove();",
        ]) !!}
      </div>
    </div>
@endif
