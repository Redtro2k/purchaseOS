<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DealerResource\Pages;
use App\Filament\Resources\DealerResource\RelationManagers;
use App\Models\Dealer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class DealerResource extends Resource
{
    protected static ?string $model = Dealer::class;

    protected static ?string $navigationGroup = "Company Management";
    protected static ?string $navigationIcon = 'fas-building';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('images')
                    ->label('Avatar')
                    ->directory('Company')
                    ->preserveFilenames()
                    ->reorderable()
                    ->panelLayout('grid')
                    ->previewable(true)
                    ->avatar()
                    ->image()
                    ->imageEditor()
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->live( true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', collect(explode(' ', $state))->map(fn($w) => strtoupper($w[0] ?? ''))->implode('')))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
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
            'index' => Pages\ListDealers::route('/'),
            'create' => Pages\CreateDealer::route('/create'),
            'view' => Pages\ViewDealer::route('/{record}'),
            'edit' => Pages\EditDealer::route('/{record}/edit'),
        ];
    }
}
