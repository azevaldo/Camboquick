@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Lista de Categorias de Produtos</h4>
    <h6>Vizualizar e pesquisar as categorias</h6>
    </div>
    <div class="page-btn">
    <a href=""  data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Adicionar Categoria
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
        <th>Categoria</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($categorias as $categoria)
        <tr class="{{ $categoria->status =="Arquivado" ? 'arquivado' : '' }}">
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
        <a href="javascript:void(0);">{{ $categoria->categoria }}</a>
        </td>
       
        <td>
            <a class="me-3" href="{{route('subcategorias.index2',$categoria->slug)}}"  title="SubCategorias"  >
                <img src="/empresa/assets/img/icons/eye.svg" alt="img">
                </a>
        <a class="me-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $categoria->id }}" title="Editar"  >
        <img src="/empresa/assets/img/icons/edit.svg" alt="img">
        </a>
      @if($categoria->status=="Valido")
        <a class="me-3 confirm-text" href="" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $categoria->id }}" title="Arquivar">
        <img src="/empresa/assets/img/icons/delete.svg" alt="img">
        </a>
        @endif


        

        

     <!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $categoria->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $categoria->id }}" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Categoria</h5>
                <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('categorias.update', $categoria->id) }}" id="formCategoriaEdit{{ $categoria->id }}">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label for="categoria{{ $categoria->id }}" class="form-label">Categoria</label>
                        <input type="text" class="form-control" id="categoria{{ $categoria->id }}" name="categoria" 
                               value="{{ $categoria->categoria }}" required
                               pattern="^[^\s].*"
                               title="A categoria não deve começar com espaço.">
                    </div>
                      <div class="mb-3">
                                                <label for="categoria_id" class="form-label">Status</label>
                                                <select class="form-control" id="armazem" name="status" required>
                                                 <option selected disabled>Seleciona o status</option>
                                                 <option value="Valido" {{($categoria->status=="Valido")?'selected':''}}>Valido</option>
                                                 <option value="Arquivado" {{($categoria->status=="Arquivado")?'selected':''}}>Arquivado</option>  
                                                </select>
                                            </div>
                  
                       <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Editar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
                </form>
            </div>
         
        </div>
    </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Seleciona todos os formulários de edição de categoria
    document.querySelectorAll('form[id^="formCategoriaEdit"]').forEach(form => {
      form.addEventListener('submit', function(event) {
        const input = form.querySelector('input[name="categoria"]');
        if (/^\s/.test(input.value)) {
          alert('A categoria não deve começar com espaço.');
          event.preventDefault();
        }
      });
    });
  });
</script>

        <form action="{{ route('categorias.arquivar', $categoria->id) }}"   style="display:inline-block;">
          

            
        
            <div class="modal" id="deleteModal{{ $categoria->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="d-flex justify-content-center align-items-center flex-grow-1">
                                <h5 class="modal-title">Arquivar Categoria?</h5>
                            </div>
                            <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem a certeza que deseja arquivar a categoria "{{ $categoria->categoria}}"?</p>
                        </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Arquivar</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
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

        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true"> 
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Adicionar Categoria</h5>
        <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('categorias.store') }}" id="formCategoriaCreate">
          @csrf
          <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <input type="text" class="form-control" id="categoria" name="categoria" required
                   pattern="^[^\s].*"
                   title="A categoria não deve começar com espaço.">
          </div>
          
             <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Adicionar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
      </div>
        </form>
      </div>
   
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formCategoriaCreate');
    const inputCategoria = form.querySelector('#categoria');

    form.addEventListener('submit', function(event) {
      if (/^\s/.test(inputCategoria.value)) {
        alert('A categoria não deve começar com espaço.');
        event.preventDefault();
      }
    });
  });
</script>

@endsection