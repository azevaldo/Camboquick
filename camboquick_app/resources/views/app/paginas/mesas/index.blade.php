@extends('app.layouts.app')


@section('content')
<div class="page-header">
        <div class="page-title">
            <h4>Lista de Mesas</h4>
            <h6>Visualizar e pesquisar as mesas</h6>
        </div>

        <div class="page-btn">
            <a href="#" data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-added">
            <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Adicionar Mesa
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
                    <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="/empresa/assets/img/icons/pdf.svg" alt="img"></a></li>
                    <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="/empresa/assets/img/icons/excel.svg" alt="img"></a></li>
                    <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="/empresa/assets/img/icons/printer.svg" alt="img"></a></li>
                </ul>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table datanew">
                <thead>
                    <tr>
                        <th><label class="checkboxs"><input type="checkbox" id="select-all"><span class="checkmarks"></span></label></th>
                        <th>Número</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mesas as $mesa)
                    <tr class="{{ $mesa->status =="Arquivado" ? 'arquivado' : '' }}">
                        <td>
                            <label class="checkboxs"><input type="checkbox"><span class="checkmarks"></span></label>
                        </td>
                        <td>{{ $mesa->numero }}</td>
                        <td>
                            <a class="me-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $mesa->id }}" title="Editar">
                                <img src="/empresa/assets/img/icons/edit.svg" alt="img">
                            </a>
 @if($mesa->status=="Valido")
                            <a class="me-3 confirm-text" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $mesa->id }}" title="Arquivar">
                                <img src="/empresa/assets/img/icons/delete.svg" alt="img">
                            </a>
                            @endif
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $mesa->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog  modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Mesa</h5>
                                            <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{ route('mesas.update', $mesa->id) }}">
                                                @method('PUT')
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="numero" class="form-label">Número</label>
                                                    <input type="text" class="form-control" id="numero" name="numero" value="{{ $mesa->numero }}" min="1" required>
                                                </div>
                                                                      <div class="mb-3">
                                                <label for="categoria_id" class="form-label">Status</label>
                                                <select class="form-control" id="armazem" name="status" required>
                                                 <option selected disabled>Seleciona o status</option>
                                                 <option value="Valido" {{($mesa->status=="Valido")?'selected':''}}>Valido</option>
                                                 <option value="Arquivado" {{($mesa->status=="Arquivado")?'selected':''}}>Arquivado</option>  
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

                            <!-- Delete Modal -->
                            <form action="{{ route('mesas.arquivar', $mesa->id) }}"   style="display:inline-block;">
                                
                                <div class="modal fade" id="deleteModal{{ $mesa->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog  modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Arquivar Mesa?</h5>
                                                <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Tem certeza que deseja arquivar a mesa "{{ $mesa->numero }}"?</p>
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
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Mesa</h5>
                <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('mesas.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero"  min="1" required>
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

@endsection
