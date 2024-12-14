<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\Product;
use DateTime;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    private $dataForm;

    function form(Form $form):Form {
        return $form->schema([
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
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 8,"xl" => 8,"xl2" => 8]),
                TextInput::make("product_amount")->label("amount")->numeric()
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 2,"xl" => 2,"xl2" => 2]),
                TextInput::make("product_price")->label("Price")->readOnly()
                ->columnSpan(["sm" => 12,"md" => 12,"lg" => 2,"xl" => 2,"xl2" => 2]),
                TextInput::make("product_name")->hidden()->disabled(),
            ])->itemLabel(fn (array $state): ?string => $state['product_name'] ?? null)
            ->live()
            ->afterStateUpdated(fn ($get,$set,$old,$state) => self::calcularTotal($state,$set))
            ->columnSpan(["sm" => 12,"md" => 12,"lg" => 12,"xl" => 12,"xl2" => 12]),





        ])->columns([
            "sm" => 12,
            "md" => 12,
            "lg" => 12,
            "xl" => 12,
            "xl2" => 12,
        ]);
    }

    protected static function calcularTotal(array $productos, callable $set): void
    {

        // dd($productos);
        $total = collect($productos)->reduce(function ($carry, $producto) {
            $precio = $producto['product_price'] ?? 0;
            $cantidad = $producto['product_amount'] ?? 0;

            return $carry + ($precio * $cantidad);
        }, 0);

        $set('total_order', $total); // Actualiza el campo 'total' en el formulario
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user=Auth::user();
        $today=new DateTime("now");
        $data["dateOrder"]=$today->format("Y-m-d");
        $data["total"]=$data["total_order"];
        $data["statu"]="pendiente";
        $data["user_id"]=$user->id;
        $this->dataForm=$data;
        return $data;
    }

    protected function beforeCreate(){
        if($this->dataForm["total"]<=0){
            Notification::make()
            ->warning()
            ->title('Error to Registered Order')
            ->body('no puede registrar una orden vacia.')
            ->send();
            $this->halt();
        }

    }

    protected function handleRecordCreation(array $data):Model{
        $order=static::getModel()::create($data);
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
        return $order;
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
