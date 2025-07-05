<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Illuminate\Database\Eloquent\Builder;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Booking Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Booking Information')
                    ->description('Basic booking details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('booking_number')
                                    ->label('Booking Number')
                                    ->disabled()
                                    ->required(),
                                    
                                Forms\Components\Select::make('booking_status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required()
                                    ->reactive(),
                                    
                                Forms\Components\Select::make('payment_status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'partial' => 'Partial',
                                        'refunded' => 'Refunded',
                                        'failed' => 'Failed',
                                    ])
                                    ->required()
                                    ->reactive(),
                            ]),
                    ]),
                    
                Tabs::make('Booking Details')
                    ->tabs([
                        Tabs\Tab::make('Event Details')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\DatePicker::make('event_date')
                                                    ->required()
                                                    ->minDate(today()),
                                                    
                                                Forms\Components\Select::make('event_type')
                                                    ->options([
                                                        'wedding' => 'Wedding',
                                                        'birthday' => 'Birthday Party',
                                                        'corporate' => 'Corporate Event',
                                                        'concert' => 'Concert',
                                                        'festival' => 'Festival',
                                                        'private' => 'Private Party',
                                                        'other' => 'Other',
                                                    ])
                                                    ->required(),
                                            ]),
                                            
                                        Forms\Components\Textarea::make('venue')
                                            ->label('Venue/Location')
                                            ->required()
                                            ->rows(2)
                                            ->columnSpanFull(),
                                            
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('number_of_pax')
                                                    ->label('Number of Attendees')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(1),
                                                    
                                                Forms\Components\TimePicker::make('installation_time')
                                                    ->label('Setup Time')
                                                    ->required(),
                                                    
                                                Forms\Components\TimePicker::make('event_start_time')
                                                    ->required(),
                                            ]),
                                            
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TimePicker::make('dismantle_time')
                                                    ->label('Dismantle Time')
                                                    ->required(),
                                                    
                                                Forms\Components\DatePicker::make('rental_start_date')
                                                    ->required(),
                                                    
                                                Forms\Components\DatePicker::make('rental_end_date')
                                                    ->required()
                                                    ->afterOrEqual('rental_start_date'),
                                            ]),
                                            
                                        Forms\Components\TextInput::make('rental_days')
                                            ->numeric()
                                            ->disabled()
                                            ->dehydrated(),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('Customer Details')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Forms\Components\Select::make('customer_id')
                                            ->label('Customer')
                                            ->relationship('customer', 'id')
                                            ->getOptionLabelFromRecordUsing(fn ($record) => 
                                                $record->user->name . ' - ' . $record->user->email
                                            )
                                            ->searchable(['user.name', 'user.email', 'phone'])
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if ($state) {
                                                    $customer = Customer::find($state);
                                                    $set('customer_name', $customer->user->name);
                                                    $set('customer_email', $customer->user->email);
                                                    $set('customer_phone', $customer->phone);
                                                    $set('customer_company', $customer->company);
                                                }
                                            }),
                                            
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('customer_name')
                                                    ->required()
                                                    ->maxLength(255),
                                                    
                                                Forms\Components\TextInput::make('customer_email')
                                                    ->email()
                                                    ->required()
                                                    ->maxLength(255),
                                            ]),
                                            
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('customer_phone')
                                                    ->tel()
                                                    ->required()
                                                    ->maxLength(255),
                                                    
                                                Forms\Components\TextInput::make('customer_company')
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('Delivery & Instructions')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Forms\Components\Textarea::make('delivery_address')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\Textarea::make('delivery_instructions')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\Select::make('delivery_status')
                                            ->options([
                                                'pending' => 'Pending',
                                                'scheduled' => 'Scheduled',
                                                'delivered' => 'Delivered',
                                                'picked_up' => 'Picked Up',
                                            ])
                                            ->default('pending')
                                            ->required(),
                                            
                                        Forms\Components\Textarea::make('special_requests')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\Textarea::make('internal_notes')
                                            ->label('Internal Notes (Staff Only)')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->helperText('These notes are not visible to customers'),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('Pricing & Payment')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('subtotal')
                                                    ->prefix('LKR')
                                                    ->numeric()
                                                    ->required()
                                                    ->reactive(),
                                                    
                                                Forms\Components\TextInput::make('discount_amount')
                                                    ->prefix('LKR')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->reactive(),
                                                    
                                                Forms\Components\TextInput::make('coupon_code')
                                                    ->maxLength(255),
                                            ]),
                                            
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('tax_amount')
                                                    ->prefix('LKR')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->reactive(),
                                                    
                                                Forms\Components\TextInput::make('delivery_charge')
                                                    ->prefix('LKR')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->reactive(),
                                                    
                                                Forms\Components\TextInput::make('total')
                                                    ->prefix('LKR')
                                                    ->numeric()
                                                    ->required()
                                                    ->disabled()
                                                    ->dehydrated(),
                                            ]),
                                            
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Toggle::make('insurance_opted')
                                                    ->label('Insurance Opted')
                                                    ->reactive(),
                                                    
                                                Forms\Components\TextInput::make('insurance_amount')
                                                    ->prefix('LKR')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->visible(fn (Forms\Get $get): bool => $get('insurance_opted')),
                                            ]),
                                            
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('payment_method')
                                                    ->options([
                                                        'stripe' => 'Stripe',
                                                        'bank_transfer' => 'Bank Transfer',
                                                        'cash' => 'Cash',
                                                    ]),
                                                    
                                                Forms\Components\DateTimePicker::make('paid_at')
                                                    ->label('Payment Date'),
                                            ]),
                                            
                                        Forms\Components\TextInput::make('stripe_payment_intent_id')
                                            ->label('Stripe Payment ID')
                                            ->visible(fn (Forms\Get $get): bool => $get('payment_method') === 'stripe'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('customer.user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => CustomerResource::getUrl('view', ['record' => $record->customer])),
                    
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
                    
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items')
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\BadgeColumn::make('delivery_status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'scheduled',
                        'success' => fn ($state) => in_array($state, ['delivered', 'picked_up']),
                    ])
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('booking_status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),
                    
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'partial' => 'Partial',
                        'refunded' => 'Refunded',
                        'failed' => 'Failed',
                    ])
                    ->multiple(),
                    
                Tables\Filters\Filter::make('event_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Event From'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Event Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->where('event_date', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->where('event_date', '<=', $date));
                    }),
                    
                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                        $record->user->name . ' - ' . $record->user->email
                    )
                    ->searchable(['user.name', 'user.email']),
                    
                Tables\Filters\Filter::make('high_value')
                    ->query(fn ($query) => $query->where('total', '>=', 100000))
                    ->label('High Value (100k+)')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->action(function (Booking $record) {
                        // TODO: Generate and download invoice
                        \Filament\Notifications\Notification::make()
                            ->title('Invoice generation coming soon')
                            ->info()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('booking_status')
                                ->label('Booking Status')
                                ->options([
                                    'pending' => 'Pending',
                                    'confirmed' => 'Confirmed',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            $records->each(fn ($record) => 
                                $record->update(['booking_status' => $data['booking_status']])
                            );
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('booking_status', 'confirmed')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('booking_status', 'pending')->count() > 0 ? 'warning' : 'gray';
    }
    
    public static function getWidgets(): array
    {
        return [
            BookingResource\Widgets\BookingStats::class,
        ];
    }
}