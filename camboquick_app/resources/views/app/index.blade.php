 @extends('app.home.app')

@section('content')
<section class="vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row align-items-center shadow-lg rounded-4 bg-white p-4">
            <!-- Imagem ilustrativa -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg" 
                     class="img-fluid rounded-start" 
                     alt="Imagem ilustrativa">
            </div>

            <!-- Conteúdo da Página Inicial -->
            <div class="col-lg-6">
                <div class="px-3 py-4">
                    <h1 class="mb-4 text-primary fw-bold">Bem-vindo à Camboquick</h1>
                    <p class="mb-4 text-muted" style="font-size: 1.1rem;">
                        Descubra uma plataforma feita para facilitar o seu dia a dia. Explore nossos serviços, consulte produtos e faça suas operações com conforto e segurança.
                    </p>

                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-primary btn-lg shadow-sm">Saiba Mais</a>
                        @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg shadow-sm">Entrar</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
