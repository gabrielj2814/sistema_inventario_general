<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Customer;
use App\Models\InventoryMovement;
use App\Models\InventoryWarehouse;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Warehouse;
use DateTime;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    private $dataForm;
    private $statuValidateStockWarehouseProduct;

    function form(Form $form):Form {
        return $form->schema([
            TextInput::make("total_order_hidden")->hidden()->default(0),
            TextInput::make("statu_Validate_Stock_Warehouse_Product")->hidden(),
            TextInput::make("total_order")->label("Total")->numeric()->default(0)->readOnly()
            ->columnSpan(["sm" => 4,"md" => 4,"lg" => 2,"xl" => 2,"xl2" => 2]),
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
            Repeater::make("products")
            ->columns([
                "sm" => 12,
                "md" => 12,
                "lg" => 12,
                "xl" => 12,
                "xl2" => 12,
            ])
            ->schema([
                Select::make("product_id")->label("Product")->required()->searchable()->options(Product::all()->pluck("name","id"))
                ->live()
                ->afterStateUpdated(function($get,$set,$old,$state){
                    $product=Product::find($state);
                    $set("product_price",$product->price_unit);
                    $set("product_name",$product->name);
                })
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 6,"xl" => 6,"xl2" => 6]),
                Select::make("warehouse")->label("Warehouse")->required()->searchable()->options(Warehouse::all()->pluck("name","id"))
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 3,"xl" => 3,"xl2" => 3]),
                TextInput::make("product_price")->label("Price")->readOnly()->hidden(fn (Get $get) => $get("product_id")=="" )
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 2,"xl" => 2,"xl2" => 2]),
                TextInput::make("product_amount")->label("amount")->numeric()->hidden(fn (Get $get) => ($get("warehouse")!="" && $get("product_id")!="")?false:true)
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 1,"xl" => 1,"xl2" => 1]),
                TextInput::make("product_name")->hidden()->disabled(),
            ])->itemLabel(fn (array $state): ?string => $state['product_name'] ?? null)
            ->live()
            ->afterStateUpdated(function ($get,$set,$old,$state) {
                $data=[];
                foreach ($state as $key => $value) {
                    $data[]=$state[$key];
                }
                $this->validateStockProductAtWarehouse($data);
                $this->calculateTotal($state,$set);
            })
            ->columnSpan(["sm" => 12,"md" => 12,"lg" => 12,"xl" => 12,"xl2" => 12]),





        ])->columns([
            "sm" => 12,
            "md" => 12,
            "lg" => 12,
            "xl" => 12,
            "xl2" => 12,
        ]);
    }

    function validateStockProductAtWarehouse($data){
        $this->statuValidateStockWarehouseProduct=true;
        Log::info( $data);
        $products=[];
        $totalSockOfProduct=[];
        // total amount product
        foreach($data as $key => $value1){
            if($data[$key]["product_id"]!=null && $data[$key]["warehouse"]!=null){
                if(!array_key_exists($data[$key]["product_id"],$totalSockOfProduct)){
                    $totalSockOfProduct[$data[$key]["product_id"]]=[
                        "amount" =>0,
                        "product" =>Product::find($data[$key]["product_id"]),
                        "warehouse" =>Warehouse::find($data[$key]["warehouse"]),
                    ];
                    $products[$data[$key]["product_id"]]=Product::whereHas("inventoryWarehouses", function($query) use ($data,$key){
                        $query->where("warehouse_id","=", $data[$key]["warehouse"]);
                    })->where("id","=",$data[$key]["product_id"])->first();
                }
                $totalSockOfProduct[$data[$key]["product_id"]]["amount"]=$totalSockOfProduct[$data[$key]["product_id"]]["amount"]+(int)$data[$key]["product_amount"];

            }
        }

        foreach($totalSockOfProduct as $key2 => $valu2){
            if($totalSockOfProduct[$key2]["warehouse"] && $totalSockOfProduct[$key2]["product"]){
                if($products[$key2]){
                    if((int)$products[$key2]->inventoryWarehouses[0]->stock<(int)$totalSockOfProduct[$key2]["amount"]){
                        $products[$key2]->inventoryWarehouses[0]->warehouse;
                        Notification::make()
                        ->danger()
                        ->title('Stock insuficiente')
                        ->body('No hay stock suficientes en el warehouse '.$totalSockOfProduct[$key2]["warehouse"]->name.' del producto '.$products[$key2]->name)
                        ->send();
                        $this->statuValidateStockWarehouseProduct=false;
                        break;
                    }
                }
                else{
                    Notification::make()
                    ->danger()
                    ->title('Stock insuficiente')
                    ->body('No hay existencia del siguiente producto '.$totalSockOfProduct[$key2]["product"]->name." en el warehouse ".$totalSockOfProduct[$key2]["warehouse"]->name)
                    ->send();
                    $this->statuValidateStockWarehouseProduct=false;
                    break;

                }
            }


        }
        Log::info( $this->statuValidateStockWarehouseProduct);
    }

    private function calculateTotal(array $productos, callable $set): void
    {

        // dd($productos);
        $total = collect($productos)->reduce(function ($carry, $producto) {
            if($producto['product_price']!=null && $producto['product_amount']!=null){
                $precio = $producto['product_price'] ?? 0;
                $cantidad = $producto['product_amount'] ?? 0;

                return $carry + ($precio * $cantidad);
            }
            return $carry+0;
        }, 0);

        $set('total_order_hidden', $total); // Actualiza el campo 'total' en el formulario
        $set('total_order', $total); // Actualiza el campo 'total' en el formulario
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user=Auth::user();
        $today=new DateTime("now");
        $data["dateOrder"]=$today->format("Y-m-d");
        $data["total"]=$data["total_order"];
        $data["statu"]="completado";
        $data["user_id"]=$user->id;
        $this->validateStockProductAtWarehouse($data["products"]);
        $this->dataForm=$data;
        Log::info($this->statuValidateStockWarehouseProduct);
        return $data;
    }

    protected function beforeCreate(){
        Log::info( $this->statuValidateStockWarehouseProduct);
        if($this->dataForm["total"]<=0){
            Notification::make()
            ->warning()
            ->title('Error to Registered Order')
            ->body('no puede registrar una orden vacia.')
            ->send();
            $this->halt();
        }
        if($this->statuValidateStockWarehouseProduct==false){
            Notification::make()
            ->warning()
            ->title('Error to Registered Order')
            ->body('No hay stock suficiente para completar la orden')
            ->send();
            $this->halt();
        }
    }



    protected function handleRecordCreation(array $data):Model{
        $order=static::getModel()::create($data);
        $copieProducts=$data["products"];
        $data["products"]=$this->joinProducts($data["products"]);
        for ($index=0; $index < count($data["products"]); $index++) {
            # code...
            $orderDetail=new OrderDetail();
            $orderDetail->order_id=$order->id;
            $orderDetail->product_id=$data["products"][$index]["product_id"];
            $orderDetail->amount=$data["products"][$index]["product_amount"];
            $orderDetail->price_unit=$data["products"][$index]["product_price"];
            $orderDetail->subTotal=(float)$data["products"][$index]["product_price"]*(int)$data["products"][$index]["product_amount"];
            $orderDetail->save();
        }
        for ($index=0; $index < count($copieProducts); $index++) {
            # code...
            $today= new DateTime("now");
            $outInventoryMovement=new InventoryMovement();
            $outInventoryMovement->date_movement=$today->format("Y-m-d");
            $outInventoryMovement->type="salida";
            $outInventoryMovement->amount=$copieProducts[$index]["product_amount"];
            $outInventoryMovement->note="venta";
            $outInventoryMovement->order_id=$order->id;
            $outInventoryMovement->warehouse_id=$copieProducts[$index]["warehouse"];
            $outInventoryMovement->user_id=$data["user_id"];
            $outInventoryMovement->product_id=$copieProducts[$index]["product_id"];
            $outInventoryMovement->save();
            $updateInventoryWarehouse=InventoryWarehouse::where("warehouse_id","=",$copieProducts[$index]["warehouse"])
            ->where("product_id","=",$copieProducts[$index]["product_id"])
            ->first();
            $updateInventoryWarehouse->stock=(int)$updateInventoryWarehouse->stock-(int)$outInventoryMovement->amount;
            $updateInventoryWarehouse->save();
            if($updateInventoryWarehouse->stock==0){
                $updateInventoryWarehouse->delete();
            }

        }

        return $order;
    }

    function joinProducts($products){
        $newProdcuts=[];
        $productosUnidos=[];
        for ($index=0; $index < count($products); $index++) {
            # code...
            if(!array_key_exists($products[$index]["product_id"],$productosUnidos)){
                $productosUnidos[$products[$index]["product_id"]]=$products[$index];
                continue;
            }
            $productosUnidos[$products[$index]["product_id"]]["product_amount"]=(int)$productosUnidos[$products[$index]["product_id"]]["product_amount"]+(int)$products[$index]["product_amount"];
        }

        foreach ($productosUnidos as $key => $value) {
            $newProdcuts[]=$productosUnidos[$key];
        }

        return $newProdcuts;
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
