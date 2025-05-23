<?php

namespace App\Filament\Resources;

use Illuminate\Support\Str;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Support\RawJs;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Produk';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('price')
                            ->label('Harga Dasar')
                            ->prefix('Rp')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 0)
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('discount')
                            ->label('Diskon Produk (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%')
                            ->helperText('Masukkan persentase diskon (0-100)'),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stock Awal')
                            ->numeric()
                            ->required(),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required(),

                        Forms\Components\Select::make('brand_id')
                            ->label('Merek')
                            ->relationship('brand', 'name')
                            ->required(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('products'),

                        Forms\Components\Toggle::make('is_new_product')
                            ->label('Produk Baru?')
                            ->default(false)
                            ->inline(false),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Repeater::make('attributes')
                    ->relationship()
                    ->itemLabel(function (array $state): ?string {
                        return $state['name'] ?? null;
                    })
                    ->label('Varian Produk')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Label Varian')
                            ->required(),

                        Forms\Components\Repeater::make('values')
                            ->relationship()
                            ->label('Isi Varian')
                            ->simple(
                                Forms\Components\TextInput::make('value')
                                    ->label('Nilai')
                                    ->required(),
                            )
                            ->addActionLabel('Tambah Nilai')
                            ->columns(1)
                    ])
                    ->collapsed()
                    ->addActionLabel('Tambah Label Varian')
                    ->columns(1),

                Forms\Components\Repeater::make('variants')
                    ->relationship()
                    ->label('Varian')
                    ->itemLabel(function (array $state): ?string {
                        if (empty($state['attribute_combination'])) {
                            return null;
                        }

                        try {
                            $combination = json_decode($state['attribute_combination'], true);
                            $values = AttributeValue::whereIn('id', $combination)
                                ->with('attribute')
                                ->get()
                                ->map(fn($value) => $value->attribute->name . ': ' . $value->value)
                                ->join(', ');

                            return $values ?: 'Varian #' . ($state['sku'] ?? '');
                        } catch (\Exception $e) {
                            return 'Varian #' . ($state['sku'] ?? '');
                        }
                    })
                    ->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->default(function ($get) {
                                // Ambil 3 huruf pertama dari nama produk (jika ada)
                                $productPrefix = strtoupper(substr($get('../../name') ?? '', 0, 3));

                                $randomPart1 = strtoupper(Str::random(4));
                                $randomPart2 = strtoupper(Str::random(4));

                                // Gabungkan semua bagian
                                return sprintf(
                                    '%s-%s-%s',
                                    $productPrefix ?: 'SKU',
                                    $randomPart1,
                                    $randomPart2
                                );
                            })
                            ->disabled(fn($get) => empty($get('../../name')))
                            ->dehydrated()
                            ->required()
                            ->unique(ProductVariant::class, 'sku', ignoreRecord: true),

                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)
                            ->prefix('Rp')
                            ->numeric(),

                        Forms\Components\TextInput::make('discount')
                            ->label('Diskon Varian (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%')
                            ->helperText('Kosongkan atau 0 untuk menggunakan diskon produk'),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stok')
                            ->numeric(),

                        Forms\Components\Select::make('attribute_combination')
                            ->label('Pilih Kombinasi Varian')
                            ->options(function ($get, $state, ?ProductVariant $record = null) {
                                $productId = $get('../../id');
                                if (!$productId) return [];

                                $attributes = Attribute::with('values')
                                    ->where('product_id', $productId)
                                    ->get();

                                $allCombinations = static::generateCombinations($attributes);

                                if ($record?->exists && $record->product) {
                                    $usedCombinations = $record->product->variants()
                                        ->where('id', '!=', $record->id)
                                        ->with('attributeValues')
                                        ->get()
                                        ->map(function ($variant) {
                                            return json_encode($variant->attributeValues->pluck('id')->sort()->toArray());
                                        })
                                        ->toArray();

                                    return array_filter($allCombinations, function ($key) use ($usedCombinations) {
                                        return !in_array($key, $usedCombinations);
                                    }, ARRAY_FILTER_USE_KEY);
                                }

                                return $allCombinations;
                            })
                            ->required()
                            ->searchable(false)
                            ->reactive()
                            ->disabled(fn($get) => empty($get('../../id')))
                            ->placeholder(function ($get) {
                                return empty($get('../../id'))
                                    ? 'Simpan produk terlebih dahulu'
                                    : 'Pilih kombinasi varian';
                            })
                            ->afterStateHydrated(function ($component, $state, ?ProductVariant $record = null) {
                                if (!$state && $record?->exists) {
                                    $ids = $record->attributeValues()->pluck('attribute_values.id')->toArray();
                                    $component->state(json_encode($ids));
                                } elseif (is_array($state)) {
                                    $component->state(json_encode($state));
                                } elseif ($state instanceof Collection) {
                                    $component->state(json_encode($state->pluck('id')->toArray()));
                                }
                            })
                            ->dehydrateStateUsing(function ($state) {
                                return is_array($state) ? json_encode($state) : $state;
                            })
                            ->saveRelationshipsUsing(function (ProductVariant $variant, $state) {
                                try {
                                    Log::debug('Pre-Save State', ['raw_state' => $state]);

                                    $ids = [];
                                    if (is_string($state)) {
                                        $ids = json_decode($state, true) ?? [];
                                    } elseif (is_array($state)) {
                                        $ids = $state;
                                    }

                                    $validIds = array_filter(array_unique($ids), function ($id) {
                                        return is_numeric($id) && $id > 0;
                                    });

                                    Log::debug('Valid IDs to Sync', ['ids' => $validIds]);

                                    $variant->attributeValues()->sync($validIds);
                                    $variant->update([
                                        'sku' => static::generateSku($variant->product, $validIds)
                                    ]);

                                    Log::debug('Post-Save Verification', [
                                        'attached' => $variant->attributeValues->pluck('id')
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Sync Failed', [
                                        'error' => $e->getMessage(),
                                        'trace' => $e->getTraceAsString()
                                    ]);
                                    throw $e;
                                }
                            }),

                        Forms\Components\Fieldset::make('Kombinasi Varian')
                            ->schema([
                                Forms\Components\Placeholder::make('existing_combination')
                                    ->content(function ($get) {
                                        $combination = $get('attribute_combination');
                                        if (empty($combination)) {
                                            return 'Belum memilih kombinasi';
                                        }

                                        try {
                                            $ids = json_decode($combination, true);
                                            $values = AttributeValue::whereIn('id', $ids)
                                                ->with('attribute')
                                                ->get()
                                                ->map(
                                                    fn($value) =>
                                                    '<strong>' . $value->attribute->name . '</strong>: ' . $value->value
                                                )
                                                ->implode('<br>');

                                            return new HtmlString($values);
                                        } catch (\Exception $e) {
                                            return 'Kombinasi tidak valid';
                                        }
                                    })
                                    ->hidden(fn($get) => empty($get('attribute_combination'))),
                            ]),

                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar Varian')
                            ->image()
                            ->directory('product-variants')
                    ])
                    ->collapsed()
                    ->addActionLabel('Tambah Varian Produk')
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Nama Produk')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Kategori Produk')
                ->sortable(),

            Tables\Columns\TextColumn::make('brand.name')
                ->label('Merek Produk')
                ->sortable(),

            Tables\Columns\TextColumn::make('price')
                ->label('Harga Produk')
                ->sortable()
                ->money('IDR'),

            Tables\Columns\TextColumn::make('stock')
                ->label('Stok Produk')
                ->sortable(),

            Tables\Columns\ImageColumn::make('image')
                ->label('Gambar Produk'),

            Tables\Columns\TextColumn::make('status')
                ->label('Status Produk')
                ->sortable(),

        ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori Produk')
                    ->relationship('category', 'name')
                    ->searchable(),

                SelectFilter::make('brand_id')
                    ->label('Merek Produk')
                    ->relationship('brand', 'name')
                    ->searchable(),

                SelectFilter::make('status')
                    ->label('Status Produk')
                    ->options([
                        'active' => 'Tersedia',
                        'inactive' => 'Tidak Tersedia',
                    ])
                    ->default('active'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    protected static function generateCombinations($attributes)
    {
        // Handle case ketika tidak ada atribut
        if ($attributes->isEmpty()) {
            return [];
        }

        $groups = $attributes->map(fn($attr) => $attr->values->map(fn($val) => [
            'id' => $val->id,
            'label' => "{$attr->name}: {$val->value}"
        ]));

        // Pastikan semua groups punya nilai
        $groups = $groups->filter(fn($group) => $group->isNotEmpty());

        if ($groups->isEmpty()) {
            return [];
        }

        $combinations = collect($groups->shift())
            ->crossJoin(...$groups)
            ->mapWithKeys(fn($items) => [
                json_encode(collect($items)->pluck('id')) =>
                collect($items)->pluck('label')->join(' + ')
            ]);

        return $combinations->all();
    }

    protected static function generateSku($product, $attributeValues)
    {
        $prefix = strtoupper(substr($product->name, 0, 3));
        $codes = AttributeValue::whereIn('id', $attributeValues)
            ->orderBy('attribute_id')
            ->get()
            ->map(fn($av) => strtoupper(substr($av->value, 0, 2)))
            ->implode('');

        return $prefix . '-' . $codes . '-' . Str::random(4);
    }
}
