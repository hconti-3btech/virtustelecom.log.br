<option value="">Selecione</option>
@foreach ($produtos as $produto)
    <option value='{{$produto->id}}'>{{$produto->nome}}</option>  
@endforeach