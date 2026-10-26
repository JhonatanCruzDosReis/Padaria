<?php
session_start();
require_once "LoginPHP/conexao.php"; // << deve ser PDO

if (!isset($_SESSION['usuario'])) {
    header("Location: LoginPHP/login.php");
    exit;
}

$idUsuario = $_SESSION['id'];
$fotoPerfil = $_SESSION['foto_perfil'] ?? "padrao.png";

/* ================================
   BUSCAR DADOS DO USUÁRIO
================================ */
$sql = "SELECT id, nome, sobrenome, cpf, data_nascimento, celular, telefone_extra, endereco, 
        logradouro, complemento, foto_perfil 
        FROM usuarios WHERE id = :id LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Erro: Usuário não encontrado.");
}

$modoEdicao = isset($_GET['editar']);
$msg = "";

/* ================================
   PROCESSO DE ATUALIZAÇÃO
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $novoNome        = trim($_POST['nome']);
    $novoSobrenome   = trim($_POST['sobrenome']);
    $novoCpf         = trim($_POST['cpf']);
    $novoCelular     = trim($_POST['celular']);
    $novoTelExtra    = trim($_POST['telefone_extra']);
    $novoEndereco    = trim($_POST['endereco']);
    $novoLogradouro  = trim($_POST['logradouro']);
    $novoComplemento = trim($_POST['complemento']);

    // Foto
    $novaFoto = $usuario['foto_perfil'];

    if (!empty($_FILES['foto']['name'])) {

        $tmp = $_FILES['foto']['tmp_name'];
        $nomeArq = basename($_FILES['foto']['name']);
        $ext = strtolower(pathinfo($nomeArq, PATHINFO_EXTENSION));
        $permitidas = ["jpg","jpeg","png"];

        if (!in_array($ext, $permitidas)) {
            $msg = "<div class='alert alert-danger'>Formato inválido (apenas JPG e PNG)</div>";
        } else {
            $novoNomeFoto = "foto_" . $idUsuario . "_" . time() . ".$ext";
            $dest = "fotos_perfil/" . $novoNomeFoto;

            if (move_uploaded_file($tmp, $dest)) {
                $novaFoto = $novoNomeFoto;
            }
        }
    }

    // UPDATE via PDO
    $sqlUp = "UPDATE usuarios SET 
                nome=:nome, sobrenome=:sobrenome, cpf=:cpf, celular=:celular, 
                telefone_extra=:telefone_extra, endereco=:endereco, logradouro=:logradouro,
                complemento=:complemento, foto_perfil=:foto 
              WHERE id=:id";

    $stmtUp = $conn->prepare($sqlUp);

    $stmtUp->execute([
        ":nome"            => $novoNome,
        ":sobrenome"       => $novoSobrenome,
        ":cpf"             => $novoCpf,
        ":celular"         => $novoCelular,
        ":telefone_extra"  => $novoTelExtra,
        ":endereco"        => $novoEndereco,
        ":logradouro"      => $novoLogradouro,
        ":complemento"     => $novoComplemento,
        ":foto"            => $novaFoto,
        ":id"              => $idUsuario
    ]);

    // Atualização das sessões
    $_SESSION['nome'] = $novoNome;
    $_SESSION['foto_perfil'] = $novaFoto;

    header("Location: perfil.php?sucesso=1");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil — Encanto do Pão</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f6f3;
            font-family: 'Poppins', sans-serif;
            color: #4e4b47;
        }
        .perfil-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 50px;
            padding: 20px;
        }
        .perfil-info {
            background: #fff;
            border-radius: 18px;
            padding: 25px;
            width: 250px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }
        .perfil-info img {
            width: 120px; height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .perfil-main {
            background: #fff;
            border-radius: 18px;
            padding: 30px;
            width: 550px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }
        .perfil-panel {
            background: #fff;
            border-radius: 18px;
            padding: 20px;
            width: 250px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }
        .perfil-panel a {
            display: block;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 8px;
            text-decoration: none;
            color: #5c4a3e;
        }
        .perfil-panel a:hover {
            background-color: #f3e9dc;
        }
        .campo-label {
            font-weight: bold;
            margin-top: 10px;
            color: #5b4e40;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #f3e9c6;
            color: #7c6c5a;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container perfil-container">

    <!-- Painel lateral -->
    <div class="perfil-info">
        <img src="fotos_perfil/<?= $usuario['foto_perfil'] ?>" alt="Foto de perfil">
        <h3><?= $usuario['nome'] ?></h3>
        <p style="color:#7c6c5a;">Encanto do Pão</p>

        <a href="perfil.php?editar=1" class="btn btn-warning mt-3">Editar Perfil</a>
    </div>

    <!-- Conteúdo principal -->
    <div class="perfil-main">

        <?php if (!$modoEdicao): ?>
            <h2>Meu Perfil</h2>

            <p><span class="campo-label">Nome:</span> <?= $usuario['nome'] ?></p>
            <p><span class="campo-label">Sobrenome:</span> <?= $usuario['sobrenome'] ?></p>
            <p><span class="campo-label">CPF:</span> <?= $usuario['cpf'] ?></p>
            <p><span class="campo-label">Celular:</span> <?= $usuario['celular'] ?></p>
            <p><span class="campo-label">Telefone Extra:</span> <?= $usuario['telefone_extra'] ?></p>
            <p><span class="campo-label">Endereço:</span> <?= $usuario['endereco'] ?></p>
            <p><span class="campo-label">Logradouro:</span> <?= $usuario['logradouro'] ?></p>
            <p><span class="campo-label">Complemento:</span> <?= $usuario['complemento'] ?></p>

        <?php else: ?>

            <h2>Editar Perfil</h2>

            <?= $msg ?>

            <form method="POST" enctype="multipart/form-data">

                <label class="campo-label">Foto de Perfil:</label>
                <input type="file" name="foto" class="form-control mb-3">

                <label class="campo-label">Nome:</label>
                <input type="text" name="nome" class="form-control" value="<?= $usuario['nome'] ?>">

                <label class="campo-label">Sobrenome:</label>
                <input type="text" name="sobrenome" class="form-control" value="<?= $usuario['sobrenome'] ?>">

                <label class="campo-label">CPF:</label>
                <input type="text" name="cpf" class="form-control" value="<?= $usuario['cpf'] ?>">

                <label class="campo-label">Celular:</label>
                <input type="text" name="celular" class="form-control" value="<?= $usuario['celular'] ?>">

                <label class="campo-label">Telefone Extra:</label>
                <input type="text" name="telefone_extra" class="form-control" value="<?= $usuario['telefone_extra'] ?>">

                <label class="campo-label">Endereço:</label>
                <input type="text" name="endereco" class="form-control" value="<?= $usuario['endereco'] ?>">

                <label class="campo-label">Logradouro:</label>
                <input type="text" name="logradouro" class="form-control" value="<?= $usuario['logradouro'] ?>">

                <label class="campo-label">Complemento:</label>
                <input type="text" name="complemento" class="form-control" value="<?= $usuario['complemento'] ?>">

                <button class="btn w-100 mt-4" style="background:#c89b68; color:white;">
                    Salvar Alterações
                </button>
            </form>

        <?php endif; ?>

    </div>

    <!-- Menu lateral -->
    <div class="perfil-panel">
        <strong style="color:#5c4a3e;">Menu</strong>
        <hr>
        <a href=". .home.php"><i class="fas fa-home"></i> Página Inicial</a>
        <a href="carrinho.php"><i class="fas fa-shopping-cart"></i> Minhas Compras</a>
        <a href="LoginPHP/suporte.php"><i class="fas fa-headset"></i> Suporte</a>
        <a href="LoginPHP/sair.php" style="color:#b03b3b;"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>

</div>

<footer>
    © 2025 Encanto do Pão • Todos os direitos reservados
</footer>

</body>
</html>
