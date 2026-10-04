 @extends('app.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-white" style="background-color: rgb(255, 176, 29)">
            <h4>Adicionar Entrada De Stock</h4>
        </div>
        <div class="card-body">
            <form id="compraForm" action="{{ route('compras.store') }}" method="POST">
                @csrf

                <!-- Seleção do Fornecedor -->
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="filtro_fornecedor" class="form-label">Buscar Fornecedor (Nome ou NIF)</label>
                        <input type="text" id="filtro_fornecedor" class="form-control" placeholder="Digite o nome ou NIF">
                    </div>
                    <div class="col-md-6">
                        <label for="fornecedor_id" class="form-label">Selecionar Fornecedor</label>
                        <select name="fornecedor_id" id="fornecedor_id" class="form-select" required>
                            <option value="">-- Selecione --</option>
                            @foreach($fornecedores as $fornecedor)
                                <option value="{{ $fornecedor->id }}">
                                    {{ $fornecedor->nome }} - NIF: {{ $fornecedor->nif }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Número da Fatura -->
                <div class="mb-3">
                    <label for="numero_fatura" class="form-label">Número da Fatura</label>
                    <input type="text" name="numero_fatura" class="form-control" required>
                </div>

                <!-- Produtos Dinâmicos -->
                <div id="produtosContainer">
                    <h5>Produtos</h5>
                    <div class="produto-item border p-3 mb-3 rounded" data-index="0">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>Buscar Produto (Nome ou Código)</label>
                                <input type="text" class="form-control buscar-produto" placeholder="Nome ou código">
                                <select class="form-select mt-1 produto-select" name="produtos[0][produto_id]" required>
                                    <option value="">-- Selecione o Produto --</option>
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}">
                                            {{ $produto->nome }} - {{ $produto->codigo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label>Quantidade</label>
                                <input type="number" name="produtos[0][quantidade]" min="1" class="form-control quantidade" required>
                            </div>
                            <div class="col-md-3">
                                <label>Imposto (%)</label>
                                <input type="number" name="produtos[0][imposto]" min="0" class="form-control imposto" required>
                            </div>
                            <div class="col-md-3">
                                <label>Custo Unitário</label>
                                <input type="number" name="produtos[0][custo_unitario]" min="0" step="0.01" class="form-control custo-unitario" required>
                            </div>
                            <div class="col-md-3">
                                <label>Custo Total</label>
                                <input type="text" class="form-control custo-total" readonly>
                            </div>
                        </div>

                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-danger btn-sm remover-produto">Remover Produto</button>
                        </div>
                    </div>
                </div>

                <!-- Botão para adicionar mais produtos -->
                <div class="mb-3">
                    <button type="button" id="addProduto" class="btn btn-secondary">+ Adicionar Produto</button>
                </div>

                <!-- Total Geral -->
                <div class="mb-3 text-end">
                    <label class="form-label fw-bold" style="font-size: 22px">Total Geral:</label>
                    <input type="text" class="form-control text-end" id="totalGeral" readonly>
                </div>

                <!-- Botões -->
                <div class="text-end">
 
                     <button type="button" class="btn btn-success" id="btnConfirmarVenda">Salvar Entrada De Stock</button>

                    <a href="{{ route('compras.index') }}" class="btn btn-danger">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmacaoVenda" tabindex="-1" aria-labelledby="modalConfirmacaoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalConfirmacaoLabel">Salvar Entrada</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>O total da entrada é <strong><span id="totalVendaModal">0.00</span> Kz</strong>.</p>
        <p>Tem a certeza que deseja salvar esta esta?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
        <button type="button" class="btn btn-success" id="btnSimFinalizar">Sim, Salvar</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    let produtosIndex = 1;

    document.getElementById('filtro_fornecedor').addEventListener('input', function () {
        const termo = this.value.toLowerCase();
        const select = document.getElementById('fornecedor_id');
        for (const opt of select.options) {
            const text = opt.text.toLowerCase();
            opt.hidden = !text.includes(termo);
        }
    });

    // Adicionar produto
    document.getElementById('addProduto').addEventListener('click', function () {
        const container = document.querySelector('#produtosContainer');
        const novoProduto = document.querySelector('.produto-item').cloneNode(true);
        novoProduto.dataset.index = produtosIndex;

        novoProduto.querySelectorAll("input, select").forEach(el => {
            const name = el.name;
            if (name) {
                const novoName = name.replace(/\[\d+\]/, `[${produtosIndex}]`);
                el.name = novoName;
                if (el.tagName === "INPUT") el.value = '';
                if (el.tagName === "SELECT") el.selectedIndex = 0;
            }
        });

        novoProduto.querySelector('.custo-total').value = '0.00';

        container.appendChild(novoProduto);
        produtosIndex++;
    });

    // Remover produto
    document.querySelector('#produtosContainer').addEventListener('click', function (e) {
        if (e.target.classList.contains('remover-produto')) {
            const produtos = document.querySelectorAll('.produto-item');
            if (produtos.length > 1) {
                e.target.closest('.produto-item').remove();
                calcularTotalGeral();
            } else {
                alert('Deve haver pelo menos um produto.');
            }
        }
    });

    // Calcular total por produto
    document.querySelector('#produtosContainer').addEventListener('input', function (e) {
        if (
            e.target.classList.contains('quantidade') ||
            e.target.classList.contains('imposto') ||
            e.target.classList.contains('custo-unitario')
        ) {
            const item = e.target.closest('.produto-item');
            const qtd = parseFloat(item.querySelector('.quantidade').value) || 0;
            const imposto = parseFloat(item.querySelector('.imposto').value) || 0;
            const custo = parseFloat(item.querySelector('.custo-unitario').value) || 0;
            const total = qtd * custo * (1 + imposto / 100);
            item.querySelector('.custo-total').value = total.toFixed(2);
            calcularTotalGeral();
        }
    });

    // Filtrar produtos ao digitar
    document.querySelector('#produtosContainer').addEventListener('input', function (e) {
        if (e.target.classList.contains('buscar-produto')) {
            const termo = e.target.value.toLowerCase();
            const select = e.target.parentElement.querySelector('.produto-select');
            for (const opt of select.options) {
                opt.hidden = !opt.text.toLowerCase().includes(termo);
            }
        }
    });

    function calcularTotalGeral() {
        let total = 0;
        document.querySelectorAll('.custo-total').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('totalGeral').value = total.toFixed(2);
    }
});
</script>

<script>
    // Botão que abre o modal de confirmação
document.getElementById('btnConfirmarVenda').addEventListener('click', function () {
    const form = document.getElementById('compraForm');
    const requiredFields = form.querySelectorAll('select[required], input[required]');
    let valid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            valid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });

    if (!valid) {
         document.getElementById("stockToastBody").textContent = "Por favor, preencha todos os campos obrigatórios antes de continuar.";

        document.querySelector('.custom-centered-toast').style.display = 'block';
        let toastElement = document.getElementById('stockToast');
        let toast = new bootstrap.Toast(toastElement);
        toast.show();
       // alert('Por favor, preencha todos os campos obrigatórios antes de continuar.');
        return;
    }

    // Se todos os campos estiverem preenchidos
    const total = document.getElementById('totalGeral').value;
    document.getElementById('totalVendaModal').textContent = total;

    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacaoVenda'));
    modal.show();
});


document.getElementById('btnSimFinalizar').addEventListener('click', function () {
    const btnSimFinalizar = this;
    const form = document.getElementById('compraForm');
    btnSimFinalizar.disabled = true;
    btnSimFinalizar.innerText = 'A processar...';
    form.submit();
});



 </script>
@endsection
