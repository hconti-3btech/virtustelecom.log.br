@extends('adminlte::page')

@section('title', 'Cadastro de Container')
    

@section('content_header')
    <h1 class="m-0 text-dark">Cadastro de Container</h1>
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
                        <h3 class="card-title">Informações do Container</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('edtContainerSave')}}" onsubmit="bloqSubmit()">
                            @csrf
                            <input type="hidden" name='id' value="{{$containerEdt['id']}}">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nome: </span>
                                </div>
                                <input id="descricao" type="text" class="form-control" placeholder="Nome do Container" name="nome" value="{{$containerEdt['nome']}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Pai: </span>
                                </div>
                                <select class="custom-select rounded-0" id="pai" name="pai">
                                    @foreach ($containers as $container)
                                        <option value='{{$container['id']}}' {{ $containerEdt['pai'] == $container['id'] ? 'selected' : ''}}>{{$container['nome']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Ativo: </span>
                                </div>
                                <select class="custom-select rounded-0" id="especial" name="status">
                                    <option value='ativo' {{ $containerEdt['status'] == 'ativo'? 'selected' : ''}}>SIM</option>
                                    <option value='desativo' {{ $containerEdt['status'] == '0'? 'selected' : ''}}>NÃO</option>
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


