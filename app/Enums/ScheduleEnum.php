<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use function App\Support\translate;

enum ScheduleEnum: string implements HasLabel
{

    case EVEN_DAYS = 'even_days';
    case ODD_DAYS  = 'odd_days';
    case EVERYDAY  = 'everyday';
    case SATURDAY  = 'saturday';
    case SUNDAY    = 'sunday';
    case MONDAY    = 'monday';
    case TUESDAY   = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY  = 'thursday';
    case FRIDAY    = 'friday';

    public function getLabel(): ?string
    {
        return translate($this->name);
    }
}
