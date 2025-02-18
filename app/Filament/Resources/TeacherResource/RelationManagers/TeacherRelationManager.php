<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class TeacherRelationManager extends RelationManager
{
    protected static string $relationship = 'timetables';

    protected static ?string $title = 'Jadwal Mengajar';

    protected static ?string $modelLabel = 'Jadwal';

    protected static ?string $pluralModelLabel = 'Jadwal';

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->modifyQueryUsing(fn ($query) => $query->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
                ->join('days', 'timetables.day_id', '=', 'days.id')
                ->orderBy('days.id')
                ->orderBy('timeslots.time_start')
                ->select('timetables.*')
            )
            ->columns([
                TextColumn::make('day.name')
                    ->label('Hari'),
                TextColumn::make('timeslot.full_time')
                    ->label('Waktu'),
                TextColumn::make('lesson.name')
                    ->label('Mata Pelajaran'),
                TextColumn::make('classroom.name')
                    ->label('Kelas'),
            ])
            ->groups([
                Group::make('day.name')
                    ->label('Hari')
                    ->titlePrefixedWithLabel(false),
            ])
            ->groupingSettingsHidden()
            ->defaultGroup('day.name');
    }
}
