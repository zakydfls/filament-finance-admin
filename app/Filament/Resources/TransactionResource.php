<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Filament\Resources\TransactionResource\TransactionExport;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-m-calculator';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_car')
                    ->maxLength(255),
                Forms\Components\TextInput::make('customer_car_number')
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('note')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('transactions'),
                Repeater::make('spare_part')
                    ->label('Spare Part')
                    ->relationship('spareParts')
                    ->schema([
                        Forms\Components\Hidden::make('id'),
                        Forms\Components\TextInput::make('spare_part')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('add_amount')
                            ->required()
                            ->numeric(),
                    ])
                    ->createItemButtonLabel('Add Spare Part')
                    ->columns(3),
                Repeater::make('services')
                    ->label('Service')
                    ->relationship('services')
                    ->schema([
                        Forms\Components\Hidden::make('id'),
                        Forms\Components\TextInput::make('service')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('add_amount')
                            ->required()
                            ->numeric(),
                    ])
                    ->createItemButtonLabel('Add Service')
                    ->columns(3)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_car')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_car_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
//                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
//                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download Image')
                    ->icon('heroicon-o-arrow-down')
                    ->action(function (Transaction $record) {
                        $filePath = storage_path("app/public/{$record->image}");
                        $filePath = str_replace('\\', '/', $filePath); // Ganti '/' dengan '\'
                        if (file_exists($filePath)) {
                            return response()->download($filePath);
                        }
//                        dd($filePath);
                        // Log error atau handle file tidak ditemukan
                        abort(404, 'File not found.');
                    })
                    ->visible(fn(Transaction $record) => !is_null($record->image)),
//                Tables\Actions\Action::make('detail')
//                    ->label('Detail')
//                    ->url(fn (Transaction $record) => "/admin/transactions/{$record->id}/detail"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(TransactionExport::class),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
//            'detail' => Pages\ViewTransactionDetail::route('/{record}/detail'),
        ];
    }
}
