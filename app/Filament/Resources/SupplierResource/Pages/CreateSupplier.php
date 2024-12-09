<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;


    public function form(Form $form): Form{

        return $form->schema([
            Section::make("Creataed")->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])->schema([
                TextInput::make('name')->label("Name")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                TextInput::make('email')->label("Email")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                TextInput::make('phone')->label("Phone")->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Textarea::make('adress')->label("Adress")->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 12,"xl" => 12,"xl2" => 12,]),
                Textarea::make('description')->label("Description")->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 12,"xl" => 12,"xl2" => 12,]),
            ])
        ]);

    }


    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Supplier Registered')
            ->body('The customer has been saved successfully.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }



}
