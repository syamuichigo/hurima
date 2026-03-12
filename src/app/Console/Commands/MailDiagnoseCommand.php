<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailDiagnoseCommand extends Command
{
    protected $signature = 'mail:diagnose 
                            {--send-test : テストメールを送信する}
                            {--strict : failoverを無効にしてSMTP直接送信（エラー詳細を表示）}';
    protected $description = 'メール認証が届かない問題の診断を行います';

    public function handle(): int
    {
        $this->info('=== メール認証 診断ツール ===');
        $this->newLine();

        $issues = [];
        $checks = [];

        // 1. メール設定の確認
        $this->info('【ステップ1】メール設定の確認');
        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');
        $appUrl = config('app.url');

        $checks[] = [
            '項目' => 'MAIL_MAILER / default',
            '値' => $mailer,
            'OK?' => $mailer ? '✓' : '✗ 未設定',
        ];
        $checks[] = [
            '項目' => 'MAIL_HOST',
            '値' => $host ?: '(未設定)',
            'OK?' => $host ? '✓' : '✗ 必須',
        ];
        $checks[] = [
            '項目' => 'MAIL_PORT',
            '値' => $port ?? '(未設定)',
            'OK?' => $port ? '✓' : '✗ 必須',
        ];
        $checks[] = [
            '項目' => 'MAIL_USERNAME',
            '値' => $username ? '***' . substr($username, -4) : '(未設定)',
            'OK?' => $username ? '✓' : '✗ 必須',
        ];
        $checks[] = [
            '項目' => 'MAIL_PASSWORD',
            '値' => $password ? '****' : '(未設定)',
            'OK?' => $password ? '✓' : '✗ 必須',
        ];
        $checks[] = [
            '項目' => 'MAIL_FROM_ADDRESS',
            '値' => $fromAddress ?: '(未設定)',
            'OK?' => $fromAddress ? '✓' : '⚠ デフォルト使用',
        ];
        $checks[] = [
            '項目' => 'APP_URL',
            '値' => $appUrl ?: '(未設定)',
            'OK?' => ($appUrl && $appUrl !== 'http://localhost') ? '✓' : '⚠ 認証リンクに影響',
        ];

        $this->table(['項目', '値', 'OK?'], $checks);

        if (!$host || !$username || !$password) {
            $issues[] = 'MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD が .env に設定されているか確認してください。';
        }
        if (str_contains($host ?? '', 'mailtrap')) {
            $this->line('  → Mailtrapを使用中。Inboxの「Email Testing」でメールを確認してください。');
        }
        if (!$appUrl || $appUrl === 'http://localhost') {
            $issues[] = 'APP_URL が localhost の場合、認証リンクが正しく生成されない可能性があります。';
        }

        $this->newLine();

        // 2. キューの確認
        $this->info('【ステップ2】キューの確認');
        $queueConnection = config('queue.default');
        $checks2 = [
            ['項目' => 'QUEUE_CONNECTION', '値' => $queueConnection, 'OK?' => $queueConnection === 'sync' ? '✓ 同期的に送信' : '⚠ 非同期の場合はワーカーが必要'],
        ];
        $this->table(['項目', '値', 'OK?'], $checks2);
        if ($queueConnection !== 'sync') {
            $issues[] = 'キューを使用している場合、`php artisan queue:work` が動作している必要があります。';
        }
        $this->newLine();

        // 3. テストメール送信
        if ($this->option('send-test')) {
            if ($this->option('strict')) {
                config(['mail.default' => 'smtp']);
                $this->line('  (strict: failoverを無効化、SMTP直接で送信します)');
            }
            $this->info('【ステップ3】テストメール送信');
            $email = $this->ask('送信先メールアドレスを入力してください');
            if ($email) {
                try {
                    Mail::raw('これはメール診断のテスト送信です。届いていればメール設定は正常です。', function ($message) use ($email) {
                        $message->to($email)->subject('メール診断テスト');
                    });
                    $this->info('  ✓ テストメールを送信しました。' . $email . ' を確認してください。');
                    $this->line('  ※ Mailtrap使用時: mailtrap.io の Email Testing → Inbox で確認');
                } catch (\Throwable $e) {
                    $this->error('  ✗ 送信エラー: ' . $e->getMessage());
                    $this->line('  クラス: ' . get_class($e));
                    $issues[] = 'メール送信でエラーが発生しています。.env の MAIL_USERNAME, MAIL_PASSWORD を確認してください。';
                }
            }
        } else {
            $this->info('【ステップ3】テストメール送信');
            $this->line('  テスト送信を行うには: php artisan mail:diagnose --send-test');
            $this->newLine();
        }

        // 4. まとめ
        $this->info('【診断結果】');
        if (empty($issues)) {
            $this->info('  重大な設定問題は見つかりませんでした。');
            $this->line('  メールが届かない場合:');
            $this->line('  1. Mailtrap使用時: Inbox → Email Testing を確認');
            $this->line('  2. スパムフォルダを確認');
            $this->line('  3. php artisan config:clear を実行してから再試行');
            $this->line('  4. 認証メール再送ボタンで再送を試す');
        } else {
            $this->warn('  以下の点を確認してください:');
            foreach ($issues as $issue) {
                $this->line('  • ' . $issue);
            }
        }

        return 0;
    }
}
