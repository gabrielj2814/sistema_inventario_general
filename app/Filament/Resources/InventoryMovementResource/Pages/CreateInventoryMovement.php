<?php

namespace App\Filament\Resources\InventoryMovementResource\Pages;

use App\Filament\Resources\InventoryMovementResource;
use App\Models\InventoryMovement;
use App\Models\InventoryWarehouse;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Warehouse;
use DateTime;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CreateInventoryMovement extends CreateRecord
{
    protected static string $resource = InventoryMovementResource::class;

    private $dataForm=[];

    function form(Form $form):Form{
        return $form->schema([
            Section::make("Created")->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])->schema([
                Select::make("product")->required()->searchable()->options(Product::all()->pluck("name","id"))->live()
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Select::make("product_supplier_id")->label("Supplier")->required()->searchable()->options(fn ($get):Collection => ProductSupplier::query()->with("supplier")->where("product_id","=",$get("product"))->get()->pluck("supplier.name","id"))
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Select::make("warehouse_id")->required()->label("Warehouse")->searchable()->options(Warehouse::all()->pluck("name","id"))
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Select::make("type")->required()->label("Type")->options([
                    "entrada"=>"Entrada",
                    "ajuste" => "Ajuste",
                ])
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                TextInput::make("amount")->required()->autocomplete(false)->numeric()->default(0)
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Textarea::make("note")->required()->autocomplete(false)->columnSpan(["sm" => 12,"md" => 12,"lg" => 12,"xl" => 12,"xl2" => 12]),
            ])
        ]);
    }



    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user=Auth::user();
        $today=new DateTime("now");
        // $data["type"]="estrada";
        $data["date_movement"]=$today->format("Y-m-d");
        $data["user_id"]=$user->id;
        $data["product_id"]=$data["product"];

        $this->dataForm=$data;
        return $data;
    }

    protected function beforeCreate(): void
    {
        // die();

        if($this->dataForm["type"]=="entrada"){
            if((int)$this->dataForm["amount"]<=0){
                Notification::make()
                ->danger()
                ->title('error al validar el formulario')
                ->body('no se permite hacer una entrada en 0.')
                ->send();
                $this->halt();
            }

        }
        else if($this->dataForm["type"]=="ajuste"){
            $productSupplier=ProductSupplier::find($this->dataForm["product_supplier_id"]);
            $isStockOfTheProductoinTheWarehose=InventoryWarehouse::query()
            ->where("product_id","=",$productSupplier->product->id)
            ->where("warehouse_id","=",$this->dataForm["warehouse_id"])
            ->get()->first();

            $InventoryWareHouse=$isStockOfTheProductoinTheWarehose;

            if($InventoryWareHouse){
                $totalStock=(int)$InventoryWareHouse->stock-(int)$this->dataForm["amount"];
                if($totalStock<0){
                    Notification::make()
                    ->warning()
                    ->title('Error al ajustar')
                    ->body('el sotck no puede quedar en negativo.')
                    ->send();
                    $this->halt();
                }
            }
        }
    }

    protected function afterCreate(){
        $record=$this->getRecord();

        $isStockOfTheProductoinTheWarehose=InventoryWarehouse::query()
        ->where("product_id","=",$record->productSupplier->product->id)
        ->where("warehouse_id","=",$record->warehouse_id)
        ->get();
        if($record->type=="entrada"){
            $InventoryWareHouse=null;
            if(count($isStockOfTheProductoinTheWarehose)>0){
                $InventoryWareHouse=$isStockOfTheProductoinTheWarehose[0];
            }
            else{
                $InventoryWareHouse=new InventoryWarehouse();
                $InventoryWareHouse->stock=0;
                $InventoryWareHouse->product_id=$record->productSupplier->product->id;
                $InventoryWareHouse->warehouse_id=$record->warehouse_id;
            }

            $InventoryWareHouse->stock=(int)$InventoryWareHouse->stock+$record->amount;
            $InventoryWareHouse->save();
        }
        else if($record->type=="ajuste"){
            if(count($isStockOfTheProductoinTheWarehose)>0){
                $InventoryWareHouse=$isStockOfTheProductoinTheWarehose[0];
                $totalStock=(int)$InventoryWareHouse->stock-$record->amount;
                if($totalStock>=0){
                    $InventoryWareHouse->stock=(int)$InventoryWareHouse->stock-$record->amount;
                    $InventoryWareHouse->save();
                    if($InventoryWareHouse->stock==0){
                        $InventoryWareHouse->delete();
                    }
                }

            }
        }

    }

    protected function getSavedNotification(){
        return Notification::make()
            ->success()
            ->title('Inventory Registered')
            ->body('The Inventory has been saved successfully.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }


}
