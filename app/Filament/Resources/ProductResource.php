<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\ProductSupplierRelationManager;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{

    protected static ?string $navigationLabel = 'Product';

    protected static ?string $recordTitleAttribute = "name";

    protected static ?string $model = Product::class;

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
                //
                TextColumn::make("name")->label("Name"),
                TextColumn::make("price_unit")->label("Precie Unit"),
                TextColumn::make("category.name")->label("Category"),
                TextColumn::make("unit_of_measurement")->label("Unit of Measurement"),
            ])
            ->filters([
                //
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('name')
                ->options(self::getModel()::all()->pluck('name', 'name'))
                ->searchable()
                ->preload(),
                Tables\Filters\SelectFilter::make('unit_of_measurement')
                ->options([
                    "mg" => "mg",
                    "g" => "g",
                    "kg" => "kg",
                    "t" => "t",
                    "mm" => "mm",
                    "cm" => "cm",
                    "m" => "m",
                    "km" => "km",
                    "ud" => "ud",
                    "dz" => "dz",
                    "pkg" => "pkg",
                    "box" => "box",
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ProductSupplierRelationManager::class
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
}
