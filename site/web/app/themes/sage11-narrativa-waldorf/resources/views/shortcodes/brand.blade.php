<div class="mx-auto max-w-[80%] inline-block shrink-0" itemscope itemtype="https://schema.org/Organization">
    <a class="inline-block" href="{{ $link ?? home_url('/') }}" itemprop="url"><span class="sr-only">Home</span>
        <img class="brand {{ $size }} hidden md:block" src="{{ $logo_path }}" alt=" Logo" itemprop="logo" />
        <img class="brand inline-block h-17.5 md:hidden" src="{{ $mobile_logo_path }}" alt=" Logo" itemprop="logo" />
    </a>
</div>
