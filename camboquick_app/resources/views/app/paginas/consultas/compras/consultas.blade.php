@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Entrada de stock</h4>
    <h6>Vizualizar e pesquisar as entradas de stock</h6>
    </div>
  
    </div>



    <div class="card">
        <div class="card-body">
        <div class="table-top">
        <div class="search-set">
        <div class="search-path">
        <a class="btn btn-filter" id="filter_search">
        <img src="/empresa/assets/img/icons/filter.svg" alt="img">
        <span><img src="/empresa/assets/img/icons/closes.svg" alt="img"></span>
        </a>
        </div>
        <div class="search-input">
        <a class="btn btn-searchset"><img src="/empresa/assets/img/icons/search-white.svg" alt="img"></a>
        </div>
        </div>
        <div class="wordset">
        <ul>
        <li>
        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="/empresa/assets/img/icons/pdf.svg" alt="img"></a>
        </li>
        <li>
        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="/empresa/assets/img/icons/excel.svg" alt="img"></a>
        </li>
        <li>
        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="/empresa/assets/img/icons/printer.svg" alt="img"></a>
        </li>
        </ul>
        </div>
        </div>
        
        








        <div class="mb-3">
            <label for="tipoFiltro" class="form-label">Selecione o Tipo de Filtro:</label>
            <select id="tipoFiltro" class="form-control" onchange="mostrarFiltro()">
                <option value="4">Secione um filtro</option>
                <option value="1">Filtro Por Data</option>
                <option value="2">Filtro Por Periodo</option>
                <option value="3">Filtro Por Mês</option>
                <option value="5">Filtro Por Ano</option>
                <option value="6">Filtro Por Fatura</option>
            </select>
        </div>
        
        
<!-- Filtro Básico -->
<form method="GET" action="{{route('consultas.compras.data')}}" class="mb-4 d-none" id="filtro1">
    <div class="row">
        <div class="col-md-3">
            <label for="dataInicio" class="form-label">Fornecedores</label>
            <select  class="form-control" name='fornecedor_id'>
                <option value="">Secione um fornecedor</option>
                @foreach($fornecedores as $fornecedor)
                <option value="{{$fornecedor->id}}">{{$fornecedor->nome}}</option>
                @endforeach
            </select>       
         </div>

        <div class="col-md-3">
            <label for="dataInicio" class="form-label">Data</label>
            <input type="date" name="data" id="data_inicio" class="form-control" value="{{ request('data') }}" required>
        </div>
        <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Status</label>
                    <select  class="form-control" name='status' required>
                        <option value="">Secione um status</option>
                        <option value="Valida">Valida</option>
                        <option value="Cancelada">Cancelada</option>
                        
                    </select>       
                 </div>
        <div class="col-md-3">
            <label for="dataFim" class="form-label">Nº Regsitros /Pag</label>
            <input type="number" name="n_registros" id="data_fim" class="form-control" min="1" value=" "  >
        </div>
        <div class="col-md-2 d-flex align-items-end mt-3">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </div>
</form>

<!-- Filtro Intermediário -->
<form method="GET" action="{{route('consultas.compras.periodo')}}" class="mb-4 d-none" id="filtro2">
    <div class="row">
 
        <div class="col-md-3">
            <label for="dataInicio" class="form-label">Fornecedores</label>
            <select  class="form-control" name='fornecedor_id'>
                <option value="">Secione um fornecedor</option>
                @foreach($fornecedores as $fornecedor)
                <option value="{{$fornecedor->id}}">{{$fornecedor->nome}}</option>
                @endforeach
            </select>       
         </div>
        <div class="col-md-3">
            <label for="dataInicio" class="form-label">Data Inicio</label>
            <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ request('data_inicio') }}" required>
        </div>
        <div class="col-md-3">
            <label for="dataFim" class="form-label">Data Fim</label>
            <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ request('data_fim') }}" required>
        </div>
        <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Status</label>
                    <select  class="form-control" name='status' required>
                        <option value="">Secione um status</option>
                        <option value="Valida">Valida</option>
                        <option value="Cancelada">Cancelada</option>
                        
                    </select>       
                 </div>
        <div class="col-md-3">
            <label for="dataFim" class="form-label">Nº Regsitros /Pag</label>
            <input type="number" name="n_registros" id="data_fim" class="form-control" min="1" value=" "  >
        </div>
        <div class="col-md-2 d-flex align-items-end mt-3">
            <button type="submit" class="btn btn-primary w-100 ">Filtrar</button>
        </div>
    </div>
