<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceResource\Pages;
use App\Filament\Resources\PriceResource\RelationManagers;
use App\Models\Price;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Section;

use Leandrocfe\FilamentPtbrFormFields\Money;



class PriceResource extends Resource
{
    protected static ?string $model = Price::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Preços para Eventos';

    protected static ?string $modelLabel = 'Tabela de Preço';
    protected static ?string $pluralModelLabel = 'Tabela de Preços';

    protected static ?int $navigationSort = 6;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('hall_id')
                            ->relationship('hall', 'name')
                            ->label('Espaço')
                            ->required(),
                        Forms\Components\Select::make('district_id')
                            ->relationship('district', 'name')
                            ->label('Região')
                            ->required(),
                    ])->columns(2),
                Section::make('Preços dos Lotes')
                    ->description('Informe o preço sem desconto')
                    ->schema([
                        Money::make('price1')
                            ->label('Lote 1')
                            ->default('500,00'),
                        /*
                        Forms\Components\TextInput::make('price1')
                            ->label('Lote 1')
                            ->prefix('R$ ')
                            ->suffix(',00')
                            ->numeric()
                            ->required()
                            ->default(500)
                            ->minValue(100)
                            ->maxLength(7),
                        */
                        Money::make('price2')
                            ->label('Lote 2')
                            ->default('500,00'),
                        /*
                        Forms\Components\TextInput::make('price2')
                            ->label('Lote 2')
                            ->prefix('R$ ')
                            ->suffix(',00')
                            ->numeric()
                            ->required()
                            ->default(500)
                            ->minValue(100)
                            ->maxLength(7),
                        */
                        Money::make('price3')
                            ->label('Lote 3')
                            ->default('500,00'),
                        /*
                        Forms\Components\TextInput::make('price3')
                            ->label('Lote 3')
                            ->prefix('R$ ')
                            ->suffix(',00')
                            ->numeric()
                            ->required()
                            ->default(500)
                            ->minValue(100)
                            ->maxLength(7),
                        */
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->paginated([50, 100, 'all'])
            ->groups([
                Group::make('hall.name')
                    ->label('Espaço')
                    ->collapsible(),
                Group::make('district.name')
                    ->label('Região') 
                    ->collapsible(),
            ])
            ->defaultGroup('hall.name')
            ->defaultSort('hall.name', 'district.name')
            ->columns([
                Tables\Columns\TextColumn::make('hall.name')
                    ->weight(FontWeight::Bold)
                    ->badge()
                    //->icon('heroicon-o-home')
                    ->searchable()
                    ->label('Espaço')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->label('Região')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price1')
                    ->money('BRL', true)
                    //->description(fn(Price $record) => $record->price1*0.9)
                    ->description(fn(Price $record) => 'R$ ' . number_format($record->price1 * 0.9, 2, ',', '.'))
                    ->color('success')
                    //->prefix('R$ ')
                    //->suffix(',00')
                    ->label('Lote 1')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('price2')
                    ->money('BRL', true)
                    ->description(fn(Price $record) => 'R$ ' . number_format($record->price2 * 0.9, 2, ',', '.'))
                    ->weight(FontWeight::SemiBold)
                    ->color('success')
                    //->prefix('R$ ')
                    //->suffix(',00')
                    ->label('Lote 2')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('price3')
                    ->money('BRL', true)
                    ->description(fn(Price $record) => 'R$ ' . number_format($record->price3 * 0.9, 2, ',', '.'))
                    ->weight(FontWeight::Bold)
                    ->color('success')
                    //->prefix('R$ ')
                    //->suffix(',00')
                    ->label('Lote 3')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('hall')
                    ->relationship('hall', 'name')
                    ->label('Espaço'),
                Tables\Filters\SelectFilter::make('district')
                    ->relationship('district', 'name')
                    ->multiple()
                    ->label('Região'),
            ])
            ->actions([
                Tables\Actions\ReplicateAction::make()
                    ->label('')
                    ->RequiresConfirmation(),
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
            'index' => Pages\ManagePrices::route('/'),
        ];
    }
}
