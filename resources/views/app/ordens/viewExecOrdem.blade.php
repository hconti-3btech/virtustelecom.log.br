@extends('adminlte::page')

@section('title', 'Ordens de Servico')

@section('plugins.Datatables', true)

@section('content_header')
    <h1 class="m-0 text-dark">Ordens de Servico</h1>
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
                        <div class="col-12"> 
                            <form method="POST" accept="{{route('viewMovimento')}}">
                                <div class="row">
                                    <div class="col-2 mt-2">
                                        <a href="{{route('cadOrdem')}}">
                                            <button type="button" class="btn  btn-success"><i class="fas fa-solid fa-plus"></i></button>
                                        </a>
                                    </div>
                                    @if ($showFiltro)
                                        <div class="col-4 mt-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" id="dateIni" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" inputmode="numeric" name="data_ini" value="{{ $data_ini }}">
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="col-4 mt-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" id="dateFim" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" inputmode="numeric" name="data_fim" value="{{ $data_fim }}">
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="col-2 mt-2">
                                            <button type="submit" class="btn  btn-primary" onclick="return confirmar()"><i class="fas fa-solid fa-filter"></i></button>
                                        </div>
                                    @endif
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
        <div class="card">
            <div class="card-header">
                <table id="tbOrdens" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Ordem</th>
                            <th>Cliente</th>
                            <th>Status</th>
                            <th>Tecnico</th>
                            <th>Tipo Serv.</th>
                            <th>Abertura</th>
                            <th>Fechamento</th>
                            <th>Executar</th>
                            <th>Visualizar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordens as $ordem)
                            <tr>
                                <td>{{$ordem['id']}} </td>
                                <td>{{$ordem['ordem']}} </td>
                                <td>{{$ordem['cliente']}} </td>
                                <td><label class="btn btn-{{$ordem['corStatus']}} btn-sm"> {{$ordem['texStatus']}} </label></td>
                                <td>{{$ordem['tecnico']}} </td>
                                <td>{{$ordem['servico']}} </td>
                                <td>{{$ordem['data_abe']}} </td>
                                <td>{{$ordem['data_fec']}} </td>
                                <td><a href="{{route('execOrdem',["id"=>$ordem['id'],"url"=>$url])}}" class="btn btn-warning btn-sm" onclick="return confirmar()">Executar</a></td>
                                <td><a href="{{route('viewItensOrdem',["id"=>$ordem['id'],"url"=>$url])}}" class="btn btn-success btn-sm" onclick="return confirmar()">Visualizar</a></td>
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
            $('#tbOrdens').DataTable( {
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
                "columnDefs": [
                    {
                        targets: 0,
                        visible: false,
                        searchable: true
                    }
                ] ,
                "order": [[6, "desc"]]
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