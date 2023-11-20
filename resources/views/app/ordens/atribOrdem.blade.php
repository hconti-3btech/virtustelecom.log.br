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
                        <form method="post" action="{{route('atribOrdem')}}" enctype="multipart/form-data" onsubmit="bloqSubmit()">
                            @csrf                
                            <input type="hidden" name="id" value="{{ $ordem['id'] }}" />
                            <input type="hidden" name="url" value="{{ $url }}" />
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Ordem: </span>
                                </div>
                                <label class="custom-select rounded-0">{{ $ordem['ordem'] }}</label>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Num. Contrato: </span>
                                </div>
                                <label class="custom-select rounded-0">{{ $ordem['cdc'] }}</label>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nome do Cliente: </span>
                                </div>
                                <label class="custom-select rounded-0">{{ $ordem['cliente'] }}</label>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Seriço: </span>
                                </div>
                                <label class="custom-select rounded-0">{{ $ordem['servico'] }}</label>
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
                                        <option value="{{$usuario->id}}" {{ $ordem['tecnico'] === $usuario->id ? 'selected' :''}}>{{$usuario->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Data: </span>
                                </div>
                                <label class="custom-select rounded-0">{{ $ordem['data_abe'] }}</label>
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