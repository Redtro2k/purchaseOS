<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseRequisition;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PurchaseRequisitionChart extends ChartWidget
{
    protected static ?string $heading = 'Purchase Requisition Overview Per Status';

    protected function getData(): array
    {
        $statuses = ['draft', 'pending', 'approved', 'rejected', 'cancelled', 'completed'];

        // Get counts for each status
        $counts = PurchaseRequisition::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Prepare dataset
        return [
            'labels' => array_map('ucfirst', $statuses),
            'datasets' => [
                [
                    'label' => 'Total',
                    'data' => array_map(fn ($status) => $counts[$status] ?? 0, $statuses),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
