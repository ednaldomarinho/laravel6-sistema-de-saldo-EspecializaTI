@extends('adminlte::page')

@section('title', 'Histórico')

@section('content_header')
    <h1>Histórico das movimentações</h1>
    <ol class="breadcrumb">
        <li><a href="">Dashboard</a></li>
        <li><a href="">Histórico</a></li>
    </ol>
@stop

@section('content')
<h4>{{$name}}</h4>
  <div class="box">
    <div class="box-header">
        <form action="{{route('historic.search')}}" method="post" class="form form-inline">
            @csrf
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="date" name="date" class="form-control">
            <select name="type" class="form-control">
                <option value="">--Selecione o tipo --</option>
                @foreach ($types as $key => $type)
                    <option value="{{$key}}">{{$type}}</option>
                @endforeach

            </select>
            <button type="submit" class="btn btn-primary">Pesquisar</button>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Valor</th>
                    <th>Tipo</th>
                    <th>Data</th>
                    <th>Destino</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historics as $historic)                    
                    <tr>
                        <td>{{$historic->id}}</td> 
                        <td>{{number_format($historic->amount,2, ',', '')}}</td> 
                        <td>{{$historic->type($historic->type)}}</td> 
                        <td>{{$historic->date}}</td>      
                        <td>
                            @if ($historic->user_id_transaction)
                                {{$historic->userSender->name}}
                            @else
                                -
                            @endif
                        </td> 
                    </tr>  
                @empty                  
                @endforelse
            </tbody>
        </table>
        @if (isset($dataForm))
            {!!$historics->appends($dataForm)->links()!!}
        @else            
            {!!$historics->links()!!}
        @endif
    </div>
    </div>
  </div>
@stop
