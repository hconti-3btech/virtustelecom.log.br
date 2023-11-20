@extends('adminlte::page')

@section('title', 'Editar Servico')
    

@section('content_header')
    <h1 class="m-0 text-dark">Editar Servico</h1>
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
                        <h3 class="card-title">Informações do Serviço</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('edtServicoSave')}}" onsubmit="bloqSubmit()">
                            @csrf
                            <input type="hidden" name='id' value="{{$servico['id']}}">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nome: </span>
                                </div>
                                <input id="descricao" type="text" class="form-control" placeholder="Nome do Container" name="nome" value="{{$servico['nome']}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Ativo: </span>
                                </div>
                                <select class="custom-select rounded-0" id="status" name="status">
                                    <option value='ativo' {{ $servico['status'] == 'ativo'? 'selected' : ''}}>SIM</option>
                                    <option value='desativo' {{ $servico['status'] == 'desativo'? 'selected' : ''}}>NÃO</option>
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

    </script>
@endsection