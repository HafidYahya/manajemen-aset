<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MutasiAsetResource\Pages;
use App\Filament\Resources\MutasiAsetResource\RelationManagers;
use App\Models\Aset;
use App\Models\MutasiAset;
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

class MutasiAsetResource extends Resource
{
    public static function canAccess(): bool
    {
        return Auth::user()->hasAnyRole(['Admin', 'Petugas']);
    }
    protected static ?string $model = MutasiAset::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-path';
    protected static ?string $navigationGroup = 'Manajemen Aset';
    protected static ?string $label = 'Mutasi Aset';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('aset_id')
                ->label('Aset')
                ->relationship('aset', 'nama',
                    modifyQueryUsing: fn (Builder $query) => $query->whereNotIn('status', ['dipinjam', 'maintenance', 'hilang']))
                ->searchable()
                ->preload()
                ->getOptionLabelFromRecordUsing(fn (Aset $record) => "{$record->nama} | {$record->kode}")
                ->required()
                ->live() // Tambahkan ini untuk trigger perubahan
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    // Ambil data aset beserta lokasinya
                    $aset = \App\Models\Aset::find($state);
                    if ($aset && $aset->lokasi_id) {
                        $set('dari_lokasi_id', $aset->lokasi_id);
                    }
                }),

                Select::make('dari_lokasi_id')
                    ->label('Dari Lokasi')
                    ->relationship('dariLokasi', 'ruangan')
                    ->disabled() // diisi otomatis dari aset
                    ->dehydrated(),

                Select::make('ke_lokasi_id')
                    ->label('Ke Lokasi')
                    ->relationship('keLokasi', 'ruangan')
                    ->required()
                    ->preload(),

                DatePicker::make('tanggal_mutasi')->required()->label('Tanggal Mutasi'),

                Textarea::make('keterangan')->label('Keterangan')->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aset.kode')->label('Kode'),
                Tables\Columns\TextColumn::make('aset.nama')->label('Aset'),
                Tables\Columns\TextColumn::make('dariLokasi.ruangan')->label('Dari Lokasi'),
                Tables\Columns\TextColumn::make('keLokasi.ruangan')->label('Ke Lokasi'),
                Tables\Columns\TextColumn::make('tanggal_mutasi')->date(),
                Tables\Columns\TextColumn::make('user.name')->label('Dipindahkan Oleh'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make()->visible(fn () => Auth::user()->hasAnyRole(['Admin', 'Petugas'])),
                Tables\Actions\DeleteAction::make()->visible(fn () => Auth::user()->hasAnyRole(['Admin'])),
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
            'index' => Pages\ListMutasiAsets::route('/'),
            'create' => Pages\CreateMutasiAset::route('/create'),
            // 'edit' => Pages\EditMutasiAset::route('/{record}/edit'),
        ];
    }
}
