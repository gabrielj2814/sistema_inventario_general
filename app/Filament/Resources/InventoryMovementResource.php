<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryMovementResource\Pages;
use App\Filament\Resources\InventoryMovementResource\RelationManagers;
use App\Models\InventoryMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryMovementResource extends Resource
{

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
}
