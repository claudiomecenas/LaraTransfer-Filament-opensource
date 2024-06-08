<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB; // Adicione esta linha para importar a classe DB


class DriverTransfeChart extends ChartWidget
{
    protected static ?string $heading = 'Transfers por Motorista';

  
    protected function getData(): array
    {
        // Consulta para obter a contagem de transfers por motorista
        $data = Transfer::join('cars', 'transfers.car_id', '=', 'cars.id')
            ->join('drivers', 'cars.driver_id', '=', 'drivers.id')
            ->select('drivers.name as driver_name', DB::raw('count(transfers.id) as transfer_count'))
            ->groupBy('drivers.name')
            ->get();

        // Arrays para armazenar os rótulos (nomes dos motoristas) e os dados (quantidade de transfers) do gráfico
        $labels = [];
        $dataPoints = [];

        // Preenche os arrays com os dados obtidos
        foreach ($data as $item) {
            $labels[] = $item->driver_name;
            $dataPoints[] = $item->transfer_count;
        }

        // Retorna os dados formatados para o gráfico
        return [
            'datasets' => [
                [
                    'data' => $dataPoints,
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#33FF8D', '#AC33FF', '#FF5733', '#FFE833'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        //return 'doughnut';
        return 'pie';
        //return 'bubble';
    }

}
