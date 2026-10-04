@extends('app.auth.layout.app')
@section('title', 'Login')

@section('content')
    <div class="container p-4 d-flex justify-content-center h-100 align-items-center custom-login-container">
        <div class="row w-auto">

            <div class="col-12">
                <div class="login-container text-center">
                    <h1>
                        Entrar
                    </h1>
                </div>
            </div>

            <div class="col-12 container-login">
                <div class="card p-4 shadow">
                    <div class="card-body">
                        <form action="{{ route('password.store') }}" method="post">
                            @csrf<!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <div class="mb-3 container-email">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" name="email" value="{{old('email', $request->email)}}" required autofocus autocomplete="username"
                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                    placeholder="Digite o seu melhor email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Campo de Senha -->
                            <div class="mb-3 container-password">
                                <label for="password" class="form-label">Nova Senha</label>
                                <input id="password" type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Digite a nova Senha" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label for="password" class="form-label">Confirmar Senha</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Confirme a nova Senha" required autocomplete="new-password">

                            </div>

                            <div class="container-login-button">
                                <button class="btn btn-secondary w-100" type="submit">
                                    Salvar nova senha
                                </button>
                            </div>
                        </form>
                    </div>
                  </div>
            </div>
        </div>
    </div>
@endsection
