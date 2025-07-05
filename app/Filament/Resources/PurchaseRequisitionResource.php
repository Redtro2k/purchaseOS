<?php

namespace App\Filament\Resources;

use App\Actions\ApprovalTopManagementAction;
use App\Filament\Resources\ItemResource\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\PurchaseRequisitionResource\Pages;
use App\Filament\Resources\PurchaseRequisitionResource\RelationManagers;
use App\Models\PurchaseRequisition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class PurchaseRequisitionResource extends Resource
{
    protected static ?string $model = PurchaseRequisition::class;
    protected static ?string $navigationIcon = 'fas-list-check';
    protected static ?string $navigationGroup = "Purchasing Officer";
    protected static ?string $recordTitleAttribute = 'pr_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Request Details')
                    ->description('Basic details about the purchase requisition request.')
                    ->schema([
                        Forms\Components\Select::make('requester_id')
                            ->label('Requested By')
                            ->relationship(
                                name: 'requester',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->when(
                                    !auth()->user()->hasRole('super_admin'),
                                    fn ($query) => $query->whereHas('roles', fn ($q) =>
                                    $q->whereIn('name', ['member'])
                                    )
                                )
                            )
                            ->disabled(fn () => auth()->user()->hasRole('member'))
                            ->dehydrated(fn () => auth()->user()->hasRole('member'))
                            ->default(auth()->id())
                            ->searchable()
                            ->preload()
                            ->live(debounce: '1000')
                            ->required(),
                        Forms\Components\Select::make('dealer_id')
                            ->label('Dealer')
                            ->relationship('dealer', 'slug')
                            ->required(),
                        Forms\Components\DatePicker::make('prepared_dt')
                            ->label('Prepared Date')
                            ->native(false)
                            ->default(now())
                            ->disabled()
                            ->dehydrated(true),

                        Forms\Components\Select::make('priority')
                            ->required()
                            ->label('Priority Level')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                                'urgent' => 'Urgent'
                            ]),
                        Forms\Components\TextInput::make('pr_number')
                            ->label('PR Number')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('required_by_date')
                            ->label('Required By Date')
                            ->native(false)
                            ->required(),

                        Forms\Components\RichEditor::make('comment')
                            ->label('Additional Notes / Comments')
                            ->required()
                            ->disableToolbarButtons([
                                'attachFiles'
                            ])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Approval and Status')
                    ->description('People involved and approval status of the requisition.')
                    ->schema([
                        Forms\Components\Radio::make('status')
                            ->label('Request Status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Completed'
                            ])
                            ->inline()
                            ->default('draft')
                            ->hidden(auth()->user()->hasRole('member'))
                            ->required(),

                        Forms\Components\Select::make('prepared_by_id')
                            ->label('Prepared By (User ID)')
                            ->searchable()
                            ->preload()
                            ->relationship('preparer', 'name', fn($query) =>  $query->when(
                                !auth()->user()->hasRole('super_admin'),
                                fn ($query) => $query->whereHas('roles', fn ($q) =>
                                $q->whereIn('name', ['member'])
                                )
                            ))
                            ->required(),


                        Forms\Components\Select::make('mansor_by_id')
                            ->label('Supervisor / Manager By (User ID)')
                            ->relationship('mansor', 'name', fn($query) => $query->whereHas('roles', fn($q) => $q->whereIn('name', ['manager', 'supervisor'])))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('gm_by_id')
                            ->label('General Manager By (User ID)')
                            ->relationship('gm', 'name', fn($query) => $query->whereHas('roles', fn($q) => $q->whereIn('name', ['gm'])))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('executive_by_id')
                            ->label('Executive Manager By (User ID)')
                            ->relationship('executive', 'name', fn($query) => $query->whereHas('roles', fn($q) => $q->whereIn('name', ['executive'])))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                Forms\Components\Section::make('Files & Documents')
                    ->description('Kindly upload your PR document along with any other relevant files.')
                    ->schema([
                        Forms\Components\FileUpload::make('attachments')
                            ->label('Attach PR File(s)')
                            ->multiple()
                            ->directory('PR')
                            ->preserveFilenames()
                            ->reorderable()
                            ->downloadable()
                            ->panelLayout('grid')
                            ->openable()
                            ->previewable(true)
                            ->required()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pr_number')
                    ->label('PR Number')
                    ->weight(FontWeight::Bold)
                    ->searchable(),
                Tables\Columns\TextColumn::make('requester.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn(string $state): string => match($state){
                        'low' => 'gray',
                        'medium' => 'warning',
                        'high' => 'success',
                        'completed' => 'danger'
                    }),
                Tables\Columns\TextColumn::make('required_by_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('preparer.name')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mansor.name')
                    ->label('Line Manager')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gm.name')
                    ->label('General Manager')
                    ->toggleable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('executive.name')
                    ->toggleable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function($query){
                if(auth()->user()->hasAnyRole('manager', 'supervisor')){
                    $query->whereNull('mansor_dt');
                }
            })
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    ApprovalTopManagementAction::make()
                    ->hidden(!auth()->user()->hasAnyRole(['manager', 'supervisor', 'gm', 'executive']))
                ])->tooltip('Actions')
            ])
            ->paginated(true)
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
            ItemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseRequisitions::route('/'),
            'create' => Pages\CreatePurchaseRequisition::route('/create'),
            'view' => Pages\ViewPurchaseRequisition::route('/{record}'),
            'edit' => Pages\EditPurchaseRequisition::route('/{record}/edit'),
        ];
    }
}
