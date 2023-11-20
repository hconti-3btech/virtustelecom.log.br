@extends('adminlte::page')

@section('title', 'Executar Ordem de Servico')

@section('plugins.Ajax', true)

@section('content_header')
    <h1 class="m-0 text-dark">Executar Ordem {{$ordem['ordem']}}</h1>
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
                        <h3 class="card-title">Informações do Movimento</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('execOrdemSave')}}" enctype="multipart/form-data" name = "forOrdem" id = "forOrdem" data-controle-url="{{route('loadControle')}}" data-mac-url="{{route('loadMac')}}">
                            @csrf
                            <div id="campos">
                                <input type="hidden" name="id" value="{{ $ordem['id'] }}" />
                                <input type="hidden" name="id_user_tec" value="{{ $ordem['id_user_tec'] }}" />
                                <input type="hidden" name="ordem" value="{{ $ordem['ordem'] }}" />
                                <input type="hidden" name="url" value="{{ $url }}" />
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Ordem: </span>
                                    </div>
                                    <label class="form-control">{{ $ordem['ordem'] }}</label>
                                    
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Num. Contrato: </span>
                                    </div>
                                    <label class="form-control">{{ $ordem['cdc'] }}</label>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Nome do Cliente: </span>
                                    </div>
                                    <label class="form-control">{{ $ordem['cliente'] }}</label>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Seriço: </span>
                                    </div>
                                    <label class="form-control">{{ $ordem['servico'] }}</label>
                                    
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Técnico: </span>
                                    </div>
                                    <label class="form-control">{{ $ordem['tecnico'] }}</label>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Data: </span>
                                    </div>
                                    <label class="form-control">{{ $ordem['data_abe'] }}</label>
                                    
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Status: </span>
                                    </div>
                                    <select class="custom-select rounded-0" placeholder="Controle" id="status" name="status">
                                        <option value="aberta" {{ 'aberta' === $ordem['status'] ? "selected" :""}}>Aberta</option>
                                        <option value="finalizada" {{ 'finalizada'  === $ordem['status'] ? "selected" :""}}>Finalizada</option>
                                        <option value="atribuida" {{ 'atribuida'  === $ordem['status'] ? "selected" :""}}>Atribuida</option>
                                        <option value="informada" {{ 'informada'  === $ordem['status'] ? "selected" :""}}>Informada</option>
                                        <option value="cancelada" {{ 'cancelada'  === $ordem['status'] ? "selected" :""}}>Cancelada</option>
                                    </select>
                                    
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Motivo: </span>
                                    </div>
                                    <textarea class="form-control" rows="3" placeholder="Motivo do cancelamento ou informe" style="height: 102px;" name="motivo"></textarea>
                                </div>
                                <hr/>
                                <label>Materias Usados</label>
                                <div class="row">
                                    <div class="col-12 col-sm-2 col-lg-4 mt-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Produtos: </span>
                                            </div>
                                            <select class="custom-select rounded-0" placeholder="Produto" id="produto0" name="produto[0]">
                                                <option value="">Selecione o Produto</option>
                                                @foreach ($produtos as $produto)
                                                    <option value="{{$produto->id}}"{{ old("produto[0]") === $produto->id ? "selected" :""}}>{{$produto->nome}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2 col-lg-2 mt-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">QTD: </span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Quantidade" name="qtd[0]" value="1">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2 col-lg-3 mt-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Controle: </span>
                                            </div>
                                            <select class="custom-select rounded-0" placeholder="Controle" id="controle0" name="controle[0]">
                                                <option value="">Selecione</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2 col-lg-3 mt-2">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Mac: </span>
                                            </div>
                                            <select class="custom-select rounded-0" placeholder="Controle" id="mac0" name="mac[0]">
                                                <option value="">Selecione</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-2 col-lg-1 mt-2"> <button id="add-campo" type="button" class="btn  btn-success"><i class="fas fa-solid fa-plus"></i></button> </div>
                                        <div class="col-2 col-lg-1 mt-2"> <button id="del-campo" type="button" class="btn  btn-danger"><i class="fas fa-solid fa-minus"></i></button> </div>
                                    </div>
                                </div>
                            </div>
                            <div id="campos-retirada">
                                <hr/>
                                <label>Materias Retirados</label>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-2 col-lg-1 mt-2"> <button id="add-campo-retirada" type="button" class="btn  btn-success"><i class="fas fa-solid fa-plus"></i></button> </div>
                                        <div class="col-2 col-lg-1 mt-2"> <button id="del-campo-retirada" type="button" class="btn  btn-danger"><i class="fas fa-solid fa-minus"></i></button> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-success form-control" onclick="return confirmar()" >Salvar</button>
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

    {{-- Confirma envio de Formularo --}}
    <script>
        function confirmar(){
        // só permitirá o envio se o usuário responder OK
        var resposta = window.confirm("Deseja mesmo" + 
                        " enviar o formulário?");
        if(resposta)
            return true;
        else
            return false; 
        }
    </script>


    {{-- Add Campos e Remover Campos --}}
    <script>
        var cont = 1;
        //https://api.jquery.com/click/
        $("#add-campo").click(function () {
            //https://api.jquery.com/append/
            if (cont <= 10){
                $("#campos").append('<div class="row" id="linha' + cont + '"><div class="col-12 col-sm-2 col-lg-4 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Produtos: </span></div><select class="custom-select rounded-0" placeholder="Produto" id="produto'+ cont +'" name="produto['+ cont +']" onchange="produtoChange'+ cont +'(this)"><option value="">Selecione o Produto</option></option>@foreach ($produtos as $produto)<option value="{{$produto->id}}"{{old("produto['+cont+']")===$produto->id ? "selected" :""}}>{{$produto->nome}}</option>@endforeach</select></div></div><div class="col-12 col-sm-2 col-lg-2 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">QTD: </span></div><input type="text" class="form-control" placeholder="Quantidade" name="qtd['+ cont +']" value="1"></div></div><div class="col-12 col-sm-2 col-lg-3 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Controle: </span></div><select class="custom-select rounded-0" placeholder="Controle" id="controle'+ cont +'" name="controle['+ cont +']" onchange="controleChange'+ cont +'(this)"> <option value="">Selecione</option></select></div></div><div class="col-12 col-sm-2 col-lg-3 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Mac: </span></div><select class="custom-select rounded-0" placeholder="MAC" id="mac'+ cont +'" name="mac['+ cont +']"> <option value="">Selecione</option></select></div></div></div><hr/>');
                cont++;
            }else{
                alert('Número máximo de itens por Movimento');
            }                
                
        });

        $("#del-campo").click(function () {
            cont--;
            $('#linha' + cont + '').remove();
            
        });
    </script>

    {{-- Add Campos e Remover Campos Retirada--}}
    <script>
        var cont2 = 0;
        //https://api.jquery.com/click/
        $("#add-campo-retirada").click(function () {
            //https://api.jquery.com/append/
            if (cont2 <= 10){
                $("#campos-retirada").append('<div class="row" id="linha-retirada' + cont2 + '"><div class="col-12 col-sm-2 col-lg-4 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Produtos:</span></div><select class="custom-select rounded-0" placeholder="Produto" name="produto-retirada['+ cont2 +']"><option value="">Selecione o Produto</option><option value="999">RETIRADA - ONU NOKIA/HUAWEI/FIBERHOME</option></select></div></div><div class="col-12 col-sm-2 col-lg-2 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">QTD:</span></div><input type="text" class="form-control" placeholder="Quantidade" name="qtd-retirada['+ cont2 +']" value="1"></div></div><div class="col-12 col-sm-2 col-lg-3 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Controle:</span></div><input type="text" class="form-control" placeholder="Controle" name="controle-retirada['+ cont2 +']"></div></div><div class="col-12 col-sm-2 col-lg-3 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Mac:</span></div><input type="text" class="form-control" placeholder="MAC" name="mac-retirada['+ cont2 +']"></div></div></div><hr/>');
                cont2++;
            }else{
                alert('Número máximo de itens por Movimento');
            }                
                
        });
    
        $("#del-campo-retirada").click(function () {
            cont2--;
            $('#linha-retirada' + cont2 + '').remove();
            
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
        
            $("#produto0").change(function(){
                let url = $('#forOrdem').attr("data-controle-url");
                let id_produto = $('#produto0').val();

                $.ajax({
                    url : url,
                    data: {
                        'id_produto': id_produto,
                        'id_usuario': {{$user->id}}
                    },
                    success: function(data){
                        $("#controle0").html(data);
                    }
                });
            });
        
            

            $("#controle0").change(function(){
                let url = $('#forOrdem').attr("data-mac-url");
                let controle = $('#controle0').val();
                let id_produto = $('#produto0').val();
                

                $.ajax({
                    url : url,
                    data: {
                        'controle' : controle,
                        'id_produto' : id_produto,
                    },
                    success: function(data){
                        $("#mac0").html(data);
                    }
                });
            });

        });
        
        //produto
        @for ($i = 1; $i < 10; $i++)

            function produtoChange{{$i}}(produto){
                let url = $('#forOrdem').attr("data-controle-url");
                let id_produto = $("#produto"+{{$i}}).val();

                $.ajax({
                    url : url,
                    data: {
                        'id_produto': id_produto,
                        'id_usuario': {{$user->id}}
                    },
                    success: function(data){
                        $("#controle"+{{$i}}).html(data);
                    }
                });
            }

        @endfor

            //produto
        @for ($i = 1; $i < 10; $i++)

            function controleChange{{$i}}(produto){
                let url = $('#forOrdem').attr("data-mac-url");
                let id_produto = $("#produto"+{{$i}}).val();
                let controle = $('#controle'+{{$i}}).val();

                $.ajax({
                    url : url,
                    data: {
                        'controle' : controle,
                        'id_produto' : id_produto,
                    },
                    success: function(data){
                        $("#mac"+{{$i}}).html(data);
                    }
                });
            }

        @endfor


        

    </script>

@endsection


