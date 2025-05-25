<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamanResource\Pages;
use App\Models\Peminjaman;
use App\Models\Aset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Notifications\PeminjamanDisetujuiNotification;
use App\Notifications\PeminjamanDitolakNotification;
class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $modelLabel = 'Peminjaman';
    protected static ?string $pluralModelLabel = 'Daftar Peminjaman';
    protected static ?string $navigationLabel = 'Peminjaman';

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        if ($user->hasRole('Karyawan')) {
            return parent::getEloquentQuery()->where('user_id', $user->id);
        }
        return parent::getEloquentQuery();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('aset_id')
                ->label('Aset')
                ->relationship('aset', 'nama')
                ->searchable()
                ->required()
                ->options(
                    Aset::where('status', 'tersedia')->pluck('nama', 'id')
                ),
            DatePicker::make('tanggal_pinjam')->required(),
            DatePicker::make('tanggal_kembali')->required(),
            Textarea::make('keterangan')->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('aset.kode')->label('Kode')->searchable(),
                TextColumn::make('aset.nama')->label('Aset')->wrap()->searchable(),
                TextColumn::make('user.name')->label('Pemohon')->searchable(),
                TextColumn::make('user.email')->label('Email')->searchable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => ['menunggu', 'disetujui'],
                        'success' => ['dipinjam'],
                        'gray' => ['dikembalikan'],
                        'danger' => ['ditolak'],
                    ]),
                TextColumn::make('tanggal_pinjam')
                    ->date('l, d F Y'),
                TextColumn::make('tanggal_kembali')
                    ->date('l, d F Y'),
                TextColumn::make('tanggal_kembali_sesuai')
                    ->label('Tgl Dikembalikan')
                    ->date('l, d F Y'),
                TextColumn::make('kondisi_kembali')->label('Kondisi Kembali')->limit(30),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'menunggu' => 'Menunggu',
                    'disetujui' => 'Disetujui',
                    'dipinjam' => 'Dipinjam',
                    'dikembalikan' => 'Dikembalikan',
                    'ditolak' => 'Ditolak',
                ]),
            ])
            ->actions([
                Action::make('Setujui')
                    ->visible(fn ($record) => $record->status === 'menunggu' && Auth::user()->hasRole(['Manajer Aset', 'Petugas']))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'disetujui',
                            'disetujui_oleh' => Auth::id(),
                            'tanggal_disetujui' => now(),
                        ]);
                        $record->user->notify(new PeminjamanDisetujuiNotification($record));
                    }),

                Action::make('Tolak')
                    ->visible(fn ($record) => $record->status === 'menunggu' && Auth::user()->hasRole(['Manajer Aset', 'Petugas']))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'ditolak',
                            'disetujui_oleh' => Auth::id(),
                            'tanggal_disetujui' => now(),
                        ]);
                        $record->user->notify(new PeminjamanDitolakNotification($record));
                    }),

                Action::make('Ambil')
                    ->label('Ambil Aset')
                    ->visible(fn ($record) => $record->status === 'disetujui' && Auth::user()->hasRole(['Manajer Aset', 'Petugas']))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'dipinjam',
                        ]);
                        $record->aset->update(['status' => 'dipinjam']);
                        Notification::make()->title('Aset telah diambil')->sendToDatabase($record->user);
                    }),

                Action::make('Kembalikan')
                    ->label('Pengembalian')
                    ->visible(fn ($record) => $record->status === 'dipinjam' && Auth::user()->hasRole(['Manajer Aset', 'Petugas']))
                    ->form([
                        DatePicker::make('tanggal_kembali_sesuai')->label('Tanggal Kembali')->required(),
                        Textarea::make('kondisi_kembali')->label('Kondisi Aset')->rows(3)->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'tanggal_kembali_sesuai' => $data['tanggal_kembali_sesuai'],
                            'kondisi_kembali' => $data['kondisi_kembali'],
                            'diterima_oleh' => Auth::id(),
                            'status' => 'dikembalikan',
                        ]);
                        $record->aset->update(['status' => 'tersedia']);
                        Notification::make()->title('Aset Telah Dikembalikan')->success()->sendToDatabase($record->user);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamen::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
        ];
    }
}
