@extends('adminlte::page')

@section('title', 'Cadastro de Produtos')
    

@section('content_header')
    <h1 class="m-0 text-dark">Cadastro de Produtos</h1>
@stop

@section('content')
    
    <div class="row">
        <div class="col-12">
            <div class="container-fluid">
               
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger"><pre>{{$error}}</pre></div>    
                    @endforeach
                @endif
                
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Produto</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('edtProduto')}}" enctype="multipart/form-data" onsubmit="bloqSubmit()">
                            @csrf
                            <input type="hidden" name="id" value="{{ $produto->id }}">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nome: </span>
                                </div>
                                <input id="descricao" type="text" class="form-control" placeholder="Nome do Produto Ex: (AMT 2018 EG)" name="nome" value="{{ $produto->nome }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Descriçao: </span>
                                </div>
                                <textarea name="descricao" class="form-control" rows="3" placeholder="Detalhes do Produto Ex (Central de alarme monitorada com 18 zonas) ...">{{$produto->descricao}}</textarea>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Unidade: </span>
                                </div>
                                <select class="custom-select rounded-0" id="especial" name="un">
                                    <option value='UN'{{ $produto->un === 'UN'? 'selected' :''}}>Unidade</option>
                                    <option value='PC'{{ $produto->un === 'PC'? 'selected' :''}}>Peça</option>
                                    <option value='CX'{{ $produto->un === 'CX'? 'selected' :''}}>Caixa</option>
                                    <option value='RL'{{ $produto->un === 'RL'? 'selected' :''}}>Rolo</option>
                                    <option value='M' {{ $produto->un === 'M'? 'selected' :''}}>Metro</option>
                                    <option value='CM'{{ $produto->un === 'CM'? 'selected' :''}}>Centimetro</option>
                                    <option value='MM'{{ $produto->un === 'MM'? 'selected' :''}}>Milimetro</option>
                                    <option value='L' {{ $produto->un === 'L'? 'selected' :''}}>Litro</option>
                                    <option value='KG'{{ $produto->un === 'KG'? 'selected' :''}}>Kilo Grama</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Fabricante: </span>
                                </div>
                                <input id="taxa" type="text" class="form-control" placeholder="Fabricante do Produto" name="fabricante" value="{{$produto->fabricante}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Minimo: </span>
                                </div>
                                <input id="taxa" type="text" class="form-control" placeholder="Estoque Minimo" name="est_min" value="{{$produto->est_min}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Pedido: </span>
                                </div>
                                <input id="taxa" type="text" class="form-control" placeholder="Quantidade do Pedido Automatico" name="est_pedido" value="{{$produto->est_pedido}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Foto: </span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imgFile" accept="image/*" name="imgFile">
                                    <label class="custom-file-label" for="imgFile" >Selecione a imagem</label>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Unico?: </span>
                                </div>
                                <select class="custom-select rounded-0" id="especial" name="unico">
                                    <option value='s'{{ $produto->unico === 's'? 'selected' :''}}>SIM</option>
                                    <option value='n'{{ $produto->unico === 'n'? 'selected' :''}}>NÃO</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Ativo: </span>
                                </div>
                                <select class="custom-select rounded-0" id="especial" name="status">
                                    <option value='ativo' {{ $produto->status == 'ativo'? 'selected' : ''}}>SIM</option>
                                    <option value='desativo' {{ $produto->status == 'desativo'? 'selected' : ''}}>NÃO</option>
                                </select>
                                
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
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

        var div = document.getElementsByClassName("custom-file-label")[0];
        var input = document.getElementById("imgFile");

        div.addEventListener("click", function(){
            input.click();
        });
        input.addEventListener("change", function(){
            var nome = "Não há arquivo selecionado. Selecionar arquivo...";
            if(input.files.length > 0) nome = input.files[0].name;
            div.innerHTML = nome;
        });

    </script>

@endsection


