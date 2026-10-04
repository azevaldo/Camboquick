@extends('app.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
<div class="container">
    <h2 class="mb-4">Detalhes do Pedido</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary">Informações do Pedido</h5>
            <p><strong>Cliente:</strong> {{ $pedido->nome_cliente }}</p>
            <p><strong>Data do Pedido:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
             <p><strong>Usuario:</strong> {{ $pedido->usuario->name }}</p>
            <p><strong>Status do Pedido:</strong> 
                @if($pedido->status === 'aberto')
                    <span class="badge bg-success text-white">Aberto</span>
                @elseif($pedido->status === 'fechado')
                    <span class="badge bg-danger text-white">Fechado</span>
                @elseif($pedido->status === 'cancelado')
                    <span class="badge bg-danger text-white">Cancelado</span>
                @endif
            </p>
   @if($pedido->status=="aberto")
   @if($pedido->user_id==Auth::id())
          @if(!$pedido->produtos->isEmpty())
        <!-- Botão para Atualizar Pedido -->
<button class="btn btn-primary me-2 mb-3 text-white" data-bs-toggle="modal" data-bs-target="#modalConfirmarAtualizacao">
    Atualizar Pedido
</button>
@endif
        @endif
        @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th>Produto</th>
                            <th>Preço Unitário (Kz)</th>
                            <th>Quantidade</th>
                            <th>Subtotal (Kz)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->produtos as $produto)
                        <tr>
                            <td>{{ $produto->nome }}</td>
                            <td>{{ number_format($produto->pivot->preco, 2, ',', '.') }}</td>
                            <td>
                                @php
                                $qntAnte=$produto->pivot->quantidade;
                               $prod2 = \App\Models\Produto::find($produto->id);
                                $qntStock=$prod2->quantidade_retalho+$qntAnte;

                                @endphp
                                <input type="number" 
                                       class="form-control text-center input-quantidade" 
                                       name="quantidades[{{ $produto->id }}]" 
                                       value="{{ $produto->pivot->quantidade }}" 
                                       min="0" max="{{$qntStock}}"
                                       data-preco="{{ number_format($produto->pivot->preco, 2, '.', '') }}">
                            </td>
                            <td class="subtotal">
                                {{ number_format($produto->pivot->quantidade * $produto->pivot->preco, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3"><strong>Total Geral (Kz)</strong></td>
                            <td><strong id="totalPedidoFormatado">0,00 Kz</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @if($pedido->status=="aberto")
    <!-- Botões -->
    <div class="text-end">
       
        @if($pedido->user_id==Auth::id())
       @if(!$pedido->produtos->isEmpty())
            <button class="btn btn-success"  data-bs-toggle="modal" data-bs-target="#finalizarP">Finalizar Pedido</button>
          @endif
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalAdicionarProduto">
            Adicionar Produto
        </button>
         @if($pedido->status=='aberto')
         <button class="btn btn-danger me-2  " data-bs-toggle="modal" data-bs-target="#deleteModal">
            Cancelar Pedido
        </button>
     
        @endif
        @endif


        
<!-- Modal de Confirmação de Atualização -->
<div class="modal fade" id="modalConfirmarAtualizacao" tabindex="-1" aria-labelledby="modalAtualizacaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{route('pedidos.atualizar',$pedido->id)}}" method="POST" id="formAtualizarPedido">
                @csrf
                @method('PUT')

                <div class="modal-header  text-black">
                    <h5 class="modal-title" id="modalAtualizacaoLabel">Atualizar Pedido</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja atualizar as quantidades dos produtos deste pedido?</p>
<p><strong>Total Geral (Kz):</strong> <span id="totalAtualizacaoModal">0,00</span></p>

 
                    <!-- Campos ocultos para enviar as quantidades -->
                    @foreach ($pedido->produtos as $produto)
                        <input type="hidden" name="quantidades[{{ $produto->id }}]" id="input-quantidade-{{ $produto->id }}">
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Sim, Atualizar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
 
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.input-quantidade');

        inputs.forEach(input => {
            const errorDiv = document.createElement('div');
            errorDiv.classList.add('text-danger', 'small');
            errorDiv.style.display = 'none';
            input.parentElement.appendChild(errorDiv);

            function mostrarErro(mensagem) {
                errorDiv.textContent = mensagem;
                errorDiv.style.display = 'block';
                setTimeout(() => {
                    errorDiv.style.display = 'none';
                }, 3000);
            }

            input.addEventListener('input', function () {
                const min = parseInt(this.min);
                const max = parseInt(this.max);
                let valor = parseInt(this.value);

                if (isNaN(valor)) return;

                if (valor < min) {
                    this.value = min;
                    mostrarErro(`Valor mínimo permitido é ${min}`);
                } else if (valor > max) {
                    this.value = max;
                    mostrarErro(`Valor máximo permitido é ${max}`);
                }
            });

            input.addEventListener('paste', function (e) {
                const pasteData = parseInt(e.clipboardData.getData('text'));
                const min = parseInt(this.min);
                const max = parseInt(this.max);

                if (isNaN(pasteData) || pasteData < min || pasteData > max) {
                    e.preventDefault();
                    mostrarErro(`Valor colado deve estar entre ${min} e ${max}`);
                }
            });
        });
    });
</script>


         <form action="{{route('pedidos.finalizar')}}" id="finalizarForm" method="get" style="display:inline-block;">
            @csrf
      

            
            <div class="modal" id="finalizarP" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="d-flex justify-content-center align-items-center flex-grow-1">
                                <h5 class="modal-title">Finalizar O Pedido?</h5>
                            </div>
                            <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem a certeza que deseja finalizar o Pedido De "{{$pedido->nome_cliente }}"?</p>
                            <p><strong>Total Geral (Kz):</strong> <span >            {{number_format($pedido->total, 2, ',', '.')            }}</span></p>
    
                            <input type="text" name="pedido" value="{{$pedido->id}}" hidden>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="btn-finalizar">Finalizar Pedido</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    
 

           <!-- Delete Modal -->
           <form action="{{route('pedidos.cancelar',$pedido->id)}}" method="get" style="display:inline-block;">
            @csrf
      

            
            <div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="d-flex justify-content-center align-items-center flex-grow-1">
                                <h5 class="modal-title">Cancelar O Pedido?</h5>
                            </div>
                            <button type="button" class="btn-close fechar" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem a certeza que deseja Cancelar o Pedido De "{{$pedido->nome_cliente }}"?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Cancelar</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </form>
       
    </div>
    @endif
</div>
</div></div>
<!-- Modal de Adição de Produtos -->
<div class="modal fade" id="modalAdicionarProduto" tabindex="-1"
     data-bs-backdrop="static" data-bs-keyboard="false"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('pedidos.update',$pedido->id) }}" method="POST" id="pedidoForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabel">Adicionar Produtos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 position-relative">
                        <input type="text" class="form-control" id="filtroProduto" placeholder="Filtrar por nome ou código...">
                        <ul class="list-group position-absolute w-100 shadow" id="listaSugestoes" style="z-index: 1050; max-height: 200px; overflow-y: auto;"></ul>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="tabelaProdutos">
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
                        <strong>Total :</strong> <span id="totalGeral">0.00</span> Kz
                        <div><strong>Total Geral:</strong> <span id="totalGeral2">0.00</span> Kz</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSimFinalizar" class="btn btn-success">Salvar Produtos</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template para linha de produto -->
