<?php

namespace App\Filament\Resources\ServiceProviderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PricingTiersRelationManager extends RelationManager
{
    protected static string $relationship = 'pricingTiers';
    
    protected static ?string $title = 'Pricing Tiers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tier_name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Basic Package, Premium Package'),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('LKR')
                            ->required()
                            ->minValue(0),
                            
                        Forms\Components\TextInput::make('duration')
                            ->required()
                            ->placeholder('e.g., 4 hours, Full Day, Per Song'),
                    ]),
                    
                Forms\Components\Repeater::make('included_features')
                    ->label('Included Features')
                    ->simple(
                        Forms\Components\TextInput::make('feature')
                            ->required()
                            ->placeholder('e.g., Sound system included')
                    )
                    ->defaultItems(3)
                    ->columnSpanFull(),
                    
                Forms\Components\KeyValue::make('additional_costs')
                    ->label('Additional Costs')
                    ->keyLabel('Item')
                    ->valueLabel('Cost (LKR)')
                    ->addActionLabel('Add Cost')
                    ->columnSpanFull(),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                            
                        Forms\Components\Toggle::make('is_popular')
                            ->label('Mark as Popular')
                            ->helperText('Highlight this tier'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tier_name')
            ->columns([
                Tables\Columns\TextColumn::make('tier_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('price')
                    ->money('LKR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('duration')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('included_features')
                    ->label('Features')
                    ->formatStateUsing(fn ($state) => 
                        is_array($state) ? count($state) . ' features' : '0 features'
                    )
                    ->badge(),
                    
                Tables\Columns\IconColumn::make('is_popular')
                    ->label('Popular')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star'),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
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