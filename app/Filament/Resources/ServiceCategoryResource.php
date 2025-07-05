<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceCategoryResource\Pages;
use App\Models\ServiceCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class ServiceCategoryResource extends Resource
{
    protected static ?string $model = ServiceCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Service Management';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $pluralModelLabel = 'Service Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Category Information')
                    ->description('Basic service category details')
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
                                    ->unique(ServiceCategory::class, 'slug', ignoreRecord: true),
                            ]),
                            
                        Forms\Components\Select::make('parent_category')
                            ->label('Parent Category')
                            ->options([
                                'Entertainment' => 'Entertainment',
                                'Technical Crew' => 'Technical Crew',
                                'Photography & Video' => 'Photography & Video',
                                'Event Staff' => 'Event Staff',
                            ])
                            ->placeholder('Select parent category')
                            ->helperText('Group this category under a parent'),
                            
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Display Settings')
                    ->description('Control how this category appears')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('icon')
                                    ->label('Font Awesome Icon Class')
                                    ->placeholder('fas fa-microphone')
                                    ->helperText('Enter Font Awesome class')
                                    ->maxLength(255),
                                    
                                Forms\Components\FileUpload::make('image')
                                    ->label('Category Image')
                                    ->image()
                                    ->directory('service-categories')
                                    ->maxSize(2048)
                                    ->helperText('Max size: 2MB. Recommended: 400x300px'),
                            ]),
                            
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->helperText('Lower numbers appear first'),
                                    
                                Forms\Components\Toggle::make('show_on_homepage')
                                    ->label('Show on Homepage')
                                    ->default(true)
                                    ->helperText('Display in homepage services section'),
                                    
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
                    ->size(50)
                    ->defaultImageUrl(url('/images/placeholder.jpg')),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->color('gray')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('parent_category')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('service_providers_count')
                    ->counts('serviceProviders')
                    ->label('Providers')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\IconColumn::make('show_on_homepage')
                    ->boolean()
                    ->label('Homepage')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
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
                Tables\Filters\SelectFilter::make('parent_category')
                    ->options([
                        'Entertainment' => 'Entertainment',
                        'Technical Crew' => 'Technical Crew',
                        'Photography & Video' => 'Photography & Video',
                        'Event Staff' => 'Event Staff',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('show_on_homepage')
                    ->label('Homepage Display'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->before(function (ServiceCategory $record) {
                        if ($record->serviceProviders()->count() > 0) {
                            throw new \Exception('Cannot delete category with service providers. Please reassign providers first.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('toggle_homepage')
                        ->label('Toggle Homepage Display')
                        ->icon('heroicon-o-eye')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'show_on_homepage' => !$record->show_on_homepage
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
            'index' => Pages\ListServiceCategories::route('/'),
            'create' => Pages\CreateServiceCategory::route('/create'),
            'view' => Pages\ViewServiceCategory::route('/{record}'),
            'edit' => Pages\EditServiceCategory::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}