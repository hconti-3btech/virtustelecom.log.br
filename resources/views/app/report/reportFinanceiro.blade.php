@extends('adminlte::page')

@section('title', 'Requisiçoes')

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
                            <form method="GET" accept="{{route('reportFinanceiro')}}">
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
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
             <h3><sup style="font-size: 20px">R$ </sup>{{$valorTotal}}</h3>
                <p>Valor Atual</p>
            </div>
        <div class="icon">
            <i class="nav-icon fas fa-solid fa-piggy-bank"></i>
        </div>
        <a href="#" class="small-box-footer">{{$dataIni}}</a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
            <h3><sup style="font-size: 20px">R$ </sup>{{$previsao}}</h3>
                <p>Previsão</p>
            </div>
        <div class="icon">
            <i class="nav-icon fas fa-solid fa-money-bill-wave"></i>
        </div>
            <a href="#" class="small-box-footer">{{$dataFim}}</a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-{{$colorGap}}">
            <div class="inner">
            <h3><sup style="font-size: 20px">R$ </sup>{{$gap}}</h3>
                <p>Custo Total: {{$custoTotal}}</p>
            </div>
        <div class="icon">
            <i class="nav-icon fas fa-solid {{$iconGap}}"></i>
        </div>
            <a href="#" class="small-box-footer">de {{$dataIni}} até {{$dataFim}}</a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
            <h3>{{$qtdTecnicos}}</h3>
                <p>Quantidade de Técnicos Ativos</p>
            </div>
        <div class="icon">
            <i class="nav-icon fas fa-solid fa-user"></i>
        </div>
            <a href="#" class="small-box-footer">de {{$dataIni}} até {{$dataFim}}</a>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-12 col-lg-12">
        <!-- DONUT CHART -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">FINANCEIRO:</h3>

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
                <canvas id="barChart"></canvas>
            </div>
        <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    
</div>

<div class="row">
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

                            @foreach ($tblTecnicos as $tblTecnicosKey => $tblTecnicosValue)
                                @php
                                    //para executar apenas 1x
                                    if ($i < 1){
                                        foreach ($tblTecnicosValue as $tblTecnicosValueKey => $tblTecnicosValueValue) {

                                                $tblTecnicosValueKeyExplode = explode(' ',$tblTecnicosValueKey);

                                                if (count($tblTecnicosValueKeyExplode) > 1 or strlen($tblTecnicosValueKey) > 7){
                                                
                                                    for ($i=0; $i < count($tblTecnicosValueKeyExplode); $i++) { 

                                                        if (strlen($tblTecnicosValueKeyExplode[$i]) > 3 ){
                                                            $tblTecnicosValueKeyExplode[$i] = substr("$tblTecnicosValueKeyExplode[$i]", 0, 3).'. ';
                                                        }else {
                                                            $tblTecnicosValueKeyExplode[$i] = $tblTecnicosValueKeyExplode[$i].' ';
                                                        }
                                                        
                                                    }
                                                }

                                                $tblTecnicosValueKeyImplode = implode($tblTecnicosValueKeyExplode);

                                            echo "<th> $tblTecnicosValueKeyImplode </th>";
                                        } 
                                    }
                                    $i++;
                                    
                                @endphp

                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                            
                        @foreach ($tblTecnicos as $tblTecnicosKey => $tblTecnicosValue)
                            <tr>
                                <td>{{$tblTecnicosKey}}</td>

                                @foreach ( $tblTecnicosValue as $tblTecnicosValueValue)
                                    <td>{{$tblTecnicosValueValue}}</td>                                    
                                @endforeach
                            
                            </tr>
                        @endforeach

                    </tbody>   
                </table>
            </div>
        </div>
    </div>
</div>


    
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
                "order": [[1, "DESC"]],
            } );
        } );
    </script>

    {{-- Chats --}}
    <script>

        $(window).on("load", function(){
            
            

            function ordensByValorServico (legenda,valor){  //-------------

                new Chart(document.getElementById("barChart"), {
                    type: 'horizontalBar',
                    data: {
                        labels: legenda,
                        datasets: [
                                {
                                label: "R$ ",
                                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850","#c91919","#f39c12"],
                                data: valor
                                }
                            ],
                    },
                        options: {
                        legend: {
                            display: false 
                        },
                        title: {
                            display: true,
                            text: 'Valores do periodo {{$dataIni}} até {{$dataFim}}, Valor Total {{$valorTotal}}',
                            fullSize : true,
                        }
                    }
                });
            }
            
            ordensByValorServico (<?=$legendaValorServicos?>,<?=$valoresValorServicos?>);

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