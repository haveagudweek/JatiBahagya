<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $modelLabel = 'Kategori Produk';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Kategori')
                ->required()
                ->maxLength(255)
                ->placeholder('Masukkan nama kategori')
                ->live(debounce: 500) // Untuk auto-generate slug
                ->afterStateUpdated(
                    fn($state, callable $set) =>
                    $set('slug', Str::slug($state))
                ),

            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->disabled() // Nonaktifkan input manual
                ->dehydrated(), // Simpan slug ke database

            Forms\Components\FileUpload::make('image')
                ->label('Gambar Kategori')
                ->image()
                ->directory('categories')
                ->maxSize(2048) // 2MB
                ->imageResizeMode('cover') // atau 'contain' tergantung kebutuhan
                ->imageCropAspectRatio('4:3'), // Rasio 3:4

            Forms\Components\Select::make('parent_id')
                ->label('Kategori Induk')
                ->relationship('parent', 'name')
                ->nullable()
                ->searchable(false)
                ->preload(), // Meningkatkan UX dalam memilih kategori
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Nama Kategori')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('slug')
                ->label('Slug')
                ->sortable()
                ->searchable(),

            Tables\Columns\ImageColumn::make('image')
                ->label('Gambar Produk'),

            Tables\Columns\TextColumn::make('parent.name')
                ->label('Kategori Induk')
                ->sortable()
                ->default('-'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diperbarui Pada')
                ->dateTime(),
        ])
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Kategori Induk')
                    ->relationship('parent', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
