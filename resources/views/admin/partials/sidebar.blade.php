@php
$menu = [
    [
        'type' => 'link',
        'label' => 'Dashboard',
        'route' => 'admin.dashboard',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>',
        'active_patterns' => ['admin.dashboard'],
    ],
    [
        'type' => 'group',
        'label' => 'Services',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/></svg>',
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
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
        'active_patterns' => ['admin.projects.*'],
    ],
    [
        'type' => 'link',
        'label' => 'Testimonials',
        'route' => 'admin.testimonials.index',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 5H9v6h1V7zm2 0h2v6h-1V7z"/></svg>',
        'active_patterns' => ['admin.testimonials.*'],
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

    {{-- Scrollable navigation area --}}
    <nav class="flex-1 overflow-y-auto mt-4 px-3 space-y-1 pb-4">
        @foreach ($menu as $item)
            @if ($item['type'] === 'link')
                <a href="{{ route($item['route']) }}"
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs(...$item['active_patterns']) ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    {!! $item['icon'] !!}
                    <span class="ml-3">{{ $item['label'] }}</span>
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

                    <div class="mt-1 ml-4 space-y-1 {{ request()->routeIs(...$item['active_patterns']) ? '' : 'hidden' }}">
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

    {{-- Fixed bottom settings link --}}
    <div class="flex-shrink-0 border-t border-white/10 p-3">
        <a href="{{ route('admin.settings') }}"
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('admin.settings') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                    clip-rule="evenodd" />
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
