<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'Pengguna Sistem';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Pengguna')
                ->required()
                ->maxLength(255)
                ->placeholder('Masukkan nama pengguna'),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->required()
                ->email()
                ->unique()
                ->maxLength(255)
                ->placeholder('Masukkan email pengguna'),

            Forms\Components\TextInput::make('phone')
                ->label('Nomor Telepon')
                ->required()
                ->maxLength(15)
                ->placeholder('Masukkan nomor telepon'),

            Forms\Components\TextInput::make('address')
                ->label('Alamat')
                ->nullable()
                ->maxLength(255)
                ->placeholder('Masukkan alamat pengguna'),

            Forms\Components\Select::make('role')
                ->label('Role Pengguna')
                ->options([
                    'customer' => 'Customer',
                    'admin' => 'Admin',
                ])
                ->required(),

            // Relasi dengan Admin, hanya tampil jika role adalah admin
            Forms\Components\Select::make('admin.role')
                ->label('Role Admin')
                ->options([
                    'super admin' => 'Super Admin',
                    'staff' => 'Staff',
                    'customer support' => 'Customer Support',
                ])
                ->required()
                ->visible(fn($get) => $get('role') === 'admin')
                ->relationship('admin', 'role'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Nama Pengguna')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('role')
                ->label('Role Pengguna')
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diperbarui Pada')
                ->dateTime(),
        ])
            ->filters([
                // Tambahkan filter berdasarkan role, misalnya
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role Pengguna')
                    ->options([
                        'customer' => 'Customer',
                        'admin' => 'Admin',
                    ])
                    ->default('customer'),
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
            // Menambahkan relasi Admin
            // AdminRelation::make('admin')
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
