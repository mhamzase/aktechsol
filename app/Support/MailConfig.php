<?php

namespace App\Support;

use App\Settings\SiteSettings;
use Illuminate\Support\Facades\Crypt;

/**
 * MailConfig — applies the admin-configured SMTP settings at runtime.
 * The password is stored encrypted in settings; it is decrypted here
 * and injected into the mail config so queued mails use it too.
 * If anything fails, the .env mail config stays untouched.
 */
class MailConfig
{
    public static function apply(): void
    {
        try {
            $settings = app(SiteSettings::class);

            if (empty($settings->mail_host) || empty($settings->mail_username)) {
                return; // Not configured — keep .env defaults.
            }

            config([
                'mail.default'                 => 'smtp',
                'mail.mailers.smtp.host'       => $settings->mail_host,
                'mail.mailers.smtp.port'       => (int) ($settings->mail_port ?: 587),
                'mail.mailers.smtp.username'   => $settings->mail_username,
                'mail.mailers.smtp.encryption' => $settings->mail_encryption ?: 'tls',
            ]);

            if (! empty($settings->mail_password)) {
                try {
                    config(['mail.mailers.smtp.password' => Crypt::decryptString($settings->mail_password)]);
                } catch (\Throwable $e) {
                    // Corrupt value — leave password as-is.
                }
            }

            if (! empty($settings->mail_from_address)) {
                config(['mail.from.address' => $settings->mail_from_address]);
            }
            if (! empty($settings->mail_from_name)) {
                config(['mail.from.name' => $settings->mail_from_name]);
            }
        } catch (\Throwable $e) {
            // Settings table not migrated yet — ignore silently.
        }
    }

    /** Encrypt a password before storing it in settings. */
    public static function encryptPassword(?string $plain): ?string
    {
        if (empty($plain)) {
            return null;
        }

        return Crypt::encryptString($plain);
    }
}
