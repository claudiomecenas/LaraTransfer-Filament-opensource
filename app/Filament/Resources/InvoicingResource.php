<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoicingResource\Pages;
use App\Filament\Resources\InvoicingResource\RelationManagers;
use App\Models\Invoicing;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Filament\Forms\Components\Section;


class InvoicingResource extends Resource
{
    protected static ?string $model = Invoicing::class;

    protected static ?string $navigationGroup = 'Financeiro';

    protected static ?string $modelLabel = 'Fatura';
    protected static ?string $pluralModelLabel = 'Faturas';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transfer_id')
                    ->required()
                    ->relationship('transfer', 'description'),
                Forms\Components\Select::make('type')
                    ->label('Entrada ou Saída?')
                    ->options([
                        'in' => 'Entrada',
                        'out' => 'Saida',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'paid' => 'Paga',
                        'unpaid' => 'Pendente',
                    ])
                    ->required(),
                 Section::make()
                    ->schema([
                        Money::make('amount')
                            ->label('Valor da Fatura')
                            ->required(),
                        Forms\Components\DatePicker::make('date_invoiced')
                            ->label('Data da Fatura')
                            ->required()
                    ])->columns(2),
                /*
                Forms\Components\TextInput::make('amount')
                    ->label('Valor da Fatura')
                    ->required()
                    ->prefix('R$')
                    ->suffix(',00')
                    ->minValue(0)
                    ->default(0),
                */
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->paginated([25,50, 100, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('transfer.description')
                    ->label('Transfer')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('type')
                    ->label('Tipo')
                    ->size(IconColumn\IconColumnSize::Medium)
                    ->icon(fn (string $state): string => match ($state) {
                        'in' => 'heroicon-o-arrow-up-circle',
                        'out' => 'heroicon-o-arrow-down-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor da Fatura')
                    ->color(fn (Invoicing $record) => $record->type === 'in' ? 'success' : 'danger')
                    ->money('BRL', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_invoiced')
                    ->label('Data da Fatura')
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->size(IconColumn\IconColumnSize::Medium)
                    ->icon(fn (string $state): string => match ($state) {
                        'paid' => 'heroicon-o-check-circle',
                        'unpaid' => 'heroicon-o-x-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Fatura criada em')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Fatura atualizada em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //create filter by type
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo de Fatura')
                    ->options([
                        'in' => 'Entrada',
                        'out' => 'Saida',
                    ]),
                //create filter by status            
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'paid' => 'Paga',
                        'unpaid' => 'Pendente',
                    ])       
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(''),
                Tables\Actions\EditAction::make()
                    ->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInvoicings::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {   
        //return sum only type = in and formt to BRL
        $in = "R$ ".static::getModel()::query()->where('type', 'in')->sum('amount').',00';
        $out = "R$ ".static::getModel()::query()->where('type', 'out')->sum('amount').',00';
        //convert to number and return
        $in = intval(str_replace('R$ ', '', $in));
        $out = intval(str_replace('R$ ', '', $out));
        $saldo = $in - $out;
        //format to BRL
        $saldo = number_format($saldo, 2, ',', '.');
        return "R$ ".$saldo;
    }
}
