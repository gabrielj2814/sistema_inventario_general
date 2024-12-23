<?php

namespace App\Filament\Resources\MoveProductsResource\Pages;

use App\Filament\Resources\MoveProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMoveProducts extends ListRecords
{
    protected static string $resource = MoveProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Move Product"),
        ];
    }
}
