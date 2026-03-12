# メール設定ガイド

## Mailpit（ローカル開発・推奨）

**認証不要**でメールをブラウザで確認できます。Mailtrapがうまく動かない場合はこちらを利用してください。

### セットアップ

1. **`.env` に以下を設定:**
   ```env
   MAIL_MAILER=mailpit
   MAIL_FROM_ADDRESS=noreply@example.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

2. **Docker を起動:**
   ```bash
   docker compose up -d
   ```

3. **メールの確認:**
   - ユーザー登録などでメール送信後、ブラウザで **http://localhost:8026** を開く
   - 届いたメールが一覧表示され、認証リンクをクリックしてメール認証を完了できる

---

## Mailtrapアカウントの準備

1. [Mailtrap](https://mailtrap.io/)にアクセスしてアカウントを作成（無料プランでも利用可能）
2. ダッシュボードにログイン後、「Email Testing」→「Inboxes」から新しいInboxを作成
3. Inboxを選択し、「SMTP Settings」タブを開く
4. 「Integrations」から「Laravel」を選択

## .envファイルの設定

`.env`ファイルに以下の設定を追加してください：

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

**重要**: 
- ホストは `sandbox.smtp.mailtrap.io` を使用してください（`smtp.mailtrap.io` は非推奨の可能性があります）
- `MAIL_USERNAME`と`MAIL_PASSWORD`は、Mailtrapの **Email Testing → Sandboxes → あなたのSandbox → Integration → SMTP** に表示される値をコピーしてください

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

### Swift_TransportException: "Expected response code 250 but got an empty response"

このエラーはSMTP認証に失敗したときに発生します。

**対処法（順に試してください）:**

1. **認証情報の取得元を確認**
   - **Email Testing**（サンドボックス）の認証情報を使用しているか確認
   - Mailtrap → **Email Testing** → **Inboxes** → 対象Inbox → **SMTP Settings** タブ
   - 「Email API」の認証情報は **sandbox.smtp.mailtrap.io** では使えません

2. **.env に以下を明示的に設定**
   ```env
   MAIL_ENCRYPTION=null
   ```
   ポート2525では暗号化なしが安定することがあります。

3. **認証情報を再コピー**
   - MailtrapのSMTP Settingsから **Username** と **Password** を再コピー
   - パスワードに余分なスペースがないか確認

4. **別のポートを試す**
   ```env
   MAIL_PORT=587
   MAIL_ENCRYPTION=tls
   ```

5. **設定キャッシュをクリア**
   ```bash
   php artisan config:clear
   ```

**エラー詳細を確認する:**
```bash
docker compose exec php php artisan mail:diagnose --send-test --strict
```

### メールが届かない場合

**診断コマンドを実行してください:**

```bash
docker compose exec php php artisan mail:diagnose
```

テストメール送信で動作確認:

```bash
docker compose exec php php artisan mail:diagnose --send-test
```

**ステップバイステップ確認:**

| ステップ | 確認内容 | 対処法 |
|---------|----------|--------|
| 1 | MailtrapのInbox | **Email Testing** → **Inboxes** → 対象のInboxを開く（**Email API**ではなく**Email Testing**） |
| 2 | メールがInboxに表示されるか | 届いていれば設定OK。届いていなければ次へ |
| 3 | `.env`のMAIL_* | `php artisan mail:diagnose`で設定値を確認 |
| 4 | 設定キャッシュ | `php artisan config:clear` を実行してから再登録 |
| 5 | failoverでlogに落ちているか | `storage/logs/laravel.log` の末尾を確認（SMTP失敗時にlogに記録される） |
| 6 | 認証メール再送 | 認証待ち画面の「認証メールを再送する」ボタンを押す |

**重要**: Mailtrapは実際のメールアドレスには送信しません。必ず [mailtrap.io](https://mailtrap.io) にログインし、**Email Testing** のInboxでメールを確認してください。

### 認証リンクが機能しない場合

- `APP_URL`が正しく設定されているか確認（Docker経由でアクセスする場合は `http://localhost` でOK）
- Fortifyの設定で`emailVerification()`が有効になっているか確認（`config/fortify.php`）

