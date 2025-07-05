<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'fas-parachute-box';

    protected static ?string $navigationGroup = "Purchasing Officer";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Supplier Information')
                    ->description('Basic details about the company or dealer you are registering.')
                    ->schema([
                        Forms\Components\TextInput::make('cb_name')
                            ->label('Company/Dealer Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_name')
                            ->label('Contact Person Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_email')
                            ->label('Contact Email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('supplier_email')
                            ->label('Supplier Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn (callable $get) => $get('same_as_contact_email'))
                            ->dehydrated(),

                        Forms\Components\Checkbox::make('same_as_contact_email')
                            ->label('Same as Contact Email')
                            ->columnSpan(2)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $set('supplier_email', $get('contact_email'));
                                } else {
                                    $set('supplier_email', '');
                                }
                            })
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Contact Phone Number')
                            ->tel()
                            ->numeric()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('dealer_id')
                            ->label('Dealer')
                            ->relationship('dealer', 'slug')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Supplier Rating & Documents')
                    ->description('Rate the supplier and upload supporting documents.')
                    ->schema([
                        \Mokhosh\FilamentRating\Components\Rating::make('rate')
                            ->label('Rating')
                            ->size('md')
                            ->allowZero()
                            ->default(0)
                            ->required()
                            ->reactive()
                            ->dehydrated(fn ($state) => filled($state)),

                        FileUpload::make('files')
                            ->label('Upload Supplier Documents or Images')
                            ->multiple()
                            ->directory('suppliers')
                            ->preserveFilenames()
                            ->reorderable()
                            ->downloadable()
                            ->openable()
                            ->previewable(true)
                            ->columnSpan(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cb_name')->label('Company Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dealer.slug')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                RatingColumn::make('rate'),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
        ];
    }
}
