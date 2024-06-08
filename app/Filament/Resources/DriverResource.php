<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverResource\Pages;
use App\Filament\Resources\DriverResource\RelationManagers;
use App\Models\Driver;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

use Leandrocfe\FilamentPtbrFormFields\Cep;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Leandrocfe\FilamentPtbrFormFields\Document;

//use Rmsramos\PostalCode\Components\PostalCode;
//https://github.com/rmsramos/postal-code
//https://www.youtube.com/watch?v=sCHsGFxWtGY


class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $navigationGroup = 'Logística';

    protected static ?string $modelLabel = 'Motorista';
    protected static ?string $pluralModelLabel = 'Motoristas';

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)->schema([
                    Forms\Components\FileUpload::make('photo')
                        ->label('Foto')
                        ->avatar(),
                ]),
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->autofocus()
                            ->required()
                            ->maxLength(255),       
                        /*               
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('Whatsapp')
                            ->required()
                            ->maxLength(15) 
                            ->mask('(99) 99999-9999')
                            ->tel()
                            ->placeholder('(99) 99999-9999'),
                        */
                        PhoneNumber::make('phone_number')
                            ->label('Telefone')
                            ->placeholder('(99)99999-9999')
                            ->format('(99)99999-9999')
                            ->maxLength(14)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),  
                        
                        Forms\Components\TextInput::make('cpf')
                            ->label('CPF')
                            ->minLength(11)
                            ->maxLength(14)
                            ->mask('999.999.999-99')
                            ->placeholder('999.999.999-99')  
                            ->required(),
                        
                        /*
                        Document::make('cpf_cnpj')
                            ->label('CPF ou CNPJ')
                            ->dynamic(),
                        */
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Data de Nascimento')
                            ->required(),
                        Forms\Components\Select::make('gender')
                            ->label('Gênero')
                            ->options([
                                'masculino' => 'Masculino',
                                'feminino' => 'Feminino',
                                'nao-binario' => 'Não-Binário',
                                'outro' => 'Outro',
                                ])
                            ->required(),
                    ])->columns(3),        
            Section::make('Endereço')
                ->description('Informe o CEP para obter o endereço.')
                ->schema([

                    Cep::make('cep')
                        ->label('CEP')
                        ->required()
                        ->live(onBlur: true)
                        ->viaCep(   
                            mode: 'suffix', // Determines whether the action should be appended to (suffix) or prepended to (prefix) the cep field, or not included at all (none).
                            errorMessage: 'CEP inválido.', // Error message to display if the CEP is invalid.
                            setFields: [
                                'address' => 'logradouro',
                                'number' => 'numero',
                                'complement' => 'complemento',
                                'neighborhood' => 'bairro',
                                'city' => 'localidade',
                                'state' => 'uf'
                            ]
                        ),
                    /*
                    PostalCode::make('cep')
                        ->label('CEP')
                        ->required()   
                        ->live(onBlur: true)
                        ->viaCep(
                            errorMessage: 'CEP inválido.', // Custom message to display if the CEP is invalid.
                            setFields: [
                                'address'        => 'logradouro',
                                'number'        => 'numero',
                                'complement'    => 'complemento',
                                'neighborhood'      => 'bairro',
                                'city'          => 'localidade',
                                'state'         => 'uf'
                            ]
                    ),*/
                    Forms\Components\TextInput::make('address')
                        ->label('Rua')
                        ->readOnly(true),
                    Forms\Components\TextInput::make('number')
                        ->label('Número')
                        ->required()
                        ->extraAlpineAttributes([
                            'x-on:cep.window' => "\$el.focus()",
                        ]),
                    Forms\Components\TextInput::make('complement')
                        ->label('Complemento'),
                    Forms\Components\TextInput::make('neighborhood')
                        ->label('Bairro')
                        ->readOnly(true),
                    Forms\Components\TextInput::make('city')
                        ->label('Cidade')
                        ->readOnly(true),
                    Forms\Components\TextInput::make('state')
                        ->label('Estado')
                        ->readOnly(true),

                ])->columns(2),

                Forms\Components\RichEditor::make('obs')
                    ->label('Observação')
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\Toggle::make('status')
                        ->default(true)
                        ->label('Ativo')
                        ->columnSpan(3)
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
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telefone'),
                Tables\Columns\TextColumn::make('city')
                    ->label('Cidade')
                    ->searchable(),            
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
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }
}
