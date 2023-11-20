@extends('adminlte::page')

@section('title', 'Editar Usuario')
    
@section('content_header')
    <h1 class="m-0 text-dark">Editar Usuario</h1>
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
                        <h3 class="card-title">Informações do Usuario</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('edtUserSave')}}" onsubmit="bloqSubmit()">
                            @csrf
                            <input type="hidden" name='id' value="{{$usuario['id']}}">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nome: </span>
                                </div>
                                <input id="descricao" type="text" class="form-control" placeholder="Nome do Usuario" name="name" value="{{$usuario['name']}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Email: </span>
                                </div>
                                <input id="descricao" type="text" class="form-control" placeholder="Email do Usuario" name="email" value="{{$usuario['email']}}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Senha: </span>
                                </div>
                                <input id="descricao" type="text" class="form-control" placeholder="Password" name="password" value="">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Status: </span>
                                </div>
                                <select class="custom-select rounded-0" id="status" name="status">
                                    <option value='ativo' {{ $usuario['status'] == 'ativo'? 'selected' : ''}}>ATIVO</option>
                                    <option value='desativo' {{ $usuario['status'] == 'desativo'? 'selected' : ''}}>DESATIVO</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Nivel de Acesso: </span>
                                </div>
                                <select class="custom-select rounded-0" id="nv_acesso" name="nv_acesso">
                                    <option value='tecnico' {{ $usuario['nv_acesso'] == 'tecnico'? 'selected' : ''}}>TECNICO</option>
                                    <option value='admin' {{ $usuario['nv_acesso'] == 'admin'? 'selected' : ''}}>ADMIN</option>
                                    <option value='almox' {{ $usuario['nv_acesso'] == 'almox'? 'selected' : ''}}>ALMOX</option>
                                    <option value='controle' {{ $usuario['nv_acesso'] == 'controle'? 'selected' : ''}}>CONTROLE</option>
                                    <option value='supervisor' {{ $usuario['nv_acesso'] == 'supervisor'? 'selected' : ''}}>SUPERVISOR</option>
                                    <option value='apoio' {{ $usuario['nv_acesso'] == 'apoio'? 'selected' : ''}}>APOIO</option>
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