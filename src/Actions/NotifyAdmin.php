<?php

namespace Eduka\Nova\Actions;

use Eduka\Cube\Models\User;
use Laravel\Nova\Notifications\NovaNotification;

class NotifyAdmin
{
    public function notify(array $recipientEmails, string $type, string $message)
    {
        $users = User::whereIn('email', $recipientEmails)->get();

        foreach ($users as $user) {
            $user->notify(
                NovaNotification::make()
                    ->message($message)
                    ->type($type)
            );
        }

    }
}
