@extends('app.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container-fluid mt-4">
            <h4 class="mb-4">Registrar Perdas</h4>

            <form action="{{ route('perdas.store') }}" method="POST" id="pedidoForm">
                @csrf

                <!-- Nome do Cliente -->
                <!-- Filtro e Adição de Produtos -->
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
                            <strong>Total Geral:</strong> <span id="totalGeral">0.00</span> Kz
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Adicionar Perda</button>
                </div>
            </form>
        </div>

        <!-- Template de Linha -->
        <template id="templateProduto">
            <tr data-produto-id="">
                <td class="produto-nome text-start"></td>
                <td>
                    <input type="number" name="produtos[][quantidade]" class="form-control quantidade text-center" min="1" value="1">
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
        
        let indexProduto = 0; // Variável para manter o controle dos índices dos produtos

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
                    adicionarProduto(prod);
                    filtroInput.value = '';
                    sugestoes.innerHTML = '';
                });
                sugestoes.appendChild(li);
            });
        });

        function adicionarProduto(produto) {
            console.log("az")
        if (produto.quantidade_retalho === 0) {
                alert(`O produto "${produto.nome}" está indisponível (quantidade a retalho = 0).`);
                return;
            }
            const linhaExistente = tabelaBody.querySelector(`tr[data-produto-id='${produto.id}']`);


            if (linhaExistente) {
                const quantidadeInput = linhaExistente.querySelector('.quantidade');
                  let novaQuantidade = parseInt(quantidadeInput.value) + 1;
 
                if (novaQuantidade > produto.quantidade_retalho) {
                    alert(`A quantidade máxima permitida para o produto "${produto.nome}" é ${produto.quantidade_retalho}.`);
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

            // Atualizando os names dos inputs para refletir o índice correto
            row.querySelector('input[name="produtos[][quantidade]"]').name = `produtos[${indexProduto}][quantidade]`;
            row.querySelector('input[name="produtos[][produto_id]"]').name = `produtos[${indexProduto}][produto_id]`;
            row.querySelector('input[name="produtos[][preco]"]').name = `produtos[${indexProduto}][preco]`;

           const quantidadeInput = row.querySelector('.quantidade');

                      quantidadeInput.addEventListener('input', function () {
                let valor = parseInt(this.value);
                if (valor > produto.quantidade_retalho) {
                    alert(`A quantidade máxima permitida para o produto "${produto.nome}" é ${produto.quantidade_retalho}.`);
                    this.value = produto.quantidade_retalho;
                } else if (valor < 1 || isNaN(valor)) {
                    this.value = 1;
                }
                atualizarTotais();
            });
            row.querySelector('.btnRemover').addEventListener('click', function () {
                row.remove();
                atualizarTotais();
            });

            tabelaBody.appendChild(row);
            indexProduto++; // Incrementa para garantir nomes únicos
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
@endsection
