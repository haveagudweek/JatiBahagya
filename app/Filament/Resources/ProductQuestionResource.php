<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductQuestionResource\Pages;
use App\Filament\Resources\ProductQuestionResource\RelationManagers;
use App\Models\ProductQuestion;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProductQuestionResource extends Resource
{
    protected static ?string $model = ProductQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'Pertanyaan Produk';

    protected static ?string $modelLabel = 'Pertanyaan Produk';

    protected static ?string $navigationGroup = 'Produk';

    protected static ?int $navigationSort = 6;

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

                Forms\Components\Textarea::make('question')
                    ->label('Pertanyaan')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(1000),

                Forms\Components\Textarea::make('answer')
                    ->label('Jawaban')
                    ->columnSpanFull()
                    ->maxLength(1000)
                    ->nullable(),

                Forms\Components\Select::make('answered_by')
                    ->label('Dijawab Oleh')
                    ->options(User::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\DateTimePicker::make('answered_at')
                    ->label('Tanggal Jawaban')
                    ->nullable(),

                Forms\Components\Toggle::make('is_visible')
                    ->label('Tampilkan di Publik')
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

                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('answer')
                    ->label('Jawaban')
                    ->limit(50)
                    ->default('-'),

                Tables\Columns\TextColumn::make('answered_by')
                    ->label('Dijawab Oleh')
                    ->formatStateUsing(fn ($state): string => $state ? User::find($state)->name : '-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('answered_at')
                    ->label('Tanggal Jawaban')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Tampil')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->label('Filter Produk'),

                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Status Visibilitas')
                    ->trueLabel('Tampilkan')
                    ->falseLabel('Sembunyikan')
                    ->nullable(),

                Tables\Filters\Filter::make('answered')
                    ->label('Status Jawaban')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('answer'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('answer')
                    ->label('Jawab')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->form([
                        Forms\Components\Textarea::make('answer')
                            ->label('Jawaban')
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->action(function (ProductQuestion $record, array $data): void {
                        $record->update([
                            'answer' => $data['answer'],
                            'answered_by' => Auth::id(),
                            'answered_at' => now(),
                        ]);
                    })
                    ->visible(fn (ProductQuestion $record): bool => empty($record->answer)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('answer')
                        ->label('Jawab Pertanyaan Terpilih')
                        ->icon('heroicon-o-chat-bubble-left-ellipsis')
                        ->form([
                            Forms\Components\Textarea::make('answer')
                                ->label('Jawaban')
                                ->required()
                                ->maxLength(1000),
                        ])
                        ->action(function ($records, array $data) {
                            $records->each->update([
                                'answer' => $data['answer'],
                                'answered_by' => Auth::id(),
                                'answered_at' => now(),
                            ]);
                        }),
                    Tables\Actions\BulkAction::make('toggleVisibility')
                        ->label('Toggle Visibilitas')
                        ->icon('heroicon-o-eye')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_visible' => !$record->is_visible]);
                            });
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
            'index' => Pages\ListProductQuestions::route('/'),
            // 'create' => Pages\CreateProductQuestion::route('/create'),
            // 'view' => Pages\ViewProductQuestion::route('/{record}'),
            // 'edit' => Pages\EditProductQuestion::route('/{record}/edit'),
        ];
    }
}