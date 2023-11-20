@extends('adminlte::page')

@section('title', 'Cadastro de Ordem de Serviço')

@section('plugins.Inputmask', true)

@section('content_header')
    <h1 class="m-0 text-dark">Cadastro de Ordem de Serviço</h1>
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
                        <h3 class="card-title">Informações da Ordem de Serviço</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('cadOrdem')}}" enctype="multipart/form-data" onsubmit="bloqSubmit()">
                            @csrf                
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Ordem: </span>
                                </div>
                                <input id="ordem" type="text" class="form-control" placeholder="Numero da Ordem" name="ordem" value="{{old('ordem')}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Contrato: </span>
                                </div>
                                <input id="cdc" type="text" class="form-control" placeholder="Numero do Contato do Cliente" name="cdc" value="{{old('cdc')}}">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nome do Cliente: </span>
                                </div>
                                <input id="nome_cli" type="text" class="form-control" placeholder="Nome do Cliente" name="nome_cli" value="{{old('nome_cli')}}">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Seriço: </span>
                                </div>
                                <select class="custom-select rounded-0" id="id_servico_tipo" name="id_servico_tipo">
                                    @foreach ( $servicos as $servico )
                                        <option value="{{$servico->id}}" {{ old('id_user_tec') === $servico->id ? 'selected' :''}}>{{$servico->nome}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Técnico: </span>
                                </div>
                                <select class="custom-select rounded-0" id="id_user_tec" name="id_user_tec">
                                    <option value="" {{ old('id_user_tec') === '' ? 'selected' :''}}>Sem Tecnico</option>
                                    @foreach ( $usuarios as $usuario )
                                        <option value="{{$usuario->id}}" {{ old('id_user_tec') === $usuario->id ? 'selected' :''}}>{{$usuario->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Data: </span>
                                </div>
                                <input type="date" class="form-control" id="dateIni" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask="" inputmode="numeric" name="dt_abertura" value="{{ $data_ini }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
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