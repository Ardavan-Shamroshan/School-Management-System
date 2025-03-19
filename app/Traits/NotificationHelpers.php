<?php

namespace App\Traits;

use Filament\Notifications\Notification;
use Livewire\Attributes\On;

trait NotificationHelpers
{
    public function somethingWentWrong(): void
    {
        Notification::make()->danger()->title('Something went wrong')->send();
    }

    public function successfully(string $title): void
    {
        Notification::make()->success()->title($title)->send();
    }

    public function notify(bool $result, $title, $dispatch, $to = null, ...$params): void
    {
        if (! $result) {
            $this->somethingWentWrong();
        }

        if ($result) {

            if ($to) {
                $this->dispatch($dispatch, ...$params)->to(app($to));
            }

            if (! $to) {
                $this->dispatch($dispatch, ...$params);
            }
        }

        $this->successfully($title);
    }
}