<template id="templateProduto">
    <tr data-produto-id="">
        <td class="produto-nome text-start"></td>
        <td>
            <input type="number" name="produtos[][quantidade]" class="form-control quantidade text-center" min="1" value="1" {{ $pedido->status == 'aberto' ? '' : 'readonly' }} oninput="this.value = this.value.replace(/[^0-9]/g, '')">

            <input type="hidden" name="produtos[][produto_id]" class="produto-id">
            <input type="hidden" name="produtos[][preco]" class="produto-preco">
        </td>
        <td class="produto-preco-text"></td>
        <td class="produto-subtotal">0.00</td>
        <td><button type="button" class="btn btn-danger btn-sm btnRemover">Remover</button></td>
    </tr>
</template>

<script>
    function formatarNumero(valor) {
        return valor.toFixed(2).replace('.', ',');
    }

    function atualizarSubtotal(row) {
        const input = row.querySelector('.input-quantidade');
        const preco = parseFloat(input.dataset.preco);
        const quantidade = parseInt(input.value) || 0;
        const subtotal = quantidade * preco;
        row.querySelector('.subtotal').textContent = formatarNumero(subtotal);
        return subtotal;
    }

    function atualizarTotal() {
        let total = 0;
        document.querySelectorAll('.input-quantidade').forEach(input => {
            total += atualizarSubtotal(input.closest('tr'));
        });
        return total;
    }

    function atualizarTotalComModal() {
        const totalPedido = atualizarTotal();
        const totalModal = parseFloat(document.getElementById('totalGeral').textContent.replace(',', '.')) || 0;
        const totalModal2 = parseFloat(document.getElementById('totalGeral2').textContent.replace(',', '.')) || 0;
        const totalGeralFinal1 = totalPedido + totalModal;
        const totalGeralFinal = totalPedido + 0;
          document.getElementById('totalGeral2').textContent = formatarNumero(totalGeralFinal1) + ' Kz';
        document.getElementById('totalPedidoFormatado').textContent = formatarNumero(totalGeralFinal) + ' Kz';
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.input-quantidade').forEach(input => {
            input.addEventListener('input', function () {
                atualizarSubtotal(input.closest('tr'));
                atualizarTotalComModal();
            });
        });
        atualizarTotalComModal();

        const produtos = @json($produtos);
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
                p.nome.toLowerCase().includes(filtro) || p.codigo.toLowerCase().includes(filtro)
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
              if (produto.quantidade_retalho === 0) {

                 document.getElementById("stockToastBody").textContent =`O produto "${produto.nome}" está indisponível (quantidade a retalho = 0).`;

    // Mostra o container do toast
    document.querySelector('.custom-centered-toast').style.display = 'block';

    // Inicializa e exibe o toast
    let toastElement = document.getElementById('stockToast');
    let toast = new bootstrap.Toast(toastElement);
    toast.show();
                //alert(`O produto "${produto.nome}" está indisponível (quantidade a retalho = 0).`);
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
                    //alert(`A quantidade máxima permitida para o produto "${produto.nome}" é ${produto.quantidade_retalho}.`);
                    return;
                }
                quantidadeInput.value = parseInt(quantidadeInput.value) + 1;
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
            atualizarTotalComModal();
        }
    });
