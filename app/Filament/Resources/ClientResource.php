<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Leandrocfe\FilamentPtbrFormFields\Document;


class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationGroup = 'Logística';

    protected static ?string $modelLabel = 'Cliente';
    protected static ?string $pluralModelLabel = 'Clientes';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([           
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->required(),
                        /*
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('Whatsapp')
                            ->placeholder('(99) 99999-9999')
                            ->mask('(99) 99999-9999')
                            ->maxLength(15)
                            ->required(),
                        */
                        PhoneNumber::make('phone_number')
                            ->label('Telefone')
                            ->placeholder('(99)99999-9999')
                            ->format('(99)99999-9999')
                            ->maxLength(14)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->maxLength(255),
                        /*
                        Document::make('cpf_cnpj')
                            ->label('CPF ou CNPJ')
                            ->validation(false)
                            ->dynamic(),
                        */
                        
                        Forms\Components\TextInput::make('cpf_cnpj')
                            ->label('CPF')
                            ->placeholder('999.999.999-99')
                            ->mask('999.999.999-99')
                            ->minLength(11)
                            ->maxLength(14),
                        
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Data de Nascimento'),
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
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('city')
                            ->label('Cidade')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\Select::make('state')
                            ->label('UF')
                            ->options([
                                'AC' => 'Acre',
                                'AL' => 'Alagoas',
                                'AP' => 'Amapá',
                                'AM' => 'Amazonas',
                                'BA' => 'Bahia',
                                'CE' => 'Ceará',
                                'DF' => 'Distrito Federal',
                                'ES' => 'Espírito Santo',
                                'GO' => 'Goiás',
                                'MA' => 'Maranhão',
                                'MT' => 'Mato Grosso',
                                'MS' => 'Mato Grosso do Sul',
                                'MG' => 'Minas Gerais',
                                'PA' => 'Pará',
                                'PB' => 'Paraíba',
                                'PR' => 'Paraná',
                                'PE' => 'Pernambuco',
                                'PI' => 'Piauí',
                                'RJ' => 'Rio de Janeiro',
                                'RN' => 'Rio Grande do Norte',
                                'RS' => 'Rio Grande do Sul',
                                'RO' => 'Rondônia',
                                'RR' => 'Roraima',
                                'SC' => 'Santa Catarina',
                                'SP' => 'São Paulo',
                                'SE' => 'Sergipe',
                                'TO' => 'Tocantins',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('country')
                            ->label('Pais')
                            ->maxLength(255)
                            ->default('Brasil')
                            ->required(),
                    ])->columns(3),
               
                Forms\Components\RichEditor::make('obs')
                    ->label('Observação')
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\Toggle::make('status')
                    ->default(true)
                    ->label('Ativo')
                    ->columnSpan(3)
                    ->required(), 
                
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->paginated([25,50, 100, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telefone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Cidade')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label('Estado')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country')
                    ->label('País')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Genero')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')
                    ->label('Ativo')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),         
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //create filter by status
                Tables\Filters\SelectFilter::make('status')
                    ->label('Ativo')
                    ->options([
                        '' => 'Todos',
                        '1' => 'Ativo',
                        '0' => 'Inativo',
                    ])
                    ->default('1'),
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Gênero')
                    ->options([
                        '' => 'Todos',
                        'Masculino' => 'Masculino',
                        'Feminino' => 'Feminino',
                        'Outro' => 'Outro',
                    ])
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
