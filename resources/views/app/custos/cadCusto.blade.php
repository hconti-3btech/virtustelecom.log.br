@extends('adminlte::page')

@section('title', 'Cadastro de Custos por Técnico')
    

@section('content_header')
    <h1 class="m-0 text-dark">Cadastro de Custos por Técnico</h1>
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
                        <h3 class="card-title">Informação de Custo</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('cadCusto')}}" enctype="multipart/form-data" onsubmit="bloqSubmit()">
                            @csrf                
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Mês: </span>
                                </div>
                                <select class="custom-select rounded-0" id="mes" name="mes">
                                    <option value='01'>Janeiro</option>
                                    <option value='02'>Fevereiro</option>
                                    <option value='03'>Março</option>
                                    <option value='04'>Abril</option>
                                    <option value='05'>Maio</option>
                                    <option value='06'>Junho</option>
                                    <option value='07'>Julho</option>
                                    <option value='08'>Agosto</option>
                                    <option value='09'>Setembro</option>
                                    <option value='10'>Outubro</option>
                                    <option value='11'>Novembro</option>
                                    <option value='12'>Dezembro</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Ano: </span>
                                </div>
                                <input id="ano" type="text" class="form-control" placeholder="ano" name="ano" value="{{date('Y')}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Valor: </span>
                                </div>
                                <input id="taxa" type="text" class="form-control" placeholder="valor" name="valor">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-dollar-sign"></i></span>
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
        var resposta = window.confirm("Deseja mesmo" +  " enviar o formulário?");

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


