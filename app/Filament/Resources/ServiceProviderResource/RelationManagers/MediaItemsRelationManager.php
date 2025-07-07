<?php

namespace App\Filament\Resources\ServiceProviderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'mediaItems';
    
    protected static ?string $title = 'Portfolio Media';
    
    protected static ?string $modelLabel = 'Media Item';
    
    protected static ?string $pluralModelLabel = 'Portfolio Items';

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
                    ->default('image')
                    ->required()
                    ->reactive() // Make reactive to show/hide fields
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        // Clear fields when type changes
                        if ($state === 'image') {
                            $set('url', null);
                            $set('thumbnail_url', null);
                        } elseif ($state === 'video') {
                            $set('image_upload', null);
                        }
                    }),
                    
                // Image Upload Field - Only visible for images
                Forms\Components\FileUpload::make('image_upload')
                    ->label('Upload Image')
                    ->image()
                    ->imageEditor()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->maxSize(10240) // 10MB
                    ->directory('service-provider-portfolio')
                    ->visibility('public')
                    ->visible(fn (Forms\Get $get): bool => $get('type') === 'image')
                    ->required(fn (Forms\Get $get): bool => $get('type') === 'image')
                    ->helperText('Upload high-quality images (Max: 10MB)'),
                    
                // URL Field - Only visible for videos and audio
                Forms\Components\TextInput::make('url')
                    ->label(fn (Forms\Get $get) => $get('type') === 'video' ? 'Video URL' : 'Media URL')
                    ->url()
                    ->visible(fn (Forms\Get $get): bool => in_array($get('type'), ['video', 'audio']))
                    ->required(fn (Forms\Get $get): bool => in_array($get('type'), ['video', 'audio']))
                    ->helperText(fn (Forms\Get $get) => 
                        $get('type') === 'video' 
                            ? 'YouTube, Vimeo, or direct video URL' 
                            : 'Direct link to audio file'
                    ),
                    
                // Thumbnail URL - Only for videos
                Forms\Components\TextInput::make('thumbnail_url')
                    ->label('Thumbnail URL (Optional)')
                    ->url()
                    ->visible(fn (Forms\Get $get): bool => $get('type') === 'video')
                    ->helperText('Leave empty to auto-generate from video'),
                    
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
                            ->default(fn () => 
                                $this->getRelationship()
                                    ->max('sort_order') + 1 ?: 1
                            )
                            ->minValue(1)
                            ->label('Display Order'),
                            
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
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('Preview')
                    ->size(60)
                    ->square()
                    ->defaultImageUrl(fn ($record) => 
                        match($record->type) {
                            'image' => $record->url, // For images, use the actual URL
                            'video' => $record->thumbnail_url ?: 'https://via.placeholder.com/60x60/9333ea/ffffff?text=VIDEO',
                            'audio' => 'https://via.placeholder.com/60x60/f59e0b/ffffff?text=AUDIO',
                            default => 'https://via.placeholder.com/60x60',
                        }
                    ),
                    
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'success',
                        'video' => 'primary',
                        'audio' => 'warning',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter(),
                    
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->alignCenter(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'image' => 'Images',
                        'video' => 'Videos',
                        'audio' => 'Audio',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Only'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Handle image upload
                        if ($data['type'] === 'image' && isset($data['image_upload'])) {
                            // The file is already stored by Filament, we just need the path
                            $data['url'] = Storage::url($data['image_upload']);
                            $data['thumbnail_url'] = $data['url']; // Same as main image for images
                            unset($data['image_upload']); // Remove the upload field
                        }
                        
                        // Auto-generate video thumbnail if not provided
                        if ($data['type'] === 'video' && empty($data['thumbnail_url'])) {
                            $data['thumbnail_url'] = $this->generateVideoThumbnail($data['url']);
                        }
                        
                        // Ensure sort_order starts from 1
                        if (!isset($data['sort_order']) || $data['sort_order'] < 1) {
                            $data['sort_order'] = $this->getRelationship()
                                ->max('sort_order') + 1 ?: 1;
                        }
                        
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Handle image upload on edit
                        if ($data['type'] === 'image' && isset($data['image_upload'])) {
                            $data['url'] = Storage::url($data['image_upload']);
                            $data['thumbnail_url'] = $data['url'];
                            unset($data['image_upload']);
                        }
                        
                        return $data;
                    }),
                    
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        // Delete the file if it's an uploaded image
                        if ($record->type === 'image' && 
                            str_starts_with($record->url, '/storage/')) {
                            $path = str_replace('/storage/', '', $record->url);
                            Storage::disk('public')->delete($path);
                        }
                    }),
                    
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Delete files for uploaded images
                            foreach ($records as $record) {
                                if ($record->type === 'image' && 
                                    str_starts_with($record->url, '/storage/')) {
                                    $path = str_replace('/storage/', '', $record->url);
                                    Storage::disk('public')->delete($path);
                                }
                            }
                        }),
                ]),
            ]);
    }
    
    /**
     * Generate thumbnail URL for videos
     */
    protected function generateVideoThumbnail(string $url): ?string
    {
        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
            return "https://img.youtube.com/vi/{$matches[1]}/maxresdefault.jpg";
        }
        
        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            // Note: Vimeo requires API call for thumbnails, returning placeholder
            return null;
        }
        
        return null;
    }
}