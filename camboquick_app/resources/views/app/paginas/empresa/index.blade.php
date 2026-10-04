@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Empresa</h4>
    <h6>Vizualizar e pesquisar os produtos</h6>
    </div>
    @if($empresa==null)
    <div class="page-btn">
    <a href=""  data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Add Empresa
    </a>
    </div>
    @endif
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
         <th>Email</th>
<th>NIF</th>
       <th>Local</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @if($empresa)
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
            <a href="javascript:void(0);">{{ $empresa->nome }}</a>
       </td>
                       
              
       <td>{{ $empresa->email }}</td>
       <td>{{ $empresa->nif }}</td>
       <td>{{ $empresa->local }}</td>

   
       
        <td>
            

           
         

            <a href="#" class="me-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $empresa->id }}" title="Editar">
                <img src="/empresa/assets/img/icons/edit.svg" alt="img">
            </a>

    
            <a href="{{route('empresa.telefones')}}" class="me-3"   title="Telefones">
                <img src="/empresa/assets/img/icons/eye.svg" alt="img">
            </a>

     
       
  


        

    
        

         <!-- Modal Editar -->
 <!-- Modal Editar -->
 
 <div class="modal fade" id="editModal{{ $empresa->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
   
    <div class="modal-dialog  modal-dialog-centered">
       
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Editar Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="post" action="{{ route('empresas.update', $empresa->id) }}" id="formEmpresaEdit{{ $empresa->id }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="nomeEdit{{ $empresa->id }}"
                               value="{{ $empresa->nome }}"
                               pattern="^[^\s].*" title="O nome não pode começar com espaço." required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $empresa->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="nif" class="form-label">NIF</label>
                        <input type="text" class="form-control" name="nif" id="nifEdit{{ $empresa->id }}"
                               value="{{ $empresa->nif }}"
                               pattern="^[A-Za-z0-9]{14}$" title="O NIF deve conter exatamente 14 letras ou números." required>
                    </div>

                    <div class="mb-3">
                        <label for="local" class="form-label">Local</label>
                        <input type="text" class="form-control" name="local" id="localEdit{{ $empresa->id }}"
                               value="{{ $empresa->local }}"
                               pattern="^[^\s].*" title="O local não pode começar com espaço." required>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form{{ $empresa->id }} = document.getElementById('formEmpresaEdit{{ $empresa->id }}');

        form{{ $empresa->id }}.addEventListener('submit', function (event) {
            const nome = document.getElementById('nomeEdit{{ $empresa->id }}');
            const local = document.getElementById('localEdit{{ $empresa->id }}');
            const nif = document.getElementById('nifEdit{{ $empresa->id }}');

            if (/^\s/.test(nome.value)) {
                alert("O nome não pode começar com espaço.");
                event.preventDefault();
                return;
            }

            if (/^\s/.test(local.value)) {
                alert("O local não pode começar com espaço.");
                event.preventDefault();
                return;
            }

            if (!/^[A-Za-z0-9]{14}$/.test(nif.value)) {
                alert("O NIF deve conter exatamente 14 letras ou números.");
                event.preventDefault();
            }
        });
    });
</script>

        
          
 
        </td>
    </tr>
    @else
    <div class="alert alert-danger">Não Existe Nenhum Registro </div>        

               @endif
           </tbody>
        </table>
        </div>
        </div>
        </div>
          <!--Modal Criar-->
          <!--Modal Criar-->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <form method="post" action="{{ route('empresas.store') }}" id="formEmpresaCreate">
                    @csrf
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="nomeCreate"
                               pattern="^[^\s].*" title="O nome não pode começar com espaço." required>
                    </div>
                    <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                    <label for="nif" class="form-label">NIF</label>
                    <input type="text" class="form-control" name="nif" id="nifCreate"
                               pattern="^[A-Za-z0-9]{14}$" title="O NIF deve conter exatamente 14 letras ou números." required>
                    </div>
                    <div class="mb-3">
                    <label for="local" class="form-label">Local</label>
                    <input type="text" class="form-control" name="local" id="localCreate"
                               pattern="^[^\s].*" title="O local não pode começar com espaço." required>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formEmpresaCreate');
        form.addEventListener('submit', function (event) {
            const nome = document.getElementById('nomeCreate');
            const local = document.getElementById('localCreate');
            const nif = document.getElementById('nifCreate');
            if (/^\s/.test(nome.value)) {
                alert("O nome não pode começar com espaço.");
                event.preventDefault();
                return;
            }
            if (/^\s/.test(local.value)) {
                alert("O local não pode começar com espaço.");
                event.preventDefault();
                return;
            }

            if (!/^[A-Za-z0-9]{14}$/.test(nif.value)) {
                alert("O NIF deve conter exatamente 14 letras ou números.");
                event.preventDefault();
            }
        });
    });
</script>
@endsection