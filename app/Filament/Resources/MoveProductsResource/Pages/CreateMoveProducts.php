<?php

namespace App\Filament\Resources\MoveProductsResource\Pages;

use App\Filament\Resources\MoveProductsResource;
use App\Models\InventoryWarehouse;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Warehouse;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateMoveProducts extends CreateRecord
{
    protected static string $resource = MoveProductsResource::class;


    private $dataForm;

    function form(Form $form):Form{
        return $form->schema([
            Section::make("Move From Warehouse")->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])->schema([
                Select::make("product_id")->required()->searchable()->options(Product::all()->pluck("name","id"))->live()
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                Select::make("from_warehouse_id")->required()->label("Warehouse")->searchable()->options(Warehouse::all()->pluck("name","id"))
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                TextInput::make("amount")->required()->autocomplete(false)->numeric()->default(0)
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
            ]),
            Section::make("Move Until Warehouse")->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])->schema([
                Select::make("until_warehouse_id")->required()->label("Warehouse")->searchable()->options(Warehouse::all()->pluck("name","id"))
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 4,"xl" => 4,"xl2" => 4]),
                ]),
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user=Auth::user();
        $data["user_id"] = $user->id;
        $this->dataForm=$data;
        return $data;
    }
    // todo: antes de crear el registro tengo que validar lo siguiente
    // validar que el producto que se quiere mover si este en el warehouse
    // validar que el warehouse selecionado si tenga el stock suficiente que se quiere mover

    protected function beforeCreate()
    {
        $data=$this->dataForm;
        $product=Product::whereHas("inventoryWarehouses", function($query) use ($data){
            $query->where("warehouse_id","=", $data["from_warehouse_id"]);
        })->where("id","=",$data["product_id"])->first();
        if(!$product){
            Notification::make()
            ->danger()
            ->title('Not Find in warehouse')
            ->body('the product '.$product->name.' not find in warehouse')
            ->send();
            $this->halt();
        }
        $inventoryWarehouses=InventoryWarehouse::where("warehouse_id","=",$data["from_warehouse_id"])->where("product_id","=",$data["product_id"])->first();
        $validateStockInNegative=(int)$inventoryWarehouses->stock-(int)$data["amount"];
        if($validateStockInNegative<0){
            Notification::make()
            ->danger()
            ->title('Not enough stock')
            ->body('the warehouse '.$inventoryWarehouses->name.' not have enough stock')
            ->send();
            $this->halt();
        }



    }

    protected function afterCreate()
    {
        $record=$this->getRecord();
        $fromWarehouse=InventoryWarehouse::where("warehouse_id","=",$record->from_warehouse_id)->where("product_id","=",$record->product_id)->first();
        $untilWarehouse=InventoryWarehouse::where("warehouse_id","=",$record->until_warehouse_id)->where("product_id","=",$record->product_id)->first();
        $fromWarehouse->stock=(int)$fromWarehouse->stock-(int)$record->amount;
        $fromWarehouse->save();
        if($record->from_warehouse_id==$record->until_warehouse_id){
            $fromWarehouse->stock=(int)$fromWarehouse->stock+(int)$record->amount;
            $fromWarehouse->save();
        }
        else{
            if($untilWarehouse){
                $untilWarehouse->stock=(int)$untilWarehouse->stock+(int)$record->amount;
                $untilWarehouse->save();
            }else{
                $untilWarehouse=new InventoryWarehouse();
                $untilWarehouse->warehouse_id=$record->until_warehouse_id;
                $untilWarehouse->product_id=$record->product_id;
                $untilWarehouse->stock=$record->amount;
                $untilWarehouse->save();
            }
        }



        if($record->from_warehouse_id!=$record->until_warehouse_id){
            if($fromWarehouse->stock==0){
                $fromWarehouse->delete();
            }
        }


    }

     protected function getSavedNotification(){
        return Notification::make()
            ->success()
            ->title('It was moved success')
            ->body('The product was moved success.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }


}
