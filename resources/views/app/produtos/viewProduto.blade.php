@extends('adminlte::page')

@section('title', 'Produtos')
    
@section('plugins.Datatables', true)

@section('content_header')
    <h1 class="m-0 text-dark">Produtos</h1>
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
                        <div class="col-12 col-lg-7 mt-2"> 
                            <a href="{{route('cadProduto')}}">
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
                            <th>Nome</th>
                            <th>Fabricante</th>
                            <th>Foto</th>
                            <th>Ativo</th>
                            <th>Editar</th>
                            <th>Deletar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produtos as $produto)
                            <tr>
                                <td>{{$produto['id']}} </td>
                                <td>{{$produto['nome']}} </td>
                                <td>{{$produto['fabricante']}} </td>
                                <td>
                                    <a href="{{ url("storage/img/produtos/{$produto['path']}") }}" data-toggle="lightbox" data-title="{{$produto['nome']}}" >
                                        <img width="60" src="{{ url("storage/img/produtos/{$produto['path']}") }}"/>
                                    </a>
                                </td>
                                <td><a href="{{route('desativaProduto',["id"=>$produto['id']])}}" class="btn btn-warning btn-sm">{{$produto['status'] == 'ativo' ? 'SIM': 'NÃO'}} </a>
                                <td><a href="{{route('edtProduto',["id"=>$produto['id']])}}" class="btn btn-primary btn-sm">Editar</a></td>
                                <td><a href="{{route('delProduto',["id"=>$produto['id']])}}" class="btn btn-danger btn-sm" onclick="return confirmar()">Deletar</a></td>
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

    {{-- Galery --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js" ></script>


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