@php
    $menu = [
        [
            'type' => 'link',
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>',
            'active_patterns' => ['admin.dashboard'],
        ],
        [
            'type' => 'group',
            'label' => 'Services',
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25v-4.25m13.5-6.9V6a2.25 2.25 0 00-2.25-2.25h-6A2.25 2.25 0 006 6v1.35m13.5 5.9a3 3 0 01-3 3h-9a3 3 0 01-3-3m0-5.9a3 3 0 013-3h9a3 3 0 013 3v5.9z"/></svg>',
            'active_patterns' => ['admin.services.*', 'admin.service-categories.*'],
            'children' => [
                [
                    'label' => 'Services List',
                    'route' => 'admin.services.index',
                    'active_patterns' => ['admin.services.*'],
                ],
                [
                    'label' => 'Categories',
                    'route' => 'admin.service-categories.index',
                    'active_patterns' => ['admin.service-categories.*'],
                ],
            ],
        ],
        [
            'type' => 'link',
            'label' => 'Projects',
            'route' => 'admin.projects.index',
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>',
            'active_patterns' => ['admin.projects.*'],
        ],
        [
            'type' => 'link',
            'label' => 'Testimonials',
            'route' => 'admin.testimonials.index',
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>',
            'active_patterns' => ['admin.testimonials.*'],
        ],
        [
            'type' => 'group',
            'label' => 'Blog',
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>',
            'active_patterns' => ['admin.blog-categories.*', 'admin.blog-posts.*'],
            'children' => [
                ['label' => 'Posts', 'route' => 'admin.blog-posts.index', 'active_patterns' => ['admin.blog-posts.*']],
                [
                    'label' => 'Categories',
                    'route' => 'admin.blog-categories.index',
                    'active_patterns' => ['admin.blog-categories.*'],
                ],
            ],
        ],
        [
            'type' => 'link',
            'label' => 'FAQs',
            'route' => 'admin.faqs.index',
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>',
            'active_patterns' => ['admin.faqs.*'],
        ],
        [
            'type' => 'link',
            'label' => 'Contact Messages',
            'route' => 'admin.contact-messages.index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>',
            'active_patterns' => ['admin.contact-messages.*'],
            'badge' => $unreadContactMessages ?? 0,
        ],
        [
            'type' => 'link',
            'label' => 'Newsletter',
            'route' => 'admin.newsletter-subscribers.index',
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z"/></svg>',
            'active_patterns' => ['admin.newsletter-subscribers.*'],
        ],
        [
            'type' => 'link',
            'label' => 'Media Library',
            'route' => 'admin.media.index',
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>',
            'active_patterns' => ['admin.media.*'],
        ],
    ];
@endphp

<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-blue-800 shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:z-auto flex flex-col">
    <div class="flex items-center justify-between h-16 px-4 border-b border-white/10 flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-2 text-white hover:opacity-80 transition-opacity">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo" class="h-8 w-auto object-contain">
            @endif
            <span class="text-xl font-bold text-white">AK Tech SOL</span>
        </a>
        <button class="lg:hidden text-white/70 hover:text-white" onclick="toggleSidebar()">
            <x-icons.x />
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto mt-4 px-3 space-y-1 pb-4">
        @foreach ($menu as $item)
            @if ($item['type'] === 'link')
                <a href="{{ route($item['route']) }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs(...$item['active_patterns']) ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    {!! $item['icon'] !!}
                    <span class="ml-3 flex-1">{{ $item['label'] }}</span>
                    @if (isset($item['badge']) && $item['badge'] > 0)
                        <span
                            class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-semibold text-white bg-red-500 rounded-full">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @elseif ($item['type'] === 'group')
                <div>
                    <button type="button" onclick="toggleSubmenu(this)"
                        class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs(...$item['active_patterns']) ? 'bg-white/10 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <span class="flex items-center">
                            {!! $item['icon'] !!}
                            <span class="ml-3">{{ $item['label'] }}</span>
                        </span>
                        <svg class="h-4 w-4 transition-transform duration-200 {{ request()->routeIs(...$item['active_patterns']) ? 'rotate-180' : '' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        class="mt-1 ml-4 space-y-1 {{ request()->routeIs(...$item['active_patterns']) ? '' : 'hidden' }}">
                        @foreach ($item['children'] as $child)
                            <a href="{{ route($child['route']) }}"
                                class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs(...$child['active_patterns']) ? 'bg-white/20 text-white shadow' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                                <span class="w-1.5 h-1.5 bg-current rounded-full mr-2 opacity-70"></span>
                                {{ $child['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>

    <div class="flex-shrink-0 border-t border-white/10 p-3">
        <a href="{{ route('admin.settings') }}"
            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('admin.settings') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="ml-3">Settings</span>
        </a>
    </div>
</aside>

<script>
    function toggleSubmenu(button) {
        const submenu = button.nextElementSibling;
        const chevron = button.querySelector('svg:last-child');
        if (submenu) {
            submenu.classList.toggle('hidden');
        }
        if (chevron) {
            chevron.classList.toggle('rotate-180');
        }
    }
</script>
