@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Lista de Entradas De Stock</h4>
    <h6>Vizualizar e pesquisar as entradas de Stock</h6>
    </div>
    <div class="page-btn">
    <a href="{{route('compras.create')}}"   class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Adicionar Entrada De Stock
    </a>
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
        <tr class="{{ $compra->status =="Cancelada" ? 'stock-baixo' : '' }}">
            
      


     
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
        <td>{{ $compra->fornecedor->nome }}</td>
        <td>{{ $compra->usuario->name }}</td>
        <td>{{ $compra->status }}</td>
        <td>
            
        <a class="me-3" href={{route('compras.fatura',$compra->id)}}" target="_blank" rel="noopener noreferrer"   title="Ver Fatura">
        <img src="/empresa/assets/img/icons/eye.svg" alt="img">
        </a>
        @if($compra->status=="Valida")
        <a class="me-3 confirm-text" href=""data-bs-toggle="modal" data-bs-target="#deleteModal{{ $compra->id }}" title="Cancelar Compra">
        <img src="/empresa/assets/img/icons/delete.svg" alt="img">
        </a>
        @endif
       

        <form action="{{ route('compras.cancelar', $compra->id) }}" method="post" style="display:inline-block;">
            @csrf
            @method('PUT')

           

            <!-- Delete Modal -->
            <div class="modal" id="deleteModal{{ $compra->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cancelar Compra?</h5>
                            <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem a certeza que deseja cancelar a Compra de "{{ $compra->n_fatura }}"?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Cancelar</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        
 
 
        </td>
    </tr>
    @endforeach
           </tbody>
        </table>
        </div>
        </div>
        </div>
       
@endsection