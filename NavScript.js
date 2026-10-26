document.addEventListener("DOMContentLoaded", function() {

    const darkModeToggle = document.getElementById("darkModeToggle");
    const body = document.body;

    if (localStorage.getItem("darkMode") === "enabled") {
        body.classList.add("dark-mode");
    }

    if (darkModeToggle) {
        darkModeToggle.addEventListener("click", function() {
            body.classList.toggle("dark-mode");
            localStorage.setItem("darkMode", body.classList.contains("dark-mode") ? "enabled" : "disabled");
        });
    }

    const increaseBtn = document.getElementById("increaseFontSize");
    const decreaseBtn = document.getElementById("decreaseFontSize");
    const htmlRoot = document.documentElement;

    function getRootFontSize() {
        const sizeString = getComputedStyle(htmlRoot).getPropertyValue('--font-size');
        return parseFloat(sizeString);
    }

    if (increaseBtn) {
        increaseBtn.addEventListener("click", function() {
            let currentSize = getRootFontSize();
            if (currentSize < 25) htmlRoot.style.setProperty("--font-size", `${currentSize * 1.1}px`);
        });
    }

    if (decreaseBtn) {
        decreaseBtn.addEventListener("click", function() {
            let currentSize = getRootFontSize();
            if (currentSize > 13) htmlRoot.style.setProperty("--font-size", `${currentSize / 1.1}px`);
        });
    }

    const navbarToggler = document.querySelector(".navbar-toggler");
    if (navbarToggler) {
        navbarToggler.addEventListener("click", function() {
            var navbarCollapse = document.querySelector(".navbar-collapse");
            navbarCollapse.style.backgroundColor = navbarCollapse.classList.contains("show") ? "" : "#ffffff";
        });
    }

    const linkCarrinho = document.getElementById("linkCarrinho");
    if (linkCarrinho) {
        linkCarrinho.addEventListener("click", function(event) {
            event.preventDefault();
            window.location.href = "carrinho.php";
        });
    }

    atualizarCarrinho();

});

let carrinho = [];

function salvarCarrinho() {
    localStorage.setItem("carrinho", JSON.stringify(carrinho));
}

function carregarCarrinho() {
    const dados = localStorage.getItem("carrinho");
    carrinho = dados ? JSON.parse(dados) : [];
}

function adicionarAoCarrinho(nome, preco, imagem) {
    const precoFloat = parseFloat(String(preco).replace(",", "."));
    carregarCarrinho();
    let item = carrinho.find(p => p.nome === nome);
    if (item) {
        item.qtd++;
    } else {
        carrinho.push({ nome, preco: precoFloat, qtd: 1, imagem });
    }
    salvarCarrinho();
    atualizarCarrinho();
    alert(`"${nome}" foi adicionado ao carrinho!`);
}

function atualizarCarrinho() {
    carregarCarrinho();
    const cartCountElement = document.getElementById("cartCount");
    const listaOffcanvas = document.getElementById("cartItems");
    const totalOffcanvas = document.getElementById("cartTotal");

    let totalItens = 0;
    let total = 0;
    let listaHTML = "";

    carrinho.forEach(item => {
        const subtotal = item.preco * item.qtd;
        total += subtotal;
        totalItens += item.qtd;
        listaHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                ${item.nome} (x${item.qtd})
                <span>R$ ${subtotal.toFixed(2)}</span>
            </li>
        `;
    });

    if (cartCountElement) {
        cartCountElement.textContent = totalItens > 0 ? totalItens : "0";
    }

    if (listaOffcanvas) {
        listaOffcanvas.innerHTML = listaHTML || '<li class="list-group-item">Carrinho vazio.</li>';
    }

    if (totalOffcanvas) {
        totalOffcanvas.textContent = "R$ " + total.toFixed(2);
    }
}

function abrirPopup() {
    const overlay = document.getElementById("overlay");
    if (overlay) overlay.style.display = "flex";
}

function fecharPopup() {
    const overlay = document.getElementById("overlay");
    if (overlay) overlay.style.display = "none";
}

document.addEventListener('DOMContentLoaded', function() {
    fetch('logs.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'log=' + encodeURIComponent(window.location.pathname)
    });
});
