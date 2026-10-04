<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="description" content="POS - Bootstrap Admin Template">
<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
<meta name="author" content="Dreamguys - Bootstrap Admin Template">
<meta name="robots" content="noindex, nofollow">
<title> {{$empresa?$empresa->nome:'Camboquick'}}</title>

<link rel="shortcut icon" type="image/x-icon" href="/empresa/assets/img/favicon.jpg">

<link rel="stylesheet" href="/empresa/assets/css/bootstrap.min.css">

<link rel="stylesheet" href="/empresa/assets/css/animate.css">

<link rel="stylesheet" href="/empresa/assets/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="/empresa/assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="/empresa/assets/plugins/fontawesome/css/all.min.css">

<link rel="stylesheet" href="/empresa/assets/css/style.css">


<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/empresa/assets/css/toastr.min.css">
 <style>
    .stock-baixo {
    background-color: #f8d7da !important;
    color: #721c24 !important;
}
 .arquivado {
    background-color: #b9b9b9 !important;
    color: #ffffff !important;
}
   .custom-centered-toast {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
    }
    .is-invalid {
    border-color: #dc3545;
    background-color: #ffe6e6;
}

 </style>
</head>
<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
        </div>


        <div class="main-wrapper">

            @include('app.partials.header')
            @include('app.partials.menu')
            <div class="page-wrapper">
                    <div class="content">
                        @yield('content')
                </div>
            </div>

        </div>

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

          <div class="modal fade" id="createModal2" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true"> 
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Fechar Turno</h5>
        <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="get" action="{{route('turno.fechar')}}" id="formCategoriaCreate">
          
        
                            <p>Tem a certeza que deseja fechar turno ?</p>
                       
          
             <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Fechar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
        </form>
      </div>
   
    </div>
  </div>
</div>

          <div class="modal fade" id="createModallito" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true"> 
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Abrir Turno</h5>
        <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="get" action="{{route('turno.abrir')}}" id="formCategoriaCreate">
        
        
                            <p>Tem a certeza que deseja abrir turno ?</p>
                       
          
             <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Abrir</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
      </div>
        </form>
      </div>
   
    </div>
  </div>
</div>


<div class="toast-container custom-centered-toast p-3" style="display: none;">
    <div id="stockToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="stockToastBody">
                <!-- Texto do alerta aqui -->
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

 



        <script src="/empresa/assets/js/jquery-3.6.0.min.js"></script>

<script src="/empresa/assets/js/feather.min.js"></script>

<script src="/empresa/assets/js/jquery.slimscroll.min.js"></script>

<script src="/empresa/assets/js/jquery.dataTables.min.js"></script>
<script src="/empresa/assets/js/dataTables.bootstrap4.min.js"></script>

<script src="/empresa/assets/js/bootstrap.bundle.min.js"></script>

<script src="/empresa/assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="/empresa/assets/plugins/apexchart/chart-data.js"></script>

<script src="/empresa/assets/js/script.js"></script>
<script src="/empresa/assets/js/toastr.min.js"></script>

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
                rtl: o,
                timeOut: 180000,          // 3 minutos antes de desaparecer automaticamente
                extendedTimeOut: 0        // Não estender o tempo ao passar o mouse
            });
        });
    </script>
    @php session()->forget('success') @endphp
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
                rtl: o,
                timeOut: 180000,          // 3 minutos
                extendedTimeOut: 0
            });
        });
    </script>
    @php session()->forget('error') @endphp
@endif


@if(request()->has('flash_error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    var encoded = {!! json_encode(request('flash_error')) !!};
    try {
        var msg = atob(encoded);
        var rtl = $("html").attr("data-textdirection") === "rtl";
        toastr.error(msg, "", {
            closeButton: true,
            tapToDismiss: true,
            progressBar: true,
            positionClass: "toast-bottom-right",
            rtl: rtl
        });
    } catch (err) {
        toastr.error("Ocorreu um erro.");
    }

    // remove o parâmetro da URL para não repetir
    const url = new URL(window.location.href);
    url.searchParams.delete('flash_error');
    window.history.replaceState({}, document.title, url);
});
</script>
@endif

@if(request()->has('flash_success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    var encoded = {!! json_encode(request('flash_success')) !!};
    try {
        var msg = atob(encoded);
        var rtl = $("html").attr("data-textdirection") === "rtl";
        toastr.success(msg, "", {
            closeButton: true,
            tapToDismiss: true,
            progressBar: true,
            positionClass: "toast-bottom-right",
            rtl: rtl
        });
    } catch (err) {
        toastr.success("Feito com sucesso.");
    }

    // remove o parâmetro da URL para não repetir
    const url = new URL(window.location.href);
    url.searchParams.delete('flash_success');
    window.history.replaceState({}, document.title, url);
});
</script>

@endif


 </body>
</html>