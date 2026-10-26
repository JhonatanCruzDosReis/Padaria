<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* 🔥 REGISTRA A ÚLTIMA PÁGINA VISITADA — ADICIONADO */
if (isset($_SESSION['user_id'])) {
    $pagina_atual = basename($_SERVER['REQUEST_URI']); // captura a página completa

    $conn = new mysqli('localhost', 'root', '', 'padaria');
    if (!$conn->connect_error) {
        $stmt = $conn->prepare("UPDATE usuarios SET paginas_visitadas = ? WHERE id = ?");
        $stmt->bind_param("si", $pagina_atual, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
/* 🔥 FIM DO TRECHO ADICIONADO */
?>

<link rel="stylesheet" href="CSS/PopUp_login.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container">

        <a class="navbar-brand" href="home.php" aria-label="Página inicial">
            <img src="imagens/logo.png" alt="Logo da padaria">
        </a>

        <button 
            class="navbar-toggler" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#menuNav"
            aria-controls="menuNav" 
            aria-expanded="false" 
            aria-label="Abrir menu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item"><a class="nav-link active" href="home.php">HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="#sobre">SOBRE</a></li>
                <li class="nav-item"><a class="nav-link" href="cardapio.php">CARDÁPIO</a></li>
                <li class="nav-item"><a class="nav-link" href="home.php/#produtos">PRODUTOS</a></li>
                <li class="nav-item"><a class="nav-link" href="contato.php">CONTATO</a></li>

                <!-- Carrinho -->
                <li class="nav-item position-relative">
                    <a href="carrinho.php" class="nav-link" aria-label="Ir para o carrinho" id="linkCarrinho">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                    </a>
                </li>

                <?php if (isset($_SESSION['tipo_login']) && $_SESSION['tipo_login'] === "admin"): ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="adminMenu" data-bs-toggle="dropdown" style="color: gold; font-size: 1.4rem;">
                            <i class="fas fa-crown"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="perfil_mestre.php">Painel do Administrador</a></li>
                            <li><a class="dropdown-item" href="LoginPHP/suporte.php">Suporte</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="LoginPHP/sair.php">Sair</a></li>
                        </ul>
                    </li>

                <!-- 🔥 USUÁRIO COMUM -->
                <?php elseif (isset($_SESSION['tipo_login']) && $_SESSION['tipo_login'] === "usuario"): ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="userMenu" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="perfil.php">Minha Conta</a></li>
                            <li><a class="dropdown-item" href="carrinho.php">Carrinho</a></li>
                            <li><a class="dropdown-item" href="LoginPHP/suporte.php">Suporte</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="LoginPHP/sair.php">Sair</a></li>
                        </ul>
                    </li>

                <!-- 🔥 NÃO LOGADO → Botão Login -->
                <?php else: ?>
                    <li class="nav-item">
                        <button id="login-btn" class="nav-link btn-login" onclick="abrirPopup()" aria-label="Login">
                            <i class="fas fa-user"></i>
                        </button>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<div class="overlay" id="overlay">
    <div class="popup">
        <span class="fechar" onclick="fecharPopup()">&times;</span>
        <h2>Entrar</h2>
        <p>Faça login para acessar sua conta 🔑</p>

        <form action="LoginPHP/login.php" method="POST">
            <input name="usuario" type="text" placeholder="Usuário ou Email" required>
            <input name="senha" type="password" placeholder="Senha" required>
            <label><input type="checkbox"> Lembrar-me</label>
            <button type="submit">LOGIN</button>
        </form>

        <div class="link">
            <p>Não tem conta? <a href="LoginPHP/cadastro.php">Cadastre-se</a></p>
        </div>
    </div>
</div>

<script src="JS/NavScript.js"></script>
