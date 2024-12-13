<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Collection;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;


    function form(Form $form):Form {
        return $form->schema([

            Section::make("Customer")->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])->schema([
                Select::make("customer_id")->label("Customer")->required()->searchable()->options(Customer::all()->pluck("name","id"))->live()
                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state){
                    $customer=Customer::find($state);
                    if(!$customer){
                        return;
                    }
                    $set("customer_email",$customer->email);
                    $set("customer_phone_number",$customer->phone);
                    $set("customer_adress",$customer->adress);


                })
                ->preload()
                ->searchingMessage('Searching Customer...')
                ->loadingMessage('Loading Customer...')
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                TextInput::make("customer_email")->label("Email")->readOnly()->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4])->hidden(fn (Get $get) => $get("customer_id")==""),
                TextInput::make("customer_phone_number")->label("phone")->readOnly()->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4])->hidden(fn (Get $get) => $get("customer_id")==""),
                Textarea::make("customer_adress")->label("adress")->readOnly()->columnSpan(["sm" => 12,"md" => 12,"lg" => 12,"xl" => 12,"xl2" => 12])->hidden(fn (Get $get) => $get("customer_id")==""),
            ]),





        ]);
    }


    protected function getSavedNotification(){
        return Notification::make()
            ->success()
            ->title('Order Registered')
            ->body('The Order has been saved successfully.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
