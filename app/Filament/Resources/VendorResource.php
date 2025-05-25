<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class VendorResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('Admin');
    }
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-s-truck';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Vendor'; // Untuk title form/edit
    protected static ?string $pluralModelLabel = 'Daftar Vendor'; // Untuk title tabel
    protected static ?string $navigationLabel = 'Vendor'; // Label sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Vendor')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord:true),
                TextInput::make('alamat')
                    ->label('Alamat')
                    ->required(),
                TextInput::make('telepon')
                    ->label('Nomor Telepon')
                    ->required()
                    ->maxLength(100)
                    ->numeric(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
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
                TextColumn::make('nama')->label('Nama Vendor')->searchable(),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->wrap() // Auto wrap text
                    ->tooltip(fn ($record) => $record->alamat), // Show full address on hover
                TextColumn::make('telepon')->label('Nomor Telepon')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
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
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
