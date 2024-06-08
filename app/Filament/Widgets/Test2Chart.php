<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Transfer;
use Carbon\Carbon;

class Test2Chart extends ChartWidget
{
    protected static ?string $heading = 'Transfers por mês';

    protected function getData(): array
    {
        $transfers = Transfer::whereYear('departure_date', '=', date('Y'))
            ->where('status', 1) // Filtrar transfers com status ativo
            ->orderBy('departure_date')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->departure_date)->format('M');
                // O Carbon é uma biblioteca PHP para manipulação de datas e horas
                // Aqui, está sendo utilizado para formatar a data no formato 'M' (abreviação do mês)
            });

        $labels = [];
        $data = [];

        foreach($transfers as $month => $transfer) {
            $labels[] = $month;
            $data[] = count($transfer);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de Transfers',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $labels,
        ];
    }
    protected function getType(): string
    {
        return 'bar';
    }

}
