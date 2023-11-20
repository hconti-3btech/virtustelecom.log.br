@extends('adminlte::page')

@section('title', 'Cadastro de Link Container')
    

@section('content_header')
    <h1 class="m-0 text-dark">Cadastro de Link Container</h1>
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
                        <form method="post" action="{{route('cadLinkContainer')}}" onsubmit="bloqSubmit()">
                            @csrf                
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Container: </span>
                                </div>
                                <select class="custom-select rounded-0" id="container" name="container">
                                    @foreach ($containers as $container)
                                        <option value='{{$container['id']}}' {{ old('container') == $container['id'] ? 'selected' : ''}}>{{$container['nome']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Pai: </span>
                                </div>
                                <select class="custom-select rounded-0" id="pai" name="pai">
                                    @foreach ($users as $user)
                                        <option value='{{$user['id']}}' {{ old('pai') == $user['id'] ? 'selected' : ''}}>{{$user['name']}}</option>
                                    @endforeach
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


