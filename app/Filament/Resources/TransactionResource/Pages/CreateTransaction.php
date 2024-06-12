<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected array $sparePartsData = [];
    protected array $servicesData = [];
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['spare_parts'])) {
            $this->sparePartsData = $data['spare_parts'];
            unset($data['spare_parts']);
        }

        if (isset($data['services'])) {
            $this->servicesData = $data['services'];
            unset($data['services']);
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->sparePartsData as $sparePartData) {
            $this->record->spareParts()->create($sparePartData);
        }

        foreach ($this->servicesData as $serviceData) {
            $this->record->services()->create($serviceData);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
