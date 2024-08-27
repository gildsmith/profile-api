<?php

namespace Gildsmith\ProfileApi\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class PasswordChanged extends Notification
{
    public function via(): array
    {
        return ['mail'];
    }

    /** @noinspection PhpUnused */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Password Changed Notification'))
            ->line(Lang::get('You are receiving this email because your password has been changed.'))
            ->line(Lang::get('If you did not initiate this change, head to the following link and change your password immediately.'))
            ->action(Lang::get('Change Password'), url('/profile/recovery'));
    }
}
