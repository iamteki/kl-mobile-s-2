<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\ProductVariation;
use Filament\Forms\Get;

class InventoryRelationManager extends RelationManager
{
    protected static string $relationship = 'inventory';

    protected static ?string $title = 'Inventory Management';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_variation_id')
                    ->label('Product Variation')
                    ->options(function (RelationManager $livewire) {
                        return $livewire->getOwnerRecord()
                            ->variations()
                            ->pluck('name', 'id');
                    })
                    ->placeholder('Main Product (No Variation)')
                    ->searchable(),
                    
                Forms\Components\Section::make('Stock Levels')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('total_quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Forms\Set $set) {
                                        $total = $get('total_quantity') ?? 0;
                                        $reserved = $get('reserved_quantity') ?? 0;
                                        $maintenance = $get('maintenance_quantity') ?? 0;
                                        $set('available_quantity', $total - $reserved - $maintenance);
                                    }),
                                    
                                Forms\Components\TextInput::make('available_quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->readOnly()
                                    ->helperText('Automatically calculated'),
                            ]),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('reserved_quantity')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Forms\Set $set) {
                                        $total = $get('total_quantity') ?? 0;
                                        $reserved = $get('reserved_quantity') ?? 0;
                                        $maintenance = $get('maintenance_quantity') ?? 0;
                                        $set('available_quantity', $total - $reserved - $maintenance);
                                    }),
                                    
                                Forms\Components\TextInput::make('maintenance_quantity')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Forms\Set $set) {
                                        $total = $get('total_quantity') ?? 0;
                                        $reserved = $get('reserved_quantity') ?? 0;
                                        $maintenance = $get('maintenance_quantity') ?? 0;
                                        $set('available_quantity', $total - $reserved - $maintenance);
                                    }),
                            ]),
                    ]),
                    
                Forms\Components\Section::make('Location & Maintenance')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('location')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Warehouse A'),
                                    
                                Forms\Components\TextInput::make('warehouse_section')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Row 5, Shelf B'),
                            ]),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('last_maintenance_date'),
                                
                                Forms\Components\DatePicker::make('next_maintenance_date'),
                            ]),
                    ]),
                    
                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'low_stock' => 'Low Stock',
                        'out_of_stock' => 'Out of Stock',
                        'discontinued' => 'Discontinued',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                Tables\Columns\TextColumn::make('variation.name')
                    ->label('Variation')
                    ->default('Main Product')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('total_quantity')
                    ->sortable()
                    ->alignCenter(),
                    
                Tables\Columns\TextColumn::make('available_quantity')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn (int $state): string => 
                        $state > 10 ? 'success' : 
                        ($state > 0 ? 'warning' : 'danger')
                    ),
                    
                Tables\Columns\TextColumn::make('reserved_quantity')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('maintenance_quantity')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->toggleable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'low_stock',
                        'danger' => fn ($state) => in_array($state, ['out_of_stock', 'discontinued']),
                    ]),
                    
                Tables\Columns\TextColumn::make('next_maintenance_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'low_stock' => 'Low Stock',
                        'out_of_stock' => 'Out of Stock',
                        'discontinued' => 'Discontinued',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}