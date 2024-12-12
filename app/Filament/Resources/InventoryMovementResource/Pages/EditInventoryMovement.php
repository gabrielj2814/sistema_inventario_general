<?php

namespace App\Filament\Resources\InventoryMovementResource\Pages;

use App\Filament\Resources\InventoryMovementResource;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Warehouse;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Collection;

class EditInventoryMovement extends EditRecord
{
    protected static string $resource = InventoryMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
