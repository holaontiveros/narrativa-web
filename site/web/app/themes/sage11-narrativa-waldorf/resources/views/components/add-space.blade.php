@props(['adType' => 'image', 'sticky' => false, 'popup' => false, 'bannerIdDesktop' => null, 'bannerIdMobile' => null, 'htmlBody' => null])

{{-- @dump($adType, $sticky, $popup, $bannerIdDesktop, $bannerIdMobile, $htmlBody) --}}

@php
    $hasContent = ($adType === 'html' && $htmlBody) || ($adType === 'image' && ($bannerIdDesktop || $bannerIdMobile));
@endphp

@if ($hasContent && $popup)
    <div
        data-add-space-popup
        class="fixed inset-0 z-999 hidden items-center justify-center bg-black/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-label="Advertisement"
    >
        <div class="relative rounded bg-white shadow-2xl">
            <button
                type="button"
                aria-label="Close ad"
                class="absolute cursor-pointer -top-3 -right-3 z-20 flex h-7 w-7 items-center justify-center rounded-full bg-white text-gray-700 shadow-md ring-1 ring-gray-200 hover:bg-gray-100"
                onclick="this.closest('[data-add-space-popup]').remove();"
            >
                <span aria-hidden="true" class="text-lg leading-none">&times;</span>
            </button>
@endif

@if ($hasContent && $sticky)
    <div data-add-space-sticky class="fixed bottom-4 right-4 z-50 shadow-2xl rounded bg-white">
        <button
            type="button"
            aria-label="Close ad"
            class="absolute cursor-pointer -top-3 -right-3 z-20 flex h-7 w-7 items-center justify-center rounded-full bg-white text-gray-700 shadow-md ring-1 ring-gray-200 hover:bg-gray-100"
            onclick="this.closest('[data-add-space-sticky]').remove();"
        >
            <span aria-hidden="true" class="text-lg leading-none">&times;</span>
        </button>
@endif

@if ($adType === 'html' && $htmlBody)
    <div class="mx-auto flex items-center justify-center relative z-0">
      <div data-add-space class="relative inline-flex items-center justify-center @unless($sticky || $popup) min-w-[18.75rem] min-h-[6.25rem] md:min-w-[58rem] md:min-h-[11.25rem] @endunless">
        <div data-add-space-loader class="absolute inset-0 z-10 flex items-center justify-center">
          <span class="h-8 w-8 animate-spin rounded-full border-2 border-[#132A26]/20 border-t-[#132A26]" aria-hidden="true"></span>
          <span class="sr-only">Loading ad</span>
        </div>
        <div data-add-space-html class="relative z-20">
          {!! $htmlBody !!}
        </div>
        <script>
          (function () {
            var script = document.currentScript;
            var container = script && script.previousElementSibling;
            if (!container) return;
            var wrapper = container.closest('[data-add-space]');
            var loader = wrapper && wrapper.querySelector('[data-add-space-loader]');
            if (!loader) return;

            var hasVisibleContent = function () {
              var nodes = container.querySelectorAll('*');
              for (var i = 0; i < nodes.length; i++) {
                var el = nodes[i];
                if (el.tagName === 'SCRIPT') continue;
                if (el.offsetWidth > 0 && el.offsetHeight > 0) {
                  return true;
                }
              }
              return false;
            };

            var removeLoader = function () { loader.remove(); };

            if (hasVisibleContent()) { removeLoader(); return; }

            var observer = new MutationObserver(function () {
              if (hasVisibleContent()) {
                observer.disconnect();
                removeLoader();
              }
            });
            observer.observe(container, { childList: true, subtree: true, attributes: true });

            // Safety net: stop waiting after 15s so a failing ad script doesn't leave the spinner forever.
            setTimeout(function () { observer.disconnect(); removeLoader(); }, 15000);
          })();
        </script>
      </div>
    </div>
@elseif ($adType === 'image' && ($bannerIdDesktop || $bannerIdMobile))
    <div class="mx-auto flex items-center justify-center">
      <div data-add-space class="relative inline-flex items-center justify-center">
        <div data-add-space-loader class="absolute inset-0 z-10 flex items-center justify-center">
          <span class="h-8 w-8 animate-spin rounded-full border-2 border-[#132A26]/20 border-t-[#132A26]" aria-hidden="true"></span>
          <span class="sr-only">Loading ad</span>
        </div>

        {!! wp_get_attachment_image($bannerIdDesktop, 'full', false, [
            'class' => 'hidden md:block w-232 h-45 object-cover object-center opacity-0 transition-opacity duration-300',
            'loading' => 'lazy',
            'decoding' => 'async',
            'onload' => "this.style.opacity='1';this.closest('[data-add-space]')?.querySelector('[data-add-space-loader]')?.remove();",
            'onerror' => "this.closest('[data-add-space]')?.querySelector('[data-add-space-loader]')?.remove();",
        ]) !!}

        {!! wp_get_attachment_image($bannerIdMobile, 'full', false, [
            'class' => 'block md:hidden w-75 h-25 object-cover object-center opacity-0 transition-opacity duration-300',
            'loading' => 'lazy',
            'decoding' => 'async',
            'onload' => "this.style.opacity='1';this.closest('[data-add-space]')?.querySelector('[data-add-space-loader]')?.remove();",
            'onerror' => "this.closest('[data-add-space]')?.querySelector('[data-add-space-loader]')?.remove();",
        ]) !!}

      </div>
    </div>
@endif

@if ($hasContent && $sticky)
    </div>
@endif

@if ($hasContent && $popup)
        </div>
    </div>
    <script>
      (function () {
        var popup = document.currentScript.previousElementSibling;
        if (!popup) return;
        var show = function () {
          setTimeout(function () {
            popup.classList.remove('hidden');
            popup.classList.add('flex');
          }, 10000);
        };
        if (document.readyState === 'complete') {
          show();
        } else {
          window.addEventListener('load', show, { once: true });
        }
      })();
    </script>
@endif
