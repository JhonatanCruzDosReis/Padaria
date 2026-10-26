<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'padaria');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT nome FROM administrador WHERE id_adm = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['admin_id']);
$stmt->execute();
$resultAdm = $stmt->get_result();
$admin = $resultAdm->fetch_assoc();
$stmt->close();

$resultUsers = $conn->query("SELECT id, nome, sobrenome, usuario, celular, data_cadastro, ultimo_login, paginas_visitadas, compras, tempo_ficou FROM usuarios ORDER BY id ASC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logs de Atividades - Padaria</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body {
    background-color: #f8f6f3;
    color: #000; 
    font-family: 'Poppins', sans-serif;
    transition: background-color 0.5s ease, color 0.5s ease; 
    font-size: 16px; 
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

/* Estilos específicos para a tabela de logs */
.logs-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fffaf4;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-top: 20px;
}
body.dark-mode .logs-table {
    background-color: #1e1e1e;
    color: #fff;
}
.logs-table th, .logs-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
body.dark-mode .logs-table th, body.dark-mode .logs-table td {
    border-bottom: 1px solid #555;
}
.logs-table th {
    background: linear-gradient(90deg, #b38b59, #e4c49b);
    color: #fff;
    font-weight: 500;
}
body.dark-mode .logs-table th {
    background: linear-gradient(90deg, #555, #777);
}
.logs-table tr:hover {
    background-color: rgba(179, 139, 89, 0.1);
}
body.dark-mode .logs-table tr:hover {
    background-color: rgba(85, 85, 119, 0.2);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}
</style>
</head>

<body>
<div class="floating-panel">
    <button id="theme-toggle" title="Alternar Tema"><i class="fas fa-moon"></i></button>
    <button id="zoom-in" title="Aumentar Fonte"><i class="fas fa-search-plus"></i></button>
    <button id="zoom-out" title="Diminuir Fonte"><i class="fas fa-search-minus"></i></button>
</div>

<div class="container" id="main-content">
    <h1 class="section-title">Logs de Atividades - <?= htmlspecialchars($admin['nome'] ?? "Administrador") ?></h1>
    <p>Registro dos usuários.</p>

    <table class="logs-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Horário de Cadastro</th>
                <th>Último Login</th>
                <th>Quando Entrou (Último)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($u = $resultUsers->fetch_assoc()): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['nome']) ?> <?= htmlspecialchars($u['sobrenome']) ?></td>
                <td><?= $u['data_cadastro'] ? $u['data_cadastro'] : 'N/A' ?></td>
                <td><?= $u['ultimo_login'] ? $u['ultimo_login'] : 'Nunca logou' ?></td>
                <td><?= $u['ultimo_login'] ? $u['ultimo_login'] : 'Nunca entrou' ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 20px;">
        <a href="perfil_mestre.php" class="btn-primary" style="padding: 10px 20px; border-radius: 25px; text-decoration: none; display: inline-block;">Voltar ao Painel</a>
        <a href="home.php" class="btn-primary" style="padding: 10px 20px; border-radius: 25px; text-decoration: none; display: inline-block; margin-left: 10px;">Voltar ao Site</a>
        <a href="LoginPHP/sair.php" class="btn-primary" style="padding: 10px 20px; border-radius: 25px; text-decoration: none; display: inline-block; margin-left: 10px; background: #dc3545;">Sair</a>
    </div>
</div>

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

    themeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        themeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
    });

    zoomIn.addEventListener('click', function() {
        const currentSize = parseFloat(getComputedStyle(mainContent).fontSize);
        const newSize = Math.min(currentSize + 2, 24); // Máximo 24px
        mainContent.style.fontSize = newSize + 'px';
        localStorage.setItem('fontSize', newSize + 'px');
    });

    zoomOut.addEventListener('click', function() {
        const currentSize = parseFloat(getComputedStyle(mainContent).fontSize);
        const newSize = Math.max(currentSize - 2, 12); // Mínimo 12px
        mainContent.style.fontSize = newSize + 'px';
        localStorage.setItem('fontSize', newSize + 'px');
    });
});
</script>

</body>
</html>