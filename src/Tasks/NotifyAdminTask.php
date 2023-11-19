<?php

namespace Eduka\Nova\Tasks;

use Eduka\Cube\Models\User;
use Laravel\Nova\Notifications\NovaNotification;

class NotifyAdminTask
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
