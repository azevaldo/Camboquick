@extends('app.layouts.app')

@section('content')
<style>
    .hover-notificacao {
        transition: background-color 0.3s ease;
    }

    .hover-notificacao:hover {
        background-color: #c23c49 !important; /* tom mais escuro de vermelho */
        color: white;
        cursor: pointer;
    }
</style>

<div class="row">
      @if ($totalNaoLidas > 0)
    <a href="{{ route('notificacoes') }}" style="text-decoration: none;">
        <div class="alert alert-danger mb-2 hover-notificacao">
            Tens {{ $totalNaoLidas }} Notificações Não Lidas!
        </div>
    </a>
@endif



    <h3>Vendas</h3>
    <div class="col-lg-3 col-sm-6 col-12">
    <div class="dash-widget">
    <div class="dash-widgetimg">
    <span><img src="/empresa/assets/img/icons/dash1.svg" alt="img"></span>
    </div>
    <div class="dash-widgetcontent">
    <h5><span >{{number_format($totalHoje,'2', ',', '.')}} kz</span></h5>
    <h6>Vendas Hoje</h6>
    <h6>{{$vendaHoje}} vendas</h6>

    </div>
    </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
    <div class="dash-widget dash1">
    <div class="dash-widgetimg">
    <span><img src="/empresa/assets/img/icons/dash2.svg" alt="img"></span>
    </div>
    <div class="dash-widgetcontent">
    <h5><span >{{number_format($totalSemana,'2',',','.')}} kz</span></h5>
    <h6>Vendas Semana</h6>
    <h6>{{$vendaSemana}} vendas</h6>
    </div>
    </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
    <div class="dash-widget dash2">
    <div class="dash-widgetimg">
    <span><img src="/empresa/assets/img/icons/dash3.svg" alt="img"></span>
    </div>
    <div class="dash-widgetcontent">
    <h5><span >{{number_format($totalMes,'2',',','.')}}kz</span></h5>
    <h6>Vendas Mês</h6>
    <h6>{{$vendaMes}} vendas</h6>
    </div>
    </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12">
    <div class="dash-widget dash3">
    <div class="dash-widgetimg">
    <span><img src="/empresa/assets/img/icons/dash4.svg" alt="img"></span>
    </div>
    <div class="dash-widgetcontent">
    <h5><span >{{number_format($totalAno,'2',',','.')}}kz</span></h5>
    <h6>Vendas Ano</h6>
    <h6>{{$vendaAno}} vendas</h6>
    </div>
    </div>
    </div>

<h3>Destaques</h3>
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
    <div class="dash-count">
    <div class="dash-counts">
    <h4>{{$gestores}}</h4>
    <h5>Gestores</h5>
    </div>
    <div class="dash-imgs">
    <i data-feather="user"></i>
    </div>
    </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
    <div class="dash-count das1">
    <div class="dash-counts">
    <h4>{{$caixas}}</h4>
    <h5>Caixas</h5>
    </div>
    <div class="dash-imgs">
    <i data-feather="user-check"></i>
    </div>
    </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
    <div class="dash-count das2">
    <div class="dash-counts">
    <h4>{{$fornecedores}}</h4>
    <h5>Fornecedores</h5>
    </div>
    <div class="dash-imgs">
    <i data-feather="file-text"></i>
    </div>
    </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
    <div class="dash-count das3">
    <div class="dash-counts">
    <h4>{{$armazens}}</h4>
    <h5>Armazens</h5>
    </div>
    <div class="dash-imgs">
    <i data-feather="file"></i>
    </div>
    </div>
    </div>
    </div>
@endsection