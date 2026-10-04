 @extends('app.home.app')

@section('content')
<section class="vh-100 d-flex justify-content-center align-items-center" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5 bg-white rounded-4">
                        <h2 class="text-center mb-4" style="color: #eea60a;">Entrar</h2>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <div class="text-danger mt-1 small">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>

                            <!-- Password -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="password">Palavra-Passe</label>
                                <input type="password" id="password" name="password" class="form-control form-control-lg" required>
                                @if ($errors->has('password'))
                                    <div class="text-danger mt-1 small">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>

                            <!-- Remember Me & Forgot -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Lembrar-me</label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none">Esqueceu-se da palavra-passe?</a>
                                @endif
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning btn-lg shadow-sm">Entrar</button>
                            </div>
                        </form>

                        <!-- Optional: Rodapé do login -->
                        <div class="mt-4 text-center small text-muted">
                            © {{ date('Y') }} {{ $empresa ? $empresa->nome : 'Camboquick' }}. Todos os direitos reservados.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
