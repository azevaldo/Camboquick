@extends('app.layouts.app')

@section('content')
 


<div class="container mt-4">
    <div class="card">
        <div class="card-header text-white" style="background-color: rgb(255, 176, 29)">
            <h4>Adicionar Venda</h4>
        </div>
        <div class="card-body">

    <!-- Campo para Nome do Cliente -->
    <div class="row mb-3">
    
        <div class="col-md-6 mb-3">
           <label>Nome Cliente</label>
            <input id="nome-cliente" type="text" class="form-control" placeholder="Nome do cliente" >
        </div>

                <div class="col-md-6 mb-3">
                   <label>NIF</label>
            <input id="nif-cliente" type="text" class="form-control" placeholder="Nif do cliente" >
        </div>
                <div class="col-md-6 mb-3">
                   <label>Contato</label>
            <input id="contato-cliente" type="text" class="form-control" placeholder="Contato do cliente" >
        </div>
              <div class="col-md-6 mb-3">
                   <label>Localização</label>
            <input id="local-cliente" type="text" class="form-control" placeholder="Localização do cliente" >
        </div>
    </div>

    <!-- Pesquisa de Produto e Seleção -->
    <div class="row mb-3">
        <div class="col-md-6">
            <input id="pesquisa-produto" type="text" class="form-control" placeholder="Pesquisar produto...">
        </div>
        <div class="col-md-6">
            <select id="produto-selecionado" class="form-select">
                <!-- Produtos serão preenchidos dinamicamente -->
                <option value="" disabled>Selecione Um Produto</option>
                @foreach ($produtos as $produto)

                    <option value="{{ $produto->id }}" data-nome="{{ $produto->nome }}" data-imposto="{{$produto->imposto}}" data-preco="{{ $produto->preco }}">
                        {{ $produto->nome }} - {{ number_format($produto->preco, 2, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mt-2">
            <button class="btn btn-primary" onclick="adicionarProduto()">Adicionar Produto</button>
        </div>
    </div>

    <!-- Tabela de Produtos Vendidos -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                 <th>Imposto</th>
                <th>Subtotal</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody id="lista-produtos"></tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                <td colspan="3"><strong id="total-venda">0.00</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Botão de Finalizar Compra -->
    <div class="col-md-12 mt-4">
        <button class="btn btn-success" onclick="finalizarCompra()">Finalizar Compra</button>
    </div>
</div>
</div>
</div>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const listaProdutos = document.getElementById("lista-produtos");
    const totalVenda = document.getElementById("total-venda");
    const pesquisaProduto = document.getElementById("pesquisa-produto");
    const selectProduto = document.getElementById("produto-selecionado");
    const nomeCliente = document.getElementById("nome-cliente");
    const nifCliente=document.getElementById('nif-cliente');
    const contatoCliente=document.getElementById('contato-cliente');
        const localCliente=document.getElementById('local-cliente');
    let vendaItens = [];

    // Função para filtrar produtos pela pesquisa
    function filtrarProdutos() {
        const termo = pesquisaProduto.value.toLowerCase();
        const options = selectProduto.getElementsByTagName("option");
        
        Array.from(options).forEach(option => {
            const nome = option.getAttribute("data-nome").toLowerCase();
            option.style.display = nome.includes(termo) ? "block" : "none";
        });
    }

    // Atualizar a tabela de produtos vendidos
    function atualizarTabela() {
        listaProdutos.innerHTML = "";
        let total = 0;
        vendaItens.forEach((item, index) => {
            const subtotal = item.quantidade * item.preco*(1 + item.imposto / 100);
            total += subtotal;
            listaProdutos.innerHTML += `
                <tr>
                    <td>${item.nome}</td>
                    <td>${item.preco.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="alterarQuantidade(${index}, -1)">-</button>
                        ${item.quantidade}
                        <button class="btn btn-sm btn-success" onclick="alterarQuantidade(${index}, 1)">+</button>
                    </td>
                       <td>${item.imposto}</td>
                    <td>${subtotal.toFixed(2)}</td>
                    <td><button class="btn btn-sm btn-warning" onclick="removerItem(${index})">Remover</button></td>
                </tr>
            `;
        });
        totalVenda.innerText = total.toFixed(2);
    }

    window.adicionarProduto = function () {
        const produtoId = parseInt(selectProduto.value);
        const nome = selectProduto.options[selectProduto.selectedIndex].getAttribute("data-nome");
        const preco = parseFloat(selectProduto.options[selectProduto.selectedIndex].getAttribute("data-preco"));
        const imposto=parseInt(selectProduto.options[selectProduto.selectedIndex].getAttribute("data-imposto"));
        const existente = vendaItens.find(item => item.id === produtoId);
        if (existente) {
            existente.quantidade++;
        } else {
            vendaItens.push({ id: produtoId, nome, preco, quantidade: 1,imposto });
        }
        atualizarTabela();
    };

    window.alterarQuantidade = function (index, quantidade) {
        if (vendaItens[index].quantidade + quantidade > 0) {
            vendaItens[index].quantidade += quantidade;
        } else {
            vendaItens.splice(index, 1);
        }
        atualizarTabela();
    };

    window.removerItem = function (index) {
        vendaItens.splice(index, 1);
        atualizarTabela();
    };

    window.finalizarCompra = function () {
        const cliente = nomeCliente.value;
        const nif_cliente2=nifCliente.value;
        const contato_cliente2=contatoCliente.value;
        const local_cliente2=localCliente.value;
        
        if (!cliente || vendaItens.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos obrigatórios',
                text: 'Por favor, preencha o nome do cliente e adicione pelo menos um produto.',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#3085d6',
                timer: 4000
            });
            return;
        }

        // Enviar os dados para o controller via AJAX
        const vendaData = {
            nome_cliente: cliente,
            nif_cliente:nif_cliente2,
            contato_cliente:contato_cliente2,
            local_cliente:local_cliente2,
            itens: vendaItens,
            total: parseFloat(totalVenda.innerText),
        };

        fetch("{{ route('vendas.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(vendaData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Venda realizada com sucesso!',
                    text: 'A venda foi registrada corretamente.',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#3085d6',
                    timer: 3000
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao realizar a venda. Tente novamente mais tarde',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#d33',
                    timer: 4000
                });
            }
        });
    };

    // Inicializar a pesquisa de produtos
    pesquisaProduto.addEventListener("input", filtrarProdutos);
});
</script>

 
       
@endsection