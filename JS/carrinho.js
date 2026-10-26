function obterCarrinho() {
    return JSON.parse(localStorage.getItem('carrinho')) || [];
}

function salvarCarrinho(carrinho) {
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    atualizarContadorCarrinho();
    atualizarCarrinhoOffcanvas();
}

function adicionarAoCarrinho(nome, preco, imagem = '', categoria = '', descricao = '') {
    const carrinho = obterCarrinho();
    const existente = carrinho.find(item => item.nome === nome);

    if (existente) {
        existente.quantidade++;
    } else {
        carrinho.push({ nome, preco, quantidade: 1, imagem, categoria, descricao });
    }

    salvarCarrinho(carrinho);
    alert(`"${nome}" foi adicionado ao carrinho!`);
}

function removerDoCarrinho(nome) {
    let carrinho = obterCarrinho();
    carrinho = carrinho.filter(item => item.nome !== nome);
    salvarCarrinho(carrinho);
}

function atualizarContadorCarrinho() {
    const carrinho = obterCarrinho();
    const totalItens = carrinho.reduce((soma, item) => soma + item.quantidade, 0);
    const contador = document.getElementById('cartCount');

    if (contador) {
        contador.textContent = totalItens;
    }
}

function atualizarCarrinhoOffcanvas() {
    const carrinho = obterCarrinho();
    const lista = document.getElementById('cartItems');
    const totalEl = document.getElementById('cartTotal');

    if (!lista || !totalEl) return;

    lista.innerHTML = '';
    let total = 0;

    carrinho.forEach(item => {
        const li = document.createElement('li');
        li.className = "list-group-item d-flex justify-content-between align-items-center";
        li.innerHTML = `
            <div>
                <strong>${item.nome}</strong><br>
                <small>${item.categoria || ''}</small>
            </div>
            <div>
                <span>R$ ${(item.preco * item.quantidade).toFixed(2)}</span><br>
                <small>x${item.quantidade}</small>
            </div>
        `;
        lista.appendChild(li);
        total += item.preco * item.quantidade;
    });

    totalEl.textContent = total.toFixed(2);
}

function finalizarCompra() {
    const carrinho = obterCarrinho();
    if (carrinho.length === 0) {
        alert("Seu carrinho está vazio!");
        return;
    }

    if (!confirm("Deseja finalizar sua compra?")) return;

    fetch('carrinho.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'carrinho=' + encodeURIComponent(JSON.stringify(carrinho))
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'ok') {
            alert("Compra finalizada com sucesso!");
            localStorage.removeItem('carrinho');
            salvarCarrinho([]);
        } else {
            alert("Erro ao finalizar compra.");
        }
    })
    .catch(() => alert("Erro ao conectar ao servidor."));
}

document.addEventListener("DOMContentLoaded", () => {
    atualizarContadorCarrinho();
    atualizarCarrinhoOffcanvas();
});
