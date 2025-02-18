<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
use App\Filament\Resources\ClassroomResource\RelationManagers\TimetablesRelationManager;
use App\Models\Classroom;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
                            'percepatan' => 'Percepatan',
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
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kelas')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('level')
                    ->label('Kelas')
                    ->options([
                        'x' => 'X',
                        'xi' => 'XI',
                        'xii' => 'XII',
                        'percepatan' => 'Percepatan',
                    ])
                    ->default('x')
                    ->selectablePlaceholder(false),
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
            TimetablesRelationManager::class,
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
