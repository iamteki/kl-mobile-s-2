<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    
    protected static ?string $navigationGroup = 'Customer Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Account')
                    ->description('Login credentials and account information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('user.name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),
                                    
                                Forms\Components\TextInput::make('user.email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(User::class, 'email', ignoreRecord: true),
                            ]),
                            
                        Forms\Components\TextInput::make('user.password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->visible(fn (string $context): bool => $context === 'create')
                            ->helperText('Leave empty to keep current password'),
                    ])
                    ->visible(fn (?Customer $record): bool => !$record || !$record->user_id),
                    
                Section::make('Customer Information')
                    ->description('Contact details and preferences')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                                    
                                Forms\Components\Select::make('customer_type')
                                    ->options([
                                        'individual' => 'Individual',
                                        'corporate' => 'Corporate',
                                    ])
                                    ->default('individual')
                                    ->required()
                                    ->reactive(),
                            ]),
                            
                        Forms\Components\Textarea::make('address')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                            
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('company')
                                    ->maxLength(255)
                                    ->visible(fn (Forms\Get $get): bool => $get('customer_type') === 'corporate')
                                    ->required(fn (Forms\Get $get): bool => $get('customer_type') === 'corporate'),
                                    
                                Forms\Components\TextInput::make('company_registration')
                                    ->label('Company Reg. No.')
                                    ->maxLength(255)
                                    ->visible(fn (Forms\Get $get): bool => $get('customer_type') === 'corporate'),
                                    
                                Forms\Components\TextInput::make('tax_id')
                                    ->label('Tax ID')
                                    ->maxLength(255)
                                    ->visible(fn (Forms\Get $get): bool => $get('customer_type') === 'corporate'),
                            ]),
                    ]),
                    
                Section::make('Preferences')
                    ->description('Communication preferences')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('newsletter_subscribed')
                                    ->label('Newsletter Subscription')
                                    ->helperText('Receive promotional emails'),
                                    
                                Forms\Components\Toggle::make('sms_notifications')
                                    ->label('SMS Notifications')
                                    ->helperText('Receive booking updates via SMS'),
                            ]),
                            
                        Forms\Components\KeyValue::make('preferences')
                            ->label('Additional Preferences')
                            ->keyLabel('Preference')
                            ->valueLabel('Value')
                            ->addActionLabel('Add Preference')
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Statistics')
                    ->description('Customer activity overview')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('total_bookings')
                                    ->numeric()
                                    ->disabled()
                                    ->default(0),
                                    
                                Forms\Components\TextInput::make('total_spent')
                                    ->prefix('LKR')
                                    ->numeric()
                                    ->disabled()
                                    ->default(0),
                                    
                                Forms\Components\DatePicker::make('last_booking_date')
                                    ->disabled(),
                            ]),
                    ])
                    ->visible(fn (string $context): bool => $context === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                    
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone'),
                    
                Tables\Columns\BadgeColumn::make('customer_type')
                    ->colors([
                        'primary' => 'individual',
                        'success' => 'corporate',
                    ]),
                    
                Tables\Columns\TextColumn::make('company')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('-'),
                    
                Tables\Columns\TextColumn::make('total_bookings')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('total_spent')
                    ->money('LKR')
                    ->sortable()
                    ->alignEnd()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('last_booking_date')
                    ->date()
                    ->sortable()
                    ->placeholder('No bookings yet'),
                    
                Tables\Columns\IconColumn::make('newsletter_subscribed')
                    ->boolean()
                    ->label('Newsletter')
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer_type')
                    ->options([
                        'individual' => 'Individual',
                        'corporate' => 'Corporate',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('newsletter_subscribed')
                    ->label('Newsletter Subscribers'),
                    
                Tables\Filters\Filter::make('has_bookings')
                    ->query(fn ($query) => $query->where('total_bookings', '>', 0))
                    ->label('Has Bookings')
                    ->toggle(),
                    
                Tables\Filters\Filter::make('high_value')
                    ->query(fn ($query) => $query->where('total_spent', '>=', 50000))
                    ->label('High Value (50k+)')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_bookings')
                    ->label('Bookings')
                    ->icon('heroicon-o-shopping-bag')
                    ->url(fn (Customer $record): string => 
                        route('filament.admin.resources.bookings.index', [
                            'tableFilters[customer][values][0]' => $record->id
                        ])
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('send_newsletter')
                        ->label('Send Newsletter')
                        ->icon('heroicon-o-envelope')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            // TODO: Implement newsletter sending
                            $count = $records->count();
                            \Filament\Notifications\Notification::make()
                                ->title("Newsletter queued for {$count} customers")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BookingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'user.email', 'phone', 'company'];
    }
}