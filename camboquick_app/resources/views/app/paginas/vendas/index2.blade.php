@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Lista de todas vendas</h4>
    <h6>Vizualizar e pesquisar as vendas</h6>
    </div>
    <div class="page-btn">
    <a href="{{route('vendas.create')}}"   class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Adicionar Venda
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
        
        <div class="card" id="filter_inputs">
        <div class="card-body pb-0">
        <div class="row">
        <div class="col-lg-2 col-sm-6 col-12">
        <div class="form-group">
        <select class="select">
        <option>Choose Category</option>
        <option>Computers</option>
        </select>
        </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-12">
        <div class="form-group">
        <select class="select">
        <option>Choose Sub Category</option>
        <option>Fruits</option>
        </select>
        </div>
        </div>
        <div class="col-lg-2 col-sm-6 col-12">
        <div class="form-group">
        <select class="select">
        <option>Choose Sub Brand</option>
        <option>Iphone</option>
        </select>
        </div>
        </div>
        <div class="col-lg-1 col-sm-6 col-12 ms-auto">
        <div class="form-group">
        <a class="btn btn-filters ms-auto"><img src="/empresa/assets/img/icons/search-whites.svg" alt="img"></a>
        </div>
        </div>
        </div>
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
        <th>Nome Cliente</th>
                                <th>Total</th>
                                <th>Quant</th>
                                 <th>Fat</th>
                                <th>Data</th>
                                <th>User</th>
                                <th>Status</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($vendas as $venda)
        <tr class="{{ $venda->status =="Cancelada" ? 'stock-baixo' : '' }}">
            
       

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
        <a href="javascript:void(0);">{{ $venda->nome_cliente }}</a>
        </td>
        <td>{{ number_format($venda->total, 2, ',', '.') }}</td>
        <td>{{ $venda->quantidade }}</td>
         <td>{{ $venda->n_fatura }}</td>
         <td>{{ $venda->created_at->format('d/m/Y H:i:s') }}</td>
           <td>
        @php
        $nome=explode(' ',$venda->usuario->name)[0];
        
        @endphp
        {{$nome}}
       </td>
         <td>{{ $venda->status }}</td>
       
        <td>
            
        <a class="me-3" href="{{route('vendas.fatura',$venda->id)}}"  target="_blank" rel="noopener noreferrer"   title="Ver Fatura">
        <img src="/empresa/assets/img/icons/eye.svg" alt="img">
        </a>
        @hasanyrole('admin|gerente')
        @if($venda->status=="Valida")
        <a class="me-3 confirm-text" href=""data-bs-toggle="modal" data-bs-target="#deleteModal{{ $venda->id }}" title="Cancelar Venda">
        <img src="/empresa/assets/img/icons/delete.svg" alt="img">
        </a>
        @endif
        @endhasanyrole

       
        <form action="{{ route('vendas.cancelar', $venda->id) }}" method="post" style="display:inline-block;">
            @csrf
            @method('PUT')
 
            <!-- Delete Modal -->
            <div class="modal" id="deleteModal{{ $venda->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cancelar Venda?</h5>
                            <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem a certeza que deseja cancelar a venda de "{{ $venda->nome_cliente }}"?</p>
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