@extends('app.layouts.app')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div class="page-title">
        <h4 class="fw-bold">Lista de Mesas</h4>
        <p class="text-muted">Visualizar e pesquisar as mesas</p>
    </div>
    
</div>

<div class="row">
    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card shadow-sm h-100 position-relative">
            
            <!-- Ícone de Mais no Canto Superior Direito -->
            
            <a href="{{route('pedidos.add2')}}"   class="position-absolute top-0 end-0 m-2 text-warning" title="Criar Pedido Para Cliente">
                <i class="bi bi-plus-circle-fill fs-4"></i>
            </a>

            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h5 class="card-title">Pedidos Sem Mesa </h5>
                    <p class="card-text text-muted">
                       Meus Pedidos Abertos: 
                        <span class="fw-bold">
                           {{$pedidosSemMesa}}
                        </span>
                    </p>
                </div>
                <div class="mt-auto">
                    <a href="{{route('pedidos.semmesa')}}" class="btn btn-success w-100">
                        Listar Pedidos
                    </a>
                </div>
                  @hasanyrole('admin|gerente')
                      <div class="mt-auto">
                          <p class="card-text text-muted">
                      Todos Pedidos Abertos: 
                        <span class="fw-bold">
                           {{$todosPedidos}}
                        </span>
                    </p>
                    <a href="{{route('pedidos.semmesa2')}}" class="btn btn-success w-100">
                       Todos Pedidos
                    </a>
                </div>
                @endhasanyrole
            </div>
        </div>
    </div>
    @foreach($mesas as $mesa)
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card shadow-sm h-100 position-relative">
                
                <!-- Ícone de Mais no Canto Superior Direito -->
                
                <a href="{{route('pedidos.add',$mesa->id)}}"   class="position-absolute top-0 end-0 m-2 text-warning" title="Criar Pedido Para Cliente">
                    <i class="bi bi-plus-circle-fill fs-4"></i>
                </a>

                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">🪑 Mesa {{ $mesa->numero }}</h5>
                        <p class="card-text text-muted">
                           Meus Pedidos Abertos: 
                            <span class="fw-bold">
                                {{ $mesa->pedidos->where('status', 'aberto')->where('user_id', Auth::user()->id)->count() }}
                            </span>
                        </p>
                    </div>
                    <div class="mt-auto">
                         
                        <a href="{{ route('pedidos.mesa', $mesa->id) }}" class="btn btn-success w-100">
                            Listar Pedidos
                        </a>
                    </div>
                    @hasanyrole('admin|gerente')
                      <div class="mt-auto">
                          <p class="card-text text-muted">
                           Todos Pedidos Abertos: 
                            <span class="fw-bold">
                                {{ $mesa->pedidos->where('status', 'aberto')->count() }}
                            </span>
                        </p>
                        <a href="{{ route('pedidos.mesa2', $mesa->id) }}" class="btn btn-success w-100">
                           Todos Pedidos
                        </a>
                    </div>
                    @endhasanyrole
                </div>
            </div>
        </div>



        <div class="modal fade" id="createModal{{$mesa->id}}" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cadastrar Cliente</h5>
                        <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('categorias.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="categoria" name="nome_cliente" required>
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
    @endforeach

</div>


@endsection
