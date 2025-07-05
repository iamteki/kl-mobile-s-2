<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    
    protected static ?string $navigationGroup = 'Inventory Management';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Package Information')
                    ->description('Basic package details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $state, Forms\Set $set) => 
                                        $set('slug', Str::slug($state))
                                    ),
                                    
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Package::class, 'slug', ignoreRecord: true),
                            ]),
                            
                        Forms\Components\Select::make('category')
                            ->options([
                                'wedding' => 'Wedding Packages',
                                'birthday' => 'Birthday Packages',
                                'corporate' => 'Corporate Event Packages',
                                'concert' => 'Concert Packages',
                                'basic' => 'Basic Packages',
                                'premium' => 'Premium Packages',
                            ])
                            ->required()
                            ->searchable(),
                            
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Package Contents')
                    ->description('What\'s included in this package')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('Package Items')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'product' => 'Equipment',
                                        'service' => 'Service',
                                    ])
                                    ->required()
                                    ->reactive(),
                                    
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->placeholder('e.g., JBL Sound System'),
                                    
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),
                                    
                                Forms\Components\Textarea::make('description')
                                    ->rows(1)
                                    ->placeholder('Brief description'),
                            ])
                            ->columns(4)
                            ->defaultItems(3)
                            ->reorderable()
                            ->collapsible()
                            ->columnSpanFull(),
                            
                        Forms\Components\Repeater::make('features')
                            ->label('Package Features')
                            ->simple(
                                Forms\Components\TextInput::make('feature')
                                    ->required()
                                    ->placeholder('e.g., Free delivery and setup')
                            )
                            ->defaultItems(4)
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Pricing & Display')
                    ->description('Package pricing and visibility settings')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('LKR')
                                    ->required()
                                    ->minValue(0),
                                    
                                Forms\Components\TextInput::make('service_duration')
                                    ->numeric()
                                    ->suffix('hours')
                                    ->default(8)
                                    ->minValue(1)
                                    ->helperText('Default service duration'),
                                    
                                Forms\Components\TextInput::make('suitable_for')
                                    ->placeholder('e.g., Up to 100 guests')
                                    ->helperText('Suitable for description'),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('badge')
                                    ->placeholder('e.g., Most Popular, Best Value')
                                    ->helperText('Special badge to display'),
                                    
                                Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->directory('packages')
                                    ->maxSize(2048),
                            ]),
                            
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                                    
                                Forms\Components\Toggle::make('featured')
                                    ->label('Featured Package')
                                    ->helperText('Show in featured section'),
                                    
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->square()
                    ->size(60)
                    ->defaultImageUrl(url('/images/package-placeholder.jpg')),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Package $record): string => 
                        Str::limit($record->description, 50)
                    ),
                    
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn (string $state): string => 
                        str_replace('-', ' ', ucfirst($state))
                    ),
                    
                Tables\Columns\TextColumn::make('price')
                    ->money('LKR')
                    ->sortable()
                    ->alignEnd()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('items')
                    ->label('Items Count')
                    ->getStateUsing(fn (Package $record): int => 
                        is_array($record->items) ? count($record->items) : 0
                    )
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('suitable_for')
                    ->toggleable()
                    ->wrap(),
                    
                Tables\Columns\TextColumn::make('badge')
                    ->badge()
                    ->color('success')
                    ->placeholder('-'),
                    
                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star'),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'wedding' => 'Wedding',
                        'birthday' => 'Birthday',
                        'corporate' => 'Corporate',
                        'concert' => 'Concert',
                        'basic' => 'Basic',
                        'premium' => 'Premium',
                    ])
                    ->multiple(),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->requiresConfirmation()
                    ->action(function (Package $record) {
                        $newPackage = $record->replicate();
                        $newPackage->name = $record->name . ' (Copy)';
                        $newPackage->slug = Str::slug($newPackage->name);
                        $newPackage->featured = false;
                        $newPackage->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggle_status')
                        ->label('Toggle Status')
                        ->icon('heroicon-o-arrow-path')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => $record->status === 'active' ? 'inactive' : 'active'
                                ]);
                            }
                        }),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order');
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }
}