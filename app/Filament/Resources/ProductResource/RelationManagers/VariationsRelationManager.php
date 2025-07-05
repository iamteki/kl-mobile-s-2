<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VariationsRelationManager extends RelationManager
{
    protected static string $relationship = 'variations';

    protected static ?string $title = 'Product Variations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., 1000W, Red Color, Large Size'),
                            
                        Forms\Components\TextInput::make('sku')
                            ->label('Variation SKU')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ]),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('LKR')
                            ->required()
                            ->minValue(0),
                            
                        Forms\Components\TextInput::make('available_quantity')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->default(0),
                    ]),
                    
                Forms\Components\KeyValue::make('attributes')
                    ->label('Variation Attributes')
                    ->keyLabel('Attribute')
                    ->valueLabel('Value')
                    ->addActionLabel('Add Attribute')
                    ->columnSpanFull(),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                            
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('price')
                    ->money('LKR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('available_quantity')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => 
                        $state > 10 ? 'success' : 
                        ($state > 0 ? 'warning' : 'danger')
                    ),
                    
                Tables\Columns\TextColumn::make('attributes')
                    ->label('Attributes')
                    ->formatStateUsing(fn ($state) => 
                        $state ? collect($state)->map(fn ($value, $key) => 
                            "{$key}: {$value}"
                        )->implode(', ') : '-'
                    )
                    ->wrap()
                    ->toggleable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
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
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc');
    }
}