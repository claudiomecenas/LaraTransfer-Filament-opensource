<?php

namespace App\Filament\Resources\HallResource\Pages;

use App\Filament\Resources\HallResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageHalls extends ManageRecords
{
    protected static string $resource = HallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
