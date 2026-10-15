/* ==========================================================================
   AK Designer Art — WhatsApp-style chatbot widget
   Vanilla JavaScript. No dependencies. AJAX via fetch().
   ========================================================================== */
(function () {
    'use strict';

    var root = document.getElementById('aktechsol-chatbot');
    if (!root) {
        return;
    }

    var ENDPOINT = root.getAttribute('data-endpoint') || '';
    var CSRF = root.getAttribute('data-csrf') || '';
    var GREETING = root.getAttribute('data-greeting') || 'Hi dear student, how can I help you?';

    var trigger = root.querySelector('.akcb-trigger');
    var panel = root.querySelector('.akcb-panel');
    var closeBtn = root.querySelector('.akcb-close');
    var messages = root.querySelector('.akcb-messages');
    var form = root.querySelector('.akcb-form');
    var input = root.querySelector('.akcb-input');
    var sendBtn = root.querySelector('.akcb-send');
    var errorBar = root.querySelector('.akcb-error');
    var errorText = root.querySelector('.akcb-error__text');
    var typingEl = root.querySelector('.akcb-typing');

    var isOpen = false;
    var hasGreeted = false;
    var isWaiting = false;      // a reply request is in flight (one message at a time)
    var lastSendAt = 0;         // client-side minimum gap between sends
    var errorTimer = null;

    var MIN_SEND_GAP_MS = 1500; // extra client-side guard on top of server throttle
    var MAX_LEN = 500;

    /* ---------- helpers ---------- */

    function scrollBottom() {
        messages.scrollTop = messages.scrollHeight;
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Make URLs clickable. The text is escaped first, so the only HTML
     * in the output is the <a> tags we generate here — XSS-safe.
     */
    function linkify(text) {
        return escapeHtml(text).replace(/(https?:\/\/[^\s<]+|www\.[^\s<]+)/gi, function (url) {
            var clean = url.replace(/[.,;:!?)\]]+$/, '');
            var trail = url.slice(clean.length);
            var href = /^https?:\/\//i.test(clean) ? clean : 'https://' + clean;
            return '<a href="' + href + '" target="_blank" rel="noopener noreferrer">' + clean + '</a>' + trail;
        });
    }

    /**
     * Soft notification pop played when the chat auto-opens via URL
     * (never on manual open, never twice). Synthesized with WebAudio —
     * no audio file needed. Browsers block audio before the first user
     * gesture, so we attempt an immediate resume (granted when the
     * origin already has media engagement, e.g. a returning visitor).
     * Otherwise the pop fires on the first interaction, but only if it
     * happens within a few seconds of the chat opening — a pop minutes
     * later on an unrelated click (e.g. the send button) would feel
     * broken, so in that case we stay silent instead.
     */
    var autoSoundCtx = null;
    var autoSoundDone = false;
    var AUTO_SOUND_WINDOW_MS = 10000;

    function playPopNotes(ctx) {
        var t = ctx.currentTime;
        [[660, 0], [880, 0.09]].forEach(function (note) {
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.value = note[0];
            gain.gain.setValueAtTime(0.0001, t + note[1]);
            gain.gain.exponentialRampToValueAtTime(0.25, t + note[1] + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + note[1] + 0.18);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(t + note[1]);
            osc.stop(t + note[1] + 0.2);
        });
    }

    function playAutoOpenSound() {
        if (autoSoundDone) {
            return;
        }
        try {
            var Ctx = window.AudioContext || window.webkitAudioContext;
            if (!Ctx) {
                return;
            }
            if (!autoSoundCtx) {
                autoSoundCtx = new Ctx();
            }
            var ctx = autoSoundCtx;
            var autoOpenedAt = Date.now();

            var fire = function () {
                if (autoSoundDone) {
                    return;
                }
                autoSoundDone = true;
                playPopNotes(ctx);
            };

            var disarm = function () {
                autoSoundDone = true;
                document.removeEventListener('pointerdown', onFirstGesture);
                document.removeEventListener('touchstart', onFirstGesture);
                document.removeEventListener('keydown', onFirstGesture);
            };

            if (ctx.state === 'running') {
                fire();
                return;
            }

            // Best effort: ask the browser to resume right away. This is
            // granted on page load when the origin already has media
            // engagement (e.g. a returning visitor).
            if (ctx.resume) {
                ctx.resume().then(function () {
                    if (ctx.state === 'running') {
                        fire();
                    }
                }).catch(function () {
                    // Still blocked — the gesture fallback below handles it.
                });
            }

            // Last resort: play on the very first interaction with the page,
            // but only while it still feels connected to the chat opening.
            var onFirstGesture = function () {
                disarm();
                if (Date.now() - autoOpenedAt > AUTO_SOUND_WINDOW_MS) {
                    return; // too late — a disconnected pop is worse than none
                }
                ctx.resume().then(function () {
                    fire();
                }).catch(function () {
                    // Audio unavailable — stay silent.
                });
            };
            document.addEventListener('pointerdown', onFirstGesture);
            document.addEventListener('touchstart', onFirstGesture);
            document.addEventListener('keydown', onFirstGesture);

            // Transparency aid: if the browser is still blocking audio a
            // moment later, say so in the console — proof it is the
            // browser's autoplay policy, not the widget.
            setTimeout(function () {
                if (!autoSoundDone && autoSoundCtx && autoSoundCtx.state !== 'running') {
                    console.info(
                        '[AK Chatbot] Auto-open sound was blocked by the browser ' +
                        '(autoplay policy: audio needs a user gesture first). ' +
                        'It will play on your first tap/click within a few seconds.'
                    );
                }
            }, 1500);
        } catch (e) {
            // Audio blocked or unsupported — stay silent.
        }
    }

    /**
     * Pop sound for manual opens via the floating trigger button.
     * A click is a user gesture, so the browser always allows this —
     * no deferral needed.
     */
    function playTriggerSound() {
        try {
            var Ctx = window.AudioContext || window.webkitAudioContext;
            if (!Ctx) {
                return;
            }
            if (!autoSoundCtx) {
                autoSoundCtx = new Ctx();
            }
            var ctx = autoSoundCtx;
            if (ctx.state === 'running') {
                playPopNotes(ctx);
                return;
            }
            if (ctx.resume) {
                ctx.resume().then(function () {
                    playPopNotes(ctx);
                }).catch(function () {
                    // Audio unavailable — stay silent.
                });
            }
        } catch (e) {
            // Audio blocked or unsupported — stay silent.
        }
    }

    function timeNow() {
        return new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
    }

    function showError(message, autoHideMs) {
        errorText.textContent = message;
        root.classList.add('akcb-error-visible');
        if (errorTimer) {
            clearTimeout(errorTimer);
        }
        errorTimer = setTimeout(hideError, autoHideMs || 4500);
    }

    function hideError() {
        root.classList.remove('akcb-error-visible');
        if (errorTimer) {
            clearTimeout(errorTimer);
            errorTimer = null;
        }
    }

    function setWaiting(waiting) {
        isWaiting = waiting;
        root.classList.toggle('akcb-waiting', waiting);
        input.disabled = waiting;
        sendBtn.disabled = waiting;
        if (waiting) {
            scrollBottom();
        }
    }

    function ticksHtml() {
        return '<span class="akcb-msg__ticks" aria-hidden="true">' +
            '<svg width="16" height="11" viewBox="0 0 16 11" fill="none">' +
            '<path d="M11.07.65l-5.5 6.13L2.6 4.2 1.5 5.34l4.07 3.7 6.6-7.36z" fill="currentColor"/>' +
            '<path d="M15.5.65l-5.5 6.13-.86-.78-1.1 1.14 1.96 1.78 6.6-7.36z" fill="currentColor"/>' +
            '</svg></span>';
    }

    function addMessage(text, type) {
        var bubble = document.createElement('div');
        bubble.className = 'akcb-msg akcb-msg--' + (type === 'out' ? 'out' : 'in');

        var textSpan = document.createElement('span');
        textSpan.className = 'akcb-msg__text';
        if (type === 'in') {
            // Bot replies are admin-authored rich HTML (Quill) — render as-is.
            // (Server replies and the static greeting contain no user input.)
            textSpan.innerHTML = text;
            // Actual HTML replies get a comfortable minimum bubble width
            // (like a normal message bubble). Plain-text bot messages
            // (e.g. the greeting) keep the exact original rendering.
            if (/<[a-z][\s\S]*>/i.test(text)) {
                bubble.classList.add('akcb-msg--rich');
            }
        } else {
            textSpan.innerHTML = linkify(text); // escaped first inside linkify — XSS-safe
        }
        bubble.appendChild(textSpan);

        var meta = document.createElement('span');
        meta.className = 'akcb-msg__meta';

        var time = document.createElement('span');
        time.textContent = timeNow();
        meta.appendChild(time);

        if (type === 'out') {
            var ticks = document.createElement('span');
            ticks.innerHTML = ticksHtml();
            ticks.firstChild.classList.add('akcb-ticks-el');
            meta.appendChild(ticks.firstChild);
            bubble.setAttribute('data-ticks', 'sent');
        }

        bubble.appendChild(meta);
        // Keep the typing indicator as the last item in the chat.
        messages.insertBefore(bubble, typingEl);
        scrollBottom();
        return bubble;
    }

    function markLastOutgoingRead() {
        var outgoing = messages.querySelectorAll('.akcb-msg--out');
        if (!outgoing.length) {
            return;
        }
        var last = outgoing[outgoing.length - 1];
        var ticks = last.querySelector('.akcb-msg__ticks');
        if (ticks) {
            ticks.classList.add('akcb-read');
        }
    }

    function addDayLabel() {
        var label = document.createElement('div');
        label.className = 'akcb-day';
        label.textContent = 'Today';
        messages.insertBefore(label, typingEl);
    }

    /* ---------- open / close ---------- */

    function openChat() {
        if (isOpen) {
            return;
        }
        isOpen = true;
        hideError();
        root.classList.add('akcb-open');
        root.classList.remove('akcb-has-unread');
        trigger.setAttribute('aria-expanded', 'true');
        panel.setAttribute('aria-hidden', 'false');
        if (!hasGreeted) {
            hasGreeted = true;
            addDayLabel();
            greet();
        }
        setTimeout(function () {
            if (!input.disabled) {
                input.focus();
            }
        }, 300);
    }

    function closeChat() {
        isOpen = false;
        root.classList.remove('akcb-open');
        trigger.setAttribute('aria-expanded', 'false');
        panel.setAttribute('aria-hidden', 'true');
    }

    function greet() {
        setWaiting(true);
        setTimeout(function () {
            setWaiting(false);
            addMessage(GREETING, 'in');
            root.classList.add('akcb-has-unread');
        }, 1200);
    }

    /* ---------- sending ---------- */

    function sendMessage() {
        var text = input.value.trim();

        if (text === '') {
            input.classList.remove('akcb-shake');
            void input.offsetWidth; // restart animation
            input.classList.add('akcb-shake');
            input.focus();
            return;
        }

        if (text.length > MAX_LEN) {
            showError('Your message is too long. Please keep it under ' + MAX_LEN + ' characters.');
            return;
        }

        if (isWaiting) {
            showError('Please wait for the reply to your last message before sending another.');
            return;
        }

        if (Date.now() - lastSendAt < MIN_SEND_GAP_MS) {
            showError("You're sending messages too quickly. Please wait a moment and try again.");
            return;
        }

        lastSendAt = Date.now();
        input.value = '';
        hideError();
        addMessage(text, 'out');
        setWaiting(true);

        var startedAt = Date.now();

        fetch(ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: text })
        })
            .then(function (response) {
                if (response.status === 429) {
                    throw { rateLimited: true };
                }
                return response.json().then(function (data) {
                    return { status: response.status, ok: response.ok, data: data };
                });
            })
            .then(function (result) {
                if (!result.ok) {
                    var msg = 'Something went wrong. Please try again.';
                    if (result.status === 422 && result.data && result.data.errors && result.data.errors.message) {
                        msg = result.data.errors.message[0];
                    }
                    throw { message: msg };
                }
                // Keep the typing indicator visible for a natural beat.
                var elapsed = Date.now() - startedAt;
                var wait = Math.max(0, 800 - elapsed);
                setTimeout(function () {
                    setWaiting(false);
                    markLastOutgoingRead();
                    addMessage(result.data.reply || GREETING, 'in');
                }, wait);
            })
            .catch(function (err) {
                setWaiting(false);
                if (err && err.rateLimited) {
                    showError("You're sending messages too quickly. Please wait a moment and try again.");
                } else if (err && err.message) {
                    showError(err.message);
                } else {
                    showError('Connection problem. Please check your internet and try again.');
                }
            });
    }

    /* ---------- events ---------- */

    trigger.addEventListener('click', function () {
        if (isOpen) {
            closeChat();
        } else {
            openChat();
            playTriggerSound();
        }
    });

    closeBtn.addEventListener('click', closeChat);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        sendMessage();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) {
            closeChat();
        }
    });

    /* ---------- auto-open via URL ---------- */

    // After the website has COMPLETELY loaded, wait ~2 seconds, then
    // auto-open the chat and attempt the notification sound.
    // No clicks needed in the code path — but note browsers block audio
    // until the visitor's first gesture (see playAutoOpenSound).
    function autoOpenWhenReady() {
        var open = function () {
            setTimeout(function () {
                openChat();
                playAutoOpenSound();
            }, 2000);
        };
        if (document.readyState === 'complete') {
            open();
        } else {
            window.addEventListener('load', open);
        }
    }

    try {
        var params = new URLSearchParams(window.location.search);
        var auto = params.get('chat') === 'true' ||
            params.get('chatbot') === 'true' ||
            params.get('chat_chatbot') === 'true';
        if (auto) {
            autoOpenWhenReady();
        }
    } catch (e) {
        // URLSearchParams unsupported — widget still works manually.
    }
})();
