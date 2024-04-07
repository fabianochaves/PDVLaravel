<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venda;


class DashboardController extends Controller
{
    public function buscaTotalVendasMes()
    {
        try {
            $total = Venda::whereMonth('datetime_venda', '=', now()->month)
                        ->sum('valor_total_venda');
        } catch (\Exception $e) {
            throw new \Exception("Erro ao buscar o total de vendas do mês: " . $e->getMessage());
        }

        if ($total == 0) {
            $retorno = "0,00";
        } else {
            $retorno = number_format($total, 2, ',', '.');
        }
        return response()->json(['total_vendas_mes' => $retorno]);
    }

    
    public function buscaTotalVendasSemana()
    {
        try {
            $total = Venda::where('datetime_venda', '>=', now()->subDays(7))
                        ->sum('valor_total_venda');
        } catch (\Exception $e) {
            return response()->json(['error' => "Erro ao buscar o total de vendas dos últimos 7 dias: " . $e->getMessage()], 500);
        }



        if ($total == 0) {
            $retorno = "0,00";
        } else {
            $retorno = number_format($total, 2, ',', '.');
        }

        return response()->json(['total_vendas_ultimos_7_dias' => $retorno]);
    }

    public function buscaTotalVendasHoje()
    {
        try {
            $total = Venda::whereDate('datetime_venda', '=', now()->toDateString())
                        ->sum('valor_total_venda');
        } catch (\Exception $e) {
            return response()->json(['error' => "Erro ao buscar o total de vendas de hoje: " . $e->getMessage()], 500);
        }

        if ($total == 0) {
            $retorno = "0,00";
        } else {
            $retorno = number_format($total, 2, ',', '.');
        }

        return response()->json(['total_vendas_hoje' => $retorno]);
    }

    public function buscaVendasMesAMes()
    {
        try {
            $dadosVendasMesAMes = Venda::selectRaw('EXTRACT(MONTH FROM datetime_venda) as mes, SUM(valor_total_venda) as total')
                                    ->whereYear('datetime_venda', now()->year)
                                    ->groupBy('mes')
                                    ->get();
        } catch (\Exception $e) {
            return response()->json(['error' => "Erro ao buscar as vendas mês a mês: " . $e->getMessage()], 500);
        }

        return response()->json($dadosVendasMesAMes);
    }
}