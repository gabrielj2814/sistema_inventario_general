<?php

namespace App\Filament\Resources\MoveProductsResource\Pages;

use App\Filament\Resources\MoveProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMoveProducts extends EditRecord
{
    protected static string $resource = MoveProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
