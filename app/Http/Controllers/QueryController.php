<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;


class QueryController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'asegurados' => [],
        ]);
    }

    public function search(Request $request)
    {

        $fechaDesde = $request->filled('fechaDesde')
            ? date('d-m-Y', strtotime($request->fechaDesde))
            : '';

        $fechaHasta = $request->filled('fechaHasta')
            ? date('d-m-Y', strtotime($request->fechaHasta))
            : '';

        $requestData = [
            'usuario' => $request->input('usuario', ''),
            'agencia' => $request->input('agencia', ''),
            'idSession' => $request->input('idSession', ''),
            'app' => $request->input('app', ''),
            'pag' => $request->input('pag', ''),
            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,
            'mediador' => $request->input('mediador', ''),
        ];

        $response = Http::get(
            'http://195.55.66.251:8181/PRUEBAPOST/PRUEBAMARCOS',
            [
                'REQUESTDATA' => json_encode($requestData)
            ]
        );

        if (!$response->successful()) {
            return back()->with('error', 'Error al consultar la API.');
        }

        $data = $response->json();

        $asegurados = $data['PRUEBAMARCOS']['DATA']['asegurados'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Filtros de tabla
        |--------------------------------------------------------------------------
        */

        $filtros = [
            'idAseg',
            'poliza',
            'fechaAltaPoliza',
            'tarifa',
            'nombreAseg',
            'fechaAltaAseg',
            'fechaNac',
            'elementoPpal',
            'capitalPpal',
        ];

        foreach ($filtros as $campo) {

            $valor = $request->input('filter_' . $campo);

            if ($valor !== null && $valor !== '') {

                $asegurados = array_filter(
                    $asegurados,
                    function ($asegurado) use ($campo, $valor) {

                        return isset($asegurado[$campo])
                            && str_contains(
                                strtolower((string) $asegurado[$campo]),
                                strtolower((string) $valor)
                            );
                    }
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Ordenación
        |--------------------------------------------------------------------------
        */

        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');

        $camposPermitidos = [
            'idAseg',
            'poliza',
            'fechaAltaPoliza',
            'tarifa',
            'nombreAseg',
            'fechaAltaAseg',
            'fechaNac',
            'elementoPpal',
            'capitalPpal',
        ];

        if ($sort && in_array($sort, $camposPermitidos)) {

            usort(
                $asegurados,
                function ($a, $b) use ($sort, $direction) {

                    $valorA = $a[$sort] ?? '';
                    $valorB = $b[$sort] ?? '';

                    $resultado = strnatcasecmp(
                        (string) $valorA,
                        (string) $valorB
                    );

                    return $direction === 'desc'
                        ? -$resultado
                        : $resultado;
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Paginación
        |--------------------------------------------------------------------------
        */

        $asegurados = collect($asegurados);

        $porPagina = 10;

        $paginaActual = LengthAwarePaginator::resolveCurrentPage();

        $resultados = $asegurados
            ->slice(
                ($paginaActual - 1) * $porPagina,
                $porPagina
            )
            ->values();

        $paginador = new LengthAwarePaginator(
            $resultados,
            $asegurados->count(),
            $porPagina,
            $paginaActual,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('welcome', [
            'asegurados' => $paginador,
        ]);
    }
}
