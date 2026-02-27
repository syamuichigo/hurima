@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transaction-chat.css') }}">
@endsection

@section('content')
<div class="transaction-chat-container">
    <div class="transaction-chat-wrapper">
        <aside class="sidebar">
            <h2 class="sidebar-title">取引中のアイテム</h2>
            <div class="sidebar-transactions">
                @if(isset($otherTransactions) && $otherTransactions->count() > 0)
                    @foreach($otherTransactions as $otherTransaction)
                        <a href="/transaction-chat/{{ $otherTransaction->id }}" class="sidebar-transaction-item">
                            {{ $otherTransaction->content->name ?? $otherTransaction->name }}
                        </a>
                    @endforeach
                @else
                    <div class="sidebar-empty">取引中のアイテムがありません</div>
                @endif
            </div>
        </aside>

        <main class="main-content">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div class="transaction-header">
                <div class="transaction-user-info">
                    <div class="user-avatar">
                        @if(isset($otherUser) && $otherUser && $otherUser->image)
                            @php
                                $profileImage = $otherUser->image;
                                if ($profileImage && strpos($profileImage, 'storage/') !== 0) {
                                    $profileImage = 'storage/' . $profileImage;
                                }
                            @endphp
                            <img src="{{ asset($profileImage) }}" alt="プロフィール画像">
                        @else
                            <div class="avatar-placeholder"></div>
                        @endif
                    </div>
                    <h1 class="transaction-title">
                        {{ optional($otherUser)->display_name ?? optional($otherUser)->username ?? 'ユーザー名' }}さんとの取引画面
                    </h1>
                </div>
                @if(isset($isBuyer) && $isBuyer && isset($buyerCanComplete) && $buyerCanComplete)
                <button class="complete-transaction-button" onclick="openRatingModal()">
                    取引を完了する
                </button>
                @endif
            </div>

            <div class="product-section">
                <div class="product-image-wrapper">
                    @if(isset($purchase) && $purchase->content)
                        <img src="{{ asset($purchase->content->image) }}" alt="{{ $purchase->content->name }}" class="product-image">
                    @elseif(isset($purchase))
                        <img src="{{ asset($purchase->image) }}" alt="{{ $purchase->name }}" class="product-image">
                    @else
                        <div class="product-image-placeholder">商品画像</div>
                    @endif
                </div>
                <div class="product-info">
                    <h2 class="product-name">
                        {{ isset($purchase) && $purchase->content ? $purchase->content->name : (isset($purchase) ? $purchase->name : '商品名') }}
                    </h2>
                    <p class="product-price">
                        ¥ {{ isset($purchase) && $purchase->content ? number_format($purchase->content->price) : (isset($purchase) ? number_format($purchase->price) : '0') }}
                    </p>
                </div>
            </div>

            <div class="chat-section">
                <div class="chat-messages">
                    @if(isset($messages) && $messages->count() > 0)
                        @foreach($messages as $index => $msg)
                            @if($msg->user_id === auth()->id())
                                <div class="message message-self" data-message-id="{{ $msg->id }}">
                                    <div class="message-content">
                                        <div class="message-header">
                                            <div class="message-header-avatar">
                                                @if($msg->user && $msg->user->image)
                                                    @php
                                                        $profileImage = $msg->user->image;
                                                        if ($profileImage && strpos($profileImage, 'storage/') !== 0) {
                                                            $profileImage = 'storage/' . $profileImage;
                                                        }
                                                    @endphp
                                                    <img src="{{ asset($profileImage) }}" alt="プロフィール画像">
                                                @else
                                                    <div class="avatar-placeholder"></div>
                                                @endif
                                            </div>
                                            <span class="message-username">{{ $msg->user->display_name ?? $msg->user->username }}</span>
                                        </div>
                                        <div class="message-bubble message-bubble-self">
                                            <div class="message-text-display">{{ $msg->message }}</div>
                                            <div class="message-text-edit" style="display: none;">
                                                <textarea class="message-edit-input" rows="3">{{ $msg->message }}</textarea>
                                                <div class="message-edit-actions">
                                                    <button type="button" class="message-save-button">保存</button>
                                                    <button type="button" class="message-cancel-button">キャンセル</button>
                                                </div>
                                            </div>
                                            @if($msg->image)
                                                <div class="message-image">
                                                    <img src="{{ asset('storage/' . $msg->image) }}" alt="メッセージ画像">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="message-actions">
                                            <button type="button" class="message-action-link message-edit-button" data-message-id="{{ $msg->id }}">編集</button>
                                            <form action="{{ route('transaction.message.delete', ['purchaseId' => $purchase->id, 'messageId' => $msg->id]) }}" method="POST" class="message-delete-form" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="message-action-link message-delete-button" onclick="return confirm('このメッセージを削除しますか？')">削除</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="message message-other">
                                    <div class="message-content">
                                        <div class="message-header">
                                            <div class="message-header-avatar">
                                                @if($msg->user && $msg->user->image)
                                                    @php
                                                        $profileImage = $msg->user->image;
                                                        if ($profileImage && strpos($profileImage, 'storage/') !== 0) {
                                                            $profileImage = 'storage/' . $profileImage;
                                                        }
                                                    @endphp
                                                    <img src="{{ asset($profileImage) }}" alt="プロフィール画像">
                                                @else
                                                    <div class="avatar-placeholder"></div>
                                                @endif
                                            </div>
                                            <span class="message-username">{{ $msg->user->display_name ?? $msg->user->username }}</span>
                                        </div>
                                        <div class="message-bubble message-bubble-other">
                                            <div class="message-text">{{ $msg->message }}</div>
                                            @if($msg->image)
                                                <div class="message-image">
                                                    <img src="{{ asset('storage/' . $msg->image) }}" alt="メッセージ画像">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        <a id="latest-message"></a>
                    @else
                        <div class="empty-message">メッセージがありません。メッセージを送信してください。</div>
                        <a id="latest-message"></a>
                    @endif
                </div>
            </div>

            <div class="message-input-section">
                <form action="/transaction-chat/{{ isset($purchase) ? $purchase->id : '' }}/message" method="POST" class="message-form" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="message" placeholder="取引メッセージを記入してください" class="message-input">
                    <button type="button" class="image-upload-button" onclick="document.getElementById('image-input').click()">
                        画像を追加
                    </button>
                    <input type="file" id="image-input" name="image" accept="image/*" class="hidden">
                    <button type="submit" class="message-send-button">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 21L23 12L2 3V10L17 12L2 14V21Z" fill="currentColor"/>
                        </svg>
                    </button>
                </form>
            </div>
        </main>
    </div>
