@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Lista de Armazens</h4>
    <h6>Vizualizar e pesquisar os armazens</h6>
    </div>
    <div class="page-btn">
    <a href=""  data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Adicionar Armazem
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
        <th>Descrição</th>
        <th>Localização</th>
        <th>Código</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($armazens as $armazem)
 <tr class="{{ $armazem->status =="Arquivado" ? 'arquivado' : '' }}">
            
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
        <a href="javascript:void(0);">{{ $armazem->descricao }}</a>
        </td>
        <td>
            {{ $armazem->localizacao }} 
        </td>
        <td >
            {{ $armazem->codigo }}
        </td>
       
        <td>
            
        <a class="me-3"data-bs-toggle="modal" data-bs-target="#editModal{{ $armazem->id }}" title="Editar" >
        <img src="/empresa/assets/img/icons/edit.svg" alt="img">
        </a>
         @if($armazem->status=="Valido")
        <a class="me-3 confirm-text" href=""data-bs-toggle="modal" data-bs-target="#deleteModal{{ $armazem->id }}" title="Arquivar">
        <img src="/empresa/assets/img/icons/delete.svg" alt="img">
        </a>
        @endif



        

        

        <!-- Modal Editar -->
<div class="modal fade" id="editModal{{ $armazem->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Armazém</h5>
                <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('armazens.update', $armazem->id) }}" id="formArmazemEdit{{ $armazem->id }}">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" class="form-control" 
                               id="descricaoEdit{{ $armazem->id }}" 
                               name="descricao" 
                               value="{{ $armazem->descricao }}" 
                               pattern="^[^\s].*" 
                               title="A descrição não pode começar com espaço." 
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Localização</label>
                        <input type="text" class="form-control" 
                               id="localizacaoEdit{{ $armazem->id }}" 
                               name="localizacao" 
                               value="{{ $armazem->localizacao }}" 
                               pattern="^[^\s].*" 
                               title="A localização não pode começar com espaço." 
                               required>
                    </div>
                      <div class="mb-3">
                                                <label for="categoria_id" class="form-label">Status</label>
                                                <select class="form-control" id="armazem" name="status" required>
                                                 <option selected disabled>Seleciona o status</option>
                                                 <option value="Valido" {{($armazem->status=="Valido")?'selected':''}}>Valido</option>
                                                 <option value="Arquivado" {{($armazem->status=="Arquivado")?'selected':''}}>Arquivado</option>  
                                                </select>
                                            </div>
                     <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Atualizar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
                  
                </form>
            </div>
           
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formEdit{{ $armazem->id }} = document.getElementById('formArmazemEdit{{ $armazem->id }}');
        if (formEdit{{ $armazem->id }}) {
            formEdit{{ $armazem->id }}.addEventListener('submit', function (event) {
                const descricao = document.getElementById('descricaoEdit{{ $armazem->id }}');
                const localizacao = document.getElementById('localizacaoEdit{{ $armazem->id }}');

                if (/^\s/.test(descricao.value)) {
                    alert('A descrição não pode começar com espaço.');
                    event.preventDefault();
                    return;
                }

                if (/^\s/.test(localizacao.value)) {
                    alert('A localização não pode começar com espaço.');
                    event.preventDefault();
                }
            });
        }
    });
</script>

                     <!-- Modal Deletar -->
                     <form   action="{{ route('armazens.arquivar', $armazem->id) }}" style="display:inline-block;">
                     
     
                        <div class="modal fade" id="deleteModal{{ $armazem->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog  modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Arquivar Armazem?</h5>
                                        <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Deseja realmente arquivar o armazém "{{ $armazem->descricao }}"?</p>
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
                <h5 class="modal-title">Adicionar Armazém</h5>
                <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('armazens.store') }}" id="formArmazemCreate">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" class="form-control" name="descricao" id="descricao" 
                               pattern="^[^\s].*" title="A descrição não pode começar com espaço." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Localização</label>
                        <input type="text" class="form-control" name="localizacao" id="localizacao"
                               pattern="^[^\s].*" title="A localização não pode começar com espaço." required>
                    </div> <div class="modal-footer">
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
    const form = document.getElementById('formArmazemCreate');
    form.addEventListener('submit', function (event) {
        const descricao = document.getElementById('descricao');
        const localizacao = document.getElementById('localizacao');

        if (/^\s/.test(descricao.value)) {
            alert('A descrição não pode começar com espaço.');
            event.preventDefault();
            return;
        }

        if (/^\s/.test(localizacao.value)) {
            alert('A localização não pode começar com espaço.');
            event.preventDefault();
        }
    });
});
</script>

@endsection