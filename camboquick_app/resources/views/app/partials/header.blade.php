<div class="header">

    <div class="header-left active">
    <a href="/" class="logo">
    <!--
        <img src="/empresa/assets/img/logo.png" alt="">
    -->
  <h1>  {{$empresa?$empresa->nome:'Camboquick'}}</h1>
    </a>
    
    <a href="/" class="logo-small">
        <h1>  {{$empresa?$empresa->nome:'Camboquick'}}</h1>
        <!--
        <img src="/empresa/assets/img/logo-small.png" alt="">
    !-->
       
    </a>
     
    </div>
    
    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
    <span class="bar-icon">
    <span></span>
    <span></span>
    <span></span>
    </span>
    </a>
    
    <ul class="nav user-menu">
    
    <li class="nav-item">
    <div class="top-nav-search">
    <a href="javascript:void(0);" class="responsive-search">
    <i class="fa fa-search"></i>
    </a>
    <form action="#">
    <div class="searchinputs">
    <input type="text" placeholder="Search Here ...">
    <div class="search-addon">
    <span><img src="/empresa/assets/img/icons/closes.svg" alt="img"></span>
    </div>
    </div>
    <a class="btn" id="searchdiv"><img src="/empresa/assets/img/icons/search.svg" alt="img"></a>
    </form>
    </div>
    </li>
     @if(!Auth::user()->turnos()->where('estado','aberto')->first())
 <button type="" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#createModallito" style="margin-top: 10px;">
            <i class="fas fa-door-open me-1"></i> Abrir Turno
        </button>
     @else
 <button type="" class="btn btn-danger btn-sm me-2" data-bs-toggle="modal" data-bs-target="#createModal2"  style="margin-top: 10px;">
            <i class="fas fa-door-closed me-1"></i> Fechar Turno
        </button>
     @endif

    
          
    <li class="nav-item dropdown has-arrow flag-nav">
    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
    <img src="/empresa/assets/img/flags/us1.png" alt="" height="20">
    </a>
    <div class="dropdown-menu dropdown-menu-right">
    <a href="javascript:void(0);" class="dropdown-item">
    <img src="/empresa/assets/img/flags/us.png" alt="" height="16"> English
    </a>
    <a href="javascript:void(0);" class="dropdown-item">
    <img src="/empresa/assets/img/flags/fr.png" alt="" height="16"> French
    </a>
    <a href="javascript:void(0);" class="dropdown-item">
    <img src="/empresa/assets/img/flags/es.png" alt="" height="16"> Spanish
    </a>
    <a href="javascript:void(0);" class="dropdown-item">
    <img src="/empresa/assets/img/flags/de.png" alt="" height="16"> German
    </a>
    </div>
    </li>
    
    
    <li class="nav-item dropdown">
   @hasanyrole('admin|gerente')
      <a href="{{route('notificacoes')}}" class="dropdown-toggle nav-link" >
    <img src="/empresa/assets/img/icons/notification-bing.svg" alt="img"> <span class="badge rounded-pill">--</span>
    </a>
   @endhasanyrole
    <div class="dropdown-menu notifications">
    <div class="topnav-dropdown-header">
    <span class="notification-title">Notifications</span>
    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
    </div>
    <div class="noti-content">
    <ul class="notification-list">
    <li class="notification-message">
    <a href="activities.html">
    <div class="media d-flex">
    <span class="avatar flex-shrink-0">
    <img alt="" src="/empresa/assets/img/profiles/avatar-02.jpg">
    </span>
    <div class="media-body flex-grow-1">
    <p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>
    <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
    </div>
    </div>
    </a>
    </li>
    <li class="notification-message">
    <a href="activities.html">
    <div class="media d-flex">
    <span class="avatar flex-shrink-0">
    <img alt="" src="/empresa/assets/img/profiles/avatar-03.jpg">
    </span>
    <div class="media-body flex-grow-1">
    <p class="noti-details"><span class="noti-title">Tarah Shropshire</span> changed the task name <span class="noti-title">Appointment booking with payment gateway</span></p>
    <p class="noti-time"><span class="notification-time">6 mins ago</span></p>
    </div>
    </div>
    </a>
    </li>
    <li class="notification-message">
    <a href="activities.html">
    <div class="media d-flex">
    <span class="avatar flex-shrink-0">
    <img alt="" src="/empresa/assets/img/profiles/avatar-06.jpg">
    </span>
    <div class="media-body flex-grow-1">
    <p class="noti-details"><span class="noti-title">Misty Tison</span> added <span class="noti-title">Domenic Houston</span> and <span class="noti-title">Claire Mapes</span> to project <span class="noti-title">Doctor available module</span></p>
    <p class="noti-time"><span class="notification-time">8 mins ago</span></p>
    </div>
    </div>
    </a>
    </li>
    <li class="notification-message">
    <a href="activities.html">
    <div class="media d-flex">
    <span class="avatar flex-shrink-0">
    <img alt="" src="/empresa/assets/img/profiles/avatar-17.jpg">
    </span>
    <div class="media-body flex-grow-1">
    <p class="noti-details"><span class="noti-title">Rolland Webber</span> completed task <span class="noti-title">Patient and Doctor video conferencing</span></p>
    <p class="noti-time"><span class="notification-time">12 mins ago</span></p>
    </div>
    </div>
    </a>
    </li>
    <li class="notification-message">
    <a href="activities.html">
    <div class="media d-flex">
    <span class="avatar flex-shrink-0">
    <img alt="" src="/empresa/assets/img/profiles/avatar-13.jpg">
    </span>
    <div class="media-body flex-grow-1">
    <p class="noti-details"><span class="noti-title">Bernardo Galaviz</span> added new task <span class="noti-title">Private chat module</span></p>
    <p class="noti-time"><span class="notification-time">2 days ago</span></p>
    </div>
    </div>
    </a>
    </li>
    </ul>
    </div>
    <div class="topnav-dropdown-footer">
    <a href="activities.html">View all Notifications</a>
    </div>
    </div>
    </li>
    
    <li class="nav-item dropdown has-arrow main-drop">
    <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
    <span class="user-img"><img src="/empresa/assets/img/profiles/avator1.jpg" alt="">
    <span class="status online"></span></span>
    </a>
    <div class="dropdown-menu menu-drop-user">
    <div class="profilename">
    <div class="profileset">
    <span class="user-img"><img src="/empresa/assets/img/profiles/avator1.jpg" alt="">
    <span class="status online"></span></span>
    <div class="profilesets">
    <h6>{{Auth::user()->name}}</h6>
    <h5>{{Auth::user()->getRoleNames()->first()}}</h5>
    </div>
    </div>
    <hr class="m-0">
    <a class="dropdown-item" href="/profile"> <i class="me-2" data-feather="user"></i> Meu Perfil</a>
    @hasanyrole('admin|gerente')
        <a class="dropdown-item" href="{{route('notificacoes')}}"> <i class="me-2" data-feather="user"></i> Notificações</a>
     @endhasanyrole
    <hr class="m-0">

  @if(!Auth::user()->turnos()->where('estado','aberto')->first())
  <a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#createModallito"> <i class="me-2" data-feather="user"></i> Abrir Turno</a>
 
     @else
  
          <a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#createModal2"> <i class="me-2" data-feather="user"></i> Fechar Turno</a>
     @endif


    <hr class="m-0">
    <a class="dropdown-item logout pb-0"  data-bs-toggle="modal" data-bs-target="#logoutModal"><img src="/empresa/assets/img/icons/log-out.svg" class="me-2" alt="img">Sair</a>
    </div>
    </div>
    </li>
    </ul>
    
    
    <div class="dropdown mobile-user-menu">
    <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
    <div class="dropdown-menu dropdown-menu-right">
    <a class="dropdown-item" href="/profile"> Meu Perfil</a>
    @hasanyrole('admin|gerente')
      <a class="dropdown-item" href="{{route('notificacoes')}}">  Notificações</a>
    @endhasanyrole
     @if(!Auth::user()->turnos()->where('estado','aberto')->first())
  <a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#createModallito">  Abrir Turno</a>
 
     @else
  
          <a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#createModal2">  Fechar Turno</a>
     @endif


  
    <a class="dropdown-item logout pb-0"  data-bs-toggle="modal" data-bs-target="#logoutModal"><img src="/empresa/assets/img/icons/log-out.svg" class="me-2" alt="img">Sair</a>
   </div>
    </div>
    
    </div>
    