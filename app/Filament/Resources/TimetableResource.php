<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimetableResource\Pages;
use App\Models\Timetable;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TimetableResource extends Resource
{
    protected static ?string $model = Timetable::class;
    // protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationLabel = 'Jadwal Pembelajaran';

    protected static ?string $navigationGroup = 'Penjadwalan';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Jadwal Pembelajaran';

    protected static ?string $pluralModelLabel = 'Jadwal';

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
            ->paginated(false)
            ->modifyQueryUsing(
                fn (Builder $query) => $query->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
                    ->join('days', 'timetables.day_id', '=', 'days.id')
                    ->select('timetables.*')
                    ->orderBy('days.id', 'asc')
                    ->orderByRaw('CAST(timeslots.time_start AS TIME) ASC')
            )
            ->columns([
                TextColumn::make('day.name')
                    ->label('Hari'),
                TextColumn::make('timeslot.full_time')
                    ->label('Waktu')
                    ->searchable(),
                TextColumn::make('lesson.name')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                TextColumn::make('teacher.name')
                    ->label('Guru'),
            ])
            ->groups([
                Group::make('day.name')
                    ->label('Hari')
                    ->titlePrefixedWithLabel(false),
            ])
            ->groupingSettingsHidden()
            ->defaultGroup('day.name')
            ->filters([
                SelectFilter::make('classroom')
                    ->relationship(
                        'classroom',
                        'name',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('id', 'asc')
                    )
                    ->default(1)
                    ->label('Kelas')
                    ->searchable()
                    ->preload()
                    ->selectablePlaceholder(false),
            ])
            // ->hiddenFilterIndicators()
            ->actions([])
            ->bulkActions([]);
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
            'index' => Pages\ListTimetables::route('/'),
            // 'create' => Pages\CreateTimetable::route('/create'),
            // 'edit' => Pages\EditTimetable::route('/{record}/edit'),
        ];
    }
}
