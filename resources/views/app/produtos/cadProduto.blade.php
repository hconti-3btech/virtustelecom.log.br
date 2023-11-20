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
                    <div class="alert alert-danger"><pre>{{print_r($errors)}}</pre></div>
                @endif
                
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Produto</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('cadProduto')}}" enctype="multipart/form-data" onsubmit="bloqSubmit()">
                            @csrf                
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nome: </span>
                                </div>
                                <input id="descricao" type="text" class="form-control" placeholder="Nome do Produto Ex: (AMT 2018 EG)" name="nome">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Descriçao: </span>
                                </div>
                                <textarea name="descricao" class="form-control" rows="3" placeholder="Detalhes do Produto Ex (Central de alarme monitorada com 18 zonas) ..."></textarea>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Unidade: </span>
                                </div>
                                <select class="custom-select rounded-0" id="especial" name="un">
                                    <option value='UN'{{ old('un') === 'UN'? 'selected' :''}}>Unidade</option>
                                    <option value='PC'{{ old('un') === 'PC'? 'selected' :''}}>Peça</option>
                                    <option value='CX'{{ old('un') === 'CX'? 'selected' :''}}>Caixa</option>
                                    <option value='RL'{{ old('un') === 'RL'? 'selected' :''}}>Rolo</option>
                                    <option value='M' {{ old('un') === 'M'? 'selected' :''}}>Metro</option>
                                    <option value='CM'{{ old('un') === 'CM'? 'selected' :''}}>Centimetro</option>
                                    <option value='MM'{{ old('un') === 'MM'? 'selected' :''}}>Milimetro</option>
                                    <option value='L' {{ old('un') === 'L'? 'selected' :''}}>Litro</option>
                                    <option value='KG'{{ old('un') === 'KG'? 'selected' :''}}>Kilo Grama</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Fabricante: </span>
                                </div>
                                <input id="taxa" type="text" class="form-control" placeholder="Fabricante do Produto" name="fabricante">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Minimo: </span>
                                </div>
                                <input id="taxa" type="text" class="form-control" placeholder="Estoque Minimo" name="est_min">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Pedido: </span>
                                </div>
                                <input id="taxa" type="text" class="form-control" placeholder="Quantidade do Pedido Automatico" name="est_pedido">
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
                                    <span class="input-group-text">Unico ?: </span>
                                </div>
                                <select class="custom-select rounded-0" id="especial" name="unico">
                                    <option value='s'{{ old('unico') === 's'? 'selected' :''}}>SIM</option>
                                    <option value='n'{{ old('unico') === 'n'? 'selected' :''}}>NÃO</option>
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
                                    <option value='ATIVO' {{ old('status') == 'ATIVO'? 'selected' : ''}}>ATIVO</option>
                                    <option value='DESATIVO' {{ old('status') == 'DESATIVO'? 'selected' : ''}}>DESATIVO</option>
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

        //apresentar nome arquivo no input
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


