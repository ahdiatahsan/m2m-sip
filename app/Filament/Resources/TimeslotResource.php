<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeslotResource\Pages;
use App\Filament\Resources\TimeslotResource\RelationManagers;
use App\Models\Timeslot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimeslotResource extends Resource
{
    protected static ?string $model = Timeslot::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Waktu';
    protected static ?string $navigationGroup = 'Manajemen Jadwal KBM';
    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Waktu';
    protected static ?string $pluralModelLabel = 'Waktu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTimeslots::route('/'),
            'create' => Pages\CreateTimeslot::route('/create'),
            'edit' => Pages\EditTimeslot::route('/{record}/edit'),
        ];
    }
}
