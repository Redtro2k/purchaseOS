<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('budget_code')
                    ->placeholder('Budget Code')
                    ->numeric(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->disableToolbarButtons([
                        'attachFiles'
                    ])
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('del_date')
                ->native(false)
                ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unit')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit_price')
                    ->numeric()
                    ->live(true)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $set('net_price', $get('unit_price'));
                    })
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
                Forms\Components\TextInput::make('net_price')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->groups([
                Group::make('purchaseRequisition.pr_number'),
                Group::make('budget_code')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('Item ID'),
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
                    ->summarize(Sum::make()
                        ->money('PHP')
                        ->label('Unit Total')
                    )
                    ->searchable(),
                Tables\Columns\TextColumn::make('net_price')
                    ->money('PHP')
                    ->summarize(
                        Sum::make()
                            ->money('PHP')
                            ->label('Net Total')
                    )
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
            ->striped()
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
