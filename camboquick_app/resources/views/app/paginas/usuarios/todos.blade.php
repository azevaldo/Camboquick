@extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
    <h4>Lista de Usuarios</h4>
    <h6>Vizualizar e pesquisar os Usuarios</h6>
    </div>
    <div class="page-btn">
    <a href=""    data-bs-toggle="modal" data-bs-target="#exampleModal"  class="btn btn-added">
    <img src="/empresa/assets/img/icons/plus.svg" class="me-1" alt="img">Add Usuario
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
        <th >Nome</th>
        <th >Sexo</th> 
        <th >Email</th> 
        <th>Papel</th> 
        <th>Ações</th>
        </tr>
        </thead>
        <tbody>

            @foreach($usuarios as $usuario)
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
        <a href="javascript:void(0);">{{ explode(" ", $usuario->name)[0]}}</a>
        </td>
    
        <td>{{$usuario->sexo}}</td>            
        <td>{{$usuario->email}}</td>
        <td>{{$usuario->getRoleNames()->first()}}</td>
       
        <td>
            
            @if($usuario->hasAnyRole(['gerente', 'caixa','arquivado']))
            <a class="me-3"data-bs-toggle="modal" data-bs-target="#editModal{{ $usuario->id }}" title="Editar Papel" >
                <img src="/empresa/assets/img/icons/edit.svg" alt="img">
                </a>
        @endif
      
           <!-- Edit Modal -->
           <div class="modal fade" id="editModal{{ $usuario->id }}" tabindex="-1">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{route('usuario.alterar.papel',$usuario->id)}}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Papel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="categoria_id" class="form-label">Papel *</label>
                                <select class="form-control" id="armazem" name="papel" required>
                                         <option selected disabled>Seleciona o Papel</option>
                                         <option value="admin">Admin</option>
                                         <option value="gerente">Gerente</option>
                                         
                                        <option value="caixa">Caixa</option>
                                               <option value="arquivado">Arquivado</option>
                                
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

    
        @if(!($usuario->tipo=="adm"))
        @if($usuario->hasAnyRole(['gerente', 'caixa']))
      <!--
 <a class="me-3 confirm-text" href=""data-bs-toggle="modal" data-bs-target="#deleteModal{{ $usuario->id }}" title="Apagar">
            <img src="/empresa/assets/img/icons/delete.svg" alt="img">
            </a>
      -->
        
        @endif
        @endif


        

        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="post" style="display:inline-block;">
            @csrf
            @method('DELETE')
       
            <div class="modal fade" id="apagarModal{{$usuario->id}}" tabindex="-1" aria-labelledby="apagarModalLabel{{$usuario->id}}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="d-flex justify-content-center align-items-center flex-grow-1">
                                <h5 class="modal-title">Apagar a usuario?</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza que deseja apagar a usuario "{{ $usuario->name }} "?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Apagar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
      
            <!-- Modal para Cadastro de Usuário -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="d-flex justify-content-center align-items-center flex-grow-1">
                                <h5 class="modal-title" id="registerModalLabel">Cadastrar Usuário</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf <!-- Token CSRF para proteger o formulário -->
                            
                                <!-- Nome -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Digite seu nome" required pattern="^[^\s].*" title="O nome não deve começar com um espaço.">
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            
                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Digite seu email" required>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            
                                <!-- Senha -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Senha *</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            
                              
                                <!-- Confirmar Senha -->
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Senha *</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme sua senha" required>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="categoria_id" class="form-label">Papel *</label>
                                    <select class="form-control" id="armazem" name="papel" required>
                                             <option selected value="" disabled>Seleciona o Papel</option>
                                             <option value="gerente">Gerente</option>
                                            <option value="caixa">Caixa</option>
                                      
                                    
                                    </select>
                                    @if ($errors->has('papel'))
                                    <span class="text-danger">{{ $errors->first('papel') }}</span>
                                    @endif
                                </div>
                                <!-- Sexo -->
                                <div class="mb-3">
                                    <label for="sexo" class="form-label">Sexo *</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="sexo_masculino" name="sexo" value="Masculino" {{ old('sexo') == 'masculino' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="sexo_masculino">Masculino</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="sexo_feminino" name="sexo" value="Feminino" {{ old('sexo') == 'feminino' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sexo_feminino">Feminino</label>
                                        </div>
                                     
                                    </div>
                                    @if ($errors->has('sexo'))
                                        <span class="text-danger">{{ $errors->first('sexo') }}</span>
                                    @endif
                                </div>
            
                                
                                <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Cadastrar</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                        </div>
                                <!-- Botão de cadastro -->
                               
                            </form>
                            
                        </div>
                    
                    </div>
                </div>
            </div>
@endsection