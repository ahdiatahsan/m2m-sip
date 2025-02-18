<?php

namespace App\Filament\Pages;

use App\Models\Teacher;
use App\Models\Timetable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction as TablesExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class MyTimetable extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $title = 'Jadwal Saya';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.my-timetable';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('teacher');
    }

    public function table(Table $table): Table
    {
        $teacher = Teacher::query()->where('user_id', auth()->user()->id)->first();

        return $table
            ->paginated(false)
            ->query(
                Timetable::query()
                    ->where('teacher_id', $teacher->id)
                    ->join('timeslots', 'timetables.timeslot_id', '=', 'timeslots.id')
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
            ->defaultGroup('day.name')
            ->headerActions([
                TablesExportAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withColumns([
                                Column::make('day.name')->heading('Hari'),
                            ])
                            ->withFilename('Jadwal Mengajar Saya')
                            ->withWriterType(\Maatwebsite\Excel\Excel::XLSX),
                    ]),
            ]);
    }
}
