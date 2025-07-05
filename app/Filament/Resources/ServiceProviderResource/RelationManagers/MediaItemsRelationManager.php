<?php

namespace App\Filament\Resources\ServiceProviderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MediaItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'mediaItems';
    
    protected static ?string $title = 'Portfolio Media';
    
    protected static ?string $modelLabel = 'Media Item';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'image' => 'Image',
                        'video' => 'Video',
                        'audio' => 'Audio Sample',
                    ])
                    ->required(),
                    
                Forms\Components\TextInput::make('url')
                    ->label('Media URL')
                    ->url()
                    ->required()
                    ->helperText('Direct link to image or YouTube/Vimeo video'),
                    
                Forms\Components\TextInput::make('thumbnail_url')
                    ->label('Thumbnail URL')
                    ->url()
                    ->helperText('For videos - thumbnail image URL'),
                    
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->rows(2)
                    ->maxLength(500),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                            
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->helperText('Show in main gallery'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('Preview')
                    ->defaultImageUrl(fn ($record) => 
                        $record->type === 'video' 
                            ? 'https://via.placeholder.com/100x75?text=Video' 
                            : $record->url
                    )
                    ->square()
                    ->size(75),
                    
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->wrap(),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'image',
                        'success' => 'video',
                        'warning' => 'audio',
                    ]),
                    
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(),
                    
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star'),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'image' => 'Image',
                        'video' => 'Video',
                        'audio' => 'Audio',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab(),
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