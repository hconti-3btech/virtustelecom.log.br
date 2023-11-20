@extends('adminlte::page')

@section('title', 'Requisições')

@section('plugins.Datatables', true)
@section('plugins.Inputmask', true)
@section('plugins.pdfMake', true)

@section('content_header')
    <h1 class="m-0 text-dark">Requisições</h1>
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
                            <form method="GET" accept="{{route('viewRequisicao')}}">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="date" class="form-control" id="dateIni" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" inputmode="numeric" name="data_ini">
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
        <div class="card">
            <div class="card-header">
                <table id="tbRequisicao" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requisicoes as $requisicao)
                            <tr>
                                <td>{{$requisicao['id']}} </td>
                                <td>{{$requisicao['origem']}} </td>
                                <td>{{$requisicao['destino']}} </td>
                                <td>{{$requisicao['created_at']}} </td>
                                <td>
                                    @if ($requisicao['status'] == 'AGUARDANDO')
                                        <a href="{{route('aprovRequisicao',["id"=>$requisicao['id'],"status"=>'CONFIRMADO'])}}" class="btn btn-success btn-sm" onclick="return confirmar()"><i class="fas fa-solid fa-thumbs-up"></i></a>
                                        <a href="{{route('aprovRequisicao',["id"=>$requisicao['id'],"status"=>'CANCELADO'])}}" class="btn btn-warning btn-sm" onclick="return confirmar()"><i class="fas fa-solid fa-thumbs-down"></i></a>    
                                    @else
                                        <a href="#" class="btn btn-{{$requisicao['cor']}} btn-sm" onclick="return confirmar()">{{$requisicao['status']}}</a>
                                    @endif
                                    
                                </td>
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
            var table = $('#tbRequisicao').DataTable( {
                "language":{
                    "url":"https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json"
                },
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
                "paging": true,
                // "info": true,
                // "searching": true,
                // "dom": 'Bfrtip',
                // "select": {
                //     info: true
                // },
                "pageLength": 50,
                "order": [[0, "desc"]],
                "buttons": ["copy", "csv", "excel", "pdf",],
                // "columnDefs": [

                //     {
                //         targets: 0,
                //         visible: false,
                //         searchable: true
                //     }
                // ] ,
            });
            
        });

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

    <script>
        //Datemask dd/mm/yyyy
        $('#dateIni').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/aaaa' })
        $('#dateFim').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/aaaa' })

    </script>
@endsection