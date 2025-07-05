<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceProviderResource\Pages;
use App\Filament\Resources\ServiceProviderResource\RelationManagers;
use App\Models\ServiceProvider;
use App\Models\ServiceCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;

class ServiceProviderResource extends Resource
{
    protected static ?string $model = ServiceProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Service Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Service Provider Details')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('service_category_id')
                                                    ->label('Service Category')
                                                    ->options(ServiceCategory::where('status', 'active')->pluck('name', 'id'))
                                                    ->required()
                                                    ->searchable(),
                                                    
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (string $state, Forms\Set $set) => 
                                                        $set('slug', Str::slug($state))
                                                    ),
                                            ]),
                                            
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('slug')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique(ServiceProvider::class, 'slug', ignoreRecord: true),
                                                    
                                                Forms\Components\TextInput::make('stage_name')
                                                    ->maxLength(255)
                                                    ->helperText('Professional/stage name if different'),
                                            ]),
                                            
                                        Forms\Components\Textarea::make('short_description')
                                            ->maxLength(500)
                                            ->rows(2)
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\RichEditor::make('bio')
                                            ->columnSpanFull()
                                            ->toolbarButtons([
                                                'bold',
                                                'bulletList',
                                                'orderedList',
                                                'italic',
                                                'link',
                                                'redo',
                                                'undo',
                                            ]),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('Experience & Skills')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\Select::make('experience_level')
                                                    ->options([
                                                        'beginner' => 'Beginner (1-2 years)',
                                                        'intermediate' => 'Intermediate (3-5 years)',
                                                        'professional' => 'Professional (5-10 years)',
                                                        'expert' => 'Expert (10+ years)',
                                                    ])
                                                    ->required(),
                                                    
                                                Forms\Components\TextInput::make('years_experience')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->default(0)
                                                    ->required(),
                                                    
                                                Forms\Components\Toggle::make('equipment_provided')
                                                    ->label('Provides Own Equipment')
                                                    ->helperText('Can they bring their own equipment?'),
                                            ]),
                                            
                                        Forms\Components\TagsInput::make('languages')
                                            ->placeholder('Add languages')
                                            ->suggestions(['English', 'Sinhala', 'Tamil', 'Hindi', 'Chinese'])
                                            ->helperText('Languages spoken')
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\TagsInput::make('specialties')
                                            ->placeholder('Add specialties')
                                            ->helperText('Areas of expertise (e.g., Wedding DJ, Corporate Events)')
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\TagsInput::make('equipment_owned')
                                            ->label('Equipment Owned')
                                            ->placeholder('Add equipment')
                                            ->helperText('List of equipment they own')
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\Repeater::make('portfolio_links')
                                            ->label('Portfolio Links')
                                            ->simple(
                                                Forms\Components\TextInput::make('link')
                                                    ->url()
                                                    ->required()
                                                    ->placeholder('https://...')
                                            )
                                            ->defaultItems(0)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('Pricing & Availability')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('base_price')
                                                    ->numeric()
                                                    ->prefix('LKR')
                                                    ->required()
                                                    ->minValue(0),
                                                    
                                                Forms\Components\Select::make('price_unit')
                                                    ->options([
                                                        'hour' => 'Per Hour',
                                                        'event' => 'Per Event',
                                                        'day' => 'Per Day',
                                                    ])
                                                    ->default('event')
                                                    ->required(),
                                                    
                                                Forms\Components\TextInput::make('min_booking_hours')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->minValue(1)
                                                    ->label('Min. Booking Hours'),
                                            ]),
                                            
                                        Forms\Components\TextInput::make('max_booking_hours')
                                            ->numeric()
                                            ->minValue(1)
                                            ->label('Max. Booking Hours')
                                            ->helperText('Leave empty for no maximum'),
                                            
                                        Forms\Components\KeyValue::make('availability')
                                            ->label('Weekly Availability')
                                            ->keyLabel('Day')
                                            ->valueLabel('Available?')
                                            ->default([
                                                'Monday' => 'Yes',
                                                'Tuesday' => 'Yes',
                                                'Wednesday' => 'Yes',
                                                'Thursday' => 'Yes',
                                                'Friday' => 'Yes',
                                                'Saturday' => 'Yes',
                                                'Sunday' => 'Yes',
                                            ])
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('Features & Status')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Forms\Components\Repeater::make('features')
                                            ->label('Key Features/Services')
                                            ->simple(
                                                Forms\Components\TextInput::make('feature')
                                                    ->required()
                                                    ->placeholder('e.g., MC services in 3 languages')
                                            )
                                            ->defaultItems(3)
                                            ->columnSpanFull(),
                                            
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('badge')
                                                    ->placeholder('e.g., Top Rated')
                                                    ->helperText('Special badge to display'),
                                                    
                                                Forms\Components\Select::make('badge_class')
                                                    ->label('Badge Color')
                                                    ->options([
                                                        'badge-primary' => 'Purple',
                                                        'badge-success' => 'Green',
                                                        'badge-warning' => 'Orange',
                                                        'badge-danger' => 'Red',
                                                    ]),
                                                    
                                                Forms\Components\TextInput::make('sort_order')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0),
                                            ]),
                                            
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Toggle::make('featured')
                                                    ->label('Featured Provider')
                                                    ->helperText('Show in featured section'),
                                                    
                                                Forms\Components\Select::make('status')
                                                    ->options([
                                                        'active' => 'Active',
                                                        'inactive' => 'Inactive',
                                                        'on_leave' => 'On Leave',
                                                    ])
                                                    ->default('active')
                                                    ->required(),
                                            ]),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (ServiceProvider $record): string => 
                        $record->stage_name ? "Stage: {$record->stage_name}" : ''
                    ),
                    
                Tables\Columns\TextColumn::make('category.name')
    ->label('Category')
    ->sortable()
    ->badge()
    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('experience_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'gray',
                        'intermediate' => 'warning',
                        'professional' => 'success',
                        'expert' => 'primary',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('base_price')
                    ->money('LKR')
                    ->sortable()
                    ->alignEnd()
                    ->description(fn (ServiceProvider $record): string => 
                        "per {$record->price_unit}"
                    ),
                    
                Tables\Columns\TextColumn::make('rating')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn (float $state): string => 
                        $state >= 4.5 ? 'success' : 
                        ($state >= 3.5 ? 'warning' : 'danger')
                    )
                    ->formatStateUsing(fn (float $state): string => 
                        number_format($state, 1) . ' ⭐'
                    ),
                    
                Tables\Columns\TextColumn::make('total_bookings')
                    ->sortable()
                    ->alignCenter()
                    ->label('Bookings'),
                    
                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->alignCenter(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                        'warning' => 'on_leave',
                    ]),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
               Tables\Filters\SelectFilter::make('service_category_id')
    ->label('Category')
    ->relationship('category', 'name')
    ->searchable()
    ->preload(),
                    
                Tables\Filters\SelectFilter::make('experience_level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'professional' => 'Professional',
                        'expert' => 'Expert',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'on_leave' => 'On Leave',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured Only'),
                    
                Tables\Filters\TernaryFilter::make('equipment_provided')
                    ->label('Provides Equipment'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_status')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'on_leave' => 'On Leave',
                            ])
                            ->required(),
                    ])
                    ->action(function (ServiceProvider $record, array $data) {
                        $record->update(['status' => $data['status']]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'active' => 'Active',
                                    'inactive' => 'Inactive',
                                    'on_leave' => 'On Leave',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->update(['status' => $data['status']]);
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

  public static function getRelations(): array
{
    return [
        RelationManagers\PricingTiersRelationManager::class,
        RelationManagers\MediaItemsRelationManager::class,
        RelationManagers\ReviewsRelationManager::class,
    ];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceProviders::route('/'),
            'create' => Pages\CreateServiceProvider::route('/create'),
            'view' => Pages\ViewServiceProvider::route('/{record}'),
            'edit' => Pages\EditServiceProvider::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        $onLeave = static::getModel()::where('status', 'on_leave')->count();
        return $onLeave > 0 ? 'warning' : 'success';
    }
}