<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;


    function form(Form $form): Form{
        return $form->schema([
            Section::make("Created")->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])->schema([
                TextInput::make("name")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // TextInput::make("barcode")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                // TextInput::make("stock")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                TextInput::make("price_unit")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Select::make("category_id")->required()->label("Category")->options(Category::all()->pluck("name","id"))->searchable()->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Select::make("unit_of_measurement")->required()->options([
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
                ])->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Textarea::make("description")->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 12,"xl" => 12,"xl2" => 12]),
            ])
        ]);
    }
    // para editar
    // protected function mutateFormDataBeforeSave(array $data)
    // para crear
    protected function mutateFormDataBeforeCreate(array $data): array{
        $data["stock"]=0;
        $data["barcode"]=Str::uuid();
        return $data;
    }


    protected function afterCreate(){
        // $record = $this->getRecord();
        // dd($record->name);
        // die();
    }


    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Product Registered')
            ->body('The Product has been saved successfully.');
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }

}
