@extends('adminlte::page')

@section('title', 'Metas')
    
@section('plugins.Datatables', true)

@section('content_header')
    <h1 class="m-0 text-dark">Metas</h1>
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
                <form method="GET">
                    <div class="row">    
                        <div class="col-12 col-lg-12 mt-2"> 
                            <a href="{{route('cadMeta')}}" class="pr-2">
                                <button type="button" class="btn  btn-success"><i class="fas fa-solid fa-plus"></i></button>
                            </a>
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
                <table id="produtos" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Descrição</th>
                            <th>Meta(%)</th>
                            <th>Comissao(%)</th>
                            <th>Mês</th>
                            <th>Ano</th>
                            <th>Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($metas as $meta)
                            <tr>
                                <td>{{$meta['id']}} </td>
                                <td>{{$meta['descricao']}} </td>
                                <td>{{$meta['meta']}} </td>
                                <td>{{$meta['comissao']}} </td>
                                <td>{{$meta['mes']}} </td>
                                <td>{{$meta['ano']}} </td>
                                <td><a href="{{route('delMeta',["id"=>$meta['id']])}}" class="btn btn-danger btn-sm" onclick="return confirmar()">Deletar</a></td>
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
            $('#produtos').DataTable( {
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
            } );
        } );
    </script>

    {{-- Gallery --}}
    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
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
@endsection