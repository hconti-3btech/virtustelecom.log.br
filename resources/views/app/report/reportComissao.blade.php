@extends('adminlte::page')

@section('title', 'Relatorio Bonus')

@section('plugins.Chart', true)

@section('plugins.Datatables', true)

@section('plugins.pdfMake', true)

@section('content_header')
    <h1 id="h01" class="m-0 text-dark"><b>{{$nomeTec}}</b></h1>
@stop

@section('content')

@if(session('msg'))
    <div class="alert alert-{{session('type')}}">
        <p>{{session('msg')}}</p>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <form method="POS">
                    <div class="row">    
                        <div class="col-12 col-lg-12 mt-2"> 
                            <form method="GET" accept="{{route('reportProducao')}}">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-user"></i></span>
                                                </div>
                                                <select class="custom-select rounded-0" id="tecnico" name="tecnico">
                                                        <option value=''>Todos</option>
                                                    @foreach ($tecnicos as $tecnico)
                                                        <option value='{{$tecnico['id']}}' {{ old('tecnico') == $tecnico['id'] ? 'selected' : ''}}>{{$tecnico['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <select class="custom-select rounded-0" id="mes" name="mes">
                                                    <option value='{{DATE('m')}}' selected >{{strftime('%B')}}</option>
                                                    <option value='01'{{ old('mes') == '01' ? 'selected' : ''}}>Janeiro</option>
                                                    <option value='02'{{ old('mes') == '02' ? 'selected' : ''}} >Fevereiro</option>
                                                    <option value='03'{{ old('mes') == '03' ? 'selected' : ''}} >Março</option>
                                                    <option value='04'{{ old('mes') == '04' ? 'selected' : ''}} >Abril</option>
                                                    <option value='05'{{ old('mes') == '05' ? 'selected' : ''}} >Maio</option>
                                                    <option value='06'{{ old('mes') == '06' ? 'selected' : ''}} >Junho</option>
                                                    <option value='07'{{ old('mes') == '07' ? 'selected' : ''}} >Julho</option>
                                                    <option value='08'{{ old('mes') == '08' ? 'selected' : ''}} >Agosto</option>
                                                    <option value='09'{{ old('mes') == '09' ? 'selected' : ''}} >Setembro</option>
                                                    <option value='10'{{ old('mes') == '10' ? 'selected' : ''}} >Outubro</option>
                                                    <option value='11'{{ old('mes') == '11' ? 'selected' : ''}} >Novembro</option>
                                                    <option value='12'{{ old('mes') == '12' ? 'selected' : ''}} >Dezembro</option>
                                            </select>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ano" name="ano" value="{{ DATE('Y')}}">
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="submit" class="btn  btn-primary" onclick="return confirmar()"><i class="fas fa-solid fa-filter"></i></i></button>
                                        <button type="button" class="btn  btn-success ms-3" id="print"> <i class="fas fa-solid fa-print"></i></i></button>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>        
                </form>    
            </div>    
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Serviços Realizados de {{$dt_ini->format('d/m/Y')}} até {{$dt_fim->format('d/m/Y')}}:</h3>
                
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>                
            </div>

            <div class="card-body">
                <table id="tblMetas" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>TIPO</th>
                            <th>QTD Servico</th>
                            <th>QTD Pontos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicos as $servico)
                            <tr>
                                <td>{{$servico['nome']}}</td>
                                <td>{{$servico['qtdServico']}}</td>
                                <td>{{$servico['qtdPontos']}}</td>
                            </tr>    
                        @endforeach
                        @foreach ( $indicadores as $indicador)
                            <tr>
                                <td>{{$indicador['servico']}}</td>
                                <td>{{$indicador['qtd']}}</td>
                                <td>{{ !isset($indicador['qtdPontos'])? '0' : $indicador['qtdPontos']}}</td>
                            </tr>    
                        @endforeach
                          
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
             <h3><sup style="font-size: 20px"></sup>{{$totalPontos}}</h3>
                <p>Pontos</p>
            </div>
        <div class="icon">
            <i class="nav-icon fas fa-solid fa-piggy-bank"></i>
        </div>
        <a href="#" class="small-box-footer">Total de Pontos</a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
            <h3><sup style="font-size: 20px">R$ </sup>{{$totalReceber}}</h3>
                <p>Valor Bonus</p>
            </div>
        <div class="icon">
            <i class="nav-icon fas fa-solid fa-money-bill-wave"></i>
        </div>
            <a href="#" class="small-box-footer">Valor a Receber</a>
        </div>
    </div>
    
</div>

<div class="row">
    <div class="col-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Pontos:</h3>
                
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>                
            </div>

            <div class="card-body">
                <table id="tblMetas" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Serviço</th>
                            <th>Valor Ponto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pontos as $ponto)
                            <tr>
                                <td>{{$ponto['servico']}}</td>
                                <td>{{$ponto['pontos']}}</td>
                            </tr>    
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Regra:</h3>
                
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>                
            </div>

            <div class="card-body">
                <table id="tblMetas" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>QTD de Ponto</th>
                            <th>Valor PG por Ponto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($regras as $regra)
                            <tr>
                                <td>{{$regra['qtd_pontos']}}</td>
                                <td>{{$regra['valor']}}</td>
                            </tr>    
                        @endforeach
                    </tbody>
                </table>
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

        $('#print').click(function () {
                if (confirm('Imprimir?')) {

                    const p = {
                        table: {
                            body: [
                                ['Nome','QTD'],
                                @foreach ($pontos as $ponto)
                                ['{{$ponto['servico']}}','{{$ponto['pontos']}}'],   
                                @endforeach
                            ],
                            fontSize: 8,
                            layout: 'lightHorizontalLines', // Adicione um layout à tabela
                            margin: [0, 10, 0, 0], // Adicione margem superior para separar o título da tabela
                            alignment: 'center', // Centralize o título
                            
                        }
                    }

                    const r = {
                        table: {
                            body: [
                                ['Meta/Pontos','Valor pago por pontos'],
                                @foreach ($regras as $regra)
                                ['{{$regra['qtd_pontos']}}','R$ {{$regra['valor']}}'],   
                                @endforeach
                            ],
                            fontSize: 8,
                            layout: 'lightHorizontalLines', // Adicione um layout à tabela
                            margin: [0, 10, 0, 0], // Adicione margem superior para separar o título da tabela
                            alignment: 'center', // Centralize o título
                           
                        }
                    }

                    // Defina os títulos das tabelas
                    const tituloP = {
                    text: 'Pontos por Serviço',
                    bold: true,
                    fontSize: 14,
                    margin: [0, 0, 0, 10] // Adicione margem inferior para separar o título da tabela
                    };

                    const tituloR = {
                    text: 'Meta do Periodo',
                    bold: true,
                    fontSize: 14,
                    margin: [0, 0, 0, 10] // Adicione margem inferior para separar o título da tabela
                    };


                        var dd = {
                            content: [
                                {text: 'Comprovante Produção', style: 'header'},
                                'Eu {{$nomeTec}} estou recebendo da empresa Virtus Telecom LTDA o valor de  R$ {{$totalReceber}} referente a {{$totalPontos}} pontos no periodo de {{$dt_ini->format("d/m/Y")}} até {{$dt_fim->format("d/m/Y")}}, relacionado a produção de:',
                                {text: 'Produção:', style: 'subheader'},
                                {
                                    style: 'tableExample',
                                    table: {
                                        body: [
                                            ['Nome','QTD Serviços','QTD Pontos'],
                                            @foreach ($servicos as $servico)
                                            ['{{$servico['nome']}}','{{$servico['qtdServico']}}','{{$servico['qtdPontos']}}',],   
                                            @endforeach
                                            @foreach ($indicadores as $indicador)
                                            ['{{$indicador['servico']}}','{{$indicador['qtd']}}','{{$indicador['qtdPontos']}}',],                                                   
                                            @endforeach
                                        ], 
                                        fontSize: 8,
                                    }
                                },
                                {text: 'Valores de referencia', style: 'header'},
                                {
                                    columns: [
                                        {
                                            stack: [tituloP, p]
                                        },
                                        {
                                            stack: [tituloR, r, '** valores pagos a partir da meta minima']
                                        }
                                    ]
                                },                                
                                {text: '{{$dataExtenso}}', style: 'data'},
                                {text: 'Testemunha :'},
                                {text: 'CPF : ',style: 'texto' },
                                {text: 'Assinatura : ___________________________________________',style: 'assinatura' },
                                {text: '{{$dataExtenso}}', style: 'data'},
                                {text: 'Técnico : {{$nomeTec}}', style: 'texto'},
                                {text: 'CPF : ',style: 'texto' },
                                {text: 'Assinatura : ___________________________________________',style: 'assinatura' },
                            ],
                            styles: {
                                header: {
                                    fontSize: 18,
                                    bold: true,
                                    margin: [150, 0, 0, 10]
                                },
                                subheader: {
                                    fontSize: 16,
                                    bold: true,
                                    margin: [0, 10, 0, 5]
                                },
                                tableExample: {
                                    margin: [0, 5, 0, 15]
                                },
                                tableHeader: {
                                    bold: true,
                                    fontSize: 13,
                                    color: 'black'
                                },
                                assinatura:{
                                    margin: [0,10]
                                },
                                texto:{
                                    fontSize:13,
                                    margin: [0,5]
                                },
                                data:{
                                    fontSize: 13,
                                    bold: true,
                                    margin: [0,50,0,20]
                                }
                            },
                            defaultStyle: {
                                alignment: 'justify'
                            }
                            
                        }
                        pdfMake.createPdf(dd).download();
                } else {
                    return false;              
                };            
            });


    </script>

@endsection