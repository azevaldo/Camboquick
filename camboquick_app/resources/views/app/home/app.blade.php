<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/adm/css/toastr.min.css')}}">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #eea60a;
        }
        .navbar-brand, .nav-link {
            color: white !important;
            transition: color 0.3s, transform 0.3s ease-in-out;
        }
        .nav-link:hover {
            color: #2c07ff !important;
            transform: scale(1.1);
        }
        .content-section {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80vh;
        }
        .content-box {
            text-align: center;
        }
        h2 {
            color: #eea60a;
        }
        .footer {
            background-color: #eea60a;
            color: white;
            text-align: center;
            padding: 20px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .footer .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 20px;
            transition: 0.3s;
        }
        .footer .social-icons a:hover {
            color: #2c07ff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#"> {{$empresa?$empresa->nome:'Camboquick'}}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                  
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="/login"><i class="bi bi-person-fill"></i> Entrar</a>
                        </li>
                    @else
                @hasanyrole('admin|gerente')
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard1"><i class="bi bi-house-door-fill"></i> Painel de controlo</a>
                        </li>
                @endhasanyrole   
                
                @hasanyrole('caixa')
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard2"><i class="bi bi-house-door-fill"></i> Painel de controlo</a>
                        </li>
                @endhasanyrole
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="modal" data-bs-target="#logoutModal" href="#"><i class="bi bi-box-arrow-right"></i> Sair</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

  @yield('content')
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirmação de Saida</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem a certeza de que deseja sair?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Sair</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
           <p>&copy; <span id="anoAtual"></span> {{ $empresa ? $empresa->nome : 'Camboquick' }}. Todos os direitos reservados.</p>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/adm/js/toastr.min.js') }}"></script>
     
 <script>
    document.getElementById('anoAtual').textContent = new Date().getFullYear();
</script>

  
    @if (session('logout'))
    <script>
        "use strict";
        var o = "rtl" === $("html").attr("data-textdirection");
    
            toastr.success("{{ session('logout') }}",
                '', {
                    closeButton: !0,
                    tapToDismiss: !0,
                    progressBar: !0,
                    positionClass: "toast-bottom-right",
                    rtl: o
                }
            );
       
    </script>
     @endif
    @if (session('login'))
        <script>
            $(document).ready(function() {
                var o = $("html").attr("data-textdirection") === "rtl";
                toastr.success("{{ session('login') }}", "", {
                    closeButton: true,
                    tapToDismiss: true,
                    progressBar: true,
                    positionClass: "toast-bottom-right",
                    rtl: o
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            $(document).ready(function() {
                var o = $("html").attr("data-textdirection") === "rtl";
                toastr.success("{{ session('success') }}", "", {
                    closeButton: true,
                    tapToDismiss: true,
                    progressBar: true,
                    positionClass: "toast-bottom-right",
                    rtl: o
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            $(document).ready(function() {
                var o = $("html").attr("data-textdirection") === "rtl";
                toastr.error("{{ session('error') }}", "", {
                    closeButton: true,
                    tapToDismiss: true,
                    progressBar: true,
                    positionClass: "toast-bottom-right",
                    rtl: o
                });
            });
        </script>
    @endif
</body>
</html>
