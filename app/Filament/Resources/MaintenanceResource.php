<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Filament\Resources\MaintenanceResource\RelationManagers;
use App\Models\Maintenance;
use App\Models\Aset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;

class MaintenanceResource extends Resource
{
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole(['Admin', 'Petugas', 'Manajer Aset']);
    }
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-s-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Manajemen Aset';
    protected static ?string $navigationLabel = 'Maintenance Aset';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('aset_id')
                    ->label('Aset')
                    ->relationship('aset', 'nama', 
                        modifyQueryUsing: fn (Builder $query) => $query->whereNotIn('status', ['dipinjam', 'maintenance', 'hilang']))
                    ->searchable()
                    ->required()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn (Aset $record) => "{$record->nama} | {$record->kode}"),

                DatePicker::make('tanggal_mulai')->label('Tanggal Mulai')->required(),
                DatePicker::make('tanggal_selesai')->label('Tanggal Selesai'),

                Select::make('status')
                    ->options([
                        'proses' => 'Dalam Proses',
                        'selesai' => 'Selesai',
                    ])
                    ->default('proses')
                    ->required(),

                Select::make('dikerjakan_oleh')
                    ->label('Penanggung Jawab')
                    ->relationship('penanggungJawab', 'name')
                    ->searchable()
                    ->preload(),

                Textarea::make('deskripsi')->label('Deskripsi Perbaikan')->rows(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aset.kode')->label('Kode'),
                Tables\Columns\TextColumn::make('aset.nama')->label('Aset'),
                Tables\Columns\TextColumn::make('tanggal_mulai')->date('l, d F Y'),
                Tables\Columns\TextColumn::make('tanggal_selesai')->date('l, d F Y')->default('-'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->colors([
                        'warning' => 'proses',
                        'success' => 'selesai',
                    ]),
                Tables\Columns\TextColumn::make('penanggungJawab.name')->label('Penanggung Jawab')->default('-'),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
        ];
    }
}
