@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Telefones</h4>
    <h6>Vizualizar e pesquisar os telefones</h6>
    </div>
  
    <div class="page-btn">
    <a href=""  data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Adicionar Telefone
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
        <th>Numero</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach($telefones as $telefone)
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
            <a href="javascript:void(0);">{{ $telefone->numero }}</a>
       </td>
                       
              
       
   
       
        <td>
            

           
         

            <a href="#" class="me-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $telefone->id }}" title="Editar">
                <img src="/empresa/assets/img/icons/edit.svg" alt="img">
            </a>
            <a class="me-3 confirm-text" href="" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $telefone->id }}" title="Apagar">
                <img src="/empresa/assets/img/icons/delete.svg" alt="img">
                </a>
             
                                   <!-- Modal Editar -->
<div class="modal fade" id="editModal{{ $telefone->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $telefone->id }}" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Telefone</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('empresa.updateTelefone', $telefone->id) }}" class="formTelefoneEditar">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="form-label">Número</label>
            <input type="text" name="numero" value="{{ $telefone->numero }}" 
                   class="form-control inputNumeroEditar" 
                   required minlength="4" 
                   pattern="^[^\s][0-9+\-\s]{3,}$"
                   title="O número deve conter no mínimo 4 caracteres, não começar com espaço e conter apenas números, '+', '-' e espaços.">
          </div>
  <div class="modal-footer">
           <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
      </div>
   
        </form>
      </div>
    
    </div>
  </div>
</div>

<!-- Validação JS para todos os formulários de editar -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Seleciona todos os formulários de edição de telefone
    const forms = document.querySelectorAll(".formTelefoneEditar");

    forms.forEach(form => {
      form.addEventListener("submit", function (event) {
        const inputNumero = form.querySelector(".inputNumeroEditar");
        const valor = inputNumero.value.trim();
        const regex = /^[^\s][0-9+\-\s]{3,}$/;

        if (!regex.test(valor)) {
          alert("Número inválido. Deve conter no mínimo 4 caracteres, não começar com espaço e conter apenas números, '+', '-' e espaços.");
          event.preventDefault();
        }
      });
    });
  });
</script>



        

    
                                        <form action="{{ route('empresa.destroyTelefone', $telefone->id) }}" method="post" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                 
                                         
                                        
                                            <div class="modal" id="deleteModal{{$telefone->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog  modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class="d-flex justify-content-center align-items-center flex-grow-1">
                                                                <h5 class="modal-title">Apagar Telefone?</h5>
                                                            </div>
                                                            <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Tem a certeza que deseja apagar o número "{{ $telefone->numero}}"?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger">Apagar</button>
                                                            <button type="button" class="btn cancel" data-bs-dismiss="modal">Cancelar</button>
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
             <!-- Modal Criar -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Adicionar Telefone</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <form method="post" action="{{ route('empresa.addTelefone') }}" id="formTelefone">
                  @csrf
                  <div class="mb-3">
                      <label for="numero" class="form-label">Número</label>
                      <input type="text" class="form-control" name="numero" id="numero"
                             required minlength="4"
                             pattern="^[^\s][0-9+\-\s]{3,}$"
                             title="O número deve conter no mínimo 4 caracteres e não pode começar com espaço. Permitido: números, '+', '-' e espaços.">
                  </div>
                  <button type="submit" class="btn btn-primary">Adicionar</button>
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
          </div>
      </div>
  </div>
</div>

<!-- Validação JavaScript -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
      const form = document.getElementById("formTelefone");
      const numeroInput = document.getElementById("numero");

      form.addEventListener("submit", function (event) {
          const valor = numeroInput.value.trim();
          const regex = /^[^\s][0-9+\-\s]{3,}$/;

          if (!regex.test(valor)) {
              alert("Número inválido. Deve conter no mínimo 4 caracteres, não começar com espaço e conter apenas números, '+', '-' e espaços.");
              event.preventDefault();
          }
      });
  });
</script>

@endsection