@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Minhas Vendas</h4>
    <h6>Vizualizar e pesquisar as minhas vendas</h6>
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
        <form method="GET" action="{{route('minhas.vendas.data')}}" class="mb-4 d-none" id="filtro1">
            <div class="row">
             
        
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
                
               
                <div class="col-md-2 d-flex align-items-end mt-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>
        
        <!-- Filtro Intermediário -->
        <form method="GET" action="{{route('minhas.vendas.periodo')}}" class="mb-4 d-none" id="filtro2">
            <div class="row">
         
                
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
                
                <div class="col-md-2 d-flex align-items-end mt-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>
        
        <!-- Filtro Avançado -->
        <form method="GET" action="{{route('minhas.vendas.mes')}}" class="mb-4 d-none" id="filtro3">
            <div class="row">
                
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
                
                <div class="col-md-2 d-flex align-items-end mt-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
         </form>
        
         <form method="GET" action="{{route('minhas.vendas.ano')}}" class="mb-4 d-none" id="filtro5">
            <div class="row">
                
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
                
                <div class="col-md-2 d-flex align-items-end mt-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
         </form>
        
         <form method="GET" action="{{route('minhas.vendas.fatura')}}" class="mb-4 d-none" id="filtro6">
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






         @if(isset($chave))

         <div class="resultado">

            <p>Pesquisa Por :  {{$pesquisa}}</p>
            @if($venda2)
            
            
  

            
            <p> <strong>Total Venda</strong>  : 
            <span class="fs-2 text-success">{{ number_format( $venda2->total,'2',',','.')}}Kz
            </span>
    &nbsp;&nbsp;
    
            <strong>Número de Registros</strong>   :
             <span class="fs-2 text-info">{{($venda2)?'1':'0'}} </span> </p>
        

        <div class="table-responsive">
        <table class="table  datanew">
        <thead>
        <tr>
      
        <th>Nome do Cliente</th>
         <th>Total</th>
        <th>Quantidade</th>
        <th>Fatura</th>
        <th>Data</th>
         <th>Status</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

         
        <tr>
            
         


     
        <td  >
       
        <a  >{{ $venda2->nome_cliente }}</a>
        </td>
        <td>{{ number_format($venda2->total, 2, ',', '.') }}</td>
        <td>{{ $venda2->quantidade }}</td>
         <td>{{ $venda2->n_fatura }}</td>
         <td>{{ $venda2->created_at->format('d/m/Y H:i:s') }}</td>
          <td class="{{
       $venda2->status=='Valida'
       ?'text-success'
      :'text-danger'
       }} " >
        {{ $venda2->status }}
       </td>
        <td>
            
        <a class="me-3" href="{{route('vendas.fatura',$venda2->id)}}" target="_blank"  title="Ver Fatura">
        <img src="/empresa/assets/img/icons/eye.svg" alt="img">
        </a>
       

        

        
 
 
        </td>
    </tr>
 
           </tbody>
        </table>
        </div>
@else
        
        <p> <strong>Total Venda</strong>  : 0Kz      <strong>Número de Registros</strong>   :0 </p>
       
@endif
    </div>

  

    @elseif(isset($vendas))
    <div class="resultado">

        <p>Pesquisa Por :  {{$pesquisa}}</p>
     <p>
    <strong>Total Vendas</strong>: 
    <span class="fs-2 text-success">{{ number_format($total_vendas, 2, ',', '.') }}Kz</span>
    &nbsp;&nbsp;
    <strong>Número de Registros</strong>: 
    <span class="fs-2 text-info">{{ $n_vendas }}</span>
</p>

        

    <div class="table-responsive">
    <table class="table  datanew">
    <thead>
    <tr>
    
    <th>Nome do Cliente</th>
                            <th>Total</th>
                            <th>Quantidade</th>
                             <th>Fatura</th>
                            <th>Data</th>
                            <th>Status</th>
    <th>Ações</th>
    </tr>
    </thead>
    <tbody>

        @foreach ($vendas as $venda)
    <tr>
        
   


 
    <td  >
   
    <a >{{ $venda->nome_cliente }}</a>
    </td>
    <td>{{ number_format($venda->total, 2, ',', '.') }}</td>
    <td>{{ $venda->quantidade }}</td>
     <td>{{ $venda->n_fatura }}</td>
     <td>{{ $venda->created_at->format('d/m/Y H:i:s') }}</td>
         <td class="{{
       $venda->status=='Valida'
       ?'text-success'
       :'text-danger'
       }} " >
        {{ $venda->status }}
       </td>
    <td>
    <a class="me-3" href="{{route('vendas.fatura',$venda->id)}}" target="_blank"  title="Ver Fatura">
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