<?php
namespace App\Traits;

use App\Models\DayOpenTimeRange;

trait OpeningHoursRelations
{
    public function dayOpenTimeRanges()
    {
        return $this->morphMany(DayOpenTimeRange::class, 'openable');
    }
}
