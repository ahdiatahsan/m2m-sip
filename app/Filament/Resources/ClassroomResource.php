<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
use App\Filament\Resources\ClassroomResource\RelationManagers;
use App\Models\Classroom;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $navigationGroup = 'Penjadwalan';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('level')
                        ->label('Tingkat')
                        ->required()
                        ->native(false)
                        ->options([
                            'x' => 'X',
                            'xi' => 'XI',
                            'xii' => 'XII',
                            'percepatan' => 'Percepatan'
                        ]),
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->autocomplete(false),
                ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->groups([
            //     Group::make('level')
            //         ->getTitleFromRecordUsing(fn (Classroom $record): string => 'Kelas ' . $record->level)
            //         ->titlePrefixedWithLabel(false)
            //         ->collapsible()
            // ])
            // ->groupingSettingsHidden()
            // ->defaultGroup('level')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kelas')
                    ->searchable(),
            ])
            ->defaultSort('level', 'asc')
            ->filters([
                SelectFilter::make('level')
                    ->label('Kelas')
                    ->multiple()
                    ->options([
                        'x' => 'X',
                        'xi' => 'XI',
                        'xii' => 'XII',
                        'percepatan' => 'Percepatan'
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListClassrooms::route('/'),
            'create' => Pages\CreateClassroom::route('/create'),
            'edit' => Pages\EditClassroom::route('/{record}/edit'),
        ];
    }
}
