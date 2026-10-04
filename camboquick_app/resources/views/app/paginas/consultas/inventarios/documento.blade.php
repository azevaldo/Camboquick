<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventario-Estoque</title>
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

    .stock-baixo2 {
    background-color: #f3f0f0 !important;
    color: #22990a !important;
}
  </style>
</head>
<body>
  <div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="row">
      <div class="col-lg-8 col-md-7 col-sm-12">
       
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
              <td class="text-center">Inventario De Estoque</td>
            </tr>
            <tr>
              <td>
                <div class="row">
                  <div class="col">Usuario:{{$inventario->usuario->name}}</div>
                  <div class="col">Data:{{$inventario->created_at->format('d/m/Y H:i:s')}}</div>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
     
    </div>

    <!-- Informações adicionais -->
 

    <!-- Produtos -->
    <div class="row">
      <div class="table-responsive">
        <table class="table table-bordered tabela-produtos">
          <thead>
            <tr>
              <th>Cod Prod</th>
              <th>Nome Prod</th>
              <th>Qnt1 Grosso</th>
              <th>Qnt1 Restante</th>
              <th>Qnt1 Retalho</th>

            

            </tr>
          </thead>
          <tbody>
          @foreach($inventario->inventarioEstoques as $item)
            <tr>
              <td>{{$item->produto->codigo}}</td>
              <td>{{$item->produto->nome}}</td>
              <td>{{$item->qnt1_grosso}}</td>
               <td>{{$item->qnt1_restante}}</td>
              <td>{{$item->qnt1_retalho}}</td>

              

          
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>


    <!-- Rodapé -->
    <div class="row mt-3">
      <div class="col">
        <p><strong>Operador:</strong> {{$inventario->usuario->name}}</p>
      </div>
    </div>
  </div>
</body>
</html>