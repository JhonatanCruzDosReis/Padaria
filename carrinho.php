<?php
session_start();

$conn = new mysqli("localhost", "root", "", "padaria");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

/* 🔥 ADIÇÃO — RECEBER CARRINHO DO JAVASCRIPT (localStorage) */
if (isset($_POST['carrinho'])) {
    $_SESSION['carrinho'] = json_decode($_POST['carrinho'], true);
}
/* 🔥 FIM DA ADIÇÃO */

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_POST['finalizar'])) {

    foreach ($_SESSION['carrinho'] as $item) {
        $id = intval($item['id']);
        $quantidade = intval($item['quantity']);
        $stmt = $conn->prepare("SELECT quantidade FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $produto = $result->fetch_assoc()) {
            if ($produto['quantidade'] >= $quantidade) {
                $novaQuantidade = $produto['quantidade'] - $quantidade;
                $updateStmt = $conn->prepare("UPDATE produtos SET quantidade = ? WHERE id = ?");
                $updateStmt->bind_param("ii", $novaQuantidade, $id);
                $updateStmt->execute();
                $updateStmt->close();
            } else {
                echo "<script>alert('Estoque insuficiente para " . htmlspecialchars($item['nome']) . "!');</script>";
                $stmt->close();
                exit;
            }
        }
        $stmt->close();
    }

    /* 🔥 ADIÇÃO — SALVAR COMPRA NO CAMPO usuario.compras */

    if (isset($_SESSION['user_id'])) {

        $uid = $_SESSION['user_id'];

        // Pegar histórico já existente
        $sql = "SELECT compras FROM usuarios WHERE id = ?";
        $s = $conn->prepare($sql);
        $s->bind_param("i", $uid);
        $s->execute();
        $r = $s->get_result();
        $linha = $r->fetch_assoc();

        $historico = [];

        if (!empty($linha['compras'])) {
            $historico = json_decode($linha['compras'], true);
        }

        // Nova compra
        $novaCompra = [
            "data" => date("Y-m-d H:i:s"),
            "itens" => $_SESSION['carrinho']
        ];

        $historico[] = $novaCompra;

        $jsonFinal = json_encode($historico, JSON_UNESCAPED_UNICODE);

        $u = $conn->prepare("UPDATE usuarios SET compras = ? WHERE id = ?");
        $u->bind_param("si", $jsonFinal, $uid);
        $u->execute();
    }

    /* 🔥 FIM DA ADIÇÃO */

    $_SESSION['carrinho'] = [];
    echo "<script>alert('Compra finalizada com sucesso!');</script>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrinho - Padaria</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
body {
    background-color: #f8f6f3;
    font-family: 'Poppins', sans-serif;
}
.navbar {
    background: linear-gradient(90deg, #b38b59, #e4c49b);
}
.navbar-brand {
    color: #fff !important;
}
.cart-empty {
    text-align: center;
    padding: 50px;
    color: #666;
}
.cart-empty i {
    font-size: 4rem;
    margin-bottom: 20px;
}
.table th {
    background: linear-gradient(90deg, #b38b59, #e4c49b);
    color: #fff;
}
.btn-success {
    background: linear-gradient(90deg, #28a745, #20c997);
    border: none;
}
.btn-success:hover {
    background: linear-gradient(90deg, #218838, #17a2b8);
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg px-4">
    <a class="navbar-brand" href="cardapio.php">
        <i class="fas fa-bread-slice"></i> Encanto do Pão
    </a>
</nav>

<div class="container py-5">
    <h2 class="text-center mb-4"><i class="fas fa-shopping-cart"></i> Seu Carrinho</h2>

    <?php if (empty($_SESSION['carrinho'])): ?>
        <div class="cart-empty">
            <i class="fas fa-shopping-cart"></i>
            <h4>Seu carrinho está vazio</h4>
            <p>Adicione alguns produtos do nosso cardápio!</p>
            <a href="cardapio.php" class="btn btn-primary">Voltar ao Cardápio</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <div class="table-responsive">
                <table class="table table-bordered bg-white text-center align-middle">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço Unitário</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalGeral = 0;
                        foreach ($_SESSION['carrinho'] as $item): 
                            $subtotal = $item['preco'] * $item['quantity'];
                            $totalGeral += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-4">
                <h4>Total: R$ <?= number_format($totalGeral, 2, ',', '.') ?></h4>
                <button type="submit" name="finalizar" class="btn btn-success btn-lg mt-3">Finalizar Compra</button>
            </div>
        </form>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
