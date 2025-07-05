<?php

namespace App\Filament\Widgets;

use App\Helpers\MonthlyChange;
use App\Models\Item;
use App\Models\PurchaseRequisition;
use App\Models\Supplier;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected ?string $heading = 'Analytics';

    protected ?string $description = 'An overview of some analytics.';
    protected function getStats(): array
    {
        $statsPR = MonthlyChange::result(PurchaseRequisition::class);
        $statsUser = MonthlyChange::result(User::class);
        $statsSupplier = MonthlyChange::result(Supplier::class);
        $statsItem = MonthlyChange::result(Item::class);

        return [
            Stat::make('All Purchase Requisition', $statsPR['current'])
                ->description($statsPR['description'])
                ->descriptionIcon($statsPR['icon'])
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color($statsPR['color']),

            Stat::make('All Users', $statsUser['current'])
                ->description($statsUser['description'])
                ->descriptionIcon($statsUser['icon'])
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color($statsUser['color']),

            Stat::make('All Items', $statsItem['current'])
                ->description($statsItem['description'])
                ->descriptionIcon($statsItem['icon'])
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color($statsItem['color']),

            Stat::make('All Supplier', $statsSupplier['current'])
                ->description($statsSupplier['description'])
                ->descriptionIcon($statsSupplier['icon'])
                ->color($statsSupplier['color']),
        ];
    }
}
