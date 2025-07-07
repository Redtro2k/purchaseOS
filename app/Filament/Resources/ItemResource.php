<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;
    protected static ?string $navigationGroup = "Company Management";
    protected static ?string $navigationIcon = 'fas-boxes-packing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('purchase_requisition_id')
                    ->relationship('purchaseRequisition', 'pr_number')
                    ->placeholder('PR Number')
                    ->required(),
                Forms\Components\TextInput::make('budget_code')
                    ->placeholder('Budget Code')
                    ->numeric(),
                Forms\Components\Select::make('dealer_id')
                    ->placeholder('Budget Code')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->disableToolbarButtons([
                        'attachFiles'
                    ])
                    ->placeholder('No Description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unit')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Radio::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ])
                    ->inline()
                    ->inlineLabel(false),
                Forms\Components\TextInput::make('unit_price')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('net_price')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('purchaseRequisition.pr_number'),
                Group::make('budget_code')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('purchaseRequisition.pr_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget_code')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->money('PHP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('net_price')
                    ->money('PHP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->colors([
                    'approved' => 'primary',
                    'rejected' => 'danger',
                    'completed' => 'success',
                ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'view' => Pages\ViewItem::route('/{record}'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
