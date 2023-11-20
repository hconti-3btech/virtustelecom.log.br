@extends('adminlte::page')

@section('title', 'Importar Material')
    

@section('content_header')
    <h1 class="m-0 text-dark">Importar Material</h1>
@stop

@section('content')
    
    <div class="row">
        <div class="col-12">
            <div class="container-fluid">

                @if(session('msg'))
                    <div class="alert alert-{{session('type')}}">
                        <p>{{session('msg')}}</p>
                    </div>
                @endif


                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            {{$error}}
                        @endforeach
                    </div>
                @endif                
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Material</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{route('importMaterialSave')}}" enctype="multipart/form-data" onsubmit="bloqSubmit()">
                            @csrf                
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Foto: </span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="csvFile" accept=".csv" name="csvFile">
                                    <label class="custom-file-label" for="csvFile" >Selecione a imagem</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-success form-control" id="btnSave" onclick="return confirmar()">Importar</button>
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
    var div = document.getElementsByClassName("custom-file-label")[0];
    var input = document.getElementById("csvFile");

    div.addEventListener("click", function(){
        input.click();
    });
    input.addEventListener("change", function(){
        var nome = "Não há arquivo selecionado. Selecionar arquivo...";
        if(input.files.length > 0) nome = input.files[0].name;
        div.innerHTML = nome;
    });


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


