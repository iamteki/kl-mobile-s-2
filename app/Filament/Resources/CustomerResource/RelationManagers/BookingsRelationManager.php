<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\BookingResource;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';
    
    protected static ?string $title = 'Booking History';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('booking_number')
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->url(fn ($record) => BookingResource::getUrl('view', ['record' => $record])),
                    
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => 
                        $state->isPast() ? 'gray' : 
                        ($state->isToday() ? 'warning' : 'primary')
                    ),
                    
                Tables\Columns\TextColumn::make('event_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    
                Tables\Columns\BadgeColumn::make('booking_status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                    
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'primary' => 'partial',
                        'danger' => fn ($state) => in_array($state, ['refunded', 'failed']),
                    ]),
                    
                Tables\Columns\TextColumn::make('total')
                    ->money('LKR')
                    ->sortable()
                    ->alignEnd()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('booking_status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->headerActions([
                // Removed create action - bookings should be created from main resource
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => BookingResource::getUrl('view', ['record' => $record])),
            ])
            ->bulkActions([
                // No bulk actions for customer's bookings
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public function isReadOnly(): bool
    {
        return true; // Make this read-only from customer view
    }
}