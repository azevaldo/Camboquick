<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fatura-Recibo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
/* 📄 Folha simulada apenas na tela */
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
       @if($compra->status=="Cancelada") 
        <h1 class="stock-baixo">Cancelada</h1>
        @endif
         <h4>{{$compra->fornecedor->nome}}</h4>
        <p>{{$compra->fornecedor->localizacao}}</p>
        <p>Telefone: {{$compra->fornecedor->contato}}</p>
        <p>NIF: {{$compra->fornecedor->nif}}</p>

        <div class="table w-75">
          <table class="table table-bordered tabela-factura">
            <tr>
              <td class="text-center">Entrada-Stock</td>
            </tr>
            <tr>
              <td>
                <div class="row">
                  <div class="col">Nº da Fatura:{{$compra->n_fatura}}</div>
                  <div class="col">Data:{{$compra->created_at->format('d/m/Y H:i:s')}}</div>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-lg-4 col-md-5 col-sm-12">
        <br><br>
        <p><strong>Cliente:</strong>{{$empresa->nome}}</p>
        <p><strong>Localização:</strong> {{$empresa->local}}</p>
      </div>
    </div>

    <!-- Informações adicionais -->
    <div class="row mt-4">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Contribuinte</th>
              <th>Forma de Pagamento</th>
              <th>Armazém</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>123456789</td>
              <td>Dinheiro</td>
              <td>Armazém Central</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Produtos -->
    <div class="row">
       <div class="table-responsive" >
    <table class="table table-bordered tabela-produtos">
          <thead>
            <tr>
              <th>Código do Produto</th>
              <th>Descrição do Produto</th>
              <th>Quantidade</th>
              <th>Preço Unitário</th>
              <th>Imposto</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
          @foreach($compra->itensCompra as $item)
            <tr>
              <td>{{$item->produto->codigo}}</td>
              <td>{{$item->produto->nome}}</td>
              <td>{{$item->quantidade}}</td>
              <td>{{ number_format($item->custo_unitario, 2, ',', '.') }} Kz</td>
              <td>{{$item->imposto}}%</td>
              <td>{{ number_format($item->custo_total, 2, ',', '.') }} Kz</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Resumo Final -->
    <div class="row">
      <div class="col-lg-6 col-sm-12">
        <div class="table-responsive" >
          <table class="table table-bordered tabela-resumo">
            <thead>
              <tr>
                <th>Taxa</th>
                <th>Valor Tributável</th>
                <th>Valor IVA</th>
              </tr>
            </thead>
            <tbody>
            @foreach($compra->itensCompra as $item)
              <tr>
                <td>{{$item->imposto}}%</td>
                <td>{{ number_format($item->custo_unitario*$item->quantidade, 2, ',', '.') }} Kz</td>
                <td>{{ number_format((($item->imposto/100)*($item->custo_unitario*$item->quantidade)), 2, ',', '.') }} Kz</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-lg-6 col-sm-12">
        <div class="table-responsive">
          <table class="table table-bordered tabela-valores">
            <tbody>
              <tr>
                <td><strong>Total de Descontos:</strong></td>
                <td>0 Kz</td>
              </tr>
              <tr>
                <td><strong>Valor Tributável:</strong></td>
                <td>{{ number_format($valor_tributavel, 2, ',', '.') }} Kz</td>
              </tr>
              <tr>
                <td><strong>Total de Impostos:</strong></td>
                <td>{{ number_format($tot_imposto, 2, ',', '.') }} Kz</td>
              </tr>
              <tr>
                <td><strong>Total do Documento:</strong></td>
                <td>{{ number_format($compra->custo_total, 2, ',', '.') }} Kz</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Rodapé -->
    <div class="row mt-3">
      <div class="col">
        <p><strong>Operador:</strong> {{$compra->usuario->name}}</p>
      </div>
    </div>
  </div>
</body>
</html>