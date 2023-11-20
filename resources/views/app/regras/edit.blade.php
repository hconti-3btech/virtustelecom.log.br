@extends('adminlte::page')

@section('title', 'Editar de Regra')
    

@section('content_header')
    <h1 class="m-0 text-dark">Editar de Regra</h1>
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
                        <h3 class="card-title">Informações da Regra</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('regra.update')}}" enctype="multipart/form-data" onsubmit="bloqSubmit()">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $regra->id }}">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">QTD de Pontos: </span>
                                </div>
                                <input id="qtd_pontos" type="text" class="form-control" placeholder="Quantidade de Pontos (EX: 1200)" name="qtd_pontos" value="{{old('qtd_pontos') <> null ? old('qtd_pontos') :$regra->qtd_pontos}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Valor: </span>
                                </div>
                                <input id="valor" type="text" class="form-control" placeholder="Valor pago (EX: 0.25)" name="valor" value="{{old('valor') <> null ? old('valor') :$regra->valor}}">
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


