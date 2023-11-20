@extends('adminlte::page')

@section('title', 'Cadastro de Movimento')
    

@section('content_header')
    <h1 class="m-0 text-dark">Entrada de Estoque</h1>
@stop

@section('content')
    
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
                        <form method="post" action="{{route('cadMovimento')}}" enctype="multipart/form-data">
                            @csrf
                            <div id="campos">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Origem: </span>
                                    </div>
                                    <select class="custom-select rounded-0" id="id_origem" name="id_origem">
                                            <option value='2'{{ old('id_origem') === 2 ? 'selected' :''}}>Entrada</option>    
                                            <option value='3'{{ old('id_origem') === 2 ? 'selected' :''}}>Saida</option>    
                                            <option disabled>─────────────────────────────────────</option>
                                        @foreach ( $containers as $container)
                                            @if ($container['id'] > 3)
                                                <option value='{{$container['id']}}'{{ old('id_origem') === $container['id'] ? 'selected' :''}}>{{$container['nome']}}</option>        
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Destino: </span>
                                    </div>
                                    <select class="custom-select rounded-0" id="id_destino" name="id_destino">
                                        <option value='2'{{ old('id_origem') === 2 ? 'selected' :''}}>Entrada</option>    
                                        <option value='3'{{ old('id_origem') === 2 ? 'selected' :''}}>Saida</option>
                                        <option disabled>─────────────────────────────────────</option>
                                        @foreach ( $containers as $container)
                                            @if ($container['id'] > 3)
                                                <option value='{{$container['id']}}'{{ old('id_origem') === $container['id'] ? 'selected' :''}}>{{$container['nome']}}</option>        
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Centro de Custo: </span>
                                    </div>
                                    <select class="custom-select rounded-0" id="cdc" name="id_cdc">
                                        @foreach ( $cdcs as $cdc)
                                            <option value='{{$cdc['id']}}'{{ old('cdc') === $cdc['id'] ? 'selected' :''}}>{{$cdc['nome']}}</option>    
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
                                            <select class="custom-select rounded-0" id="produto0" name="produto[0]">
                                                @foreach ($produtos as $produto)
                                                    <option value='{{$produto['id']}}'{{ old('produto[0]') === $produto['id'] ? 'selected' :''}}>{{$produto['nome']}}</option>  
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
                                    <div class="col-12 col-sm-2 col-lg-4 mt-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Controle: </span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Controle" name="controle[0]" value="{{ old('controle[0]') }}">                  
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
                                            <button class="btn btn-success form-control" onclick="return confirmar()" >Salvar</button>
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


    {{-- Add Campos e Remover Campos --}}
    <script>
        var cont = 1;
        //https://api.jquery.com/click/
        $("#add-campo").click(function () {
            //https://api.jquery.com/append/
                $("#campos").append('<div class="row" id="linha' + cont + '"><div class="col-12 col-sm-2 col-lg-4 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Produtos: </span></div><select class="custom-select rounded-0" id="produto'+ cont +'" name="produto['+ cont +']" ><?=$optProdutos?></select></div></div><div class="col-12 col-sm-2 col-lg-4 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">QTD: </span></div><input type="text" class="form-control" placeholder="Quantidade" name="qtd['+ cont +']"></div></div><div class="col-12 col-sm-2 col-lg-4 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Controle: </span></div><input type="text" class="form-control" placeholder="Controle" name="controle['+ cont +']"></div></div>');
                cont++;
                
        });

        $("#del-campo").click(function () {
            cont--;
            $('#linha' + cont + '').remove();
            
        });
    </script>

@endsection


