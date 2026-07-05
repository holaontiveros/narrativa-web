@props(['adType' => 'image', 'bannerIdDesktop' => null, 'bannerIdMobile' => null, 'htmlBody' => null])

@if ($adType === 'html' && $htmlBody)
    <div class="mx-auto flex items-center justify-center">
      <div data-add-space class="relative inline-flex items-center justify-center min-w-[18.75rem] min-h-[6.25rem] md:min-w-[58rem] md:min-h-[11.25rem]">
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
