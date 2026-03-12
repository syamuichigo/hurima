<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RatingReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ratedUser;
    public $raterUser;
    public $rating;

    public function __construct(User $ratedUser, User $raterUser, int $rating)
    {
        $this->ratedUser = $ratedUser;
        $this->raterUser = $raterUser;
        $this->rating = $rating;
    }

    public function build()
    {
        return $this->subject('取引の評価を受けました')
            ->view('emails.rating-received');
    }
}
