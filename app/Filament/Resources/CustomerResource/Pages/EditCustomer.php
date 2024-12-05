<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    public function form(Form $form): Form{

        return $form->schema([
            Section::make("Edit")->schema([
                TextInput::make('name')->label("Name")->required()->autocomplete(false),
                TextInput::make('email')->label("Email")->required()->autocomplete(false),
                TextInput::make('phone')->label("Phone")->autocomplete(false),
                Textarea::make('adress')->label("Adress")->autocomplete(false),
            ])
        ]);

    }



    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Customer Updated')
            ->body('The customer has been saved successfully.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}