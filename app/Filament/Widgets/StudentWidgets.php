<?php

namespace App\Filament\Widgets;

use App\Models\Classroom;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentWidgets extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Kelas', Classroom::count()),
            Stat::make('Total Guru', Teacher::count()),
            Stat::make('Tanggal', date('d M Y')),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('student');
    }
}
