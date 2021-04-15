<?php

namespace App\Traits;

use App\Entities\OpeningHours;
/** @property OpeningHours opening_hours */
use App\Traits\Commentable;
use App\Traits\HashId;
use App\Traits\OpeningHoursRelations;
use App\Traits\OpeningHoursAttribute;
use App\Traits\OpeningHoursScopes;

trait HasOpeningHours
{
    use OpeningHoursRelations;
    use OpeningHoursAttribute;
    use OpeningHoursScopes;
}
