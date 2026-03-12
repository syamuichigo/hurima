<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Mailtrap使用時: SMTP設定（ポート2525ではencryption=nullが安定する場合あり）
        $host = config('mail.mailers.smtp.host');
        if ($host && str_contains($host, 'mailtrap')) {
            $port = config('mail.mailers.smtp.port', 2525);
            // ポート2525の場合は暗号化なしで接続を試す（Mailtrapの推奨設定）
            $encryption = ($port == 2525) ? null : config('mail.mailers.smtp.encryption', 'tls');
            config([
                'mail.mailers.smtp.host' => 'sandbox.smtp.mailtrap.io',
                'mail.mailers.smtp.port' => $port,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.timeout' => 30,
                'mail.mailers.smtp.local_domain' => 'localhost',
                'mail.mailers.smtp.stream' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ]);
            // メインはsmtp、失敗時のみlogにフォールバック（SMTP成功を優先）
            config([
                'mail.default' => 'failover',
                'mail.mailers.failover.mailers' => ['smtp', 'log'],
            ]);
        }

        // ページネーションをBootstrap風のHTML（ul.pagination…）で出力する
        Paginator::useBootstrap();
    }
}
