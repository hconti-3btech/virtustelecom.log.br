<option value=""></option>
@foreach ($controles as $controle)
    <option value='{{$controle->controle}}'>{{$controle->controle}}</option>  
@endforeach