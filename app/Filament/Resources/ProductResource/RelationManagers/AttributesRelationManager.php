<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributes';

    protected static ?string $title = 'Product Specifications';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('attribute_key')
                    ->label('Specification Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Power Output, Weight, Dimensions'),
                    
                Forms\Components\Textarea::make('attribute_value')
                    ->label('Value')
                    ->required()
                    ->rows(2)
                    ->placeholder('e.g., 1000 Watts, 25 kg, 60x40x30 cm'),
                    
                Forms\Components\Select::make('attribute_type')
                    ->label('Type')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'boolean' => 'Yes/No',
                        'list' => 'List',
                    ])
                    ->default('text')
                    ->required(),
                    
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('attribute_key')
            ->columns([
                Tables\Columns\TextColumn::make('attribute_key')
                    ->label('Specification')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('attribute_value')
                    ->label('Value')
                    ->searchable()
                    ->wrap()
                    ->limit(50),
                    
                Tables\Columns\BadgeColumn::make('attribute_type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'text',
                        'success' => 'number',
                        'warning' => 'boolean',
                        'danger' => 'list',
                    ]),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('attribute_type')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'boolean' => 'Yes/No',
                        'list' => 'List',
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