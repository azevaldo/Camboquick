<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Documento De Perdas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
   body {
  font-size: 14px;
  width: 210mm;
  min-height: 297mm;
  margin: auto;
  padding: 20mm;
  box-sizing: border-box;
  background: #ffffff;
}
.container-a4 {
  max-width: 210mm;
  margin: auto;
  background: #fff;
  box-shadow: 0 0 5px rgba(0,0,0,0.1);
}

/* 🖨️ Estilos só para impressão */
@media print {
  @page {
    size: A4;
    margin: 20mm;
  }

  html, body {
    width: auto !important;
    min-height: auto !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #fff !important;
  }

  .container-a4 {
    width: auto !important;
    max-width: none !important;
    margin: 0 !important;
    padding: 0 !important;
    box-shadow: none !important;
  }

  .row { display: block !important; }
  .table-responsive { overflow: visible !important; }

  table {
    page-break-inside: auto;
    break-inside: auto;
    width: 100% !important;
    border-collapse: collapse;
  }
  tr { page-break-inside: avoid; }
  thead { display: table-header-group; }
  tfoot { display: table-footer-group; }
}
    .stock-baixo {
    background-color: #f8d7da !important;
    color: #721c24 !important;
}
  </style>
</head>
<body>
  <div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="row">
      <div class="col-lg-8 col-md-7 col-sm-12">
        @if($perda->status=="Cancelada") 
        <h1 class="stock-baixo">Cancelada</h1>
        @endif
        <h4>{{$empresa->nome}}</h4>
        <p>{{$empresa->local}}</p>
        <p>Telefone:   
        @foreach($empresa->telefones()->take(2) as $telefone)

        <span>{{$telefone->numero}}/</span> 
        @endforeach
        </p>
        <p>NIF: {{$empresa->nif}}</p>

        <div class="table w-75">
          <table class="table table-bordered tabela-factura">
            <tr>
              <td class="text-center">Documento De Perdas</td>
            </tr>
            <tr>
              <td>
                <div class="row">
                 
                  <div class="col">Data:{{$perda->created_at->format('d/m/Y H:i:s')}}</div>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
 
    </div>

   

    <!-- Produtos -->
    <div class="row">
      <div class="table-responsive">
        <table class="table table-bordered tabela-produtos">
          <thead>
            <tr>
              <th>Cod Prod</th>
              <th>Desc  Prod</th>
              <th>Quant</th>
              <th>Custo Unitario de Perda </th>
              <th>SubTotal de Perda </th>
            </tr>
          </thead>
          <tbody>
          @foreach($perda->produtos as $produto)
            <tr>
              <td>{{$produto->codigo}}</td>
              <td>{{$produto->nome}}</td>
              <td>{{$produto->pivot->quantidade}}</td>
               <td> {{ number_format($produto->pivot->preco, 2, ',', '.') }}</td>
                <td>{{ number_format($produto->pivot->subtotal, 2, ',', '.') }}   </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>


  <div class="row mt-3">
  <div class="col d-flex justify-content-end">
    <p><strong>Total Geral Perda:{{number_format($perda->total,"2",",",".")}}Kz</strong></p>
  </div>
</div>
<!-- Motivo da Perda -->
<div class="row mt-3">
  <div class="col">
    <h5><strong>Motivo da Perda</strong></h5>
    <div class="border p-3 bg-light" style="min-height: 100px;">
      {!! nl2br(e($perda->motivo)) ?? '---' !!}
    </div>
  </div>
</div>

    <!-- Rodapé -->
    <div class="row mt-3">
      <div class="col">
        <p><strong>Operador:{{$perda->user->name}}</strong></p>
      </div>
    </div>
  </div>
</body>
</html>