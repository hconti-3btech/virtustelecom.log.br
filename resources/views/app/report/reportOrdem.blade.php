@extends('adminlte::page')

@section('title', 'Relatorio de Serviços Geral')

@section('plugins.Chart', true)

@section('plugins.Datatables', true)

@section('content_header')
    <h1 id="h01" class="m-0 text-dark">Relatorio de Serviços Geral</h1>
@stop

@section('content')

    @if(session('msg'))
        <div class="alert alert-{{session('type')}}">
            <p>{{session('msg')}}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form method="POS">
                        <div class="row">    
                            <div class="col-12 col-lg-12 mt-2"> 
                                <form method="GET" accept="{{route('reportOrdem')}}">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" id="dateIni" name="data_ini" value="{{$dataIni}}">
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" id="dateFim" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" inputmode="numeric" name="data_fim">
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn  btn-primary" onclick="return confirmar()">Filtro <i class="fas fa-solid fa-filter"></i></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>        
                    </form>    
                </div>    
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-6">
            <!-- DONUT CHART -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">QTD ORDENS / TIPO:</h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="ordensByServico" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-lg-6">
            <!-- PIE CHART -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">% DE RETORNO FINANCEIRO:</h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                    </div>
                </div>
            <div class="card-body">
                <canvas id="ordensByValorServico" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        
    </div>

    <div class="row">
        <div class="col-12 col-lg-6">
            <!-- DONUT CHART -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">QTD ORDENS / STATUS:</h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="ordensByStatus" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-lg-6">
            <!-- PIE CHART -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">% DE ORDENS / STATUS:</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            <div class="card-body">
                <canvas id="ordensByStatusPorCem" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        
    </div>

    <div class="row">
        <div class="col-12 col-lg-12">
            <!-- PIE CHART -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Servicos por Status:</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            <div class="card-body">
                <table id="tabelaTipoServStatus" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th id='col1'>Serviço</th>
                            <th>Aberta</th>
                            <th>Finalizada</th>
                            <th>Atribuida</th>
                            <th>Informada</th>
                            <th>Cancelada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tabelaTipoServStatus as $tabelaTipoServStatusKey => $tabelaTipoServStatusValue)
                            <tr>
                                <td>{{$tabelaTipoServStatusKey}}</td>

                                @foreach ( $tabelaTipoServStatusValue as $tabelaTipoServStatusValueValue)
                                    <td>{{$tabelaTipoServStatusValueValue}}</td>    
                                @endforeach
                            </tr>
                            
                        @endforeach
                    </tbody>   
                </table>
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

        {{-- <!-- /.col -->
        <div class="col-12 col-lg-4">
            <!-- DONUT CHART -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Servicos por Status:</h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                    </div>
                </div>
                <div class="card-body">
                    
                </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col --> --}}
        
    </div>

    {{-- <div class="row">
        <div class="col-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">O.S / Tarefa por Status:</h3>
                    
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                </div>
                <div class="card-body">
                    <table id="ordensByTec" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th id='col1'>Técnico</th>
                                @php
                                    $i = 0;
                                @endphp

                                @foreach ($ordensByUsers as $keyOrdensByUser => $ordensByUser)
                                    @php
                                        //para executar apenas 1x
                                        if ($i < 1){
                                            foreach ($ordensByUser as $keyOrdemByUser => $OrdemByUser) {
                                                
                                                                                                
                                                $explodeKeyOrdemByUser = explode(' ',$keyOrdemByUser);

                                                for ($i=0; $i < count($explodeKeyOrdemByUser); $i++) { 

                                                    if (strlen($explodeKeyOrdemByUser[$i]) > 3 ){
                                                        $explodeKeyOrdemByUser[$i] = substr("$explodeKeyOrdemByUser[$i]", 0, 3).'. ';
                                                    }else {
                                                        $explodeKeyOrdemByUser[$i] = $explodeKeyOrdemByUser[$i].' ';
                                                    }
                                                    
                                                }

                                                $explodeKeyOrdemByUser = implode($explodeKeyOrdemByUser);

                                                if (substr($keyOrdemByUser,0,5) !== 'Med. ' and $keyOrdemByUser !== 'id_tec'){

                                                    echo "<th> $explodeKeyOrdemByUser </th>";
                                                }
                                                
                                            } 
                                        }
                                        $i++;
                                        
                                    @endphp

                                @endforeach

                                <th>Visualizar</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($ordensByUsers as $keyOrdensByUser => $ordensByUser)
                            
                                    @php

                                        echo "<tr>";

                                        $explodeKeyOrdensByUser = explode(' ',$keyOrdensByUser);
 
                                        
                                        if (count($explodeKeyOrdensByUser) >= 2 and strlen($explodeKeyOrdensByUser[1]) >= 3){
                                            $tecnicoNome = $explodeKeyOrdensByUser[0].' '.substr($explodeKeyOrdensByUser[1], 0, 3).'.' ;

                                        }elseif (count($explodeKeyOrdensByUser) >= 2 and strlen($explodeKeyOrdensByUser[1]) <= 3 ){
                                            $tecnicoNome = $explodeKeyOrdensByUser[0].' '.substr($explodeKeyOrdensByUser[2], 0, 3).'.' ;
                                        }
                                        else{
                                            $tecnicoNome = $explodeKeyOrdensByUser[0];
                                        }
                                        
                                        echo "<td>$tecnicoNome</td>";
                                        
                                        foreach ($ordensByUsers[$keyOrdensByUser] as $keyOrdemByUser => $ordemByUser) {
                                            
                                            if (substr($keyOrdemByUser,0,5) !== 'Med. ' and  $keyOrdemByUser !== 'id_tec'){
                                                echo "<td>$ordemByUser</td>";
                                            }

                                        }

                                        echo "<td><a href='".route('viewExecOrdem',['dt_ini' => $dt_ini, 'dt_fim' => $dt_fim, 'tec' => $ordensByUser['id_tec']])."' class='btn btn-primary btn-sm' onclick='return confirmar()'>Visualizar</a></td>";
                                        
                                        echo "</tr>";

                                    @endphp
                                                    
                            @endforeach
                        </tbody>   
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
    
@stop

@section('js')

    {{-- Datatable --}}
    <script>
        $(document).ready(function() {
            $('#ordensByTec').DataTable( {
                "language":{
                    "url":"https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                },
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
                "paging": true,
                "info": true,
                "searching": true,
                "dom": 'Bfrtip',
                "pageLength": 50,
                "buttons": ["copy", "csv", "excel", "pdf"],
                "order": [[1, "desc"]],
            } );
        } );
    </script>

    <script>
        $(document).ready(function() {
            $('#tabelaTipoServStatus').DataTable( {
                "language":{
                    "url":"https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                },
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
                "paging": true,
                "info": true,
                "searching": false,
                "dom": 'Bfrtip',
                "pageLength": 50,
                "buttons": [],
                "order": [[1, "desc"]],
            } );
        } );
    </script>

    {{-- Chats --}}
    <script>

        $(window).on("load", function(){
            
            function ordensByQtdServico (legenda,valores){  //-------------
                //- DONUT CHART -
                //-------------
                // Get context with jQuery - using jQuery's .get() method.
                var donutChartCanvas = $('#ordensByServico').get(0).getContext('2d')
                
                var donutData        = {
                labels : legenda,
                datasets: valores
                
                }
                //datasets
                var donutOptions     = {
                maintainAspectRatio : false,
                responsive : true,
                title: {
                        display: true,
                        text: 'Valores do periodo {{$dataIni}} até {{$dataFim}}, Total {{$qtdTotal}}',
                        fullSize : true,
                    }
                }
                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
                })
            }

            function ordensByValorServicoRetorno (legenda,valores){  //-------------
                //- PIE CHART -
                //-------------
                // Get context with jQuery - using jQuery's .get() method.
                var pieChartCanvas = $('#ordensByValorServico').get(0).getContext('2d')
                var pieData        = {
                labels : legenda,
                datasets: valores
                
                }

                //datasets
                var pieOptions     = {
                maintainAspectRatio : false,
                responsive : true,
                title: {
                        display: true,
                        text: 'Valores do periodo {{$dataIni}} até {{$dataFim}}, Total {{$qtdTotal}}',
                        fullSize : true,
                    }
                }
                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
                })
            }

            function ordensByStatus (legenda,valores){  //-------------
                //- PIE CHART -
                //-------------
                // Get context with jQuery - using jQuery's .get() method.
                var pieChartCanvas = $('#ordensByStatus').get(0).getContext('2d')
                var pieData        = {
                labels : legenda,
                datasets: valores

                
                }

                //datasets
                var pieOptions     = {
                    maintainAspectRatio : false,
                    responsive : true,
                    title: {
                        display: true,
                        text: 'Valores do periodo {{$dataIni}} até {{$dataFim}}, Total {{$qtdTotal}}',
                        fullSize : true,
                    }
                }
                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: pieData,
                options: pieOptions
                })
            } 

            function ordensByStatusPorCem (legenda,valores){  //-------------
                //- PIE CHART -
                //-------------
                // Get context with jQuery - using jQuery's .get() method.
                var pieChartCanvas = $('#ordensByStatusPorCem').get(0).getContext('2d')
                var pieData        = {
                labels : legenda,
                datasets: valores

                
                }

                //datasets
                var pieOptions     = {
                    maintainAspectRatio : false,
                    responsive : true,
                    title: {
                        display: true,
                        text: 'Valores do periodo {{$dataIni}} até {{$dataFim}}, Total {{$qtdTotal}}',
                        fullSize : true,
                    }
                }
                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
                })
            } 

            ordensByQtdServico (<?=$legendaQtdServicos?>,<?=$valoresQtdServicos?>);
            ordensByValorServicoRetorno (<?=$legendaValorServicosRetorno?>,<?=$valoresValorServicosRetorno?>);
            ordensByStatus (<?=$legendaServicosStatus?>,<?=$valoresServicosStatus?>);
            ordensByStatusPorCem (<?=$legendaServicosStatusPorCem?>,<?=$valoresServicosStatusPorCem?>);
        })
        
    </script>

    {{-- Confirma envio de Formularo --}}

    <script>
        function confirmar(){
        // só permitirá o envio se o usuário responder OK
        var resposta = window.confirm("Deseja mesmo" + 
                        " enviar o formulário?");
        if(resposta)
            return true;
        else
            return false; 
        }
    </script>

@endsection