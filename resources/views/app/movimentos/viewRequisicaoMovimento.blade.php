@extends('adminlte::page')

@section('title', 'Cadastro de Movimento')

@section('plugins.Ajax', true)

@section('content_header')
    <h1 class="m-0 text-dark">Cadastro de Movimento</h1>
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
                        <input type="hidden" name='id' value="{{ $requisicao['id'] }}"/>
                        <div id="campos">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Origem: </span>
                                </div>
                                <label class="custom-select rounded-0"> {{$requisicao['origem']}} </label>
                                <input type="hidden" name="id_origem" value="{{$requisicao['id_origem']}}" />
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Destino: </span>
                                </div>
                                <label class="custom-select rounded-0"> {{$requisicao['destino']}} </label>
                                <input type="hidden" name="id_destino" value="{{$requisicao['id_destino']}}" />
                            </div>
                            <div class="row">
                                @php
                                    $contador = 0;
                                @endphp
                                @if (count($itenRequisicoes) == 0)
                                <div class="col-12">
                                    <div class="input-group mb-3">
                                        Sem Itens
                                    </div>
                                </div>
                                @endif
                                @for ($i = 0; $i < count($itenRequisicoes); $i++) 
                                        
                                    @if ($itenRequisicoes[$i]['unico'] == 's')

                                        @for ($in = 0; $in < $itenRequisicoes[$i]['qtd']; $in++)
                                            <div class="col-12 col-sm-2 col-lg-4 mt-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Produtos: </span>
                                                    </div>      
                                                    <label class="custom-select rounded-0"> {{$itenRequisicoes[$i]['produto']}} </label>
                                                    <input type="hidden" name="produto[{{$contador}}]" value="{{$itenRequisicoes[$i]['id_produto']}}" /> 
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-2 col-lg-2 mt-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">QTD: </span>
                                                    </div>
                                                    <label class="custom-select rounded-0"> 1 </label>               
                                                    <input type="hidden" name="qtd[{{$contador}}]" value="1" />
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-2 col-lg-3 mt-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Controle: </span>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="Controle" id="controle{{$contador}}" name="controle[{{$contador}}]">                
                                                </div>
                                            </div>    
                                            @php
                                                $contador++;
                                            @endphp
                                        @endfor                                            
                                    @else
                                        <div class="col-12 col-sm-2 col-lg-4 mt-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Produtos: </span>
                                                </div>      
                                                <label class="custom-select rounded-0"> {{$itenRequisicoes[$i]['produto']}} </label>
                                                <input type="hidden" name="produto[{{$contador}}]" value="{{$itenRequisicoes[$i]['id_produto']}}" /> 
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-2 col-lg-2 mt-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">QTD: </span>
                                                </div>
                                                <label class="custom-select rounded-0"> {{$itenRequisicoes[$i]['qtd']}} </label>               
                                                <input type="hidden" name="qtd[{{$contador}}]" value="{{$itenRequisicoes[$i]['qtd']}}" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-2 col-lg-3 mt-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Controle: </span>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Controle" id="controle{{$contador}}" name="controle[{{$contador}}]">                
                                            </div>
                                        </div>    
                                        @php
                                            $contador++;
                                        @endphp
                                    @endif                                        
                                @endfor
                            </div>
                        </div>
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

    <script>
        //Datemask dd/mm/yyyy
        $('#dateIni').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/aaaa' })
        $('#dateFim').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/aaaa' })

    </script>


    {{-- Add Campos e Remover Campos --}}
    {{-- <script>
        var cont = 1;
        //https://api.jquery.com/click/
        $("#add-campo").click(function () {
            //https://api.jquery.com/append/
            if (cont <= 10){
                $("#campos").append('<div class="row" id="linha' + cont + '"><div class="col-12 col-sm-2 col-lg-4 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Produtos: </span></div><select class="custom-select rounded-0" placeholder="Produto" id="produto'+ cont +'" name="produto['+ cont +']">@foreach ($produtos as $produto)<option value="{{$produto->id}}"{{ old("produto['+cont+']") === $produto->id ? "selected" :""}}>{{$produto->nome}}</option>@endforeach</select></div></div><div class="col-12 col-sm-2 col-lg-2 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">QTD: </span></div><input type="text" class="form-control" placeholder="Quantidade" name="qtd['+ cont +']"></div></div><div class="col-12 col-sm-2 col-lg-3 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Controle: </span></div><input type="text" class="form-control" placeholder="Controle" id="controle'+ cont +'" name="controle['+ cont +']" ></div></div><div class="col-12 col-sm-2 col-lg-3 mt-2"><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Mac: </span></div><input type="text" class="form-control" placeholder="MAC" id="mac'+ cont +'" name="mac['+ cont +']"></div></div>');
                cont++;
            }else{
                alert('Número máximo de itens por Movimento');
            }                
                
        });

        $("#del-campo").click(function () {
            cont--;
            $('#linha' + cont + '').remove();
            
        });
    </script> --}}

@endsection


