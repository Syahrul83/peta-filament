<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected function getStats(): array
    {
        return [

            Stat::make('Lokasi/Gedung Terdaftar', DB::table('peta_koordiants')
                ->select(DB::raw('count(*) as count'))
                ->count()),
            Stat::make('Daerah Terdaftar', DB::table('peta_koordiants')
                ->distinct('name')->count('name')),

        ];
    }
}
