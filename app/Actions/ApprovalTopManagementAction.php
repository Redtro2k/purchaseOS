<?php
namespace App\Actions;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard\Step;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

class ApprovalTopManagementAction extends Action
{
    public static function make(?string $name = 'view_approval'): static
    {
        return parent::make($name)
            ->form([
                Grid::make(2)
                    ->schema(fn($record) => [
                        Placeholder::make('pr_number')
                            ->label('PR Number')
                            ->content(fn($record) => $record->pr_number),
                            Grid::make(4)
                                ->schema([
                                    Placeholder::make('requester.name')
                                        ->label('Requester By')
                                        ->content(function($record){
                                            $name = $record->requester->name;
                                            $isApproved = $record->prepared_dt != null ? '✅': '';
                                            return new HtmlString($name . ' '.$isApproved);
                                        }),
                                    Placeholder::make('preparer.name')
                                        ->label('Preparer By')
                                        ->content(function($record){
                                            $name = $record->preparer->name;
                                            $isApproved = $record->prepared_dt != null ? '✅': '';
                                            return new HtmlString($name . ' '.$isApproved);
                                        }),
                                    Placeholder::make('mansor.name')
                                        ->label('Manager/Supervisor By')
                                        ->content(function($record){
                                            $name = $record->mansor->name;
                                            $isApproved = $record->mansor_dt != null ? '✅': '';
                                            return new HtmlString($name . ' '.$isApproved);
                                        }),
                                    Placeholder::make('executive.name')
                                        ->label('Manager/Supervisor By')
                                        ->content(function($record){
                                            $name = $record->executive->name;
                                            $isApproved = $record->executive_at != null ? '✅': '';
                                            return new HtmlString($name . ' '.$isApproved);
                                        }),
                                ]),
                        FileUpload::make('attachments')
                            ->default($record->attachments)
                            ->openable()
                            ->previewable(true)
                            ->directory('PR')
                            ->multiple()
                            ->disabled()
                        ->columnSpan(3),
                    ])
            ])
            ->label('Approval')
            ->color('primary')
            ->icon('heroicon-s-shield-exclamation')
            ->successNotificationTitle('Approval Successful')
            ->modalSubmitActionLabel('Approval')
            ->action(function(array $data, Action $action , $record) {
                if(auth()->user()->hasAnyRole(['manager', 'supervisor'])) {
                    $record->update([
                        'mansor_dt' => now(),
                    ]);
                }
                if(auth()->user()->hasAnyRole('gm')) {
                    $record->update([
                        'gm_dt' => now(),
                    ]);
                }
                if(auth()->user()->hasAnyRole('executive')) {
                    $record->update([
                        'executive_at' => now(),
                    ]);
                }
            });
    }
}
