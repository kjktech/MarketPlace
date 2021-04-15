<?php

namespace App\Traits;

use App\Entities\OpeningHours;
use App\Entities\OpeningHoursForDay;
use App\Entities\TimeRange;
use App\Exceptions\Exception;
use App\Models\DayOpenTimeRange;

trait OpeningHoursAttribute
{
    public function getOpeningHoursAttribute()
    {
        if (!key_exists('opening_hours', $this->attributes) || !$this->attributes['opening_hours']) {
            //dd($this->dayOpenTimeRanges());
            $hours = $this->dayOpenTimeRanges
                ->groupBy('day')->map(function ($day) {
                    return $day->map(function (DayOpenTimeRange $range) {
                        //return $range->start.'-'.$range->end;
                        if($range->is_open == 1){
                          return $range->start.'-'.$range->end;
                        }else{
                          return "00:00:00-00:00:00";
                        }
                    });
                });
            $this->attributes['opening_hours'] = OpeningHours::create($hours->toArray());
        }
        return $this->attributes['opening_hours'];
    }
    public function setOpeningHoursAttribute($data)
    {
        // clear previous open times
        $this->attributes['opening_hours'] = null;
        $this->dayOpenTimeRanges()->delete();
        if ($data == null) {
            return;
        }
        if ($data instanceof OpeningHours) {
            $this->applyOpeningHours($data);
            return;
        }
        if (is_array($data) || is_object($data)) {
            $this->applyOpeningHours(OpeningHours::create((array) $data));
            return;
        }
        throw new Exception("Invalid argument `{$data}` applied to opening hours attribute.");
    }
    private function applyOpeningHours(OpeningHours $openingHours)
    {
        $entries = $this->parseOpeningHoursForDB($openingHours);
        $this->dayOpenTimeRanges()->saveMany($entries);
        $this->attributes['opening_hours'] = $openingHours;
    }
    private function parseOpeningHoursForDB(OpeningHours $openingHours)
    {
        $ranges = $openingHours->flatMap(function (OpeningHoursForDay $openingHoursForDay, string $day) {
            return $openingHoursForDay->map(function (TimeRange $timeRange) use ($day) {
                return [
                    'day'   => $day,
                    'start' => $timeRange->start(),
                    'end'   => $timeRange->end()
                ];
            });
        });
        return collect($ranges)->map(function ($range) {
            return new DayOpenTimeRange($range);
        });
    }
}
