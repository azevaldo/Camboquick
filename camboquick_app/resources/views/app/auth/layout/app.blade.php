<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de senha</title>
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
 

  @yield('content')
    
 
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
