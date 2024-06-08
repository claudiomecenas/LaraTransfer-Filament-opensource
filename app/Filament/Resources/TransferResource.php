<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferResource\Pages;
//use App\Filament\Resources\TransferResource\RelationManagers;
use App\Models\Transfer;
//use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\Money;


/*
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\RawJs;
use PharIo\Version\NoPreReleaseSuffixException;
*/


class TransferResource extends Resource
{
    protected static ?string $model = Transfer::class;

    protected static ?string $navigationGroup = 'Logística';

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('status')
                    ->label('Ativo')
                    ->required()
                    ->default(true),
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label('Descrição do Transfer')
                            ->required()
                            ->maxLength(255)
                            ->default(''),
                        Money::make('negotiated_value')
                            ->label('Valor negociado')
                            ->required(),

                        /*
                        Forms\Components\TextInput::make('negotiated_value')
                            ->label('Valor negociado'),
                            ->mask(RawJs::make(<<<'JS'
                                     $input.length >= 4 ? '9.999' : ''
                                JS))
                            ->live()
                        

                            ->numeric()
                            ->prefix('R$')
                            ->suffix(',00')
                            ->required(),
                        */
                        Forms\Components\Select::make('payment_type')
                            ->label('Forma de Pagamento')
                            ->options([
                                'PIX' => 'PIX',
                                'LINK' => 'LINK',
                                'MAQUININHA' => 'MAQUININHA',
                                'CORTESIA' => 'CORTESIA',
                                ])
                            ->required(),
                    ])->columns(3),
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('client_id')
                                ->label('Cliente')
                                ->relationship('client', 'name')
                                ->required(),
                        Forms\Components\Select::make('car_id')
                                ->label('Veículo')
                                ->relationship('car', 'name')
                                ->required(),     
                    ])->columns(2),
                Section::make()
                        ->schema([
                            Forms\Components\DatePicker::make('departure_date')
                                ->label('Data de Saída')
                                ->required(),
                            Forms\Components\TextInput::make('departure_time')
                                ->label('Horário de Saída')
                                ->mask('99:99')
                                ->placeholder('HH:MM')
                                ->maxLength(5)
                                ->minLength(5)
                                ->required(),
                            Forms\Components\TextInput::make('destination_address')
                                ->label('Endereço de Destino')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('origin_address')
                                ->label('Endereço de Origem')
                                ->required()
                                ->maxLength(255),        
                        ])->columns(2),    
                Forms\Components\TextInput::make('pax')
                    ->label('Número de passageiros')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(40)
                    ->default(1),
                Forms\Components\TextInput::make('bags')
                    ->label('Número de bagagens')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(8)
                    ->default(0),   
                Forms\Components\TextInput::make('stops')
                    ->label('Número de paradas')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->default(0),   
                Forms\Components\RichEditor::make('obs')
                    ->label('Observações')
                    ->columnSpanFull(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->paginated([25,50, 100, 'all'])
            ->columns([
                Tables\Columns\ToggleColumn::make('status')
                    ->label('Ativo'),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('destination_address')
                    ->label('Destino')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('negotiated_value')
                    ->label('Valor negociado')
                    ->money('BRL', true)
                    ->color('success'),
                    //->prefix('R$ '),
                Tables\Columns\TextColumn::make('car.name')
                    ->label('Veículo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departure_date')
                    ->label('Data de Saída')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('departure_time')
                    ->label('Horário de Saída')
                    ->time('H:i')
                    ->searchable(),
                Tables\Columns\TextColumn::make('origin_address')
                    ->label('Origem')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('pax')
                    ->label('Passageiros')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Forma de Pagamento')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stops')
                    ->label('Paradas')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bags')
                    ->label('Bagagens')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //create a filter the transfer status
                Tables\Filters\SelectFilter::make('status')
                    ->label('Ativo')
                    ->options([
                        '1' => 'Ativo',
                        '0' => 'Inativo',
                    ])
                    ->default('1'), 
                //create a filter the transfer for car
                Tables\Filters\SelectFilter::make('car')
                    ->label('Veículo')
                    ->relationship('car', 'name')
                    ->placeholder('Todos'),               
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransfers::route('/'),
            'create' => Pages\CreateTransfer::route('/create'),
            'edit' => Pages\EditTransfer::route('/{record}/edit'),
        ];
    }

    //get navigation baget
    public static function getNavigationBadge(): ?string
    {   
        //return only status = 1
        return static::getModel()::query()->where('status', 1)->count();
    }
}
