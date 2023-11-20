@extends('adminlte::page')

@section('title', 'Editar Ponto')
    

@section('content_header')
    <h1 class="m-0 text-dark">Editar Ponto</h1>
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
                        <h3 class="card-title">Informações do Ponto</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('ponto.update')}}" enctype="multipart/form-data" onsubmit="bloqSubmit()">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $ponto->id }}">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Serviço: </span>
                                </div>
                                {{-- <input id="id_servico" type="text" class="form-control" placeholder="Serviço" name="id_servico" value="{{old('id_servico') <> null ? old('id_servico') :$ponto->id_servico}}"> --}}
                                <select class="custom-select rounded-0" id="id_servico" name="id_servico">
                                    @foreach ( $servicos as $servico )
                                        <option value='{{$servico->id}}'{{ $servico->id === $ponto->id_servico ? 'selected' :''}}>{{$servico->nome}}</option>    
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-solid fa-asterisk"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Valor: </span>
                                </div>
                                <input id="pontos" type="text" class="form-control" placeholder="Qtd de Pontos (EX: 13)" name="pontos" value="{{old('pontos') <> null ? old('pontos') :$ponto->pontos}}">
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


