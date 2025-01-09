<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryMovementResource\Pages;
use App\Filament\Resources\InventoryMovementResource\RelationManagers;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryMovementResource extends Resource
{
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $navigationLabel = 'Inventory';

    protected static ?string $recordTitleAttribute = "name";

    protected static ?string $model = InventoryMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //show date
                //show type movement

                TextColumn::make("date_movement")->date("Y-m-d"),
                TextColumn::make("type"),
                TextColumn::make("productSupplier.product.name")->label("Porduct"),
                TextColumn::make("productSupplier.supplier.name")->label("Supplier"),
                TextColumn::make("amount"),
                TextColumn::make("user.name")->label("User"),
                TextColumn::make("note"),
            ])
            ->filters([
                //
                Filter::make("relationships")->form([
                    Select::make('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                    Select::make('Supplier')
                    ->options(Supplier::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                    Select::make('Employee')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                ])
                ->query( function(Builder $query, array $data): Builder{
                    if (isset($data['Product'])) {
                        $query->whereHas('productSupplier', function (Builder $query) use ($data) {
                            $query->where('product_id', $data['Product']);
                        });
                    }
                    if (isset($data['Supplier'])) {
                        $query->whereHas('productSupplier', function (Builder $query) use ($data) {
                            $query->where('supplier_id', $data['Supplier']);
                        });
                    }
                    if (isset($data['Employee'])) {
                        $query->whereHas('user', function (Builder $query) use ($data) {
                            $query->where('user_id', $data['Employee']);
                        });
                    }
                    return $query;
                }),

                Tables\Filters\SelectFilter::make('Type')
                ->options([
                    "entrada" => "Entrada",
                    "salida" => "Salida",
                    "ajuste" => "Ajuste",
                ]),
                Filter::make("Date")->form([
                    DatePicker::make('from')->default(now()),
                    DatePicker::make('until')->default(now()),
                ])
                ->query(function (Builder $query, array $data):Builder {
                    return $query->whereBetween('date_movement', [$data['from'], $data['until']]);
                }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListInventoryMovements::route('/'),
            'create' => Pages\CreateInventoryMovement::route('/create'),
            // 'edit' => Pages\EditInventoryMovement::route('/{record}/edit'),
        ];
    }

    // public function panel(Panel $panel){
    //     return $panel->navigationGroups([
    //         NavigationGroup::make()->label("Inventory")->icon("heroicon-o-rectangle-stack"),
    //         NavigationGroup::make()->label("Move Product")->icon("heroicon-o-rectangle-stack")->collapsed(),
    //     ]);
    // }
}