</script>
<script>
    document.getElementById('modalConfirmarAtualizacao').addEventListener('show.bs.modal', function () {
        document.querySelectorAll('.input-quantidade').forEach(input => {
            const produtoId = input.name.match(/\d+/)[0];
            const campoHidden = document.getElementById('input-quantidade-' + produtoId);
            if (campoHidden) {
                campoHidden.value = input.value;
            }
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalAdicionarProduto');
    const form = document.getElementById('pedidoForm');
    const tabelaProdutos = document.querySelector('#tabelaProdutos tbody');
    const totalGeral = document.getElementById('totalGeral');
    const totalGeral2 = document.getElementById('totalGeral2');

    modal.addEventListener('hidden.bs.modal', function () {
        // Limpar o formulário
        form.reset();

        // Limpar a tabela de produtos
        tabelaProdutos.innerHTML = '';

        // Resetar totais
        totalGeral.textContent = '0.00';
        totalGeral2.textContent = '0.00';
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('pedidoForm');
    const tabelaProdutos = document.querySelector('#tabelaProdutos tbody');

    form.addEventListener('submit', function (e) {


        if (tabelaProdutos.querySelectorAll('tr').length === 0) {
            e.preventDefault();


             document.getElementById("stockToastBody").textContent =`Você deve adicionar pelo menos um produto antes de salvar.`;

    // Mostra o container do toast
    document.querySelector('.custom-centered-toast').style.display = 'block';

    // Inicializa e exibe o toast
    let toastElement = document.getElementById('stockToast');
    let toast = new bootstrap.Toast(toastElement);
    toast.show();
            // Exibe um alerta (você pode substituir por um toast bonito se quiser)
           // alert('Você deve adicionar pelo menos um produto antes de salvar.');
           return
        }

        const linhasProdutos = document.querySelectorAll('#tabelaProdutos tbody tr');

    for (let row of linhasProdutos) {
        const qtdInput = row.querySelector('.quantidade');
        const qtd = qtdInput.value.trim();

        if (qtd === "" || isNaN(qtd) || parseInt(qtd) < 1) {
             e.preventDefault();
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
   // document.getElementById('pedidoForm').submit();

    });


 const form2 = document.getElementById('finalizarForm');
form2.addEventListener('submit', function (e) {
    
 const btnSimFinalizar = document.getElementById('btn-finalizar');
        const finalizarForm = this;
     btnSimFinalizar.disabled = true;
            btnSimFinalizar.innerText = 'A processar...';

            // Submeter o formulário
            finalizarForm.submit();
   // document.getElementById('pedidoForm').submit();

    });



});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputQuantidades = document.querySelectorAll('.input-quantidade');
        const totalPedidoSpan = document.getElementById('totalPedidoFormatado');
        const totalAtualizacaoSpan = document.getElementById('totalAtualizacaoModal');
        const totalFinalizarSpan = document.getElementById('totalFinalizarModal');

        function calcularTotal() {
            let total = 0;
            inputQuantidades.forEach(input => {
                const preco = parseFloat(input.dataset.preco);
                const quantidade = parseInt(input.value) || 0;
                total += preco * quantidade;

                // Atualiza subtotal da linha
                const subtotalCelula = input.closest('tr').querySelector('.subtotal');
                if (subtotalCelula) {
                    subtotalCelula.textContent = (preco * quantidade).toLocaleString('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Kz';
                }
            });

            const totalFormatado = total.toLocaleString('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Kz';
            totalPedidoSpan.textContent = totalFormatado;
            if (totalAtualizacaoSpan) totalAtualizacaoSpan.textContent = totalFormatado;
            if (totalFinalizarSpan) totalFinalizarSpan.textContent = totalFormatado;
        }

        // Atualiza total sempre que o valor muda
        inputQuantidades.forEach(input => {
            input.addEventListener('input', calcularTotal);
        });

        // Também calcula o total na primeira carga da página
        calcularTotal();
    });
</script>


 
@endsection
