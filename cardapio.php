<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'padaria');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if (isset($_GET['get_cart']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($_SESSION['carrinho'] ?? []);
    exit;
}

$categorias = [
    1 => 'Café',
    2 => 'Almoço',
    3 => 'Sobremesa',
    4 => 'Jantar',
    5 => 'Especial',
    6 => 'Gelados'
];

function renderProdutos($conn, $categoriaId = null) {
    $sql = "SELECT DISTINCT p.id, p.nome, p.descricao, p.preco, p.imagem, p.categoria_id
            FROM produtos p";
    if ($categoriaId !== null) {
        $sql .= " WHERE p.categoria_id = ?";
    }
    $sql .= " ORDER BY p.categoria_id, p.nome";

    $stmt = $conn->prepare($sql);
    if ($categoriaId !== null) {
        $stmt->bind_param("i", $categoriaId);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($produto = $result->fetch_assoc()) {
            $srcImagem = (strpos($produto['imagem'], 'http') === 0) ? $produto['imagem'] : 'IMG/' . $produto['imagem'];
            echo '<div class="col-md-4 col-lg-3 produto-card" data-category="' . $produto['categoria_id'] . '" data-name="' . strtolower(htmlspecialchars($produto['nome'])) . '" data-description="' . strtolower(htmlspecialchars($produto['descricao'])) . '" role="article" aria-labelledby="produto-' . $produto['id'] . '-title">';
            echo '<div class="card h-100 text-center">';
            echo '<img src="' . htmlspecialchars($srcImagem) . '" class="card-img-top" alt="' . htmlspecialchars($produto['nome']) . '" loading="lazy" onerror="this.src=\'IMG/placeholder.jpg\'">';
            echo '<div class="card-body p-3 d-flex flex-column justify-content-between">';
            echo '<div>';
            echo '<h5 id="produto-' . $produto['id'] . '-title" class="card-title">' . htmlspecialchars($produto['nome']) . '</h5>';
            echo '<p class="text-muted small">' . htmlspecialchars($produto['descricao']) . '</p>';
            echo '<p class="card-text text-muted small">R$ ' . number_format($produto['preco'], 2, ',', '.') . '</p>';
            echo '</div>';
            echo '<button class="btn btn-primary btn-sm add-to-cart mt-auto" data-id="' . $produto['id'] . '" data-nome="' . htmlspecialchars($produto['nome']) . '" data-preco="' . $produto['preco'] . '" data-img="' . htmlspecialchars($srcImagem) . '" aria-label="Adicionar ' . htmlspecialchars($produto['nome']) . ' ao carrinho">Adicionar ao carrinho</button>';
            echo '</div></div></div>';
        }
    } else {
        echo '<p class="text-center text-muted">Nenhum produto encontrado.</p>';
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Cardápio</title>
<link rel="stylesheet" href="CSS/estilos.css">
<link rel="stylesheet" href="CSS/cardapio.css">
<link rel="stylesheet" href="CSS/acessibilidade.css">
<link rel="stylesheet" href="CSS/navbar_estilo.css">
<link rel="stylesheet" href="CSS/PoUp_login.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<style>
body {
    background-color: #f8f6f3;
    color: #000; 
    font-family: 'Poppins', sans-serif;
    transition: background-color 0.5s ease, color 0.5s ease; 
    font-size: 16px; /* Tamanho base da fonte */
}
body.dark-mode { 
    background-color: #121212; 
    color: #fff; 
}
body.dark-mode .card { background-color: #1e1e1e; color: #fff; }
body.dark-mode .category-btn { background: #333; color: #fff; border-color: #555; }
body.dark-mode .category-btn:hover,
body.dark-mode .category-btn.active { background: linear-gradient(90deg, #555, #777); color: #fff; }
body.dark-mode .section-title { background: linear-gradient(90deg, #555, #777); color: #fff; }
body.dark-mode .text-muted { color: #cccccc !important; } 
body.dark-mode h2, body.dark-mode h5, body.dark-mode p { color: #fff; } 

.category-nav { display: flex; justify-content: center; gap: 12px; margin: 25px 0; flex-wrap: wrap; }
.category-btn { border: 1px solid #b38b59; background: #fff; color: #b38b59; padding: 10px 22px; border-radius: 50px; font-weight: 500; cursor: pointer; transition: .3s; }
.category-btn:hover, .category-btn.active { background: linear-gradient(90deg, #b38b59, #e4c49b); color: #fff; border: none; }

.produto-section { display: none; }
.produto-section.show { display: block; }

.card { background-color: #fffaf4; border: none; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); transition: .3s; border-radius: 15px; overflow: hidden; }
.card:hover { transform: translateY(-6px); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); }

.card-img-top { height: 200px; object-fit: cover; border-radius: 15px 15px 0 0; transition: .3s; }
.card-img-top:hover { transform: scale(1.05); }

.btn-primary { background: linear-gradient(90deg, #b38b59, #e4c49b); border: none; }
.btn-primary:hover { background: linear-gradient(90deg, #a47844, #d3b182); }

.section-title { background: linear-gradient(90deg, #b38b59, #e4c49b); padding: 8px 16px; border-radius: 25px; color: #fff; font-weight: 500; display: inline-block; margin-bottom: 20px; }

.search-container { margin-bottom: 20px; text-align: center; }
.search-input { border: 2px solid #b38b59; border-radius: 25px; padding: 8px 16px; font-size: 14px; width: 300px; max-width: 90%; outline: none; transition: border-color 0.3s; }
.search-input:focus { border-color: #e4c49b; }

.floating-panel {
    position: fixed;
    right: 10px;
    top: 40%;
    padding: 20px;
    z-index: 999;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    transition: all 0.4s ease;
    max-width: 180px;
}
body.dark-mode .floating-panel {
    background: linear-gradient(135deg, rgba(30, 30, 30, 0.95), rgba(18, 18, 18, 0.95));
    color: #fff;
    border-color: rgba(228, 196, 155, 0.4);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
}
.floating-panel button {
    background: linear-gradient(90deg, #b38b59, #e4c49b);
    border: none;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.floating-panel button:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(179, 139, 89, 0.3);
}
body.dark-mode .floating-panel button {
    background: linear-gradient(90deg, #555, #777);
    color: #fff;
}
@media (max-width: 768px) {
    .floating-panel {
        top: 70px;
        right: 10px;
        left: auto;
        transform: none;
        flex-direction: row;
        justify-content: space-around;
        padding: 10px;
        max-width: none;
        width: auto;
    }
}

#main-content {
    transition: font-size 0.3s ease; /* Transição para o zoom de fonte */
}

@keyframes themeFade {
    0% { opacity: 0.9; transform: scale(0.98); }
    50% { opacity: 1; transform: scale(1); }
    100% { opacity: 1; transform: scale(1); }
}
body {
    animation: themeFade 0.5s ease; 
}

.modal-content {
    border-radius: 15px;
}
.modal-header {
    background: linear-gradient(90deg, #b38b59, #e4c49b);
    color: #fff;
    border-radius: 15px 15px 0 0;
}
.modal-body {
    text-align: center;
}

#cartConfirmModal .modal-content {
    border-radius: 15px;
}
#cartConfirmModal .modal-header {
    background: linear-gradient(90deg, #b38b59, #e4c49b);
    color: #fff;
    border-radius: 15px 15px 0 0;
}
#cartConfirmModal .modal-body {
    text-align: center;
    color: #000;
}

/* Acessibilidade adicional */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

.focus-visible:focus {
    outline: 2px solid #b38b59;
    outline-offset: 2px;
}

</style>
</head>

<body>
<?php include 'navbar.php'; ?>

<div class="floating-panel">
    <button id="zoom-out" title="Diminuir zoom" aria-label="Diminuir zoom"><i class="fas fa-search-minus"></i></button>
    <button id="zoom-in" title="Aumentar zoom" aria-label="Aumentar zoom"><i class="fas fa-search-plus"></i></button>
    <button id="toggle-dark-mode" title="Alternar modo escuro" aria-label="Alternar modo escuro"><i class="fas fa-moon"></i></button>
    <button id="search-toggle" title="Buscar produto" aria-label="Buscar produto"><i class="fas fa-search"></i></button>
</div>

<div class="modal fade" id="quantityModal" tabindex="-1" aria-labelledby="quantityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="quantityModalLabel">Quantidade</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>Quantas unidades deseja adicionar?</p>
        <input type="number" id="quantityInput" class="form-control" min="1" max="99" value="1" style="width: 100px; margin: 0 auto;" aria-label="Quantidade">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmAdd">Adicionar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="cartConfirmModal" tabindex="-1" aria-labelledby="cartConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cartConfirmModalLabel">Item adicionado!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p>Gostaria de ver o carrinho?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
        <button type="button" class="btn btn-primary" id="goToCartBtn">Sim</button>
      </div>
    </div>
  </div>
</div>

<div id="main-content">
<main class="container py-4">
  <h2 class="text-center mb-4">Nosso Cardápio</h2>

  <div class="category-nav">
    <button class="category-btn active" data-filter="0" aria-pressed="true">Todos</button>
    <?php foreach ($categorias as $id => $nome): ?>
      <button class="category-btn" data-filter="<?php echo $id; ?>" aria-pressed="false"><?php echo htmlspecialchars($nome); ?></button>
    <?php endforeach; ?>
  </div>

  <div class="produto-section show" id="todos">
    <div class="text-center mb-4">
      <span class="section-title">Todos os Itens</span>
    </div>
    <div class="row g-4">
      <?php renderProdutos($conn); ?>
    </div>
  </div>

  <?php foreach ($categorias as $id => $nome): ?>
    <div class="produto-section" id="cat<?php echo $id; ?>">
      <div class="text-center mb-4">
        <span class="section-title"><?php echo htmlspecialchars($nome); ?></span>
      </div>
      <div class="row g-4">
        <?php renderProdutos($conn, $id); ?>
      </div>
    </div>
  <?php endforeach; ?>
</main>

<footer class="text-center py-3 mt-5">
  <p>&copy; <?php echo date("Y"); ?> | Encanto do Pão - Sabor & Elegância</p>
</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    let cart = [];
    const mainContent = document.getElementById('main-content');
    let fontSize = 16; // Tamanho base da fonte

    // ---------------- DARK MODE ----------------
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        document.getElementById('toggle-dark-mode')
            .querySelector('i').className = 'fas fa-sun';
    }

    // ---------------- CARREGAR O CARRINHO ----------------
    const loadCart = () => {
        fetch('?get_cart=1')
            .then(res => res.json())
            .then(data => { 
                cart = data; 
                updateCartCount(); // 🔥 ATUALIZA O CONTADOR APÓS CARREGAR
            })
            .catch(err => console.error('Erro ao carregar carrinho:', err));
    };

    // 🔥 NOVA FUNÇÃO: ATUALIZAR CONTADOR DO CARRINHO
    const updateCartCount = () => {
        const totalItems = cart.reduce((sum, item) => sum + (item.quantidade || 1), 0);
        const cartCountEl = document.getElementById('cartCount');
        if (cartCountEl) {
            cartCountEl.textContent = totalItems;
            cartCountEl.style.display = totalItems > 0 ? 'inline-block' : 'none'; // Oculta se 0
        }
    };

    loadCart();

    // ---------------- FILTRO DE CATEGORIAS ----------------
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', () => {

            document.querySelectorAll('.category-btn').forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });

            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');

            const filter = btn.dataset.filter;

            document.querySelectorAll('.produto-section')
                .forEach(sec => sec.classList.remove('show'));

            document.getElementById(filter === '0' ? 'todos' : 'cat' + filter)
                .classList.add('show');
        });
    });

// ---------------- MODAL DE QUANTIDADE ----------------
const quantityModal = new bootstrap.Modal(document.getElementById('quantityModal'));
const quantityInput = document.getElementById('quantityInput');
const confirmAdd = document.getElementById('confirmAdd');
let currentProduct = null;

document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', () => {
        currentProduct = {
            id: btn.dataset.id,
            nome: btn.dataset.nome,
            preco: parseFloat(btn.dataset.preco),
            img: btn.dataset.img
        };

        quantityInput.value = 1;
        quantityModal.show();
    });
});

// ---------------- CONFIRMAR ADIÇÃO AO CARRINHO ----------------
const cartConfirmModal = new bootstrap.Modal(document.getElementById('cartConfirmModal'));
const goToCartBtn = document.getElementById('goToCartBtn');

confirmAdd.addEventListener('click', () => {
    const quantity = parseInt(quantityInput.value);

    if (quantity > 0 && quantity <= 99 && currentProduct) {
        fetch('conexao_compra.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: currentProduct.id,
                quantidade: quantity
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                quantityModal.hide();
                loadCart(); // 🔥 RECARREGA O CARRINHO E ATUALIZA O CONTADOR
                cartConfirmModal.show();
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error('Erro:', err);
            alert('Erro de rede ao adicionar ao carrinho.');
        });
    }
});

goToCartBtn.addEventListener('click', () => {
    cartConfirmModal.hide();
    window.location.href = 'carrinho.php';
});

// ---------------- ACESSIBILIDADE (ZOOM) ----------------
document.getElementById('zoom-in').addEventListener('click', () => {
    fontSize = Math.min(fontSize + 2, 32);
    mainContent.style.fontSize = `${fontSize}px`;
});

document.getElementById('zoom-out').addEventListener('click', () => {
    fontSize = Math.max(fontSize - 2, 12);
    mainContent.style.fontSize = `${fontSize}px`;
});

// ---------------- DARK MODE (EVENTO) ----------------
const toggleDarkMode = document.getElementById('toggle-dark-mode');

toggleDarkMode.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');

    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark);

    const icon = toggleDarkMode.querySelector('i');
    icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
});

// ---------------- BARRA DE PESQUISA EXPANSÍVEL ----------------
const searchToggle = document.getElementById('search-toggle');

const searchInput = document.createElement('input');
searchInput.type = 'text';
searchInput.id = 'search-input';
searchInput.placeholder = 'Digite o nome do produto';
searchInput.className = 'search-input-navbar';

searchInput.style.cssText = `
    position: fixed;
    top: 50%;
    right: 20px;
    width: 0;
    opacity: 0;
    border: 2px solid #b38b59;
    border-radius: 25px;
    padding: 5px 10px;
    font-size: 14px;
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    transition: all 0.3s ease;
    outline: none;
    z-index: 1000;
`;

document.body.appendChild(searchInput);

searchToggle.addEventListener('click', () => {
    if (searchInput.style.width === '200px') {
        searchInput.style.width = '0';
        searchInput.style.opacity = '0';
        searchInput.value = '';

        document.querySelectorAll('.produto-card')
            .forEach(card => card.style.display = 'block');
    } else {
        searchInput.style.width = '200px';
        searchInput.style.opacity = '1';
        searchInput.focus();
    }
});

searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase();

    document.querySelectorAll('.produto-card').forEach(card => {
        const name = card.dataset.name;
        const desc = card.dataset.description;

        card.style.display =
            (name.includes(query) || desc.includes(query))
                ? 'block'
                : 'none';
    });
});

// ---------------- REDIRECIONAR LOGIN ----------------
const loginBtn = document.getElementById('login-btn'); // Ajustar conforme navbar.php

if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        window.location.href = 'LoginPHP/Login.php';
    });
}

});

document.addEventListener('DOMContentLoaded', function() {
    fetch('logs.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'log=' + encodeURIComponent(window.location.pathname)
    });
});
</script>

</body>
</html>
