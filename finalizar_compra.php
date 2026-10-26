<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !is_array($data)) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "Padaria";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro na conexão com o banco.']);
    exit;
}

$conn->query("CREATE TABLE IF NOT EXISTS Pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_produto VARCHAR(100),
    preco DECIMAL(10,2),
    quantidade INT,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $conn->prepare("INSERT INTO Pedido (nome_produto, preco, quantidade) VALUES (?, ?, ?)");

foreach ($data as $item) {
    $stmt->bind_param("sdi", $item['nome'], $item['preco'], $item['quantidade']);
    $stmt->execute();
}

$stmt->close();

/* ============================================================
   🔥 TRECHO ADICIONAL — MANTIDO EXATAMENTE COMO PEDIU 🔥
   ============================================================ */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* Em vez de usar o JSON para saber o que foi comprado,
   pegamos EXATAMENTE os produtos do carrinho da sessão */
$produtos_comprados = [];

// Se o usuário estiver logado e o carrinho existir:
if (isset($_SESSION['user_id']) && isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {

    // Extrai SOMENTE os nomes dos produtos que estavam no carrinho
    foreach ($_SESSION['carrinho'] as $item) {
        if (isset($item['nome'])) {
            $produtos_comprados[] = $item['nome'];
        }
    }

    // Monta a string dos produtos
    $produtos_str = implode(', ', $produtos_comprados);

    // Salva no campo "compras" do usuário
    $stmt = $conn->prepare("UPDATE usuarios 
        SET compras = CONCAT(IFNULL(compras, ''), ', $produtos_str') 
        WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

echo json_encode(['success' => true]);
?>
