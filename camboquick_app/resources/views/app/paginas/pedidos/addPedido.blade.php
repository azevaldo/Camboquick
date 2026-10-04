 @extends('app.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container-fluid mt-4">
            <h4 class="mb-4">Novo Pedido</h4>

            <form action="{{ route('pedidos.store') }}" method="POST" id="pedidoForm">
                @csrf

                <!-- Nome do Cliente -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nome_cliente" class="form-label">Nome do Cliente</label>
                        <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" placeholder="Opcional">
                        @if($mesa==1)
                        <input type="text" class="form-control" id="nome_cliente" name="mesa_id" value="{{$mesa_id}}" hidden>
                        @endif
                    </div>
                </div>

                <!-- Adicionar Produtos -->
                <div class="card mb-4">
                    <div class="card-header">Adicionar Produtos</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 position-relative">
                                <input type="text" class="form-control" id="filtroProduto" placeholder="Filtrar por nome ou código...">
                                <ul class="list-group position-absolute w-100 shadow" id="listaSugestoes" style="z-index: 1050; max-height: 200px; overflow-y: auto;"></ul>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle text-center" id="tabelaProdutos">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Preço Unitário</th>
                                        <th>Subtotal</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="text-end mt-3">
                            <strong>Total Geral:</strong> <span id="totalGeral" style="font-size: 22px">0.00</span> Kz
                        </div>
                    </div>
                </div>

                <div class="text-end">
                     <button type="button" class="btn btn-success" id="btnConfirmarVenda">Salvar Pedido</button>
                </div>
            </form>



            <!-- Modal de Confirmação de Venda -->
<div class="modal fade" id="modalConfirmacaoVenda" tabindex="-1" aria-labelledby="modalConfirmacaoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalConfirmacaoLabel">Salvar Pedido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>O total do Pedido é <strong><span id="totalVendaModal">0.00</span> Kz</strong>.</p>
        <p>Tem a certeza que deseja salvar  este pedido?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
        <button type="button" class="btn btn-success" id="btnSimFinalizar">Sim, salvar</button>
      </div>
    </div>
  </div>
</div>

        </div>

        <!-- Template de Produto -->
        <template id="templateProduto">
            <tr data-produto-id="">
                <td class="produto-nome text-start"></td>
                <td>
                    <input type="number" name="produtos[][quantidade]" class="form-control quantidade text-center" min="1" value="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <input type="hidden" name="produtos[][produto_id]" class="produto-id">
                    <input type="hidden" name="produtos[][preco]" class="produto-preco">
                </td>
                <td class="produto-preco-text"></td>
                <td class="produto-subtotal">0.00</td>
                <td><button type="button" class="btn btn-danger btn-sm btnRemover">Remover</button></td>
            </tr>
        </template>
    </div>
</div>

<script>
    const produtos = @json($produtos);

    document.addEventListener('DOMContentLoaded', function () {
        const filtroInput = document.getElementById('filtroProduto');
        const sugestoes = document.getElementById('listaSugestoes');
        const tabelaBody = document.querySelector('#tabelaProdutos tbody');
        const totalSpan = document.getElementById('totalGeral');
        const template = document.getElementById('templateProduto').content;
        
        let indexProduto = 0;

        filtroInput.addEventListener('input', function () {
            const filtro = this.value.toLowerCase();
            sugestoes.innerHTML = '';

            if (filtro.length === 0) return;

            const encontrados = produtos.filter(p =>
                p.nome.toLowerCase().includes(filtro) ||
                p.codigo.toLowerCase().includes(filtro)
            );

            encontrados.forEach(prod => {
                const li = document.createElement('li');
                li.classList.add('list-group-item', 'list-group-item-action');
                li.textContent = `${prod.codigo} - ${prod.nome}`;
                li.style.cursor = 'pointer';
                li.addEventListener('click', function () {

         if (prod.quantidade_retalho <= 0) {
           //         alert(`O produto "${prod.nome}" não tem stock disponível para venda a retalho.`);
                    //return;
               
                document.getElementById("stockToastBody").textContent =`O produto "${prod.nome}" não tem stock disponível para venda a retalho.`;

    // Mostra o container do toast
    document.querySelector('.custom-centered-toast').style.display = 'block';

    // Inicializa e exibe o toast
    let toastElement = document.getElementById('stockToast');
    let toast = new bootstrap.Toast(toastElement);
    toast.show();

    return;
                }

                    adicionarProduto(prod);
                    filtroInput.value = '';
                    sugestoes.innerHTML = '';
                });
                sugestoes.appendChild(li);
            });
        });

        function adicionarProduto(produto) {
            if (produto.quantidade_retalho === 0) {
              //  alert(`O produto "${produto.nome}" está indisponível (quantidade a retalho = 0).`);
                      document.getElementById("stockToastBody").textContent =`O produto "${produto.nome}" está indisponível (quantidade a retalho = 0).`;

    // Mostra o container do toast
    document.querySelector('.custom-centered-toast').style.display = 'block';

    // Inicializa e exibe o toast
    let toastElement = document.getElementById('stockToast');
    let toast = new bootstrap.Toast(toastElement);
    toast.show();
                return;
            }

            const linhaExistente = tabelaBody.querySelector(`tr[data-produto-id='${produto.id}']`);

            if (linhaExistente) {
                const quantidadeInput = linhaExistente.querySelector('.quantidade');
                let novaQuantidade = parseInt(quantidadeInput.value) + 1;

                if (novaQuantidade > produto.quantidade_retalho) {
                      document.getElementById("stockToastBody").textContent =`A quantidade máxima permitida para o produto "${produto.nome}" é ${produto.quantidade_retalho}.`;

    // Mostra o container do toast
    document.querySelector('.custom-centered-toast').style.display = 'block';

    // Inicializa e exibe o toast
    let toastElement = document.getElementById('stockToast');
    let toast = new bootstrap.Toast(toastElement);
    toast.show();
                 //   alert(`A quantidade máxima permitida para o produto "${produto.nome}" é ${produto.quantidade_retalho}.`);
                    return;
                }

                quantidadeInput.value = novaQuantidade;
                atualizarTotais();
                return;
            }

            const row = template.cloneNode(true).querySelector('tr');
            row.setAttribute('data-produto-id', produto.id);

            row.querySelector('.produto-nome').textContent = `${produto.codigo} - ${produto.nome}`;
            row.querySelector('.produto-id').value = produto.id;
            row.querySelector('.produto-preco').value = produto.preco;
            row.querySelector('.produto-preco-text').textContent = parseFloat(produto.preco).toFixed(2);

            row.querySelector('input[name="produtos[][quantidade]"]').name = `produtos[${indexProduto}][quantidade]`;
            row.querySelector('input[name="produtos[][produto_id]"]').name = `produtos[${indexProduto}][produto_id]`;
            row.querySelector('input[name="produtos[][preco]"]').name = `produtos[${indexProduto}][preco]`;

            const quantidadeInput = row.querySelector('.quantidade');
            quantidadeInput.addEventListener('input', function () {
                let valor = parseInt(this.value);
              if (valor > produto.quantidade_retalho) {
    document.getElementById("stockToastBody").textContent =`A quantidade máxima permitida para o produto "${produto.nome}" é ${produto.quantidade_retalho}.`;
    document.querySelector('.custom-centered-toast').style.display = 'block';
    let toastElement = document.getElementById('stockToast');
    let toast = new bootstrap.Toast(toastElement);
    toast.show(); 
    this.value = produto.quantidade_retalho;
}
atualizarTotais(); // 🔄 Mantemos atualizando sempre

            });

            row.querySelector('.btnRemover').addEventListener('click', function () {
                row.remove();
                atualizarTotais();
            });

            tabelaBody.appendChild(row);
            indexProduto++;
            atualizarTotais();
        }

        function atualizarTotais() {
            let total = 0;
            tabelaBody.querySelectorAll('tr').forEach(row => {
                const qtd = parseFloat(row.querySelector('.quantidade').value) || 0;
                const preco = parseFloat(row.querySelector('.produto-preco').value) || 0;
                const subtotal = qtd * preco;
                row.querySelector('.produto-subtotal').textContent = subtotal.toFixed(2);
                total += subtotal;
            });
            totalSpan.textContent = total.toFixed(2);
        }
    });
</script>


<script>
    // Botão que abre o modal de confirmação
document.getElementById('btnConfirmarVenda').addEventListener('click', function () {
    const linhasProdutos = document.querySelectorAll('#tabelaProdutos tbody tr');

    if (linhasProdutos.length === 0) {
        // Alerta ou toast caso nenhum produto tenha sido adicionado
        document.getElementById("stockToastBody").textContent = "Adicione pelo menos um produto antes de  salvar o pedido.";

        document.querySelector('.custom-centered-toast').style.display = 'block';
        let toastElement = document.getElementById('stockToast');
        let toast = new bootstrap.Toast(toastElement);
        toast.show();
        return;
    }

    const total = document.getElementById('totalGeral').textContent;
    document.getElementById('totalVendaModal').textContent = total;
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacaoVenda'));
    modal.show();
});


// Botão "Sim" no modal que envia o formulário
document.getElementById('btnSimFinalizar').addEventListener('click', function () {

const linhasProdutos = document.querySelectorAll('#tabelaProdutos tbody tr');

    for (let row of linhasProdutos) {
        const qtdInput = row.querySelector('.quantidade');
        const qtd = qtdInput.value.trim();

        if (qtd === "" || isNaN(qtd) || parseInt(qtd) < 1) {
            document.getElementById("stockToastBody").textContent = "Todos os produtos devem ter uma quantidade válida (mínimo 1).";
            document.querySelector('.custom-centered-toast').style.display = 'block';
            let toastElement = document.getElementById('stockToast');
            let toast = new bootstrap.Toast(toastElement);
            toast.show();
            return;
        }
    }
    

     const btnSimFinalizar = document.getElementById('btnSimFinalizar');
        const formPedido = document.getElementById('pedidoForm');
     btnSimFinalizar.disabled = true;
            btnSimFinalizar.innerText = 'A processar...';

            // Submeter o formulário
            formPedido.submit();
    document.getElementById('pedidoForm').submit();
});

 </script>



 
@endsection
