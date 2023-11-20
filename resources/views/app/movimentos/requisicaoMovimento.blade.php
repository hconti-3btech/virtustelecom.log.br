@extends('adminlte::page')

@section('title', 'Cadastro de Movimento')

@section('plugins.Ajax', true)

@section('content_header')
    <h1 class="m-0 text-dark">Cadastro de Movimento</h1>
@stop

@section('content')

    @if(isset($msg) and count($msg) > 0)
        <div class="alert alert-warning">

            @foreach ($msg as $item)
                <p>{{$item}}</p>    
            @endforeach
            
        </div>
    @endif
    
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
                        <form method="post" action="{{route('requisicaoMovimentoSave')}}" enctype="multipart/form-data" name = "forMovimento" id = "forMovimento" onsubmit="bloqSubmit()">
                            @csrf
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
                                    @for ($i = 0; $i < count($itenRequisicoes); $i++) 
                                            
                                        @if ($itenRequisicoes[$i]['unico'] == 's')

                                            @for ($in = 0; $in < $itenRequisicoes[$i]['qtd']; $in++)
                                                <div class="col-12 col-sm-12 col-lg-6 mt-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Produtos: </span>
                                                        </div>      
                                                        <label class="form-control" > {{$itenRequisicoes[$i]['produto']}} </label>
                                                        <input type="hidden" name="produto[{{$contador}}]" value="{{$itenRequisicoes[$i]['id_produto']}}" /> 
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-lg-2 mt-2">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">QTD: </span>
                                                        </div>
                                                        <input type="text"  class="form-control"  name="qtd[{{$contador}}]" value="1" />         
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-lg-3 mt-2">
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
                                            <div class="col-12 col-sm-12 col-lg-6 mt-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Produtos: </span>
                                                    </div>      
                                                    <label class="form-control"> {{$itenRequisicoes[$i]['produto']}} </label>
                                                    <input type="hidden" name="produto[{{$contador}}]" value="{{$itenRequisicoes[$i]['id_produto']}}" /> 
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-2 mt-2">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">QTD: </span>
                                                    </div>
                                                    {{-- <label class="custom-select rounded-0"> {{$itenRequisicoes[$i]['qtd']}} </label>                --}}
                                                    <input type="text"  class="form-control"  name="qtd[{{$contador}}]" value="{{$itenRequisicoes[$i]['qtd']}}" />
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-3 mt-2">
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
                            <div class="row">
                                <div class="col-12">
                                    {{-- <div class="row">
                                        <div class="col-2 col-lg-1 mt-2"> <button id="add-campo" type="button" class="btn  btn-success"><i class="fas fa-solid fa-plus"></i></button> </div>
                                        <div class="col-2 col-lg-1 mt-2"> <button id="del-campo" type="button" class="btn  btn-danger"><i class="fas fa-solid fa-minus"></i></button> </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-12 mt-2">
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

        //Datemask dd/mm/yyyy
        $('#dateIni').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/aaaa' })
        $('#dateFim').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/aaaa' })

    </script>

@endsection


