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
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    function form(Form $form): Form{
        return $form->schema([
            Section::make("Edit")->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])->schema([
                TextInput::make("name")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                TextInput::make("price_unit")->required()->autocomplete(false)->numeric()->inputMode('decimal')
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
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
    protected function mutateFormDataBeforeSave(array $data): array{
        // $data["stock"]=1;
        return $data;
    }

    protected function afterSave(){
        // $record = $this->getRecord();
        // dd($record->barcode);
        // die();
    }


    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Product Updated')
            ->body('The Product has been saved successfully.');
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }

}
