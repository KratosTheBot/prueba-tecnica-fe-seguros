<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FE Seguros - Gestión</title>

    @vite('resources/css/app.css')
</head>

<body>

    <!-- CABECERA -->
    <header class="header">
        <div class="container header-content">
            <img
                src="{{ asset('logo.png') }}"
                alt="FE Seguros"
                class="logo"
            >
        </div>
    </header>


    <!-- CONTENIDO PRINCIPAL -->
    <main class="container">

        <!-- CABECERA DE PÁGINA -->
        <section class="page-header">
            <div>
                <h2>Prueba tecnica Juan Marcos Rodriguez Tapiz</h2>
                <p>Consulta, filtra y gestiona los registros.</p>
            </div>
        </section>


        <!-- FORMULARIO -->
        <form action="{{ route('search') }}" method="GET">

            {{-- =====================================================
                 CAMPOS OCULTOS PARA ORDENACIÓN
            ====================================================== --}}

            <input
                type="hidden"
                name="sort"
                id="sort-column"
                value="{{ request('sort', '') }}"
            >

            <input
                type="hidden"
                name="direction"
                id="sort-direction"
                value="{{ request('direction', 'asc') }}"
            >


            {{-- =====================================================
                 FILTROS PRINCIPALES
            ====================================================== --}}

            <section class="filters">

                {{-- USUARIO --}}
                <div class="filter-group">

                    <label for="usuario">
                        Usuario
                    </label>

                    <select
                        id="usuario"
                        name="usuario"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="rcn01"
                            {{ request('usuario') == 'rcn01' ? 'selected' : '' }}
                        >
                            rcn01
                        </option>

                

                    </select>

                </div>


                {{-- AGENCIA --}}
                <div class="filter-group">

                    <label for="agencia">
                        Agencia
                    </label>

                    <select
                        id="agencia"
                        name="agencia"
                    >

                        <option value="">
                            Todas
                        </option>

                        <option
                            value="18"
                            {{ request('agencia') == '18' ? 'selected' : '' }}
                        >
                            18
                        </option>

                     

                    </select>

                </div>


                {{-- ID SESSION --}}
                <div class="filter-group">

                    <label for="idSession">
                        ID Session
                    </label>

                    <select
                        id="idSession"
                        name="idSession"
                    >

                        <option value="">
                            Todos
                        </option>

                       

                    </select>

                </div>


                {{-- APP --}}
                <div class="filter-group">

                    <label for="app">
                        App
                    </label>

                    <select
                        id="app"
                        name="app"
                    >

                        <option value="">
                            Todas
                        </option>

                        

                    </select>

                </div>


                {{-- PÁGINA --}}
                <div class="filter-group">

                    <label for="pag">
                        Pág
                    </label>

                    <select
                        id="pag"
                        name="pag"
                    >

                        <option value="">
                            Todas
                        </option>
                   

                    </select>

                </div>


                {{-- MEDIADOR --}}
                <div class="filter-group">

                    <label for="mediador">
                        Mediador
                    </label>

                    <select
                        id="mediador"
                        name="mediador"
                    >

                        <option value="">
                            Todos
                        </option>

                        <option
                            value="T"
                            {{ request('mediador') == 'T' ? 'selected' : '' }}
                        >
                            T
                        </option>

                        <option
                            value="A"
                            {{ request('mediador') == 'A' ? 'selected' : '' }}
                        >
                            A
                        </option>

                    </select>

                </div>


                {{-- FECHA DESDE --}}
                <div class="filter-group">

                    <label for="fechaDesde">
                        Fecha desde
                    </label>

                    <input
                        type="date"
                        id="fechaDesde"
                        name="fechaDesde"
                        value="{{ request('fechaDesde') }}"
                    >

                </div>


                {{-- FECHA HASTA --}}
                <div class="filter-group">

                    <label for="fechaHasta">
                        Fecha hasta
                    </label>

                    <input
                        type="date"
                        id="fechaHasta"
                        name="fechaHasta"
                        value="{{ request('fechaHasta') }}"
                    >

                </div>


                {{-- BOTÓN BUSCAR --}}
                <div class="filter-actions">

                    <button
                        type="submit"
                        name="buscar"
                        value="1"
                        class="btn btn-primary"
                    >
                        Buscar
                    </button>

                </div>

            </section>


            {{-- =====================================================
                 TABLA
            ====================================================== --}}

            <section class="table-container">

                <table class="data-table">

                    <thead>

                        @php

                            $columnas = [

                                'idAseg' => 'ID Asegurado',

                                'poliza' => 'Póliza',

                                'fechaAltaPoliza' => 'Fecha Alta Póliza',

                                'tarifa' => 'Tarifa',

                                'nombreAseg' => 'Nombre Asegurado',

                                'fechaAltaAseg' => 'Fecha Alta Asegurado',

                                'fechaNac' => 'Fecha Nacimiento',

                                'elementoPpal' => 'Elemento Principal',

                                'capitalPpal' => 'Capital Principal',

                            ];

                        @endphp


                        {{-- =================================================
                             CABECERA DE COLUMNAS
                        ================================================== --}}

                        <tr>

                            @foreach ($columnas as $campo => $nombre)

                                @php

                                    /*
                                     * Si esta columna es la actualmente
                                     * ordenada y está en ASC,
                                     * el siguiente click será DESC.
                                     *
                                     * En cualquier otro caso,
                                     * el siguiente click será ASC.
                                     */

                                    $nuevaDireccion =
                                        request('sort') === $campo &&
                                        request('direction') === 'asc'
                                            ? 'desc'
                                            : 'asc';

                                @endphp


                                <th id="">

                                    <button
                                        type="submit"
                                        class="sort-button"
                                        onclick="
                                            document.getElementById('sort-column').value = '{{ $campo }}';
                                            document.getElementById('sort-direction').value = '{{ $nuevaDireccion }}';
                                        "
                                    >

                                        {{ $nombre }}


                                        {{-- FLECHA --}}

                                        @if (request('sort') === $campo)

                                            @if (request('direction') === 'asc')

                                                ↑

                                            @else

                                                ↓

                                            @endif

                                        @else

                                            ↕️

                                        @endif

                                    </button>

                                </th>

                            @endforeach

                        </tr>


                        {{-- =================================================
                             FILTROS DE CADA COLUMNA
                        ================================================== --}}

                        <tr class="column-filters">

                            @foreach ($columnas as $campo => $nombre)

                                <th id="">

                                    <input
                                        type="text"
                                        name="filter_{{ $campo }}"
                                        value="{{ request('filter_' . $campo) }}"
                                        placeholder="Filtrar..."
                                        class="column-filter"
                                    >

                                </th>

                            @endforeach

                        </tr>

                    </thead>


                    {{-- =====================================================
                         RESULTADOS
                    ====================================================== --}}

                    <tbody>

                        @if ($asegurados)

                            @forelse ($asegurados as $asegurado)

                                <tr>

                                    <td>
                                        {{ $asegurado['idAseg'] ?? '' }}
                                    </td>

                                    <td>
                                        {{ $asegurado['poliza'] ?? '' }}
                                    </td>

                                    <td>
                                        {{ $asegurado['fechaAltaPoliza'] ?? '' }}
                                    </td>

                                    <td>
                                        {{ $asegurado['tarifa'] ?? '' }}
                                    </td>

                                    <td>
                                        {{ $asegurado['nombreAseg'] ?? '' }}
                                    </td>

                                    <td>
                                        {{ $asegurado['fechaAltaAseg'] ?? '' }}
                                    </td>

                                    <td>
                                        {{ $asegurado['fechaNac'] ?? '' }}
                                    </td>

                                    <td>
                                        {{ $asegurado['elementoPpal'] ?? '' }}
                                    </td>

                                    <td>
                                        {{ $asegurado['capitalPpal'] ?? '' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="9"
                                        class="no-results"
                                    >
                                        No hay resultados.
                                    </td>

                                </tr>

                            @endforelse

                        @else

                            {{-- =================================================
                                 ESTADO INICIAL
                                 No se ha realizado ninguna búsqueda.
                            ================================================== --}}

                            <tr>

                                <td
                                    colspan="9"
                                    class="no-results"
                                >
                                    Introduce los criterios de búsqueda y pulsa
                                    <strong>Buscar</strong>.
                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </section>

        </form>


        {{-- =============================================================
             PAGINACIÓN
        ============================================================== --}}

        @if ($asegurados && $asegurados->hasPages())

            <div class="pagination">


                {{-- ANTERIOR --}}

                @if ($asegurados->onFirstPage())

                    <span class="pagination-button disabled">
                        Anterior
                    </span>

                @else

                    <a
                        href="{{ $asegurados->previousPageUrl() }}"
                        class="pagination-button"
                    >
                        Anterior
                    </a>

                @endif


                {{-- INFORMACIÓN --}}

                <span class="pagination-current">

                    Página

                    {{ $asegurados->currentPage() }}

                    de

                    {{ $asegurados->lastPage() }}

                    ·

                    {{ $asegurados->total() }}

                    resultados

                </span>


                {{-- SIGUIENTE --}}

                @if ($asegurados->hasMorePages())

                    <a
                        href="{{ $asegurados->nextPageUrl() }}"
                        class="pagination-button"
                    >
                        Siguiente
                    </a>

                @else

                    <span class="pagination-button disabled">
                        Siguiente
                    </span>

                @endif

            </div>

        @endif

    </main>

</body>
</html>
