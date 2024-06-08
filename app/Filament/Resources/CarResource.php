<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use Filament\Forms\Components\FileUpload;
use App\Models\Car;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationGroup = 'Logística';

    protected static ?string $modelLabel = 'Veículo';
    protected static ?string $pluralModelLabel = 'Veículos';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)->schema([
                    Forms\Components\FileUpload::make('photo')
                        ->label('Foto do veículo')
                        ->image()
                        ->imageEditor()
                        ->maxSize(1024),
                ]),
                Forms\Components\Select::make('driver_id')
                    ->label('Motorista')
                    ->relationship('driver', 'name')
                    ->required(),                 
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->maxLength(255),
                Forms\Components\TextInput::make('brand')
                    ->label('Marca')
                    ->datalist([
                        'Alfa Romeo',
                        'Audi',
                        'BMW',
                        'BYD',
                        'Cadillac',
                        'Changan Suzuki',
                        'Chery',
                        'Chevrolet',
                        'Chrysler',
                        'Citroen',
                        'Dacia',
                        'Dodge',
                        'Ferrari',
                        'Fiat',
                        'Ford',
                        'GMC',
                        'Hawtai',
                        'Holden',
                        'Honda',
                        'Hummer',
                        'Hyundai',
                        'JAC Motors	',
                        'Jaguar',
                        'Jeep',
                        'Keyton',
                        'Kia',
                        'Lamborghini',
                        'Lancia',
                        'Land Rover',
                        'Landwind',
                        'Lexus',
                        'Lifan',
                        'Maruti Suzuki',
                        'Mercedes-Benz',
                        'Mitsubishi',
                        'Mazda',
                        'Nissan',
                        'Peugeot',
                        'Porsche',
                        'Renault',
                        'Rolls-Royce',
                        'Shineray',
                        'SsangYong',
                        'Subaru',
                        'Suzuki',
                        'Toyota',
                        'Volkswagen',
                        'Volvo',
                        'Yamaha',
                        'Zotye',
                        'Lexus',
                        'Outros',
                    ])
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->label('Modelo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('plate')
                    ->label('Placa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('color')
                    ->label('Cor')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('year')
                    ->label('Ano')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pax')
                    ->label('Capacidade')
                    ->numeric()
                    ->required()
                    ->default('4')
                    ->maxValue('48')
                    ->minValue('4'),
                Forms\Components\Toggle::make('armoured_car')
                    ->label('Blindado')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->paginated([25,50, 100, 'all'])
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->square(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Ativo'),
                    //->boolean(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Apelido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Motorista'),
                Tables\Columns\TextColumn::make('model')
                    ->label('Modelo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plate')
                    ->label('Placa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('Cor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pax')
                    ->label('Pax')
                    ->searchable(),
                Tables\Columns\IconColumn::make('armoured_car')
                    ->label('Blindado')
                    ->boolean(),
                /*
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                */
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('')),
                Tables\Actions\EditAction::make()
                    ->label(__('')),
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
