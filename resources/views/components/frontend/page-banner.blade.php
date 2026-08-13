@props([
    'title'       => '',
    'subtitle'    => '',
    'description' => null,
])

<section class="bg-gradient-to-br from-blue-950 via-blue-900 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24">
        <div class="max-w-3xl">
            <h1 class="text-4xl sm:text-5xl font-extrabold">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-4 text-lg text-blue-100/80">{{ $subtitle }}</p>
            @endif
            @if($description)
                <p class="mt-3 text-blue-200/70 text-base">{{ $description }}</p>
            @endif
        </div>
    </div>
</section>
