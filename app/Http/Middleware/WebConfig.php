<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $fromEmail = get_settings('smtp_from_email');
        if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            $fromEmail = config('mail.from.address');
            if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
                $serverHost = $_SERVER['SERVER_NAME'] ?? parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
                $fromEmail = 'noreply@' . ($serverHost ?: 'edunexa.com');
            }
        }

        $fromName = get_settings('system_title') ?: config('mail.from.name', 'Academy');
        $serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';

        config(
            [
                'app.name' => get_settings('system_title') ?: config('app.name'),
                'app.timezone' => get_settings('timezone') ?: config('app.timezone'),

                // //SMTP configuration
                'mail.mailers.smtp.transport' => get_settings('protocol') ?: 'smtp',
                'mail.mailers.smtp.host' => get_settings('smtp_host') ?: config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => get_settings('smtp_port') ?: config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.encryption' => get_settings('smtp_crypto') ?: config('mail.mailers.smtp.encryption'),
                'mail.mailers.smtp.username' => get_settings('smtp_user') ?: config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => get_settings('smtp_pass') ?: config('mail.mailers.smtp.password'),
                'mail.mailers.smtp.timeout' => null,
                'mail.mailers.smtp.local_domain' => $serverName,
                'mail.from.name' => $fromName,
                'mail.from.address' => $fromEmail,
            ]
        );
        return $next($request);
    }
}
