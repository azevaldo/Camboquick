@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Lista de Perdas</h4>
    <h6>Vizualizar e pesquisar Perdas</h6>
    </div>
    <div class="page-btn">
    <a href="{{route('perdas.create')}}"    class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Adicionar Perdas
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
        <th>Total</th>
        <th>Usuario</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($perdas as $perda)
        <tr class="{{ $perda->status =="Cancelada" ? 'stock-baixo' : '' }}">
        
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
        <a href="javascript:void(0);">{{ $perda->created_at->format('d/m/Y H:i') }}</a>
        </td>
       <td>{{number_format( $perda->total,2,',','.')}}</td>
       <td>{{$perda->user->name}}</td>
        <td>
       <a class="me-3" href="{{ route('perdas.show', $perda->id) }}" target="_blank" rel="noopener noreferrer" title="Documento">
    <img src="/empresa/assets/img/icons/eye.svg" alt="img">
</a>

        @if($perda->status=="Valida")
        <a class="me-3 confirm-text" href=""data-bs-toggle="modal" data-bs-target="#deleteModal{{ $perda->id }}" title="Apagar">
        <img src="/empresa/assets/img/icons/delete.svg" alt="img">
        </a>
        @endif

  

       <!-- Delete Modal -->
       <div class="modal fade" id="deleteModal{{ $perda->id }}" tabindex="-1">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('perdas.cancelar', $perda->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Cancelar Perda?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja Cancelar Perda <strong>{{ $perda->id }}</strong>?</p>
                    </div>
                       <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Cancelar</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

        </td>
    </tr>
    @endforeach
           </tbody>
        </table>
        </div>
        </div>
        </div>
 
@endsection