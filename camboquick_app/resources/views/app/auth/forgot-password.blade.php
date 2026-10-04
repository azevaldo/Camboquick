@extends('app.auth.layout.app')
@section('title', 'Recuperar senha')

@section('content')
    <div class="container p-4 d-flex justify-content-center h-100 align-items-center custom-login-container">
        <div class="row w-auto">

            <div class="col-12">
                <div class="login-container text-center">
                    <h1>
                        Recuperar senha
                    </h1>
                </div>
            </div>
            <div class="col-12 container-login">
                <a href="/" >Voltar na página inicial</a>
              @if(session("status"))
                <div class="alert alert-success">
                    {{session("status")}}
                </div>
                @endif
                <div class="card p-4 shadow">
                    <div class="card-body">
                        <form action="{{ route('password.email') }}" method="post">
                            @csrf
                            <div class="mb-3 container-email">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Digite o seu melhor email" required autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="container-login-button">
                                <button class="btn btn-secondary w-100" type="submit">
                                    Enviar Link de Recuperação
                                </button>
                            </div>
                       
                        </form>
                    </div>
                  </div>
                
            </div>
        </div>
    </div>
@endsection
