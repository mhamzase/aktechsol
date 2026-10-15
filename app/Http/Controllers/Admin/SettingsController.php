<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteAsset;
use App\Settings\SiteSettings;
use App\Support\MailConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * SettingsController — tailor-app style tabbed one-page settings.
 * No page refresh: each tab saves via AJAX, active tab persists in
 * localStorage until the user navigates from another page.
 */
class SettingsController extends Controller
{
    /** Tabs shown on the settings page. */
    public const TABS = [
        'general' => 'General',
        'contact' => 'Contact & WhatsApp',
        'email'   => 'Email',
        'payments'=> 'Payments',
        'popup'   => 'Popup',
        'homepage'=> 'Homepage',
        'seo'     => 'SEO',
        'chatbot' => 'Chatbot',
    ];

    /** Validatable fields per tab. */
    protected function rules(string $tab): array
    {
        return match ($tab) {
            'general' => [
                'site_name' => 'required|string|max:255',
                'site_email' => 'nullable|email|max:255',
                'site_phone' => 'nullable|string|max:50',
                'site_address' => 'nullable|string|max:500',
                'google_map_embed_url' => 'nullable|url|max:2000',
                'stat1_value' => 'nullable|string|max:20',
                'stat1_label' => 'nullable|string|max:50',
                'stat2_value' => 'nullable|string|max:20',
                'stat2_label' => 'nullable|string|max:50',
                'stat3_value' => 'nullable|string|max:20',
                'stat3_label' => 'nullable|string|max:50',
                'whatsapp_number' => 'nullable|string|max:20',
                'popup_enabled' => 'nullable|boolean',
                'popup_link_url' => 'nullable|url|max:500',
                'popup_version' => 'nullable|string|max:50',
                'popup_image' => 'nullable|image|max:2048',
                'footer_text' => 'nullable|string',
                'copyright_text' => 'nullable|string|max:255',
                'facebook_url' => 'nullable|url|max:500',
                'twitter_url' => 'nullable|url|max:500',
                'linkedin_url' => 'nullable|url|max:500',
                'instagram_url' => 'nullable|url|max:500',
                'logo' => 'nullable|image|max:2048',
                'favicon' => 'nullable|image|max:1024',
            ],
            'contact' => [
                'whatsapp_number' => 'nullable|string|max:30',
                'whatsapp_enabled' => 'nullable|boolean',
                'notification_email' => 'nullable|email|max:255',
            ],
            'email' => [
                'mail_host' => 'nullable|string|max:255',
                'mail_port' => 'nullable|integer|min:1|max:65535',
                'mail_username' => 'nullable|string|max:255',
                'mail_password' => 'nullable|string|max:500',
                'mail_encryption' => 'nullable|in:tls,ssl,none',
                'mail_from_address' => 'nullable|email|max:255',
                'mail_from_name' => 'nullable|string|max:255',
            ],
            'payments' => [
                'payment_methods' => 'nullable|array',
                'payment_methods.*.method' => 'nullable|string|max:100',
                'payment_methods.*.account_holder' => 'nullable|string|max:255',
                'payment_methods.*.bank_name' => 'nullable|string|max:255',
                'payment_methods.*.account_number' => 'nullable|string|max:100',
                'payment_methods.*.iban' => 'nullable|string|max:100',
                // Back-compat with the old name/details shape.
                'payment_methods.*.name' => 'nullable|string|max:100',
                'payment_methods.*.details' => 'nullable|string|max:500',
            ],
            'popup' => [
                'popup_enabled' => 'nullable|boolean',
                'popup_link_url' => 'nullable|url|max:500',
                'popup_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            ],
            'homepage' => [
                'brand_message' => 'nullable|string|max:500',
                'demo_video_url' => 'nullable|url|max:500',
                'demo_video_title' => 'nullable|string|max:255',
                'about_hero_title' => 'nullable|string|max:255',
                'about_hero_subtitle' => 'nullable|string',
                'about_intro_title' => 'nullable|string|max:255',
                'about_intro_text' => 'nullable|string',
                'about_mission_title' => 'nullable|string|max:255',
                'about_mission_subtitle' => 'nullable|string',
            ],
            'seo' => [
                'seo_title' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:500',
                'seo_keywords' => 'nullable|string|max:500',
                'seo_canonical_url' => 'nullable|url|max:500',
                'seo_og_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
                'seo_robots' => 'nullable|string|max:255',
            ],
            'chatbot' => [
                'chatbot_enabled' => 'nullable|boolean',
                'chatbot_greeting' => 'nullable|string|max:500',
                'chatbot_team_name' => 'nullable|string|max:100',
            ],
            default => [],
        };
    }

    public function edit(SiteSettings $settings, string $tab = 'general')
    {
        $tab = array_key_exists($tab, self::TABS) ? $tab : 'general';
        $siteAsset = SiteAsset::firstOrCreate(['name' => 'default']);

        return view('admin.settings', [
            'settings' => $settings,
            'tab' => $tab,
            'tabs' => self::TABS,
            'logoUrl' => $siteAsset->getLogoUrl(),
            'faviconUrl' => $siteAsset->getFaviconUrl(),
            'popupImageUrl' => $siteAsset->getPopupImageUrl(),
            'seoOgImageUrl' => $siteAsset->getSeoOgImageUrl(),
        ]);
    }

    public function update(Request $request, SiteSettings $settings, string $tab = 'general')
    {
        $tab = array_key_exists($tab, self::TABS) ? $tab : 'general';
        $data = $request->validate($this->rules($tab));

        // Checkboxes: absent = false.
        foreach (['whatsapp_enabled', 'popup_enabled', 'chatbot_enabled'] as $flag) {
            if (array_key_exists($flag, $this->rules($tab))) {
                $data[$flag] = $request->boolean($flag);
            }
        }

        // Encrypt the SMTP password; blank keeps the stored one.
        if ($tab === 'email') {
            if (! empty($data['mail_password'])) {
                $data['mail_password'] = MailConfig::encryptPassword($data['mail_password']);
            } else {
                unset($data['mail_password']);
            }
        }

        // Payment methods: drop empty rows.
        if ($tab === 'payments' && isset($data['payment_methods'])) {
            $data['payment_methods'] = array_values(array_filter(
                $data['payment_methods'],
                fn ($m) => ! empty($m['method'] ?? $m['name'] ?? null)
            ));
        }

        $settings->fill($data);
        // Spatie throws MissingSettings on partial tab saves when some properties
        // were never persisted. Update submitted keys directly instead.
        foreach ($data as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['group' => SiteSettings::group(), 'name' => $key],
                ['payload' => json_encode($value), 'updated_at' => now(), 'created_at' => now()],
            );
            $settings->$key = $value;
        }

        // Logo / favicon uploads (general tab).
        if ($tab === 'general') {
            $siteAsset = SiteAsset::firstOrCreate(['name' => 'default']);
            if ($request->hasFile('logo')) {
                $siteAsset->clearMediaCollection('logo');
                $siteAsset->addMediaFromRequest('logo')->toMediaCollection('logo');
            }
            if ($request->hasFile('favicon')) {
                $siteAsset->clearMediaCollection('favicon');
                $siteAsset->addMediaFromRequest('favicon')->toMediaCollection('favicon');
            }
        }

        // Popup image upload (popup tab, optional).
        if ($tab === 'popup') {
            $siteAsset = SiteAsset::firstOrCreate(['name' => 'default']);
            if ($request->hasFile('popup_image')) {
                $siteAsset->clearMediaCollection('popup_image');
                $siteAsset->addMediaFromRequest('popup_image')->toMediaCollection('popup_image');
                // Bump version so "don't show again" resets for everyone.
                $version = (string) time();
                DB::table('settings')->updateOrInsert(
                    ['group' => SiteSettings::group(), 'name' => 'popup_version'],
                    ['payload' => json_encode($version), 'updated_at' => now(), 'created_at' => now()],
                );
            }
        }

        // OG image upload (seo tab, optional).
        if ($tab === 'seo') {
            $siteAsset = SiteAsset::firstOrCreate(['name' => 'default']);
            if ($request->hasFile('seo_og_image')) {
                $siteAsset->clearMediaCollection('seo_og_image');
                $siteAsset->addMediaFromRequest('seo_og_image')->toMediaCollection('seo_og_image');
            }
        }

        $response = ['message' => self::TABS[$tab] . ' settings saved.'];
        $siteAsset = SiteAsset::first();
        if ($tab === 'general') {
            $response['logo_url'] = $siteAsset?->getLogoUrl();
            $response['favicon_url'] = $siteAsset?->getFaviconUrl();
        }
        if ($tab === 'popup') {
            $response['popup_image_url'] = $siteAsset?->getPopupImageUrl();
        }
        if ($tab === 'seo') {
            $response['seo_og_image_url'] = $siteAsset?->getSeoOgImageUrl();
        }

        // Re-apply mail config immediately so the next mail uses it.
        if ($tab === 'email') {
            MailConfig::apply();
        }

        return response()->json($response);
    }

    /** Send a test email to verify the SMTP configuration. */
    public function testEmail(Request $request, SiteSettings $settings)
    {
        $request->validate(['email' => 'required|email']);

        try {
            Mail::raw(
                'This is a test email from AK Tech Sol admin settings. Your SMTP configuration works.',
                fn ($m) => $m->to($request->email)->subject('AK Tech Sol — SMTP test')
            );

            return response()->json(['message' => 'Test email sent to ' . $request->email]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed: ' . $e->getMessage()], 422);
        }
    }
}
