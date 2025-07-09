<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $navigationGroup = 'Inventory Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Product Details')
                    ->tabs([
                        Tabs\Tab::make('General Information')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\Select::make('category_id')
                                                    ->label('Category')
                                                    ->options(Category::where('status', 'active')->pluck('name', 'id'))
                                                    ->required()
                                                    ->reactive()
                                                    ->searchable()
                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                        $set('subcategory', null);
                                                    }),
                                                    
                                                Forms\Components\TextInput::make('sku')
                                                    ->label('SKU')
                                                    ->required()
                                                    ->unique(Product::class, 'sku', ignoreRecord: true)
                                                    ->maxLength(255),
                                                    
                                                Forms\Components\TextInput::make('brand')
                                                    ->maxLength(255),
                                            ]),
                                            
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (string $state, Set $set) => 
                                                        $set('slug', Str::slug($state))
                                                    ),
                                                    
                                                Forms\Components\TextInput::make('slug')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique(Product::class, 'slug', ignoreRecord: true),
                                            ]),
                                            
                                        Forms\Components\TextInput::make('subcategory')
                                            ->maxLength(255)
                                            ->helperText('Optional subcategory for better organization'),
                                            
                                        Forms\Components\Textarea::make('short_description')
                                            ->maxLength(500)
                                            ->rows(2)
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\RichEditor::make('detailed_description')
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
                            
                        Tabs\Tab::make('Pricing & Inventory')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('base_price')
                                                    ->numeric()
                                                    ->prefix('LKR')
                                                    ->required()
                                                    ->minValue(0)
                                                    ->step(0.01),
                                                    
                                                Forms\Components\Select::make('price_unit')
                                                    ->options([
                                                        'day' => 'Per Day',
                                                        'event' => 'Per Event',
                                                        'hour' => 'Per Hour',
                                                    ])
                                                    ->default('day')
                                                    ->required(),
                                                    
                                                Forms\Components\TextInput::make('available_quantity')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(0)
                                                    ->default(0),
                                            ]),
                                            
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('min_quantity')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->minValue(1)
                                                    ->label('Minimum Order Quantity'),
                                                    
                                                Forms\Components\TextInput::make('max_quantity')
                                                    ->numeric()
                                                    ->default(10)
                                                    ->minValue(1)
                                                    ->label('Maximum Order Quantity'),
                                                    
                                                Forms\Components\TextInput::make('sort_order')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0),
                                            ]),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('Additional Details')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Forms\Components\Repeater::make('included_items')
    ->label('What\'s Included')
    ->simple(
        Forms\Components\TextInput::make('item')
            ->required()
            ->placeholder('e.g., 2x Professional Speakers')
    )
    ->addActionLabel('Add Item')
    ->reorderable()
    ->defaultItems(1)
    ->columnSpanFull()
    ->helperText('List all items included with this rental'),
                                            
                                        Forms\Components\Repeater::make('addons')
                                            ->label('Available Add-ons')
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->required(),
                                                Forms\Components\TextInput::make('price')
                                                    ->numeric()
                                                    ->prefix('LKR')
                                                    ->required(),
                                                Forms\Components\Textarea::make('description')
                                                    ->rows(2),
                                            ])
                                            ->columns(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('Media')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('main')
                                            ->label('Main Product Image')
                                            ->collection('main')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->maxSize(2048) // 2MB
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->columnSpanFull()
                                            ->helperText('Upload main product image. Max size: 2MB. Formats: JPEG, PNG, WebP'),
                                            
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                                            ->label('Product Gallery')
                                            ->collection('gallery')
                                            ->multiple()
                                            ->reorderable()
                                            ->image()
                                            ->maxFiles(10)
                                            ->maxSize(2048) // 2MB each
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->columnSpanFull()
                                            ->helperText('Upload up to 10 additional product images. Drag to reorder.'),
                                    ]),
                            ]),
                            
                        Tabs\Tab::make('SEO')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->maxLength(255)
                                            ->helperText('Leave empty to use product name'),
                                            
                                        Forms\Components\Textarea::make('meta_description')
                                            ->maxLength(500)
                                            ->rows(3)
                                            ->helperText('Leave empty to use short description'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
                    
                Section::make('Visibility')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('featured')
                                    ->label('Featured Product')
                                    ->helperText('Show in featured products section'),
                                    
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
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (Product $record): string => 
                        $record->brand ? "Brand: {$record->brand}" : ''
                    ),
                    
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('base_price')
                    ->money('LKR')
                    ->sortable()
                    ->alignEnd()
                    ->description(fn (Product $record): string => 
                        "per {$record->price_unit}"
                    ),
                    
                Tables\Columns\TextColumn::make('available_quantity')
                    ->label('Stock')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn (int $state): string => 
                        $state > 10 ? 'success' : 
                        ($state > 0 ? 'warning' : 'danger')
                    ),
                    
                Tables\Columns\TextColumn::make('variations_count')
                    ->counts('variations')
                    ->label('Variations')
                    ->alignCenter()
                    ->badge(),
                    
                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->alignCenter()
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

                Tables\Columns\SpatieMediaLibraryImageColumn::make('main')
                    ->label('Image')
                    ->collection('main')
                    ->circular()
                    ->size(50)
                    ->defaultImageUrl(url('images/product-placeholder.jpg')),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured'),
                    
                Tables\Filters\Filter::make('low_stock')
                    ->query(fn ($query) => $query->where('available_quantity', '<=', 5))
                    ->label('Low Stock')
                    ->toggle(),
                    
                Tables\Filters\Filter::make('out_of_stock')
                    ->query(fn ($query) => $query->where('available_quantity', 0))
                    ->label('Out of Stock')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->requiresConfirmation()
                    ->action(function (Product $record) {
                        $newProduct = $record->replicate();
                        $newProduct->name = $record->name . ' (Copy)';
                        $newProduct->slug = Str::slug($newProduct->name);
                        $newProduct->sku = $record->sku . '-COPY-' . time();
                        $newProduct->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggle_featured')
                        ->label('Toggle Featured')
                        ->icon('heroicon-o-star')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'featured' => !$record->featured
                                ]);
                            }
                        }),
                    Tables\Actions\BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'active' => 'Active',
                                    'inactive' => 'Inactive',
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
            RelationManagers\VariationsRelationManager::class,
            RelationManagers\AttributesRelationManager::class,
            RelationManagers\InventoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        $outOfStock = static::getModel()::where('available_quantity', 0)->count();
        return $outOfStock > 0 ? 'danger' : 'success';
    }
}