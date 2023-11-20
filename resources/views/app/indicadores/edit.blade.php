@extends('adminlte::page')

@section('title', 'Editar de Indicador')
    

@section('content_header')
    <h1 class="m-0 text-dark">Editar de Indicador</h1>
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
                        <h3 class="card-title">Informações do Indicador</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('indicador.store')}}" enctype="multipart/form-data" onsubmit="bloqSubmit()">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $indicador->id }}">             
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Tecnico: </span>
                                </div>
                                <select class="custom-select rounded-0" id="id_servico" name="id_user_tec">
                                    @foreach ( $tecnicos as $tecnico )
                                        <option value='{{$tecnico->id}}'{{ $indicador->id_user_tec === $tecnico->id ? 'selected' :''}}>{{$tecnico->name}}</option>    
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Servico: </span>
                                </div>
                                <select class="custom-select rounded-0" id="id_servico_tipo" name="id_servico_tipo">
                                    @foreach ( $servicos as $servico )
                                        <option value='{{$servico->id}}'{{ $indicador->id_servico_tipo === $servico->id ? 'selected' :''}}>{{$servico->nome}}</option>    
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Mês: </span>
                                </div>
                                <select class="custom-select rounded-0" id="mes" name="mes">
                                    @for($i = 1; $i < 13; $i++)
                                        @if ($i < 10 )
                                            <option value='0{{$i}}'{{ $i === $indicador->mes ? 'selected' :''}}>0{{$i}}</option>                                            
                                        @else
                                            <option value='{{$i}}'{{ $i === $indicador->mes ? 'selected' :''}}>{{$i}}</option>                                            
                                        @endif
                                    @endfor
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Ano: </span>
                                </div>
                                <input id="ano" type="text" class="form-control" placeholder="Ano" name="ano" value="{{$indicador->ano}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Quantidade: </span>
                                </div>
                                <input id="qtd" type="text" class="form-control" placeholder="Quantidade" name="qtd" value="{{$indicador->qtd}}">
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


