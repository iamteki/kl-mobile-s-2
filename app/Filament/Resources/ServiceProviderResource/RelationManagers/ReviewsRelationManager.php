<?php

namespace App\Filament\Resources\ServiceProviderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';
    
    protected static ?string $title = 'Customer Reviews';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rating')
                    ->options([
                        5 => '5 Stars - Excellent',
                        4 => '4 Stars - Good',
                        3 => '3 Stars - Average',
                        2 => '2 Stars - Poor',
                        1 => '1 Star - Terrible',
                    ])
                    ->required()
                    ->disabled(),
                    
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->disabled(),
                    
                Forms\Components\Textarea::make('comment')
                    ->rows(4)
                    ->disabled()
                    ->columnSpanFull(),
                    
                Forms\Components\KeyValue::make('ratings_breakdown')
                    ->label('Rating Breakdown')
                    ->disabled()
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                    
                Forms\Components\Toggle::make('is_featured')
                    ->label('Featured Review')
                    ->helperText('Show this review prominently'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('customer.user.name')
                    ->label('Customer')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('rating')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        5 => 'success',
                        4 => 'primary',
                        3 => 'warning',
                        2, 1 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => 
                        str_repeat('⭐', $state)
                    ),
                    
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->wrap()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('comment')
                    ->wrap()
                    ->limit(50)
                    ->toggleable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                    
                Tables\Columns\IconColumn::make('verified_booking')
                    ->label('Verified')
                    ->boolean(),
                    
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                    
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        5 => '5 Stars',
                        4 => '4 Stars',
                        3 => '3 Stars',
                        2 => '2 Stars',
                        1 => '1 Star',
                    ]),
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(fn ($record) => $record->update(['status' => 'approved'])),
                    
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(fn ($record) => $record->update(['status' => 'rejected'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_selected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(fn ($record) => 
                                $record->update(['status' => 'approved'])
                            );
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}