</form>

<!-- Filtro Avançado -->
<form method="GET" action="{{route('consultas.compras.mes')}}" class="mb-4 d-none" id="filtro3">
    <div class="row">
        <div class="col-md-3">
            <label for="dataInicio" class="form-label">Fornecedores</label>
            <select  class="form-control" name='fornecedor_id'>
                <option value="">Secione um fornecedor</option>
                @foreach($fornecedores as $fornecedor)
                <option value="{{$fornecedor->id}}">{{$fornecedor->nome}}</option>
                @endforeach
            </select>       
         </div>
        <div class="col-md-3">
            <label for="data_inicio" class="form-label">Mês de Início</label>
            <input type="month" name="mes" id="data_inicio" class="form-control" value="{{ request('data_inicio') }}" required>
        </div>
        <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Status</label>
                    <select  class="form-control" name='status' required>
                        <option value="">Secione um status</option>
                        <option value="Valida">Valida</option>
                        <option value="Cancelada">Cancelada</option>
                        
                    </select>       
                 </div>
        <div class="col-md-3">
            <label for="dataFim" class="form-label">Nº Regsitros /Pag</label>
            <input type="number" name="n_registros" id="data_fim" class="form-control" min="1" value=" ">
        </div>
        <div class="col-md-2 d-flex align-items-end mt-3">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </div>
 </form>

 <form method="GET" action="{{route('consultas.compras.ano')}}" class="mb-4 d-none" id="filtro5">
    <div class="row">
        <div class="col-md-3">
            <label for="dataInicio" class="form-label">Fornecedores</label>
            <select  class="form-control" name='fornecedor_id'>
                <option value="">Secione um fornecedor</option>
                @foreach($fornecedores as $fornecedor)
                <option value="{{$fornecedor->id}}">{{$fornecedor->nome}}</option>
                @endforeach
            </select>       
         </div>
        <div class="col-md-3">
            <label for="ano" class="form-label">Ano</label>
            <input type="number" name="ano" id="ano" class="form-control" 
                   value="{{ request('ano') }}" min="2000" max="{{ now()->year }}" required>
        </div>
        <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Status</label>
                    <select  class="form-control" name='status' required>
                        <option value="">Secione um status</option>
                        <option value="Valida">Valida</option>
                        <option value="Cancelada">Cancelada</option>
                        
                    </select>       
                 </div>
        <div class="col-md-3">
            <label for="dataFim" class="form-label">Nº Regsitros /Pag</label>
            <input type="number" name="n_registros" id="data_fim" class="form-control" min="1" value=" "  >
        </div>
        <div class="col-md-2 d-flex align-items-end mt-3">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </div>
 </form>

 <form method="GET" action="{{route('consultas.compras.fatura')}}" class="mb-4 d-none" id="filtro6">
    <div class="row">
   
        <div class="col-md-3">
            <label for="dataFim" class="form-label">Nº Fatura</label>
            <input type="text" name="n_fatura" id="data_fim" class="form-control" min="1" value="{{ request('n_nfatura') }}" required>
        </div>
        <div class="col-md-2 d-flex align-items-end ">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </div>
 </form>







         @if(isset($chave))

         <div class="resultado">

            <p>Pesquisa Por :  {{$pesquisa}}</p>
            @if($compra2)
            
                 <p> <strong>Total Compras</strong>  :
         <span class="fs-2 text-success">{{ number_format($compra2->custo_total,'2',',','.')}}Kz </span>
    &nbsp;&nbsp;  
         <strong>Número de Registros</strong>   : <span class="fs-2 text-info">{{($compra2)?'1':'0'}}</span> </p>
         
         
            
        

        <div class="table-responsive">
        <table class="table  datanew">
        <thead>
        <tr>
         <th>
        <label class="checkboxs">
        <input type="checkbox" id="select-all">
        <span class="checkmarks"></span>
        </label>
        </th>
        <th>Data</th>
        <th>Nº Fatura</th>
        <th>Qnt Total</th>
        <th>Custo Total</th>
         <th>Fornecedor</th>
         <th>Usuario</th>
          <th>Status</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

         
        <tr>
            
        


      <td>
        <label class="checkboxs">
        <input type="checkbox">
        <span class="checkmarks"></span>
        </label>
        </td>
     
     <td class="productimgname">
        <a href="javascript:void(0);" class="product-img">
        <img src="/empresa/assets/img/product/noimage.png" alt="product">
        </a>
        <a href="javascript:void(0);">{{ $compra2->created_at->format('d/m/Y H:i:s') }}</a>
        </td>
        
        <td>{{ $compra2->n_fatura }}</td>
        <td>{{ $compra2->quantidade_total }}</td>
        <td>{{ number_format($compra2->custo_total, 2, ',', '.') }}</td>
       
                       <td>
        @php
          $nome1=$compra2->fornecedor->nome; 
        @endphp
        {{$nome1}}</td>

                        <td>
        @php
          $nome2=explode(' ',optional($compra2->usuario )->name)[0]; 
        @endphp
        {{$nome2}}</td>


                                <td class="{{
       $compra2->status=='Valida'
       ?'text-success'
       :'text-danger'
       }} " >
        {{ $compra2->status }}
       </td>
        <td>
            
        <a class="me-3" href="{{route('compras.fatura',$compra2->id)}}"   title="Ver Fatura">
        <img src="/empresa/assets/img/icons/eye.svg" alt="img">
        </a>
       

        

        
 
 
        </td>
    </tr>
 
           </tbody>
        </table>
        </div>

        @endif
        <p> <strong>Total Compra</strong>  : 0Kz      <strong>Número de Registros</strong>   :0 </p>
       

    </div>

  

    @elseif(isset($compras))
    <div class="resultado">

        <p>Pesquisa Por :  {{$pesquisa}}</p>
        
          <p> <strong>Total Compras</strong>  :
         <span class="fs-2 text-success">{{ number_format( $total_compras,'2',',','.')}}Kz </span>
    &nbsp;&nbsp;  
         <strong>Número de Registros</strong>   : <span class="fs-2 text-info">{{$n_compras}}</span> </p>
        
       
        

    <div class="table-responsive">
    <table class="table  datanew">
    <thead>
    <tr>
    <th>
    <label class="checkboxs">
    <input type="checkbox" id="select-all">
    <span class="checkmarks"></span>
    </label>
    </th>
    <th>Data</th>
    <th>Nº Fatura</th>
    <th>Qnt Total</th>
    <th>Custo Total</th>
     <th>Fornecedor</th>
     <th>Usuario</th>
     <th>Status</th>
    <th>Ações</th>
    </tr>
    </thead>
    <tbody>

        @foreach ($compras as $compra)
    <tr>
        
    <td>
    <label class="checkboxs">
    <input type="checkbox">
    <span class="checkmarks"></span>
    </label>
    </td>


 
    <td class="productimgname">
    <a href="javascript:void(0);" class="product-img">
    <img src="/empresa/assets/img/product/noimage.png" alt="product">
    </a>
    <a href="javascript:void(0);">{{ $compra->created_at->format('d/m/Y H:i:s') }}</a>
    </td>
    
                                    <td>{{ $compra->n_fatura }}</td>
                                    <td>{{ $compra->quantidade_total }}</td>
                                    <td>{{ number_format($compra->custo_total, 2, ',', '.') }}</td>
                                       <td>
        @php
          $nome1=$compra->fornecedor->nome; 
        @endphp
        {{$nome1}}</td>

                        <td>
        @php
          $nome2=explode(' ',optional($compra->usuario )->name)[0]; 
        @endphp
        {{$nome2}}</td>
                                      <td class="{{
       $compra->status=='Valida'
       ?'text-success'
       :'text-danger'
       }} " >
        {{ $compra->status }}
       </td>
    <td>
    <a class="me-3" href="{{route('compras.fatura',$compra->id)}}" target="_blank" rel="noopener noreferrer"   title="Ver Fatura">
    <img src="/empresa/assets/img/icons/eye.svg" alt="img">
    </a>
    </td>
</tr>
@endforeach
       </tbody>
    </table>
    </div>
    
</div>
 

@endif









        </div>
        </div>
       

        <script>
            function mostrarFiltro() {
                let tipo = document.getElementById("tipoFiltro").value;
                document.getElementById("filtro1").classList.add("d-none");
                document.getElementById("filtro2").classList.add("d-none");
                document.getElementById("filtro3").classList.add("d-none");
                document.getElementById("filtro5").classList.add("d-none");
                document.getElementById("filtro6").classList.add("d-none");
                
                document.getElementById("filtro" + tipo).classList.remove("d-none");
            }
        </script>
@endsection