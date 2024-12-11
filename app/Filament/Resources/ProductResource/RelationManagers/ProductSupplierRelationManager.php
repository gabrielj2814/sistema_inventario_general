<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductSupplierRelationManager extends RelationManager
{
    protected static string $relationship = 'productSuppliers';

    protected static ?string $model = ProductSupplier::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("supplier_id")->label("Supplier")->required()->options(Supplier::all()->pluck("name","id"))->searchable(),
                TextInput::make("price_supply")->label("Price Supply")->required()->autocomplete(false)
                ->numeric()
                ->inputMode('decimal'),
            ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Supplier')
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name'),
                Tables\Columns\TextColumn::make('price_supply'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // redirección sin parametros
                // Tables\Actions\CreateAction::make()->successRedirectUrl(route('filament.admin.resources.products.create')),
                Tables\Actions\CreateAction::make()
                ->after(function(Tables\Actions\CreateAction $action, ProductSupplier $record){
                    $ownerRecord=$this->getOwnerRecord();

                    $recordsProductsSuppliers=ProductSupplier::query()->where("supplier_id","=",$record->supplier_id)->where("product_id","=",$ownerRecord->id)->get();
                    for ($index=0; $index < count($recordsProductsSuppliers); $index++) {
                        if($record->id!=$recordsProductsSuppliers[$index]->id){
                            $recordsProductsSuppliers[$index]->delete();
                        }

                    }
                })->successRedirectUrl(fn (): string => route('filament.admin.resources.products.edit', [
                    'record' => $this->getOwnerRecord()->id,
                ]))
            ])
            ->actions([
                // redirección con paramentros
                Tables\Actions\EditAction::make()
                ->after(function(Tables\Actions\EditAction $action, ProductSupplier $record){
                    $ownerRecord=$this->getOwnerRecord();

                    $recordsProductsSuppliers=ProductSupplier::query()->where("supplier_id","=",$record->supplier_id)->where("product_id","=",$ownerRecord->id)->get();
                    for ($index=0; $index < count($recordsProductsSuppliers); $index++) {
                        if($record->id!=$recordsProductsSuppliers[$index]->id){
                            $recordsProductsSuppliers[$index]->delete();
                        }

                    }
                })
                ->successRedirectUrl(fn (): string => route('filament.admin.resources.products.edit', [
                    'record' => $this->getOwnerRecord()->id,
                ])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
