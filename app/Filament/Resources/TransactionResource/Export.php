<?php

namespace App\Filament\Resources\TransactionResource;

use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionExport implements FromQuery, WithHeadings, ShouldQueue
{
    use Exportable;

    public function query()
    {
        return Transaction::query()
            ->with(['spareParts', 'services'])
            ->select([
                'transactions.id',
                'transactions.customer_name',
                'transactions.customer_phone',
                'transactions.customer_car',
                'transactions.customer_car_number',
                'transactions.category_id',
                'transactions.date',
                'transactions.note',
                'transactions.image',
                'transactions.created_at',
                'transactions.updated_at',
            ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Customer Name',
            'Customer Phone',
            'Customer Car',
            'Customer Car Number',
            'Category ID',
            'Date',
            'Note',
            'Image',
            'Created At',
            'Updated At',
        ];
    }
}