<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Booking;
use App\Models\BookingItem;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    
    protected static ?string $title = 'Booking Items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('item_type')
                            ->options([
                                'product' => 'Equipment',
                                'service_provider' => 'Service Provider',
                                'package' => 'Package',
                            ])
                            ->required()
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('item_name')
                            ->required()
                            ->disabled(),
                    ]),
                    
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                            
                        Forms\Components\TextInput::make('unit_price')
                            ->prefix('LKR')
                            ->numeric()
                            ->required(),
                            
                        Forms\Components\TextInput::make('rental_days')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                    ]),
                    
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'delivered' => 'Delivered',
                        'returned' => 'Returned',
                        'damaged' => 'Damaged',
                        'lost' => 'Lost',
                    ])
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('delivered_at')
                    ->visible(fn (Forms\Get $get) => in_array($get('status'), ['delivered', 'returned'])),
                    
                Forms\Components\DateTimePicker::make('returned_at')
                    ->visible(fn (Forms\Get $get) => $get('status') === 'returned'),
                    
                Forms\Components\Textarea::make('notes')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_name')
            ->columns([
                Tables\Columns\TextColumn::make('item_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'product' => 'primary',
                        'service_provider' => 'success',
                        'package' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'product' => 'Equipment',
                        'service_provider' => 'Service',
                        'package' => 'Package',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('item_name')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record) => 
                        $record->variation_name ? "Variation: {$record->variation_name}" : null
                    ),
                    
                Tables\Columns\TextColumn::make('quantity')
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('unit_price')
                    ->money('LKR')
                    ->alignEnd(),
                    
                Tables\Columns\TextColumn::make('rental_days')
                    ->alignCenter()
                    ->suffix(' days'),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->money('LKR')
                    ->alignEnd()
                    ->weight('bold'),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => fn ($state) => in_array($state, ['confirmed', 'returned']),
                        'primary' => 'delivered',
                        'danger' => fn ($state) => in_array($state, ['damaged', 'lost']),
                    ]),
                    
                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('returned_at')
                    ->dateTime()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('item_type')
                    ->options([
                        'product' => 'Equipment',
                        'service_provider' => 'Service Provider',
                        'package' => 'Package',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'delivered' => 'Delivered',
                        'returned' => 'Returned',
                        'damaged' => 'Damaged',
                        'lost' => 'Lost',
                    ])
                    ->multiple(),
            ])
            ->headerActions([
                // No adding items after booking is created
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => !in_array($record->status, ['returned', 'lost']))
                    ->after(function ($record) {
                        $this->updateBookingDeliveryStatus($record->booking);
                    }),
                    
                Tables\Actions\Action::make('mark_delivered')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'confirmed')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'delivered',
                            'delivered_at' => now(),
                        ]);
                        $this->updateBookingDeliveryStatus($record->booking);
                    }),
                    
                Tables\Actions\Action::make('mark_returned')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'delivered')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'returned',
                            'returned_at' => now(),
                        ]);
                        $this->updateBookingDeliveryStatus($record->booking);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'confirmed' => 'Confirmed',
                                    'delivered' => 'Delivered',
                                    'returned' => 'Returned',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            $bookings = [];
                            
                            $records->each(function ($record) use ($data, &$bookings) {
                                $updateData = ['status' => $data['status']];
                                
                                if ($data['status'] === 'delivered' && !$record->delivered_at) {
                                    $updateData['delivered_at'] = now();
                                }
                                
                                if ($data['status'] === 'returned' && !$record->returned_at) {
                                    $updateData['returned_at'] = now();
                                }
                                
                                $record->update($updateData);
                                $bookings[$record->booking_id] = $record->booking;
                            });
                            
                            // Update all affected bookings
                            foreach ($bookings as $booking) {
                                $this->updateBookingDeliveryStatus($booking);
                            }
                        }),
                ]),
            ]);
    }
    
    /**
     * Update booking delivery status based on items status
     */
    protected function updateBookingDeliveryStatus(Booking $booking): void
    {
        $items = $booking->items;
        $allDelivered = $items->every(fn ($item) => $item->status === 'delivered');
        $allReturned = $items->every(fn ($item) => $item->status === 'returned');
        $someDelivered = $items->contains(fn ($item) => $item->status === 'delivered');
        
        if ($allReturned) {
            $booking->update(['delivery_status' => 'picked_up']);
        } elseif ($allDelivered) {
            $booking->update(['delivery_status' => 'delivered']);
        } elseif ($someDelivered) {
            $booking->update(['delivery_status' => 'scheduled']);
        } else {
            $booking->update(['delivery_status' => 'pending']);
        }
        
        // Also update booking status if needed
        if ($allDelivered && $booking->booking_status === 'confirmed') {
            $booking->update(['booking_status' => 'in_progress']);
        } elseif ($allReturned && $booking->booking_status === 'in_progress') {
            $booking->update(['booking_status' => 'completed']);
        }
    }
}