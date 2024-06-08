<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Invoicing;
use Illuminate\Support\Facades\DB;


class BlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Financeiro';

    protected function getData(): array
    {
         // Consulta para obter os valores totais de entrada e saída por mês
    $data = Invoicing::select(
        DB::raw("DATE_FORMAT(date_invoiced, '%Y-%m') as month"),
        DB::raw("SUM(CASE WHEN type = 'in' THEN amount ELSE 0 END) as total_in"),
        DB::raw("SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END) as total_out")
    )
    ->groupBy('month')
    ->get();

// Arrays para armazenar os rótulos (meses) e os dados (total de entrada e saída) do gráfico
$labels = [];
$totalIn = [];
$totalOut = [];

// Preenche os arrays com os dados obtidos
foreach ($data as $item) {
    $labels[] = $item->month;
    $totalIn[] = $item->total_in;
    $totalOut[] = $item->total_out;
}

// Retorna os dados formatados para o gráfico
return [
    'datasets' => [
        [
            'label' => 'Entrada',
            'data' => $totalIn,
            'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
            'borderColor' => 'rgba(54, 162, 235, 1)',
        ],
        [
            'label' => 'Saída',
            'data' => $totalOut,
            'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
            'borderColor' => 'rgba(255, 99, 132, 1)',
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
