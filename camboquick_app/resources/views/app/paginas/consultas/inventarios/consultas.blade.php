@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Inventarios de Estoque</h4>
    <h6>Vizualizar e pesquisar todos inventarios de estoque</h6>
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
                <option value="1">Filtro Por Periodo</option>
                  </select>
        </div>

        <!-- Filtro Intermediário -->
        <form method="GET" action="{{route('inventarios.periodo')}}" class="mb-4 d-none" id="filtro1">
            <div class="row">
          


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


        @if(isset($resultados))
            <p>Pesquisa Por :  {{$resultados}}</p>
        @endif
        
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
        <th>Usuario</th>
        <th>Data </th>
       
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($inventarios as $inventario)
        <tr >
            
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
        <a href="javascript:void(0);">{{ $inventario->usuario->name }}</a>
        </td>
         <td>{{ $inventario->created_at ->format('d/m/Y H:i:s') }}</td>
          
        <td>
            
        <a class="me-3" href="{{route('inventarios.documento',$inventario->id)}}"  target="_blank" rel="noopener noreferrer"   title="Documento de inventario">
        <img src="/empresa/assets/img/icons/eye.svg" alt="img">
        </a>
       

      
        
 
 
        </td>
    </tr>
    @endforeach
           </tbody>
        </table>
        </div>
        </div>
        </div>
       

 


        <script>
            function mostrarFiltro() {
                let tipo = document.getElementById("tipoFiltro").value;
                document.getElementById("filtro1").classList.add("d-none");
           
                document.getElementById("filtro" + tipo).classList.remove("d-none");
            }
        </script>
@endsection