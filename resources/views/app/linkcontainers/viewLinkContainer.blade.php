@extends('adminlte::page')

@section('title', 'Link Container')

@section('plugins.Datatables', true)

@section('content_header')
    <h1 class="m-0 text-dark">Link Container</h1>
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
                            <a href="{{route('cadLinkContainer')}}" class="pr-2">
                                <button type="button" class="btn  btn-success"><i class="fas fa-solid fa-plus"></i></button>
                            </a>
                            <a href="{{route('copyLinkContainer')}}" class="pr-2">
                                <button type="button" class="btn  btn-primary"><i class="fas fa-regular fa-copy"></i></i></button>
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
                <table id="containers" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Container</th>
                            <th>Pai</th>
                            <th>Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tabelaItens as $tabelaIten)
                            <tr>
                                <td>{{$tabelaIten['id']}} </td>    
                                <td>{{$tabelaIten['nome']}} </td>
                                <td>{{$tabelaIten['pai']}} </td>
                                {{-- <td><a href="#" class="btn btn-danger btn-sm" onclick="return confirmar()">Deletar</a></td> --}}
                                <td><a href="{{route('delLinkContainer',["id"=>$tabelaIten['id']])}}" class="btn btn-danger btn-sm" onclick="return confirmar()">Deletar</a></td>
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
            $('#containers').DataTable( {
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
                    }],
            })
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