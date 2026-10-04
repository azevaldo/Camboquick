@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Lista de Fornecedores</h4>
    <h6>Vizualizar e pesquisar fornecedores</h6>
    </div>
    <div class="page-btn">
    <a href=""  data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Adicionar Fornecedor
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
        <th>Nome</th>
        <th>NIF</th>
        <th>Contato</th>
        <th>Localização</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($fornecedores as $fornecedor)
       <tr class="{{ $fornecedor->status =="Arquivado" ? 'arquivado' : '' }}">
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
        <a href="javascript:void(0);">{{ $fornecedor->nome }}</a>
        </td>
      
        <td>{{ $fornecedor->nif }}</td>
        <td>{{ $fornecedor->contato }}</td>
        <td>{{ $fornecedor->localizacao }}</td>
        <td>
           
        <a class="me-3"   data-bs-toggle="modal" data-bs-target="#editModal{{ $fornecedor->id }}" title="Editar" >
        <img src="/empresa/assets/img/icons/edit.svg" alt="img">
        </a>
        @if($fornecedor->status=="Valido")
        <a class="me-3 confirm-text" href=""data-bs-toggle="modal" data-bs-target="#deleteModal{{ $fornecedor->id }}" title="Arquivar">
        <img src="/empresa/assets/img/icons/delete.svg" alt="img">
        </a>
        @endif



        

        

           <!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $fornecedor->id }}" tabindex="-1">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('fornecedores.update', $fornecedor->id) }}" id="formEditFornecedor{{ $fornecedor->id }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Fornecedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nome</label>
                        <input type="text" name="nome" id="editNome{{ $fornecedor->id }}" class="form-control"
                               value="{{ $fornecedor->nome }}" required
                               pattern="^[^\s][A-Za-zÀ-ú\s]*$"
                               title="O nome deve começar com uma letra e não conter apenas espaços.">
                    </div>
                    <div class="mb-3">
                        <label>NIF</label>
                        <input type="text" name="nif" id="editNif{{ $fornecedor->id }}" class="form-control"
                               value="{{ $fornecedor->nif }}" required
                               pattern="^[A-Za-z0-9]{14}$"
                               maxlength="14" minlength="14"
                               title="O NIF deve conter exatamente 14 caracteres (letras ou números).">
                    </div>
                    <div class="mb-3">
                        <label>Contato</label>
                        <input type="text" name="contato" id="editContato{{ $fornecedor->id }}" class="form-control"
                               value="{{ $fornecedor->contato }}" required
                               pattern="^[0-9+\-\s]+$"
                               title="O contato deve conter apenas números, espaços, '+' ou '-'">
                    </div>
                    <div class="mb-3">
                        <label>Localização</label>
                        <input type="text" name="localizacao" id="editLocalizacao{{ $fornecedor->id }}" class="form-control"
                               value="{{ $fornecedor->localizacao }}"
                               pattern="^[^\s][A-Za-zÀ-ú\s]*$"
                               title="A localização deve começar com uma letra e não conter apenas espaços.">
                    </div>
                                      <div class="mb-3">
                                                <label for="categoria_id" class="form-label">Status</label>
                                                <select class="form-control" id="armazem" name="status" required>
                                                 <option selected disabled>Seleciona o status</option>
                                                 <option value="Valido" {{($fornecedor->status=="Valido")?'selected':''}}>Valido</option>
                                                 <option value="Arquivado" {{($fornecedor->status=="Arquivado")?'selected':''}}>Arquivado</option>  
                                                </select>
                                            </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Validação JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const formId = "formEditFornecedor{{ $fornecedor->id }}";
        const form = document.getElementById(formId);
        const nome = document.getElementById("editNome{{ $fornecedor->id }}");
        const nif = document.getElementById("editNif{{ $fornecedor->id }}");
        const contato = document.getElementById("editContato{{ $fornecedor->id }}");
        const local = document.getElementById("editLocalizacao{{ $fornecedor->id }}");

        if (form) {
            form.addEventListener("submit", function (event) {
                const nomeRegex = /^[^\s][A-Za-zÀ-ú\s]*$/;
                const nifRegex = /^[A-Za-z0-9]{14}$/;
                const contatoRegex = /^[0-9+\-\s]+$/;
                const localRegex = /^[^\s][A-Za-zÀ-ú\s]*$/;

                if (!nomeRegex.test(nome.value.trim())) {
                    alert("Nome inválido. Deve começar com uma letra e não conter apenas espaços.");
                    event.preventDefault();
                    return;
                }

                if (!nifRegex.test(nif.value.trim())) {
                    alert("NIF inválido. Deve conter exatamente 14 caracteres alfanuméricos.");
                    event.preventDefault();
                    return;
                }

                if (!contatoRegex.test(contato.value.trim())) {
                    alert("Contato inválido. Use apenas números, espaços, '+' ou '-'.");
                    event.preventDefault();
                    return;
                }

                if (local.value.trim() !== "" && !localRegex.test(local.value.trim())) {
                    alert("Localização inválida. Deve começar com uma letra e não conter apenas espaços.");
                    event.preventDefault();
                    return;
                }
            });

            // Limitar NIF a 14 caracteres ao digitar
            nif.addEventListener("input", function () {
                if (this.value.length > 14) {
                    this.value = this.value.slice(0, 14);
                }
            });
        }
    });
