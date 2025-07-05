<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseRequisition;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAdminPR extends BaseWidget
{
    protected static ?string $heading = 'Latest Purchase Requisition';

    public function table(Table $table): Table
    {
        return $table
            ->query(PurchaseRequisition::query()->withCount('items'))
            ->defaultSort('created_at', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('pr_number')->label('PR Number'),
                Tables\Columns\TextColumn::make('priority')->label('Priority'),
                Tables\Columns\TextColumn::make('items_count') // <- this works
                ->label('Item Count')
                    ->badge()
                    ->color('primary'),
            ]);
    }
}
