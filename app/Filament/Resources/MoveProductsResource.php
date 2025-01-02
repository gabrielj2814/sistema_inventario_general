<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MoveProductsResource\Pages;
use App\Filament\Resources\MoveProductsResource\RelationManagers;
use App\Models\InventoryMovement;
use App\Models\MoveProduct;
use App\Models\MoveProducts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MoveProductsResource extends Resource
{

    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $navigationLabel = 'Move Product';

    protected static ?string $model = MoveProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralModelLabel(): string
    {
        return 'Move Product'; // Título personalizado
    }

    public static function getNavigationLabel(): string
    {
        return 'Move Product'; // Texto en la barra de navegación
    }


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
                //
                TextColumn::make("product.name")->label("Product"),
                TextColumn::make("fromWarehouse.name")->label("Form Warehouse"),
                TextColumn::make("untilWarehouse.name")->label("Until Warehouse"),
                TextColumn::make("amount")->label("Amount"),
                TextColumn::make("user.name")->label("User"),
            ])
            ->filters([
                //
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
            'index' => Pages\ListMoveProducts::route('/'),
            'create' => Pages\CreateMoveProducts::route('/create'),
            // 'edit' => Pages\EditMoveProducts::route('/{record}/edit'),
        ];
    }
}