</script>


       <!-- Delete Modal -->
       <div class="modal fade" id="deleteModal{{ $fornecedor->id }}" tabindex="-1">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <form  action="{{ route('fornecedores.arquivar', $fornecedor->id) }}">
                  
                    <div class="modal-header">
                        <h5 class="modal-title">Arquivar Fornecedor?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja arquivar o fornecedor <strong>{{ $fornecedor->nome }}</strong>?</p>
                    </div>
                     <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Arquivar</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
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
          <!-- Create Modal -->
      <div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('fornecedores.store') }}" id="formFornecedor">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Fornecedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3"> 
                        <label>Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" required
                            pattern="^[^\s][A-Za-zÀ-ú\s]*$"
                            title="O nome deve começar com uma letra e não pode conter apenas espaços.">
                    </div>

                    <div class="mb-3">
                        <label>NIF</label>
                        <input type="text" name="nif" id="nif" class="form-control" required
                            pattern="^[A-Za-z0-9]{14}$"
                            title="O NIF deve conter exatamente 14 caracteres (letras ou números)."
                            maxlength="14" minlength="14">
                    </div>

                    <div class="mb-3">
                        <label>Contato</label>
                        <input type="text" name="contato" id="contato" class="form-control" required
                            pattern="^[0-9+\-\s]+$"
                            title="O contato deve conter apenas números, espaços, '+' ou '-'">
                    </div>

                    <div class="mb-3">
                        <label>Localização</label>
                        <input type="text" name="localizacao" id="localizacao" class="form-control"
                            pattern="^[^\s][A-Za-zÀ-ú\s]*$"
                            title="A localização deve começar com uma letra e não conter apenas espaços.">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Validação JavaScript -->
<script>
document.getElementById("formFornecedor").addEventListener("submit", function (event) {
    const nome = document.getElementById("nome").value.trim();
    const nif = document.getElementById("nif").value.trim();
    const contato = document.getElementById("contato").value.trim();
    const local = document.getElementById("localizacao").value.trim();

    const nomeRegex = /^[^\s][A-Za-zÀ-ú\s]*$/;
    const nifRegex = /^[A-Za-z0-9]{14}$/;
    const contatoRegex = /^[0-9+\-\s]+$/;
    const localRegex = /^[^\s][A-Za-zÀ-ú\s]*$/;

    if (!nomeRegex.test(nome)) {
        alert("Nome inválido. Deve começar com uma letra e não conter apenas espaços.");
        event.preventDefault();
        return;
    }

    if (!nifRegex.test(nif)) {
        alert("NIF inválido. Deve conter exatamente 14 caracteres alfanuméricos.");
        event.preventDefault();
        return;
    }

    if (!contatoRegex.test(contato)) {
        alert("Contato inválido. Use apenas números, espaços, '+' ou '-'.");
        event.preventDefault();
        return;
    }

    if (local && !localRegex.test(local)) {
        alert("Localização inválida. Deve começar com uma letra e não conter apenas espaços.");
        event.preventDefault();
        return;
    }
});

// Bloquear mais de 14 caracteres ao digitar no NIF
document.getElementById("nif").addEventListener("input", function () {
    if (this.value.length > 14) {
        this.value = this.value.slice(0, 14);
    }
});
</script>


@endsection