<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsetResource\Pages;
use App\Models\Aset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\AsetResource\RelationManagers\PeminjamanRelationManager;


class AsetResource extends Resource
{
    protected static ?string $model = Aset::class;

    protected static ?string $navigationIcon = 'heroicon-s-archive-box';
    protected static ?string $navigationLabel = 'Aset';
    protected static ?string $pluralModelLabel = 'Daftar Aset';
    protected static ?string $modelLabel = 'Aset';
    protected static ?string $navigationGroup = 'Manajemen Aset';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode')
                ->label('Kode Aset')
                ->disabled()
                ->hidden(fn ($operation) => $operation !== 'create')
                ->dehydrated()
                ->default(function () {
                    $last = Aset::latest('id')->first();
                    $next = $last ? $last->id + 1 : 1;
                    return 'A' . str_pad($next, 3, '0', STR_PAD_LEFT);
                }),
            TextInput::make('nama')
                ->required()
                ->label('Nama Aset'),

            Select::make('kategori_id')
                ->label('Kategori')
                ->relationship('kategori', 'nama')
                ->required()
                ->preload(),

            Select::make('lokasi_id')
                ->label('Lokasi')
                ->relationship('lokasi', 'ruangan')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->ruangan} - {$record->gedung}")
                ->required()
                ->preload(),

            Select::make('vendor_id')
                ->label('Vendor')
                ->relationship('vendor', 'nama')
                ->preload(),

            DatePicker::make('tanggal_perolehan')
                ->label('Tanggal Perolehan'),

            TextInput::make('nilai_perolehan')
                ->numeric()
                ->label('Nilai Perolehan(Harga)')
                ->required(),

            Select::make('status')
                ->options([
                    'tersedia' => 'Tersedia',
                    'rusak' => 'Rusak',
                    'rusak ringan' => 'Rusak Ringan',
                    'maintenance' => 'Maintenance',
                    'hilang' => 'Hilang',
                ])
                ->default('tersedia')
                ->label('Status'),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->placeholder('Keterangan tambahan kondisi aset'),

            FileUpload::make('foto')
                ->image()
                ->imageEditor()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                ->label('Foto')
                ->directory('aset')
                ->disk('public')
                ->visibility('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->size(80)
                    ->disk('public')
                    ->action(
                    Tables\Actions\Action::make('viewImage')
                        ->modalContent(function ($record) { // Correct way to get the URL
                            $url = $record->foto ? Storage::url($record->foto) : null;
                            
                            return view('filament.components.image-modal', [
                                'imageUrl' => $url,
                                'filename' => $record->foto ? basename($record->foto) : null
                            ]);
                        })
                        ->modalHeading('Preview Gambar')
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                ),
                TextColumn::make('kode')->sortable()->searchable(),
                TextColumn::make('nama')->sortable()->searchable()->wrap(),
                TextColumn::make('kategori.nama')->label('Kategori'),
                TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->wrap()
                    ->formatStateUsing(function ($state) {
                        return $state->ruangan . ' - ' . $state->gedung;
                    }),
                TextColumn::make('vendor.nama')->label('Vendor')->default('-'),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => ['tersedia'],
                        'warning' => ['dipinjam', 'maintenance', 'rusak ringan'],
                        'danger' => ['rusak', 'hilang'],
                    ])
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                        'rusak' => 'Rusak',
                        'rusak ringan' => 'Rusak Ringan',
                        'hilang' => 'Hilang',
                        'maintenance' => 'Maintenance',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn (): bool => Auth::user()->hasAnyRole('Admin', 'Petugas')),
                Tables\Actions\DeleteAction::make()->visible(fn (): bool => Auth::user()->hasAnyRole('Admin', 'Petugas')),
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
            PeminjamanRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsets::route('/'),
            'create' => Pages\CreateAset::route('/create'),
            'edit' => Pages\EditAset::route('/{record}/edit'),
        ];
        // Hanya tambahkan route edit jika user adalah admin
    // if (Auth::user()?->hasRole('admin')) {
    //     $pages['edit'] = Pages\EditAset::route('/{record}/edit');
    // }

    // return $pages;
    }
}
