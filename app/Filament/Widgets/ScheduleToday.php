<?php

namespace App\Filament\Widgets;

use App\Models\Teacher;
use App\Models\Timetable;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ScheduleToday extends BaseWidget
{
    protected static ?string $heading = 'Jadwal Hari Ini';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        $teacher = Teacher::query()->where('user_id', auth()->user()->id)->first();

        return $table
            ->paginated(false)
            ->query(
                Timetable::query()
                    ->where('teacher_id', $teacher->id)
                    ->where('day_id', now()->format('N'))
                    ->whereHas('timeslot', function (Builder $query) {
                        $query->where('time_end', '>', now()->format('H:i:s'));
                    })
                    ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
                    ->orderBy('timeslots.time_start')
                    ->select('timetables.*')
            )
            ->columns([
                TextColumn::make('timeslot.full_time')
                    ->label('Waktu'),
                TextColumn::make('classroom.name')
                    ->label('Kelas'),
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
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('teacher');
    }
}
