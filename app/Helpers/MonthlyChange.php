<?php

namespace App\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;


class MonthlyChange
{
    public static function result($model): array
    {
        $now = now();
        $lastMonth = now()->subMonth();
        $current = $model::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
        $previous = $model::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        $difference = $current - $previous;

        return [
            'current'     => $current,
            'previous'    => $previous,
            'difference'  => $difference,
            'status'      => match (true) {
                $difference > 0  => 'increase',
                $difference < 0  => 'decrease',
                default          => 'no change',
            },
            'icon'        => match (true) {
                $difference > 0  => 'fas-arrow-trend-up',
                $difference < 0  => 'fas-arrow-trend-down',
                default          => 'fas-arrow-up',
            },
            'description' => match (true) {
                $difference > 0  => $difference."+ Increase this month",
                $difference < 0  => abs($difference) . "- Decrease this month",
                default          => "No change compared to last month",
            },
            'color'       => match (true) {
                $difference > 0  => 'success',
                $difference < 0  => 'danger',
                default          => 'warning',
            },
        ];
    }
}
