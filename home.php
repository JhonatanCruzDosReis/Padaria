<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Encanto do Pão - Home</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="CSS/estilos.css">
<link rel="stylesheet" href="CSS/navbar_estilo.css">
<link rel="stylesheet" href="CSS/acessibilidade.css">
<link rel="stylesheet" href="CSS/PopUp_login.css">
<link rel="stylesheet" href="CSS/home.css">

<!-- 🔥 CSS DO PAINEL FLUTUANTE ADICIONADO -->
<style>
/* CSS Transformado: Painel de Acessibilidade com Botões Adaptados ao Estilo do Segundo CSS */

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

/* 🔥 PAINEL DE ACESSIBILIDADE TRANSFORMADO: Usando .floating-panel em vez de .accessibility-panel */
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
    background: linear-gradient(135deg, rgba(248, 246, 243, 0.95), rgba(255, 255, 255, 0.95));
    border: 1px solid rgba(179, 139, 89, 0.4);
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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

/* Estilos específicos para navbar (adaptados do primeiro CSS) */
.navbar {
    background-color: #f8f9fa;
}
.navbar-nav .nav-link {
    color: #000000;
}
.navbar-nav .nav-link:hover {
    color: #00796b;
}
/* 🔥 BOTÕES ALTERADOS: Agora usam gradiente e estilo do segundo CSS */
.navbar-nav .nav-item button {
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
.navbar-nav .nav-item button:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(179, 139, 89, 0.3);
}
.navbar-collapse.collapse.show {
    background-color: #ffffff;
}
body.dark-mode .navbar {
    background-color: #1e1e1e !important;
}
body.dark-mode .navbar .nav-link {
    color: #e0e0e0 !important;
}
body.dark-mode .navbar .nav-link:hover {
    color: #ffffff !important;
}
/* 🔥 BOTÕES EM DARK MODE: Gradiente escuro */
body.dark-mode .navbar-nav .nav-item button {
    background: linear-gradient(90deg, #555, #777);
    color: #fff;
}
body.dark-mode .navbar-collapse.collapse.show {
    background-color: #000000;
}
body.dark-mode .popup {
    background: #1e1e1e;
    color: #f1f1f1;
}
body.dark-mode .popup input {
    background: #2c2c2c;
    color: #f1f1f1;
    border: 1px solid #555;
}
body.dark-mode .popup input::placeholder {
    color: #bbb;
}
/* 🔥 BOTÕES DO POPUP ALTERADOS: Gradiente */
body.dark-mode .popup button {
    background: linear-gradient(90deg, #b38b59, #e4c49b);
    color: white;
    border: none;
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
body.dark-mode .popup button:hover {
    background: linear-gradient(90deg, #a47844, #d3b182);
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(179, 139, 89, 0.3);
}
body.dark-mode .fechar {
    color: #f1f1f1;
}
body.dark-mode .link a {
    color: #64b5f6;
}
</style>

</head>
<body>

<?php include 'navbar.php'; ?>

<section class="foco d-flex align-items-center justify-content-center">
  <h1 class="text-center text-white">Bem-vindo à Padaria Artesanal</h1>
</section>

<!-- 🔥 PAINEL DE ACESSIBILIDADE APAGADO E SUBSTITUÍDO PELO FLUTUANTE -->
<div class="floating-panel">
    <button id="theme-toggle" title="Alternar Tema"><i class="fas fa-moon"></i></button>
    <button id="zoom-in" title="Aumentar Fonte"><i class="fas fa-search-plus"></i></button>
    <button id="zoom-out" title="Diminuir Fonte"><i class="fas fa-search-minus"></i></button>
</div>

<main class="container py-5" id="main-content">

  <section id="sobre" class="py-5">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="imagens/sobrenos.jpg" alt="Padaria artesanal" class="img-fluid rounded shadow">
      </div>
      <div class="col-md-6">
        <h3>Nossa História</h3>
        <p>A <strong>Encanto do Pão</strong> é uma padaria artesanal, produzindo pães com fermentação natural Levain, sem aditivos químicos.</p>
        <p>O pão natural possui menos glúten e lactobacilos exclusivos, garantindo sabor e durabilidade.</p>
        <p>O tempo de preparo estendido permite um produto consumível por vários dias sem perder qualidade.</p>
      </div>
    </div>
  </section>

  <section id="valores" class="py-5 text-center">
    <h2>Nossos Valores</h2>
    <div class="row mt-4">
      <div class="col-md-4 mb-3">
        <h4>Qualidade</h4>
        <p>Ingredientes selecionados e sabor inigualável.</p>
      </div>
      <div class="col-md-4 mb-3">
        <h4>Frescura</h4>
        <p>Todos os produtos são preparados no dia.</p>
      </div>
      <div class="col-md-4 mb-3">
        <h4>Compromisso</h4>
        <p>Atendimento com dedicação e carinho.</p>
      </div>
    </div>
  </section>

  <section id="categorias" class="py-5">
    <h2 class="text-center mb-4">Nosso Cardápio</h2>
    <div class="row g-4">
      <div class="col-12 col-md-4">
        <button class="card h-100 shadow rounded-4 border-0 text-start p-0 hover-scale" style="background:#DEB887;" onclick="window.location.href='cardapio.php'">
          <img src="https://i.panelinha.com.br/i1/228-bk-7824-blog-ayu1111.webp" class="card-img-top rounded-top-4" alt="Café da Manhã">
          <div class="card-body">
            <h5 class="card-title">Café da Manhã</h5>
            <p class="card-text">Comece bem o dia com nossas delícias matinais.</p>
          </div>
        </button>
      </div>
      <div class="col-12 col-md-4">
        <button class="card h-100 shadow rounded-4 border-0 text-start p-0 hover-scale" style="background:#DEB887;" onclick="window.location.href='cardapio.php'">
          <img src="https://i.ytimg.com/vi/q5_r4oZ_M8I/hqdefault.jpg" class="card-img-top rounded-top-4" alt="Almoço">
          <div class="card-body">
            <h5 class="card-title">Almoço</h5>
            <p class="card-text">Pratos quentinhos e variados para sua tarde.</p>
          </div>
        </button>
      </div>
      <div class="col-12 col-md-4">
        <button class="card h-100 shadow rounded-4 border-0 text-start p-0 hover-scale" style="background:#DEB887;" onclick="window.location.href='cardapio.php'">
          <img src="https://www.falamart.com.br/wp-content/uploads/2021/08/bebidas-aperitivas.jpg" class="card-img-top rounded-top-4" alt="Bebidas">
          <div class="card-body">
            <h5 class="card-title">Bebidas</h5>
            <p class="card-text">Refresque-se com nossas opções geladinhas.</p>
          </div>
        </button>
      </div>
    </div>
  </section>

    <section id="produtos" class="py-5 text-center">
    <h2 class="mb-4">Nossos Produtos</h2>
    <p class="text-muted mb-5">
      Todos os produtos são artesanais, livres de aditivos e produzidos com ingredientes locais sempre que possível.
    </p>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="produto-item p-4 h-100">
          <div class="icon-circle mx-auto mb-3"><i class="fas fa-bread-slice"></i></div>
          <h5 class="fw-bold">Pães de Fermentação Natural</h5>
          <p>Linha completa de pães artesanais. Faça seu pedido online.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="produto-item p-4 h-100">
          <div class="icon-circle mx-auto mb-3"><i class="fas fa-mug-hot"></i></div>
          <h5 class="fw-bold">Café da Manhã "O Dia Todo"</h5>
          <p>All Day Breakfast, combos servidos durante todo o dia.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="produto-item p-4 h-100">
          <div class="icon-circle mx-auto mb-3"><i class="fas fa-utensils"></i></div>
          <h5 class="fw-bold">Lanches & Refeições</h5>
          <p>Mais de 20 opções de lanches e refeições servidos diariamente.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="produto-item p-4 h-100">
          <div class="icon-circle mx-auto mb-3"><i class="fas fa-coffee"></i></div>
          <h5 class="fw-bold">Carta de Cafés</h5>
          <p>Cafés moídos na hora e encapsulados.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="produto-item p-4 h-100">
          <div class="icon-circle mx-auto mb-3"><i class="fas fa-egg"></i></div>
          <h5 class="fw-bold">Brusquetas, Saladas & Omeletes</h5>
          <p>Servidos durante todo o dia.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="produto-item p-4 h-100">
          <div class="icon-circle mx-auto mb-3"><i class="fas fa-wine-glass-alt"></i></div>
          <h5 class="fw-bold">Sucos, Bebidas & Delicatessen</h5>
          <p>Produtos parceiros artesanais e bebidas variadas.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5">
    <h2 class="mb-4">Nossos melhores comentários:</h2>
    <form id="CommentForm" class="mb-4">
      <div class="mb-3">
        <label for="username" class="form-label">🚹 Nome:</label>
        <input type="text" class="form-control" id="username" placeholder="Seu nome..." required>
      </div>
      <div class="mb-3">
        <label for="comment" class="form-label">Comentário:</label>
        <textarea class="form-control" id="comment" rows="3" placeholder="Escreva seu comentário..." required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Enviar comentário</button>
    </form>
    <div id="commentList"></div>
  </section>

</main>

<div id="footer-container"></div>

<div class="overlay" id="overlay">
  <div class="popup">
    <span class="fechar" onclick="fecharPopup()">&times;</span>
    <h2>Entrar</h2>
    <p>Faça login para acessar sua conta!</p>
    <input type="text" id="usuario" placeholder="E-mail">
    <input type="password" id="senha" placeholder="Senha">
    <label><input type="checkbox" id="lembrar"> Lembrar-me</label>
    <button onclick="login()">LOGIN</button>
    <p class="mensagem" id="mensagem"></p>
    <div class="link">
      <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>
  </div>
</div>

<script src="JS/acessibilidade.js"></script>
<script src="JS/comentarios.js"></script>
<script src="NavScript.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const zoomIn = document.getElementById('zoom-in');
    const zoomOut = document.getElementById('zoom-out');
    const body = document.body;
    const mainContent = document.getElementById('main-content');

    const savedTheme = localStorage.getItem('theme');
    const savedFontSize = localStorage.getItem('fontSize');

    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    } else {
        themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
    }

    if (savedFontSize) {
        mainContent.style.fontSize = savedFontSize;
    }

    // Alternar tema
    themeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        themeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
    });

    // Aumentar fonte
    zoomIn.addEventListener('click', function() {
        const currentSize = parseFloat(getComputedStyle(mainContent).fontSize);
        const newSize = Math.min(currentSize + 2, 24); // Máximo 24px
        mainContent.style.fontSize = newSize + 'px';
        localStorage.setItem('fontSize', newSize + 'px');
    });

    // Diminuir fonte
    zoomOut.addEventListener('click', function() {
        const currentSize = parseFloat(getComputedStyle(mainContent).fontSize);
        const newSize = Math.max(currentSize - 2, 12); // Mínimo 12px
        mainContent.style.fontSize = newSize + 'px';
        localStorage.setItem('fontSize', newSize + 'px');
    });
});
</script>

<script>
  window.addEventListener("DOMContentLoaded", function () {
    document.getElementById("footer-container").innerHTML = `
      <footer class="mt-5 py-4 text-center" style="background-color: var(--cor-primaria); color:white; border-top-left-radius:20px; border-top-right-radius:20px;">
        <p>© 2025 Encanto do Pão - Todos os direitos reservados</p>
      </footer>
    `;
  });
</script>

</body>
</html>
