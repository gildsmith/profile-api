<?php

namespace Gildsmith\ProfileApi\Listeners;

use Gildsmith\ProfileApi\Notifications\PasswordChanged;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Notifications\Notifiable;

class SendPasswordChangeNotification
{
    public function handle(PasswordReset $event): void
    {
        if (in_array(Notifiable::class, class_uses_recursive($event->user))) {
            $event->user->notify(new PasswordChanged());
        }
    }
}
