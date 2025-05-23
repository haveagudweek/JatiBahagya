<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Shipping;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater; // Pastikan import ini
use Filament\Forms\Components\Select;    // Pastikan import ini
use Filament\Forms\Components\TextInput;  // Pastikan import ini
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str; 

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Display fields, but make them disabled for editing
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pengguna')
                    ->disabled()
                    ->columnSpanFull(), // Disable editing

                Forms\Components\Select::make('user_address_id')
                    ->relationship('userAddress', 'full_address')
                    ->label('Alamat Pengguna')
                    ->disabled(),

                Forms\Components\TextInput::make('order_code')
                    ->label('Kode Pesanan')
                    ->disabled(),

                Forms\Components\TextInput::make('total_order')
                    ->label('Total Pesanan')
                    ->numeric()
                    ->disabled(),
                    // ->formatStateUsing(fn(string $state): string => number_format($state, 0, ',', '.')), // Format as IDR

                Forms\Components\TextInput::make('total_shipping')
                    ->label('Biaya Pengiriman')
                    ->numeric()
                    ->disabled(),
                    // ->formatStateUsing(fn(string $state): string => number_format($state, 0, ',', '.')), // Format as IDR

                Forms\Components\TextInput::make('total_fee')
                    ->label('Biaya Tambahan')
                    ->numeric()
                    ->disabled(),
                    // ->formatStateUsing(fn(string $state): string => number_format($state, 0, ',', '.')), // Format as IDR

                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah Total')
                    ->numeric()
                    ->disabled(),
                    // ->formatStateUsing(fn(string $state): string => number_format($state, 0, ',', '.')), // Format as IDR

                Forms\Components\Textarea::make('shipping_address')
                    ->label('Alamat Pengiriman')
                    ->disabled(),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->disabled(),

                // Editable fields: status and payment_status
                Forms\Components\Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending' => 'Menunggu',
                        'process' => 'Proses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'canceled' => 'Dibatalkan',
                    ])
                    ->required(),

                Forms\Components\Select::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Dibayar',
                        'paid' => 'Dibayar',
                        'refunded' => 'Dikembalikan',
                    ])
                    ->required()
                    ->reactive() // Make it reactive
                    ->afterStateUpdated(function ($state, $set, $get) {
                        if ($state === 'paid' && $get('payment_method') === 'cod' && !$get('shipping_exists')) {  // Check if already created to prevent duplicates
                            $shippingMethod = $get('shipping_method'); // Assuming you have this field in your form.

                            // Validation to ensure $shippingMethod is not null
                            if (!$shippingMethod) {
                                session()->flash('error', 'Metode pengiriman harus diisi jika pembayaran COD.');
                                $set('payment_status', 'unpaid'); // Revert back to unpaid.
                                return;
                            }

                            $estimatedDeliveryDays = match ($shippingMethod) {
                                'jne', 'jnt' => 3,
                                'go-send', 'grab-express', 'lalamove' => 1,
                                'private' => 2,
                                default => 3,
                            };
                            $estimatedDeliveryDate = now()->addDays($estimatedDeliveryDays);

                            $trackingPrefix = $shippingMethod == 'private' ? 'SHIP' : strtoupper($shippingMethod);
                            $trackingNumber = $trackingPrefix . '-' . strtoupper(Str::random(16));

                            $order = Order::find($get('id')); // Get the current order

                            Shipping::create([
                                'order_id' => $order->id,
                                'courier_name' => $shippingMethod,
                                'tracking_number' => $trackingNumber,
                                'status' => 'in_transit',
                                'estimated_delivery_date' => $estimatedDeliveryDate,
                                'delivered_at' => null,
                            ]);

                            $set('shipping_exists', true); // Set a flag to prevent creating multiple shipments
                        }
                    }),

                Repeater::make('orderItems')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->disabled(), // Disable editing product

                        TextInput::make('quantity')
                            ->label('Kuantitas')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('price_per_item')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('total_price')
                            ->label('Total Harga')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columnSpanFUll()
                    ->collapsible()
                    ->minItems(1)
                    ->itemLabel(fn(array $state): ?string => 'Produk Item Order #' . ($state['product_id'] ?? null))
                    ->disabled(), // Disable the entire repeater

            ]);
    }

    public function orderItems(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => collect(json_decode($value, true))->map(function ($item) {
                $item['price_per_item'] = number_format($item['price_per_item'], 0, ',', '.');
                $item['total_price'] = number_format($item['total_price'], 0, ',', '.');
                return $item;
            })->toArray(),
            set: fn($value) => json_encode($value), // Important: Add the setter!
        );
    }

    // ... (Kode table, getRelations, getPages tetap sama)
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pengguna'),
                TextColumn::make('order_code')
                    ->label('Kode Pesanan'),
                TextColumn::make('total_order')
                    ->label('Total Pesanan')
                    ->money('idr'),
                TextColumn::make('status')
                    ->label('Status Pesanan')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'process' => 'Proses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'canceled' => 'Dibatalkan',
                    }),
                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'unpaid' => 'Belum Dibayar',
                        'paid' => 'Dibayar',
                        'refunded' => 'Dikembalikan',
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal Pesanan')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
