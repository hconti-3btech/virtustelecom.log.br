@extends('adminlte::page')

@section('title', 'Visualizar Ordem de Servico')

@section('content_header')
    <h1 class="m-0 text-dark">Visualizar Ordem {{$ordem['ordem']}}</h1>
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
                                    <label class="form-control">{{ $ordem['status'] }}</label>
                                    
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Motivo: </span>
                                    </div>
                                    <textarea class="form-control" rows="3" placeholder="Motivo do cancelamento ou informe" style="height: 102px;" name="motivo">{{$ordem['motivo']}}</textarea>
                                </div>
                                <hr/>
                                @foreach ( $tabelaItensMovimentos as $tabelaItensMovimento)
                                    <div class="row">
                                        <div class="col-12 col-sm-2 col-lg-4 mt-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Produtos: </span>
                                                </div>
                                                <label class="form-control">{{ $tabelaItensMovimento['produto'] }}</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-2 col-lg-2 mt-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">QTD: </span>
                                                </div>
                                                <label class="form-control">{{ $tabelaItensMovimento['qtd'] }}</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-2 col-lg-3 mt-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Controle: </span>
                                                </div>
                                                <label class="form-control">{{ $tabelaItensMovimento['controle'] }}</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-2 col-lg-3 mt-2">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Mac: </span>
                                                </div>
                                                <label class="form-control">{{ $tabelaItensMovimento['mac'] }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    </div>
@stop
