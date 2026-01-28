# Mailtrap設定ガイド

このガイドでは、Mailtrapを使用したメール認証システムの設定方法を説明します。

## Mailtrapアカウントの準備

1. [Mailtrap](https://mailtrap.io/)にアクセスしてアカウントを作成（無料プランでも利用可能）
2. ダッシュボードにログイン後、「Email Testing」→「Inboxes」から新しいInboxを作成
3. Inboxを選択し、「SMTP Settings」タブを開く
4. 「Integrations」から「Laravel」を選択

## .envファイルの設定

`.env`ファイルに以下の設定を追加してください：

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

**注意**: `MAIL_USERNAME`と`MAIL_PASSWORD`は、MailtrapのInbox設定画面に表示される「Username」と「Password」を使用してください。

## メール認証機能について

以下の機能が実装されています：

1. **ユーザー登録時**: 新規ユーザー登録時に自動的にメール認証メールが送信されます
2. **メール認証画面**: メール認証が完了していないユーザーは、認証完了を促す画面が表示されます
3. **認証リンクの再送信**: メールが届かない場合、認証リンクを再送信できます

## テスト方法

1. 新しいユーザーを登録する
2. MailtrapのInboxを確認してメールが届いているか確認
3. メール内の認証リンクをクリックして認証を完了
4. ログイン後、通常通りアプリケーションを使用できることを確認

## トラブルシューティング

### メールが届かない場合

- `.env`ファイルの設定が正しいか確認
- MailtrapのInboxにメールが届いているか確認（スパムフォルダも確認）
- `php artisan config:clear`で設定キャッシュをクリア

### 認証リンクが機能しない場合

- `APP_URL`が正しく設定されているか確認
- Fortifyの設定で`emailVerification()`が有効になっているか確認（`config/fortify.php`）

