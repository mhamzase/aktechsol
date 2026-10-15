{{-- Modern frontend footer --}}
<footer class="bg-[#0a1f44] text-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            {{-- Brand --}}
            <div>
                <a href="{{ url('/') }}" class="inline-block">
                    @if($logoUrl)
                        <span class="inline-block bg-white rounded-xl px-3 py-2">
                            <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?? 'AK Tech SOL' }}" class="h-10 w-auto object-contain">
                        </span>
                    @else
                        <span class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#1d4ed8] to-[#2f6fed] text-white font-extrabold flex items-center justify-center text-lg">A</span>
                        <span class="font-extrabold text-lg text-white">AK Tech SOL</span>
                    @endif
                </a>
                <p class="text-sm text-slate-400 mt-4 leading-relaxed">{{ $siteSettings->footer_text ?? 'Professional software & freelancing agency delivering top-notch digital solutions.' }}</p>
                <div class="flex gap-2.5 mt-5">
                    @foreach([
                        [$siteSettings->facebook_url ?? null, 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z', 'Facebook'],
                        [$siteSettings->twitter_url ?? null, 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z', 'Twitter'],
                        [$siteSettings->linkedin_url ?? null, 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z', 'LinkedIn'],
                        [$siteSettings->instagram_url ?? null, 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z', 'Instagram'],
                    ] as [$href, $path, $label])
                        @if($href)
                            <a href="{{ $href }}" target="_blank" rel="noopener" aria-label="{{ $label }}"
                               class="w-9 h-9 rounded-lg bg-white/10 hover:bg-[#1d4ed8] flex items-center justify-center transition">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $path }}"/></svg>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-5">Company</h4>
                <ul class="space-y-3 text-sm">
                    @foreach([['/', 'Home'], ['/about', 'About Us'], ['/portfolio', 'Portfolio'], ['/blog', 'Blog'], ['/faqs', 'FAQs']] as [$href, $label])
                        <li><a href="{{ url($href) }}" class="hover:text-white transition inline-flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-[#2f6fed]"></span>{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Services --}}
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-5">Services</h4>
                <ul class="space-y-3 text-sm">
                    @foreach([['/services', 'All Services'], [route('quote.create'), 'Get a Quote'], ['/portfolio', 'Our Work']] as [$href, $label])
                        <li><a href="{{ url($href) }}" class="hover:text-white transition inline-flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-[#2f6fed]"></span>{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Newsletter --}}
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-5">Stay Updated</h4>
                <p class="text-sm text-slate-400 mb-4">Subscribe for tips and updates.</p>
                <form id="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" id="newsletter-email" placeholder="Your email address" required
                        class="w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-[#2f6fed] focus:outline-none">
                    <button type="submit" class="mt-2.5 w-full px-4 py-3 bg-[#1d4ed8] text-white text-sm font-bold rounded-xl hover:bg-[#1669d9] transition">Subscribe</button>
                    <p id="newsletter-message" class="mt-2 text-sm hidden"></p>
                </form>
                @if ($siteSettings->site_email)
                    <p class="text-sm mt-4"><a href="mailto:{{ $siteSettings->site_email }}" class="hover:text-white transition">{{ $siteSettings->site_email }}</a></p>
                @endif
                @if ($siteSettings->site_phone)
                    <p class="text-sm mt-1"><a href="tel:{{ $siteSettings->site_phone }}" class="hover:text-white transition">{{ $siteSettings->site_phone }}</a></p>
                @endif
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <p>{{ $siteSettings->copyright_text ?? '© ' . date('Y') . ' AK Tech SOL. All rights reserved.' }}</p>
            <div class="flex gap-5">
                <a href="{{ url('/privacy-policy') }}" class="hover:text-white transition">Privacy Policy</a>
                <a href="{{ url('/terms-conditions') }}" class="hover:text-white transition">Terms & Conditions</a>
            </div>
        </div>
    </div>
</footer>

<script>
    (function() {
        const form = document.getElementById('newsletter-form');
        const messageEl = document.getElementById('newsletter-message');
        if (!form || !messageEl) return;

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const emailInput = document.getElementById('newsletter-email');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;

            if (!emailInput.value.trim()) {
                messageEl.textContent = 'Please enter your email.';
                messageEl.classList.remove('hidden', 'text-green-400');
                messageEl.classList.add('text-red-400');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = '...';
            messageEl.classList.add('hidden');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ email: emailInput.value })
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    messageEl.textContent = data.message || 'Subscribed!';
                    messageEl.classList.remove('hidden', 'text-red-400');
                    messageEl.classList.add('text-green-400');
                    emailInput.value = '';
                } else {
                    messageEl.textContent = data.message || 'Something went wrong.';
                    messageEl.classList.remove('hidden', 'text-green-400');
                    messageEl.classList.add('text-red-400');
                }
            } catch (error) {
                messageEl.textContent = 'Network error. Try again.';
                messageEl.classList.remove('hidden', 'text-green-400');
                messageEl.classList.add('text-red-400');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        });
    })();
</script>
