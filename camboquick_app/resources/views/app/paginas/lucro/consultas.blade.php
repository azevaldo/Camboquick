@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>    Lucros  </h4>      
    <h6>Vizualizar e pesquisar os lucros</h6>
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
          
            </select>
        </div>
        
        
        <!-- Filtro Básico -->
        <form method="GET" action="{{route('relatorio.lucros.data')}}" class="mb-4 d-none" id="filtro1">
            <div class="row">
          
              <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Produtos</label>
                    <select  class="form-control" name='produto_id'>
                        <option value="">Secione um produto</option>
                        @foreach($produtos as $produto)
                        <option value="{{$produto->id}}">{{$produto->nome}}</option>
                        @endforeach
                    </select>       
                 </div>
                <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Data</label>
                    <input type="date" name="data" id="data_inicio" class="form-control" value="{{ request('data') }}" required>
                </div>
                        
                
               
                <div class="col-md-2 d-flex align-items-end mt-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>
        
        <!-- Filtro Intermediário -->
        <form method="GET" action="{{route('relatorio.lucros.periodo')}}" class="mb-4 d-none" id="filtro2">
            <div class="row">
         
                   <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Produtos</label>
                    <select  class="form-control" name='produto_id'>
                        <option value="">Secione um produto</option>
                        @foreach($produtos as $produto)
                        <option value="{{$produto->id}}">{{$produto->nome}}</option>
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
               
                
                <div class="col-md-2 d-flex align-items-end mt-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>
        
        <!-- Filtro Avançado -->
        <form method="GET" action="{{route('relatorio.lucros.mes')}}" class="mb-4 d-none" id="filtro3">
            <div class="row">
              
                   <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Produtos</label>
                    <select  class="form-control" name='produto_id'>
                        <option value="">Secione um produto</option>
                        @foreach($produtos as $produto)
                        <option value="{{$produto->id}}">{{$produto->nome}}</option>
                        @endforeach
                    </select>       
                 </div>
                <div class="col-md-3">
                    <label for="data_inicio" class="form-label">Mês de Início</label>
                    <input type="month" name="mes" id="data_inicio" class="form-control" value="{{ request('data_inicio') }}" required>
                </div>
               
                
                <div class="col-md-2 d-flex align-items-end mt-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
         </form>
        
         <form method="GET" action="{{route('relatorio.lucros.ano')}}" class="mb-4 d-none" id="filtro5">
            <div class="row">
                 
                <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Produtos</label>
                    <select  class="form-control" name='produto_id'>
                        <option value="">Secione um produto</option>
                        @foreach($produtos as $produto)
                        <option value="{{$produto->id}}">{{$produto->nome}}</option>
                        @endforeach
                    </select>       
                 </div>

                <div class="col-md-3">
                    <label for="ano" class="form-label">Ano</label>
                    <input type="number" name="ano" id="ano" class="form-control" 
                           value="{{ request('ano') }}" min="2000" max="{{ now()->year }}" required>
                </div>
                 
                
                <div class="col-md-2 d-flex align-items-end mt-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
         </form>
        
         <form method="GET" action="{{route('consultas.vendas.fatura')}}" class="mb-4 d-none" id="filtro6">
            <div class="row">
           
                <div class="col-md-3">
                    <label for="dataFim" class="form-label">Nº Fatura</label>
                    <input type="text" name="n_fatura" id="data_fim" class="form-control" min="1" value="{{ request('n_nfatura') }}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end mt-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
         </form>





 
        

  

   @if(isset($lucro2))
    <div class="resultado">

        <p>Pesquisa Por :  {{$pesquisa}}</p>
        
               <p> <strong>Total Lucros</strong>  :
         <span class="fs-2 text-success">{{ number_format(  $total_lucros,'2',',','.')}}Kz </span>
    &nbsp;&nbsp;  
         <strong>Número de Registros</strong>   : <span class="fs-2 text-info">{{$n_lucros}}</span> </p>
        
       
        

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
    <th>Nome Prod</th>
    <th>Data</th> 
     <th>Lucro Uni</th>
    <th>Lucro Tot</th> 
    <th>Preço Uni Venda</th>
    <th>Qnt</th>
     <th>Custo Uni</th>
     <th>Ações</th>
    </tr>
    </thead>
    <tbody>

        @foreach ($lucros as $lucro)
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
        <a href="javascript:void(0);">{{ $lucro->produto->nome}}</a>
    </td>
    <td>{{ $lucro->created_at->format('d/m/Y H:i:s') }}</td>
     <td>{{ number_format($lucro->lucro_unitario, 2, ',', '.') }}</td>
    <td>{{ number_format($lucro->lucro_total, 2, ',', '.') }}</td>
    <td>{{ number_format($lucro->preco_venda_unitario, 2, ',', '.') }}</td>
    <td>{{ $lucro->quantidade }}</td>
     <td>{{ number_format($lucro->preco_custo_unitario, 2, ',', '.') }}</td>
      
   
          
    <td>
    <a class="me-3" href="{{route('vendas.fatura',$lucro->venda->id)}}" target="_blank" rel="noopener noreferrer"   title="Ver Fatura de Venda">
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
              
                document.getElementById("filtro" + tipo).classList.remove("d-none");
            }
        </script>
@endsection