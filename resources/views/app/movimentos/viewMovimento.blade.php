@extends('adminlte::page')

@section('title', 'Movimentos')

@section('plugins.Datatables', true)

@section('content_header')
    <h1 class="m-0 text-dark">Movimento</h1>
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
                            <form method="GET" accept="{{route('viewMovimento')}}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <a href="{{route('addMovimento')}}">
                                                    <button type="button" class="btn  btn-success"><i class="fas fa-solid fa-plus"></i></button>
                                                </a> 
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="inpSearch" value="{{ $oldInpSearch }}">
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <select class="form-control" name="selCampo">
                                                    <option value="controle" {{ $oldSelCampo == "controle" ? 'selected' : '' }}>Controle</option>
                                                    <option value="ordem" {{ $oldSelCampo == "ordem" ? 'selected' : '' }}>Ordem</option>
                                                    <option value="mac" {{ $oldSelCampo == "mac" ? 'selected' : '' }}>MAC</option>
                                                    <option value="requisicao" {{ $oldSelCampo == "requisicao" ? 'selected' : '' }}>Requisição</option>
                                                </select>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="date" class="form-control" id="dateIni" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" inputmode="numeric" name="data_ini" value="{{ $data_ini }}">
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="date" class="form-control" id="dateFim" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" inputmode="numeric" name="data_fim" value="{{ $data_fim }}">
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <button type="submit" class="btn  btn-primary" onclick="return confirmar()"><i class="fas fa-solid fa-filter"></i></button>
                                            </div>  
                                        </div>
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
        <div class="card">
            <div class="card-header">
                <table id="tbMovimentos" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Produto</th>
                            <th>QTD</th>
                            <th>Controle</th>
                            <th>Ordem</th>
                            <th>Requisição</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movimentos as $movimento)
                            <tr>
                                <td>{{$movimento['id']}} </td>
                                <td>{{$movimento['origem']}} </td>
                                <td>{{$movimento['destino']}} </td>
                                <td>{{$movimento['produto']}} </td>
                                <td>{{$movimento['qtd']}} </td>
                                <td>{{$movimento['controle']}} </td>
                                <td>{{$movimento['ordem']}} </td>
                                <td>{{$movimento['requisicao']}} </td>
                                <td>{{$movimento['created_at']}} </td>
                                <td>
                                    @if ($movimento['status'] == 'AGUARDANDO')
                                        <a href="{{route('aprovMovimento',["id"=>$movimento['id'],"status"=>'CONFIRMADO'])}}" class="btn btn-success btn-sm" onclick="return confirmar()"><i class="fas fa-solid fa-thumbs-up"></i></a>
                                        <a href="{{route('aprovMovimento',["id"=>$movimento['id'],"status"=>'CANCELADO'])}}" class="btn btn-warning btn-sm" onclick="return confirmar()"><i class="fas fa-solid fa-thumbs-down"></i></a>    
                                    @else
                                        <a href="#" class="btn btn-primary btn-sm" onclick="return confirmar()">{{$movimento['status']}}</a>
                                    @endif
                                    
                                </td>
                                <td><a href="{{route('delMovimento',["id"=>$movimento['id'],"url"=>$url])}}" class="btn btn-danger btn-sm" onclick="return confirmar()">Deletar</a></td>
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
            $('#tbMovimentos').DataTable( {
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
                "order": [[7, "desc"]]
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