</div>

<div id="ratingModal" class="rating-modal">
    <div class="rating-modal-content">
        <h2 class="rating-modal-title">取引が完了しました。</h2>
        <p class="rating-modal-question">今回の取引相手はどうでしたか?</p>
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <div>{{ session('error') }}</div>
            </div>
        @endif
        @if(isset($purchase))
            @if($ratedUserId)
            <form id="ratingForm" action="/transaction-chat/{{ $purchase->id }}/rating" method="POST">
                @csrf
                <div class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" required>
                        <label for="rating{{ $i }}" class="star">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor"/>
                            </svg>
                        </label>
                    @endfor
                </div>
                <input type="hidden" name="rated_user_id" value="{{ $ratedUserId }}">
                <button type="submit" class="rating-submit-button">送信する</button>
            </form>
            @else
            <div class="alert alert-error">
                評価を送信するための情報が不足しています。取引相手の情報が見つかりません。
            </div>
            @endif
        @else
        <div class="alert alert-error">
            評価を送信するための情報が不足しています。取引情報が見つかりません。
        </div>
        @endif
    </div>
</div>

<script>
function openRatingModal() {
    document.getElementById('ratingModal')?.classList.add('show');
}

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('ratingModal');
    if (!modal) return;

    @php
        $hasRatingErrors = $errors->any() || session('error');
        $shouldAutoOpen = $hasRatingErrors || (isset($shouldShowRatingModal) && $shouldShowRatingModal);
    @endphp
    @if($shouldAutoOpen)
    openRatingModal();
    @endif

    modal.addEventListener('click', e => {
        if (e.target === modal) modal.classList.remove('show');
    });

    document.querySelectorAll('.rating-modal-content, #ratingForm, .rating-submit-button').forEach(el => {
        el?.addEventListener('click', e => e.stopPropagation());
    });

    const stars = document.querySelectorAll('.rating-stars label.star');
    const radios = document.querySelectorAll('.rating-stars input[type="radio"]');
    const updateStars = value => {
        stars.forEach((label, i) => {
            label.style.color = i < value ? '#ffd700' : '#d0d0d0';
        });
    };

    radios.forEach(radio => {
        radio.addEventListener('change', () => updateStars(parseInt(radio.value)));
    });

    stars.forEach((label, index) => {
        label.addEventListener('mouseenter', () => updateStars(index + 1));
    });

    document.querySelector('.rating-stars')?.addEventListener('mouseleave', () => {
        const checked = document.querySelector('.rating-stars input[type="radio"]:checked');
        updateStars(checked ? parseInt(checked.value) : 0);
    });

    document.querySelectorAll('.message-edit-button').forEach(button => {
        button.addEventListener('click', function() {
            const msg = this.closest('.message');
            msg.querySelector('.message-text-display').style.display = 'none';
            const edit = msg.querySelector('.message-text-edit');
            edit.style.display = 'block';
            msg.querySelector('.message-edit-input').focus();
        });
    });

    document.querySelectorAll('.message-save-button').forEach(button => {
        button.addEventListener('click', function() {
            const msg = this.closest('.message');
            const messageId = msg.getAttribute('data-message-id');
            const newMessage = msg.querySelector('.message-edit-input').value.trim();
            if (!newMessage) {
                alert('メッセージを入力してください');
                return;
            }
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                alert('セキュリティトークンが見つかりません。ページを再読み込みしてください。');
                return;
            }
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/transaction-chat/{{ $purchase->id }}/message/${messageId}`;
            form.style.display = 'none';
            const fields = [['_token', csrfToken], ['_method', 'PUT'], ['message', newMessage]];
            fields.forEach(([name, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
        });
    });

    document.querySelectorAll('.message-cancel-button').forEach(button => {
        button.addEventListener('click', function() {
            const msg = this.closest('.message');
            msg.querySelector('.message-text-display').style.display = 'block';
            msg.querySelector('.message-text-edit').style.display = 'none';
        });
    });
});
</script>
@endsection
