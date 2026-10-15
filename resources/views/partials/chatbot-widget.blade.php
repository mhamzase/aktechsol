{{-- WhatsApp-style chatbot widget (settings-driven). --}}
@php
    $s = $siteSettings ?? app(\App\Settings\SiteSettings::class);
    $showChatbot = !empty($s->chatbot_enabled);
    $teamName = $s->chatbot_team_name ?: 'AK Tech SOL Team';
    $greeting = $s->chatbot_greeting ?: 'Hi! How can we help you today?';
@endphp

@if ($showChatbot)
<div id="aktechsol-chatbot" data-endpoint="{{ route('chatbot.message') }}" data-csrf="{{ csrf_token() }}"
    data-greeting="{{ $greeting }}" aria-live="polite">

    {{-- Floating trigger --}}
    <button type="button" class="akcb-trigger" aria-label="Open chat with {{ $teamName }}" aria-expanded="false">
        <span class="akcb-trigger__pulse" aria-hidden="true"></span>
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 2a8 8 0 1 1-4.1 14.9l-.3-.2-2.9.8.8-2.8-.2-.3A8 8 0 0 1 12 4zm-3.2 4.1c-.2 0-.5 0-.7.3-.2.3-.9.9-.9 2.2s.9 2.5 1.1 2.7c.1.2 1.9 3 4.7 4 .6.3 1.1.4 1.5.6.6.2 1.2.2 1.6.1.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.6-.3l-2-1c-.3-.1-.5-.2-.7.1l-.9 1.1c-.2.2-.3.2-.6.1-.3-.1-1.2-.5-2.3-1.6-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.4.1-.6l.5-.6c.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5L8.6 6.4c-.2-.4-.4-.3-.8-.3z" />
        </svg>
        <span class="akcb-trigger__dot" aria-hidden="true"></span>
    </button>

    {{-- Chat panel --}}
    <div class="akcb-panel" role="dialog" aria-label="Chat with {{ $teamName }}" aria-hidden="true">
        <div class="akcb-header">
            <div class="akcb-avatar">
                @if(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="{{ $teamName }} logo">
                @else
                    <span class="akcb-avatar__letter">{{ strtoupper(substr($teamName, 0, 1)) }}</span>
                @endif
                <span class="akcb-avatar__status" aria-hidden="true"></span>
            </div>
            <div class="akcb-header__info">
                <div class="akcb-header__name">
                    <span>{{ $teamName }}</span>
                    <svg width="17" height="17" viewBox="0 0 24 24" aria-label="Verified account">
                        <circle cx="12" cy="12" r="10" fill="#53bdeb" />
                        <path d="M10.6 15.6l-3.1-3.1 1.4-1.4 1.7 1.7 4.9-4.9 1.4 1.4z" fill="#075e54" />
                    </svg>
                </div>
                <div class="akcb-header__status">online</div>
            </div>
            <button type="button" class="akcb-close" aria-label="Close chat">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 9l6 6 6-6" />
                </svg>
            </button>
        </div>

        <div class="akcb-messages" aria-live="polite">
            <div class="akcb-typing" aria-hidden="true">
                <span></span><span></span><span></span>
            </div>
        </div>

        <div class="akcb-error" role="alert">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
            </svg>
            <span class="akcb-error__text"></span>
        </div>

        <form class="akcb-form" autocomplete="off" novalidate>
            <div class="akcb-inputbar">
                <input type="text" class="akcb-input" name="message" placeholder="Type a message"
                    aria-label="Type a message" maxlength="500">
                <button type="submit" class="akcb-send" aria-label="Send message">
                    <svg class="akcb-send__icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M3.4 20.4l17.5-8.4L3.4 3.6l-.01 6.53L14 12 3.39 13.87z" />
                    </svg>
                    <span class="akcb-send__spinner" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>
</div>
@endif
