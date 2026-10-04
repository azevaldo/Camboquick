@extends('app.layouts.app')



@section('content')
<div class="page-header">
    
    <div class="page-title">
    <h4>Lista De Produtos</h4>
    <h6>Vizualizar e pesquisar os produtos</h6>
    </div>
    @hasanyrole('admin|gerente')
    <div class="page-btn">
    <a href=""  data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Adicionar Produto
    </a>

     <a href=""  data-bs-toggle="modal" data-bs-target="#inventario"  class="btn btn-added mt-4">
     Guardar Inventario
    </a>
    </div>
    @endhasanyrole
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
        <th>SubCat</th>
        <th>Qnt Grosso</th>
                <th>Qnt Restante</th>
        <th>Qnt Retalho</th>
        <th>Preço</th>
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach ($produtos as $produto)



 @if($produto->status=="Valido")
    <tr class="{{ $produto->quantidade <= $produto->limite_minimo ? 'stock-baixo' : '' }}">
@else
    <tr class="arquivado">
        @endif
     

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
        <a href="javascript:void(0);">{{ $produto->nome }}</a>
       </td>
                       
        <td>{{ $produto->subcategoria->sub }}</td>
        <td>{{ $produto->quantidade }}</td>
        
          <td>{{  $produto->quantidade_retalho- $produto->quantidade*$produto->quant_uni_grosso }}</td>
        
        <td>{{ $produto->quantidade_retalho }}</td>

        <td>{{ number_format($produto->preco, 2, ',', '.') }}</td>

   
       
        <td>
            <a class="me-3" href="#"  data-bs-toggle="modal" data-bs-target="#modalVisualizar{{ $produto->id }}" title="Visualizar">
                 
                <img src="/empresa/assets/img/icons/eye.svg" alt="img">
            </a> 

           
            @hasanyrole('admin|gerente')

            <a href="#" class="me-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $produto->id }}" title="Editar">
                <img src="/empresa/assets/img/icons/edit.svg" alt="img">
            </a>

            @endhasanyrole


     
       
        @hasanyrole('admin|gerente')
        @if($produto->status=="Valido")
        <a href="#" class="me-3 confirm-text" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $produto->id }}" title="Arquivar">
            
            <img src="/empresa/assets/img/icons/delete.svg" alt="img"></a>
        @endif
            @endhasanyrole


        

        <div class="modal fade" id="modalVisualizar{{ $produto->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Detalhes do Produto Nº {{ $produto->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                         
                            <tr>
                                <th>Nome</th>
                                <td>{{ $produto->nome }}</td>
                            </tr>
                             <tr>
                                <th>Status</th>
                                <td>{{ $produto->status }}</td>
                            </tr>
                            <tr>
                                <th>Quantidade Grosso</th>
                                <td>{{ $produto->quantidade }}</td>
                            </tr>
                                                        <tr>
                                <th>Quantidade Restante</th>
                                <td>       {{  $produto->quantidade_retalho- $produto->quantidade*$produto->quant_uni_grosso }}</td>
                            </tr>
                            
                            
                     
                             <tr>
                                <th>Quantidade Retalho</th>
                                <td> {{$produto->quantidade_retalho}}</td>
                            </tr>
                            <tr>
                                <th>Preço</th>
                                <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                            </tr>
                             <tr>
                                <th>Custo De Stock Atual</th>
                                <td>R$ {{ number_format(bcmul(strval( $produto->custo_m_p_retalho),strval($produto->quantidade_retalho),20), 2, ',', '.') }}</td>
                            </tr>
                         
                      
                               <th>Custo Medio Ponderado Grosso</th>
                                <td>R$ {{ number_format($produto-> custo_m_p, 2, ',', '.') }}</td>
                            </tr> 

                            <th>Custo Medio Ponderado Retalho</th>
                                <td>R$ {{ number_format($produto->custo_m_p_retalho, 2, ',', '.') }}</td>
                            </tr>

                           
                            <tr>
                                <th>Quantidade Unidades Por Grosso</th>
                                <td> {{$produto->quant_uni_grosso}}</td>
                            </tr>
                           
                            <tr>
                                <th>Limite Mínimo</th>
                                <td>{{ $produto->limite_minimo }}</td>
                            </tr>
                            <tr>
                                <th>Imposto (%)</th>
                                <td>{{ $produto->imposto }}%</td>
                            </tr>
                            <tr>
                                <th>Código</th>
                                <td>{{ $produto->codigo }}</td>
                            </tr>
                            <tr>
                                <th>Slug</th>
                                <td>{{ $produto->slug }}</td>
                            </tr>
                            <tr>
                                <th>Subcategoria</th>
                                <td>{{ $produto->subCategoria->sub ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Armazém</th>
                                <td>{{ $produto->armazem->descricao?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Criado em</th>
                                <td>{{ $produto->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Última Atualização</th>
                                <td>{{ $produto->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="modal fade" id="editModal{{ $produto->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $produto->id }}" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
        
                    <div class="modal-body">
                        <form method="post" action="{{ route('produtos.update', $produto->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="nome{{ $produto->id }}" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome{{ $produto->id }}" name="nome" value="{{ $produto->nome }}" required pattern="^[^\s].*" title="O nome não deve começar com um espaço.">
                            </div>
        
                 <!-- Parte do modal (apenas o trecho relevante) -->
<div class="mb-3">
    <label for="categoria{{ $produto->id }}" class="form-label">Categoria</label>
    <select class="form-control categoria-select" id="categoria{{ $produto->id }}" name="categoria_id"
            data-produto-id="{{ $produto->id }}" required>
        @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}"
                {{ optional($produto->subcategoria)->categoria_id == $categoria->id ? 'selected' : '' }}>
                {{ $categoria->categoria }}
            </option>
        @endforeach
    </select>
</div>
    <label for="subcategoria{{ $produto->id }}" class="form-label">SubCategoria</label>
<select class="form-control" id="subcategoria{{ $produto->id }}" name="subCategoria_id" required
        data-subcategoria-atual="{{ $produto->subCategoria_id ?? '' }}">
    <option value="" disabled selected>Seleciona a SubCategoria</option>
</select>

        
        
        
                             
        
                            <div class="mb-3">
                                <label for="preco{{ $produto->id }}" class="form-label">Preço</label>
                                <input type="number" class="form-control" id="preco{{ $produto->id }}" min="1" step="0.01" name="preco" value="{{number_format($produto->preco,2,'.','') }}"  required>
                            </div>
         <div class="mb-3">
                                                <label for="categoria_id" class="form-label">Armazem</label>
                                                <select class="form-control" id="armazem" name="armazem_id" required>
                                                         <option selected disabled>Seleciona o Armazem</option>
                                                    @foreach($armazens as $armazem)
        
                                                        <option value="{{ $armazem->id }}" {{($armazem->id==$produto->armazem_id)?'selected':''}}>{{ $armazem->descricao }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="mb-3">
                                                <label for="categoria_id" class="form-label">Status</label>
                                                <select class="form-control" id="armazem" name="status" required>
                                                 <option selected disabled>Seleciona o status</option>
                                                 <option value="Valido" {{($produto->status=="Valido")?'selected':''}}>Valido</option>
                                                 <option value="Arquivado" {{($produto->status=="Arquivado")?'selected':''}}>Arquivado</option>  
                                                </select>
                                            </div>
                                             <div class="mb-3">
                                                <label for="preco" class="form-label">Imposto</label>
                                                <input type="number" class="form-control" min="0" max="100" id="imposto" value="{{ $produto->imposto }}" name="imposto" required>
                                            </div>
                                              <div class="mb-3">
                                                <label for="preco" class="form-label">Limite Minimo</label>
                                                <input type="number" class="form-control" id="limite_minimo" min="0" value="{{ $produto->limite_minimo}}" name="limite_minimo" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="preco" class="form-label">Quantidade Unidade Por Grosso</label>
                                                <input type="number" class="form-control" id="limite_minimo" min="1" value="{{$produto->quant_uni_grosso}}" name="quant_uni_grosso" required>
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

    const baseUrl = "{{ url('subcategorias/buscar') }}"; // rota que retorna JSON [ {id, sub}, ... ]
    const cache = new Map(); // cache simples por categoriaId

    // util: debounce
    function debounce(fn, wait) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    // Para cada modal (manténs modal por produto)
    document.querySelectorAll('.modal').forEach(modal => {
        // controller por modal para abortar requests quando necessário
        let currentAbortController = null;

        modal.addEventListener('shown.bs.modal', function () {
            // Extrair id do produto a partir do id do modal (ex: editModal123)
            const produtoId = this.id.replace('editModal', '');

            const categoriaSelect = document.getElementById('categoria' + produtoId);
            const subcategoriaSelect = document.getElementById('subcategoria' + produtoId);

            if (!categoriaSelect || !subcategoriaSelect) return;

            // Garantir que data-subcategoria-atual existe
            const subCategoriaAtual = subcategoriaSelect.dataset.subcategoriaAtual || '';

            // Função que efetivamente carrega e popula (com cancelamento e cache)
            const carregarSubcategorias = async function(categoriaId, marcarId = '') {
                if (!categoriaId) {
                    subcategoriaSelect.innerHTML = '<option value="" disabled selected>Seleciona a SubCategoria</option>';
                    return;
                }

                // se cache existe, usa imediatamente e não faz fetch
                if (cache.has(categoriaId)) {
                    const data = cache.get(categoriaId);
                    popularSelect(subcategoriaSelect, data, marcarId);
                    return;
                }

                // Abort previous request if still pending
                if (currentAbortController) currentAbortController.abort();
                currentAbortController = new AbortController();
                const signal = currentAbortController.signal;

                // UI: bloquear e mostrar carregando
                subcategoriaSelect.disabled = true;
                const previousHTML = subcategoriaSelect.innerHTML;
                subcategoriaSelect.innerHTML = '<option disabled selected>Carregando...</option>';

                try {
                    const res = await fetch(`${baseUrl}/${categoriaId}`, { signal });
                    if (!res.ok) throw new Error('Resposta do servidor não OK: ' + res.status);

                    const data = await res.json();

                    // guarda no cache
                    cache.set(categoriaId, data);

                    // Só popular se a categoriaSelect ainda tem o mesmo valor (evita race)
                    if (String(categoriaSelect.value) === String(categoriaId)) {
                        popularSelect(subcategoriaSelect, data, marcarId);
                    } else {
                        // Se mudou entre requisição e resposta, ignora (a mudança irá disparar novo load)
                        // opcional: do nothing
                    }

                } catch (err) {
                    if (err.name === 'AbortError') {
                        // requisição foi cancelada - isto é esperado em change rápido
                        //console.log('fetch abortado para categoria', categoriaId);
                    } else {
                        console.error('Erro ao buscar subcategorias:', err);
                        subcategoriaSelect.innerHTML = '<option value="" disabled>Erro ao carregar</option>';
                    }
                } finally {
                    subcategoriaSelect.disabled = false;
                }
            };

            // função para popular o select
            function popularSelect(selectEl, data, marcarId = '') {
                selectEl.innerHTML = '<option value="" disabled>Seleciona a SubCategoria</option>';
                data.forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value = sub.id;
                    opt.textContent = sub.sub;
                    if (String(sub.id) === String(marcarId)) opt.selected = true;
                    selectEl.appendChild(opt);
                });
                selectEl.disabled = false;
            }

            // Debounced wrapper para evitar múltiplos fetch rápidos
            const debouncedCarregar = debounce((catId, marcar) => carregarSubcategorias(catId, marcar), 150);

            // Ao abrir o modal, carrega subcategorias da categoria atual (marcando a subcategoria atual)
            const categoriaIdInicial = categoriaSelect.value;
            if (categoriaIdInicial) {
                debouncedCarregar(categoriaIdInicial, subCategoriaAtual);
            } else {
                subcategoriaSelect.innerHTML = '<option value="" disabled selected>Seleciona a SubCategoria</option>';
            }

            // Antes de adicionar event listener, removemos listeners anteriores para evitar duplicação
            // (usamos um atributo no elemento para sinalizar que já tem listener)
            if (!categoriaSelect.dataset.listenerAttached) {
                categoriaSelect.dataset.listenerAttached = '1';

                categoriaSelect.addEventListener('change', function () {
                    const novaId = this.value;
                    // limpar seleção antiga imediatamente
                    subcategoriaSelect.innerHTML = '<option disabled selected>Carregando...</option>';
                    // chama a versão com debounce
                    debouncedCarregar(novaId, '');
                });
            }

        }); // end shown.bs.modal

        // Quando fecham o modal, abortar requisição pendente para limpar recursos
        modal.addEventListener('hidden.bs.modal', function () {
            if (currentAbortController) {
                try { currentAbortController.abort(); } catch(e) {}
            }
        });

    }); // end loop modals

});
</script>
  
           <!-- Delete Modal -->
           <form action="{{ route('produtos.arquivar', $produto->id) }}"  style="display:inline-block;">
        
            
            <div class="modal" id="deleteModal{{ $produto->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Arquivar Produto?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza que deseja arquivar o produto "{{ $produto->nome }}"?</p>
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
          <!-- Create Modal -->
          <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h5 class="modal-title">Adicionar Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form method="post" action="{{ route('produtos.store') }}">
                            @csrf
                   <div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" class="form-control" id="nome" name="nome" 
           required pattern="^[^\s].*" title="O nome não deve começar com um espaço.">
</div>

                            <div class="mb-3">
                                <label for="categoria_id" class="form-label">Categoria</label>
                                <select class="form-control" id="categoria12" name="categoria_id" required>
                                         <option value="" selected disabled>Seleciona a Categoria</option>
                                    @foreach($categorias as $categoria)

                                        <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="escala_id" class="form-label">SubCategoria</label>
                                <select class="form-control" id="subcategoria12" name="subCategoria_id" required>
                                      <option value="" selected disabled>Seleciona a SubCategoria</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="preco" class="form-label">Preço</label>
                                <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="1" required>
                            </div>
                         <div class="mb-3">
                                <label for="categoria_id" class="form-label">Armazem</label>
                                <select class="form-control" id="armazem" name="armazem_id" required>
                                         <option selected disabled>Seleciona o Armazem</option>
                                    @foreach($armazens as $armazem)

                                        <option value="{{ $armazem->id }}">{{ $armazem->descricao }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="mb-3">
                                <label for="preco" class="form-label">Imposto</label>
                                <input type="number" class="form-control" min="0" max="100" id="imposto" name="imposto" required>
                            </div>
                                   <div class="mb-3">
                                <label for="preco" class="form-label">Limite Minimo</label>
                                <input type="number" class="form-control" id="limite_minimo" min="0" name="limite_minimo" required>
                            </div>
                            <div class="mb-3">
                                <label for="preco" class="form-label">Quantidade Unidade Por Grosso</label>
                                <input type="number" class="form-control" id="limite_minimo" min="1"  name="quant_uni_grosso" required>
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
        


        
            <div class="modal fade" id="inventario" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true"> 
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Guardar Inventario</h5>
        <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="get" action="{{route('inventarios.store')}}" id="formCategoriaCreate">
        
        
                            <p>Tem a certeza que deseja guardar o inventario ?</p>
                       
          
             <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
      </div>
        </form>
      </div>
   
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const baseUrl = "{{ url('subcategorias/buscar') }}"; // rota JSON [id, sub, ...]

    const categoriaCreate = document.getElementById('categoria12');
    const subcategoriaCreate = document.getElementById('subcategoria12');
    let abortControllerCreate = null;

    // Debounce simples
    function debounce(fn, wait) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    async function carregarSubcategorias(categoriaId) {
        if (!categoriaId) {
            subcategoriaCreate.innerHTML = '<option value="" disabled selected>Seleciona a SubCategoria</option>';
            return;
        }

        // Cancelar requisição anterior se houver
        if (abortControllerCreate) abortControllerCreate.abort();
        abortControllerCreate = new AbortController();
        const signal = abortControllerCreate.signal;

        // UI: mostrar carregando e desabilitar select
        subcategoriaCreate.disabled = true;
        subcategoriaCreate.innerHTML = '<option disabled selected>Carregando...</option>';

        try {
            const res = await fetch(`${baseUrl}/${categoriaId}`, { signal });
            if (!res.ok) throw new Error('Resposta do servidor não OK: ' + res.status);

            const data = await res.json();

            // Popular select
            subcategoriaCreate.innerHTML = '<option value="" disabled selected>Seleciona a SubCategoria</option>';
            data.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = sub.sub;
                subcategoriaCreate.appendChild(opt);
            });

        } catch (err) {
            if (err.name === 'AbortError') {
                // requisição cancelada, ignora
            } else {
                console.error('Erro ao carregar subcategorias:', err);
                subcategoriaCreate.innerHTML = '<option value="" disabled>Erro ao carregar</option>';
            }
        } finally {
            subcategoriaCreate.disabled = false;
        }
    }

    // Debounce no change
    categoriaCreate.addEventListener('change', debounce(function () {
        carregarSubcategorias(this.value);
    }, 150));

    // Se quiser carregar a primeira categoria ao abrir o modal automaticamente
    $('#createModal').on('shown.bs.modal', function () {
        const catId = categoriaCreate.value;
        if (catId) {
            carregarSubcategorias(catId);
        }
    });

});
</script>

@endsection