<?php

namespace App\Filament\Resources\MoveProductsResource\Pages;

use App\Filament\Resources\MoveProductsResource;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Warehouse;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Collection;

class CreateMoveProducts extends CreateRecord
{
    protected static string $resource = MoveProductsResource::class;



    function form(Form $form):Form{
        return $form->schema([
            Section::make("From")->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])->schema([
                Select::make("product")->required()->searchable()->options(Product::all()->pluck("name","id"))->live()
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // Select::make("product_supplier_id")->label("Supplier")->required()->searchable()->options(fn ($get):Collection => ProductSupplier::query()->with("supplier")->where("product_id","=",$get("product"))->get()->pluck("supplier.name","id"))
                // ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Select::make("warehouse_id")->required()->label("Warehouse")->searchable()->options(Warehouse::all()->pluck("name","id"))
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Select::make("type")->required()->label("Type")->options([
                    "entrada"=>"Entrada",
                    "ajuste" => "Ajuste",
                ])
                // ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // TextInput::make("amount")->required()->autocomplete(false)->numeric()->default(0)
                // ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // Textarea::make("note")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 12,"xl" => 12,"xl2" => 12]),
            ]),
            Section::make("Until")->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])->schema([
                // Select::make("product")->required()->searchable()->options(Product::all()->pluck("name","id"))->live()
                // ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // Select::make("product_supplier_id")->label("Supplier")->required()->searchable()->options(fn ($get):Collection => ProductSupplier::query()->with("supplier")->where("product_id","=",$get("product"))->get()->pluck("supplier.name","id"))
                // ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // Select::make("warehouse_id")->required()->label("Warehouse")->searchable()->options(Warehouse::all()->pluck("name","id"))
                // ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // Select::make("type")->required()->label("Type")->options([
                //     "entrada"=>"Entrada",
                //     "ajuste" => "Ajuste",
                // ])
                // ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // TextInput::make("amount")->required()->autocomplete(false)->numeric()->default(0)
                // ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // Textarea::make("note")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 12,"xl" => 12,"xl2" => 12]),
                ]),

        ]);
    }


}
