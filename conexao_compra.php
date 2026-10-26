<?php


session_start();

$conn = new mysqli("localhost", "root", "", "padaria");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['id'], $input['quantidade'])) {
        $id = intval($input['id']);
        $quantidade = intval($input['quantidade']);
        
        $stmt = $conn->prepare("SELECT nome, preco, quantidade FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $produto = $result->fetch_assoc()) {
            if ($produto['quantidade'] >= $quantidade) {
                $encontrado = false;
                foreach ($_SESSION['carrinho'] as &$item) {
                    if ($item['id'] == $id) {
                        $item['quantity'] += $quantidade;
                        $encontrado = true;
                        break;
                    }
                }
                if (!$encontrado) {
                    $_SESSION['carrinho'][] = [
                        'id' => $id,
                        'nome' => $produto['nome'],
                        'preco' => floatval($produto['preco']),
                        'quantity' => $quantidade
                    ];
                }
                echo json_encode(['success' => true, 'message' => 'Produto adicionado ao carrinho!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Estoque insuficiente!']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Produto não encontrado!']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos!']);
    }
    exit;
}

header('Location: cardapio.php');
exit;
?>
