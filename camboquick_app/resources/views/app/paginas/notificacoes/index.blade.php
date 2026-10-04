 @extends('app.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h4>Lista de Notificações</h4>
    </div>
    <div class="page-btn">
        <a href="{{ route('produtos.index') }}" class="btn btn-added">
             Ver Stock
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($notificacoes->count() > 0)
            @foreach ($notificacoes as $notificacao)
                <div class="alert {{ $notificacao->read_at ? 'alert-light' : 'alert-warning' }} d-flex justify-content-between align-items-center hover-notificacao mb-3">
                    <div>
                        <strong>{{ $notificacao->data['mensagem'] ?? 'Notificação sem mensagem' }}</strong><br>
                        <small>{{ $notificacao->created_at->diffForHumans() }}</small>
                    </div>

                    <div class="btn-group">
                        @if (is_null($notificacao->read_at))
                            <form action="{{ route('notificacoes.marcarLida', $notificacao->id) }}" method="POST" class="me-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-check"></i> Marcar como Lida
                                </button>
                            </form>
                        @else
                            <form action="{{ route('notificacoes.marcarNaoLida', $notificacao->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fa fa-undo"></i> Marcar como Não Lida
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="pagination-container">
                    {{ $notificacoes->links('pagination::bootstrap-5') }}
                </div>
        @else
            <div class="alert alert-info">Nenhuma notificação encontrada.</div>
        @endif
    </div>
</div>

<style>
    .hover-notificacao {
        transition: background-color 0.3s ease;
    }

    .hover-notificacao:hover {
        background-color: #f0f0f0;
        cursor: pointer;
    }

    .btn-group .btn {
        min-width: 160px;
    }
</style>
@endsection
