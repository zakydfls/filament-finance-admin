<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected array $sparePartsData = [];
    protected array $servicesData = [];

    protected function mutateFormDataBeforeSave(array $data): array
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


    protected function afterSave(): void
    {
        // Handle Spare Parts
//        $sparePartsIds = array_column($this->sparePartsData, 'id');
//        $this->record->spareParts()->whereNotIn('id', $sparePartsIds)->delete();

        foreach ($this->sparePartsData as $sparePartData) {
            if (isset($sparePartData['id'])) {
                $this->record->spareParts()->where('id', $sparePartData['id'])->update($sparePartData);
            } else {
                $this->record->spareParts()->create($sparePartData);
            }
        }

        // Handle Services
//        $servicesIds = array_column($this->servicesData, 'id');
//        $this->record->services()->whereNotIn('id', $servicesIds)->delete();

        foreach ($this->servicesData as $serviceData) {
            if (isset($serviceData['id'])) {
                $this->record->services()->where('id', $serviceData['id'])->update($serviceData);
            } else {
                $this->record->services()->create($serviceData);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
