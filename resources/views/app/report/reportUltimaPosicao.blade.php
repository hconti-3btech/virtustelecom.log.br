@extends('adminlte::page')

@section('title', 'Relatorio de Ultima Posição')

@section('plugins.Datatables', true)

@section('content_header')
    <h1 id="h01" class="m-0 text-dark">Relatorio de Ultima Posição</h1>
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
                                <form method="GET" accept="{{route('reportUltimaPosicao')}}">
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
                    <table id="movimentos" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th id='col1'>Origem</th>
                                <th>Destino</th>
                                <th>Produto</th>
                                <th>QTD</th>
                                <th>Controle</th>
                                <th>MAC</th>
                                <th>Ordem</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movimentos as $keyMovimentos => $movimento)
                                <tr>
                                    <td>{{$movimento['origem']}}</td>
                                    <td>{{$movimento['destino']}}</td>
                                    <td>{{$movimento['produto']}}</td>
                                    <td>{{$movimento['qtd']}}</td>
                                    <td>{{$movimento['controle']}}</td>
                                    <td>{{$movimento['mac']}}</td>
                                    <td>{{$movimento['ordem']}}</td>
                                    <td>{{$movimento['data']}}</td>
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
            $('#movimentos').DataTable( {
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