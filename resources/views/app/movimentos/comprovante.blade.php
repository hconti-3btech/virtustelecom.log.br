@extends('adminlte::page')

@section('title', 'Containers')

@section('plugins.pdfMake', true)
    
@section('content_header')

    @if(session('msg'))
        <div class="alert alert-{{session('type')}}">
            <p>{{session('msg')}}</p>
        </div>
    @endif
    
    <h1 class="m-0 text-dark">Download Requisição</h1>
        
@stop

@section('js')

<script>
    var dd = {
        content: [
            {text: 'Requerimento de Material N°{{$relatorio['requisicao']['numero']}}', style: 'header'},
            'Eu {{$relatorio['destino']['nome']}} estou recebendo do {{$relatorio['origem']['nome']}} os seguintes materiais:',
            {text: 'Materias Recebidos:', style: 'subheader'},
            {
                style: 'tableExample',
                table: {
                    body: [
                        ['Descrição','QTD', 'UN', 'Controle'],
                        @foreach ($relatorio['materiais'] as $relatorioMateriais)
                        ['{{$relatorioMateriais['nome']}}','{{$relatorioMateriais['qtd']}}', '{{$relatorioMateriais['un']}}', '{{$relatorioMateriais['controle']}}',],   
                        @endforeach
                        
                    ]
                }
            },
            {text: '{{$dataExtenso}}', style: 'data'},
            {text: 'Remetente : {{$relatorio['origem']['nome']}}', style: 'texto'},
            {text: 'CPF : ',style: 'texto' },
            {text: 'Assinatura : ___________________________________________',style: 'assinatura' },
            
            {text: 'Destinatario : {{$relatorio['destino']['nome']}}', style: 'texto'},
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
                margin: [0,20]
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
            // alignment: 'justify'
        }
        
    }

    pdfMake.createPdf(dd).download();
</script>
@endsection