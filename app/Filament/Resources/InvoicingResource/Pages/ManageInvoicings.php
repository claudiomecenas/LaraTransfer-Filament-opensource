<?php

namespace App\Filament\Resources\InvoicingResource\Pages;

use App\Filament\Resources\InvoicingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInvoicings extends ManageRecords
{
    protected static string $resource = InvoicingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
