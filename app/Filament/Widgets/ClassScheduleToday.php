<?php

namespace App\Filament\Widgets;

use App\Models\Timetable;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ClassScheduleToday extends BaseWidget
{
    protected static ?string $heading = 'Jadwal Hari Ini';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(
                Timetable::query()
                    ->where('day_id', now()->format('N'))
                    ->whereHas('timeslot', function (Builder $query) {
                        $query->where('time_end', '>', now()->format('H:i:s'));
                    })
                    ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
                    ->select('timetables.*')
                    ->orderBy('timeslots.time_start')
            )
            ->filters([
                SelectFilter::make('classroom_id')
                    ->relationship(
                        'classroom',
                        'name',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('id', 'asc')
                    )
                    ->default(1)
                    ->label('Pilih Kelas')
                    ->searchable()
                    ->preload()
                    ->selectablePlaceholder(false),
            ])
            ->columns([
                TextColumn::make('timeslot.full_time')->label('Waktu'),
                TextColumn::make('classroom.name')->label('Kelas'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function ($record): string {
                        $now = Carbon::now();
                        $currentTime = Carbon::createFromFormat('H:i:s', $now->format('H:i:s'));
                        $startTime = Carbon::createFromFormat('H:i:s', $record->timeslot->time_start);
                        $endTime = Carbon::createFromFormat('H:i:s', $record->timeslot->time_end);

                        return $currentTime->between($startTime, $endTime) ? 'Berlangsung' : 'Belum Dimulai';
                    })
                    ->colors([
                        'success' => 'Berlangsung',
                        'info' => 'Belum Dimulai',
                    ]),
            ])
            ->actions([]);
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('student');
    }
}
