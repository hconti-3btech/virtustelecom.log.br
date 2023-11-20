@extends('adminlte::page')

@section('title', 'Cadastro de Requisição')

@section('plugins.Ajax', true)

@section('content_header')
    <h1 class="m-0 text-dark">Cadastro de Requisição</h1>
@stop

@section('content')

    @if(isset($msg) and count($msg) > 1)
        <div class="alert alert-warning">

            @foreach ($msg as $item)
                <p>{{$item}}</p>    
            @endforeach
            
        </div>
    @endif
    
    <div class="row">
        <div class="col-12">
            <div class="container-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger"><pre>{{print_r($errors)}}</pre></div>
                @endif
                
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Movimento</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('addRequisicaoSave')}}" enctype="multipart/form-data" name = "forMovimento" id = "forMovimento" onsubmit="bloqSubmit()">
                            @csrf
                            <div id="campos">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Origem: </span>
                                    </div>
                                    <label class="custom-select rounded-0"> Almoxarifado </label>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Destino: </span>
                                    </div>
                                        <select class="custom-select rounded-0" placeholder="Destino" id="id_destino" name="id_destino">
                                            @foreach ($containers as $container)
                                                <option value='{{$container->id}}'{{ old('id_destino') === $container->id ? 'selected' :''}}>{{$container->nome}}</option>        
                                            @endforeach
                                        </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-2 col-lg-4 mt-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Produtos: </span>
                                            </div>      
                                            <select class="custom-select rounded-0" placeholder="Produto" id="produto0" name="produto[0]">
                                                @foreach ($produtos as $produto)
                                                    <option value='{{$produto->id}}'{{ old('produto[0]') === $produto->id ? 'selected' :''}}>{{$produto->nome}}</option>        
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2 col-lg-4 mt-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">QTD: </span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Quantidade" name="qtd[0]" value="{{ old('qtd[0]') }}">                
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-2 col-lg-1 mt-2"> <button id="add-campo" type="button" class="btn  btn-success"><i class="fas fa-solid fa-plus"></i></button> </div>
                                        <div class="col-2 col-lg-1 mt-2"> <button id="del-campo" type="button" class="btn  btn-danger"><i class="fas fa-solid fa-minus"></i></button> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-success form-control" id="btnSave" onclick="return confirmar()">Salvar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
@stop

@section('js')

    {{-- Add Campos e Remover Campos --}}
    <script>

        //-- Confirma envio de Formularo --
        function confirmar(){
        // só permitirá o envio se o usuário responder OK
        var resposta = window.confirm("Deseja mesmo" + 
                        " enviar o formulário?");
        if(resposta)
            return true;
        else
            return false; 
        }

        //Desabilita botao apos click
        function bloqSubmit(){
            document.getElementById("btnSave").disabled = true;
        }

        
        var cont = 1;
        //https://api.jquery.com/click/
        $("#add-campo").click(function () {
            //https://api.jquery.com/append/
            if (cont <= 10){
                $("#campos").append('<div class="row" id="linha' + cont + '"><div class="col-12 col-sm-2 col-lg-4 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Produtos: </span></div><select class="custom-select rounded-0" placeholder="Produto" id="produto'+ cont +'" name="produto['+ cont +']">@foreach ($produtos as $produto)<option value="{{$produto->id}}"{{ old("produto['+cont+']") === $produto->id ? "selected" :""}}>{{$produto->nome}}</option>@endforeach</select></div></div><div class="col-12 col-sm-2 col-lg-4 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">QTD: </span></div><input type="text" class="form-control" placeholder="Quantidade" name="qtd['+ cont +']"></div></div>');
                cont++;
            }else{
                alert('Número máximo de itens por Movimento');
            }                
                
        });

        $("#del-campo").click(function () {
            cont--;
            $('#linha' + cont + '').remove();
            
        });
    </script>

@endsection


