<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Ulasan Produk';

    protected static ?string $modelLabel = 'Ulasan Produk';

    protected static ?string $navigationGroup = 'Produk';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '1 Bintang',
                        2 => '2 Bintang',
                        3 => '3 Bintang',
                        4 => '4 Bintang',
                        5 => '5 Bintang',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label('Judul Ulasan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('comment')
                    ->label('Komentar')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_approved')
                    ->label('Disetujui')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('rating')
                    ->label('Rating')
                    ->icon(fn(int $state): string => match (true) {
                        $state >= 5 => 'heroicon-o-star',
                        $state >= 4 => 'heroicon-o-star',
                        $state >= 3 => 'heroicon-o-star',
                        $state >= 2 => 'heroicon-o-star',
                        $state >= 1 => 'heroicon-o-star',
                        default => 'heroicon-o-star',
                    })
                    ->color(fn(int $state): string => match (true) {
                        $state >= 5 => 'success',
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        $state >= 2 => 'danger',
                        $state >= 1 => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->label('Filter Produk'),

                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '1 Bintang',
                        2 => '2 Bintang',
                        3 => '3 Bintang',
                        4 => '4 Bintang',
                        5 => '5 Bintang',
                    ])
                    ->label('Filter Rating'),

                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Hanya yang Disetujui')
                    ->trueLabel('Sudah disetujui')
                    ->falseLabel('Belum disetujui')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Setujui Ulasan Terpilih')
                        ->icon('heroicon-o-check')
                        ->action(function ($records) {
                            $records->each->update(['is_approved' => true]);
                        }),
                    Tables\Actions\BulkAction::make('reject')
                        ->label('Tolak Ulasan Terpilih')
                        ->icon('heroicon-o-x-mark')
                        ->action(function ($records) {
                            $records->each->update(['is_approved' => false]);
                        }),
                ]),
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
            'index' => Pages\ListReviews::route('/'),
            // 'create' => Pages\CreateReview::route('/create'),
            // 'view' => Pages\ViewReview::route('/{record}'),
            // 'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
