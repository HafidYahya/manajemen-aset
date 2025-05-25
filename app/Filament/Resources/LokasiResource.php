<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LokasiResource\Pages;
use App\Filament\Resources\LokasiResource\RelationManagers;
use App\Models\Lokasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class LokasiResource extends Resource
{
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('Admin');
    }
    protected static ?string $model = Lokasi::class;

    protected static ?string $navigationIcon = 'heroicon-s-map-pin';
    
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Lokasi'; // Untuk title form/edit
    protected static ?string $pluralModelLabel = 'Daftar Lokasi'; // Untuk title tabel
    protected static ?string $navigationLabel = 'Lokasi'; // Label sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ruangan')
                    ->label('Ruangan')
                    ->required()
                    ->maxLength(100),
                TextInput::make('gedung')
                    ->label('Gedung')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->getStateUsing(fn ($record, $rowLoop) => $rowLoop->index + 1),
                TextColumn::make('ruangan')->label('Ruangan')->searchable(),
                TextColumn::make('gedung')->label('Gedung')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLokasis::route('/'),
            'create' => Pages\CreateLokasi::route('/create'),
            'edit' => Pages\EditLokasi::route('/{record}/edit'),
        ];
    }
}
