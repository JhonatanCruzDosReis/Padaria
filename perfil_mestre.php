<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'padaria');
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT id_adm, nome, cpf, tel, tel_auxiliar, endereco, data_nasc, h_trab, salario FROM administrador LIMIT 1";
$resultAdm = $conn->query($sql);
$admin = $resultAdm->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ------------------------------------
       EXCLUIR USUÁRIO
    ------------------------------------ */
    if (isset($_POST['excluir_usuario'])) {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
    }

    /* ------------------------------------
       CRIAR PRODUTO (CORRIGIDO imagens/)
    ------------------------------------ */
    if (isset($_POST['criar_produto'])) {

        $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, quantidade, imagem, categoria_id) 
                                VALUES (?, ?, ?, ?, ?, ?)");

        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $quantidade = $_POST['quantidade'];
        $categoria_id = $_POST['categoria_id'];

        $imagem = $_FILES['imagem']['name'];
        if ($imagem) {
            move_uploaded_file($_FILES['imagem']['tmp_name'], "imagens/" . $imagem);
        }

        $stmt->bind_param("ssdisi", $nome, $descricao, $preco, $quantidade, $imagem, $categoria_id);
        $stmt->execute();
    }

    /* ------------------------------------
       EDITAR PRODUTO (CORRIGIDO)
    ------------------------------------ */
    if (isset($_POST['editar_produto'])) {

        $stmt = $conn->prepare("UPDATE produtos 
                                SET nome=?, descricao=?, preco=?, quantidade=?, categoria_id=? 
                                WHERE id=?");

        $stmt->bind_param("ssdisi",
            $_POST['nome'],
            $_POST['descricao'],
            $_POST['preco'],
            $_POST['quantidade'],
            $_POST['categoria_id'],
            $_POST['id']
        );
        $stmt->execute();

        /* Atualizar imagem se enviada */
        if ($_FILES['imagem']['name']) {
            $imagem = $_FILES['imagem']['name'];
            move_uploaded_file($_FILES['imagem']['tmp_name'], "imagens/" . $imagem);
            $conn->query("UPDATE produtos SET imagem='$imagem' WHERE id=" . $_POST['id']);
        }
    }

    /* ------------------------------------
       EXCLUIR PRODUTO
    ------------------------------------ */
    if (isset($_POST['excluir_produto'])) {
        $stmt = $conn->prepare("DELETE FROM produtos WHERE id=?");
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
    }

    /* ------------------------------------
       ADICIONAR QUANTIDADE
    ------------------------------------ */
    if (isset($_POST['add_qtd'])) {
        $stmt = $conn->prepare("UPDATE produtos SET quantidade = quantidade + ? WHERE id=?");
        $stmt->bind_param("ii", $_POST['mais'], $_POST['id']);
        $stmt->execute();
    }
}

$orderBy = isset($_GET['order']) ? $_GET['order'] : 'id';
$orderDir = isset($_GET['dir']) && $_GET['dir'] === 'asc' ? 'ASC' : 'DESC';

$resultProdutos = $conn->query("SELECT * FROM produtos ORDER BY $orderBy $orderDir");
$resultUsuarios = $conn->query("SELECT * FROM usuarios ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel do Administrador</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
.dashboard-container { display:flex; width:1200px; max-width:95%; margin:30px auto; background:white; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); overflow:hidden; }
.sidebar { width:220px; background:#dde3e9; padding:20px; text-align:center; }
.sidebar .profile-icon { font-size:48px; margin-bottom:15px; }
.sidebar a { display:block; padding:10px; margin:5px 0; border-radius:6px; text-decoration:none; font-weight:bold; color:#333; }
.sidebar a:hover, .ativo { background:#c7d1db; }
.main-content { flex:1; padding:30px; }
table { width:100%; border-collapse:collapse; background:white; border:2px solid #ccc; border-radius:8px; overflow:hidden; position:relative; }
table::before { content:''; position:absolute; top:-2px; left:-2px; width:20px; height:20px; background:gold; border-radius:0 0 20px 0; }
table::after { content:''; position:absolute; top:-2px; right:-2px; width:20px; height:20px; background:gold; border-radius:0 0 0 20px; }
th, td { padding:10px; border-bottom:1px solid #ddd; border-left:1px solid #ccc; border-right:1px solid #ccc; }
th { background:#f9f9f9; cursor:pointer; position:relative; }
th:hover { background:#e9e9e9; }
th a { text-decoration:none; color:#333; display:block; }
.user-img { width:60px; height:60px; object-fit:cover; border-radius:50%; border:1px solid #ccc; }
.product-img { width:60px; height:60px; object-fit:cover; border-radius:5px; }
.product-img-placeholder { width:60px; height:60px; display:flex; align-items:center; justify-content:center; background:#eee; border-radius:5px; border:1px solid #ccc; }
.add-btn { background:#28a745; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; margin-bottom:20px; }
.btn-del { background:none; border:none; cursor:pointer; }
form { display:inline; }
input, select, textarea { padding:8px; margin:5px 0; width:100%; box-sizing:border-box; }
.modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; }
.modal-content { background:white; padding:20px; border-radius:10px; width:400px; }
</style>
</head>

<body>
<div class="dashboard-container">

    <aside class="sidebar">
        <div class="profile-icon"><i class="fa-solid fa-crown"></i></div>
        <h3><?= htmlspecialchars($admin['nome'] ?? "Administrador") ?></h3>

        <a href="#" class="item-menu ativo" data-secao="produtos">Gerenciar Produtos</a>
        <a href="#" class="item-menu" data-secao="usuarios">Gerenciar Usuários</a>
        <a href="logs.php" class="item-menu">Logs</a> 

        <a href="home.php">Voltar ao Site</a>

        <a href="LoginPHP/sair.php" style="color:red;">Sair</a>
    </aside>

    <main class="main-content">

        <section id="secao-produtos" style="display:block;">
            <h1>Gerenciamento de Produtos</h1>

            <button class="add-btn" onclick="abrirModal('modalAdd')">Novo Produto</button>

            <table>
                <thead>
                    <tr>
                        <th><a href="?order=id&dir=<?= $orderDir === 'ASC' ? 'desc' : 'asc' ?>">ID <?= $orderBy === 'id' ? ($orderDir === 'ASC' ? '↑' : '↓') : '' ?></a></th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Qtd</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = $resultProdutos->fetch_assoc()): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td>
                            <?php
                            $imagemPath = "imagens/" . $p['imagem'];
                            if (!empty($p['imagem']) && file_exists($imagemPath)) {
                                echo "<img src='$imagemPath' class='product-img'>";
                            } else {
                                echo "<div class='product-img-placeholder'>Sem Img</div>";
                            }
                            ?>
                        </td>
                        <td><?= $p['nome'] ?></td>
                        <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                        <td><?= $p['quantidade'] ?></td>
                        <td>
                            <button onclick='editarProduto(<?= json_encode($p) ?>)'><i class="fa fa-edit"></i></button>

                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button name="excluir_produto" onclick="return confirm('Excluir?')"><i class="fa fa-trash" style="color:red;"></i></button>
                            </form>

                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <input type="number" name="mais" min="1" value="1" style="width:60px;">
                                <button name="add_qtd"><i class="fa fa-plus"></i></button>
                            </form>

                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section id="secao-usuarios" style="display:none;">
            <h1>Gerenciamento de Usuários</h1>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Email / Usuário</th>
                        <th>Telefone</th>
                        <th>Ação</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($u = $resultUsuarios->fetch_assoc()): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>

                        <td>
                            <img src="imagens/user.png" class="user-img">
                        </td>

                        <td><?= htmlspecialchars($u['nome']) ?> <?= htmlspecialchars($u['sobrenome']) ?></td>

                        <td><?= htmlspecialchars($u['usuario']) ?></td>

                        <td><?= htmlspecialchars($u['celular']) ?></td>

                        <td>
                            <form method="POST" onsubmit="return confirm('Deseja realmente excluir este usuário?')">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button name="excluir_usuario" class="btn-del">
                                    <i class="fa fa-trash" style="color:red;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

    </main>
</div>

<!-- 🔥 MODAIS ADICIONADOS PARA CRIAR E EDITAR PRODUTOS -->
<div id="modalAdd" class="modal">
    <div class="modal-content">
        <h2>Novo Produto</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nome" placeholder="Nome" required>
            <textarea name="descricao" placeholder="Descrição"></textarea>
            <input type="number" step="0.01" name="preco" placeholder="Preço" required>
            <input type="number" name="quantidade" placeholder="Quantidade" required>
            <input type="file" name="imagem">
            <select name="categoria_id" required>
                <!-- Adicione opções de categoria aqui, ex.: -->
                <option value="1">Categoria 1</option>
                <option value="2">Categoria 2</option>
                <option value="3">Categoria 3</option>
                <option value="4">Categoria 4</option>
                <option value="5">Categoria 5</option>
                <option value="6">Categoria 6</option>
                <!-- Ajuste conforme suas categorias -->
            </select>
            <button type="submit" name="criar_produto">Criar</button>
            <button type="button" onclick="fecharModal('modalAdd')">Cancelar</button>
        </form>
    </div>
</div>

<div id="modalEdit" class="modal">
    <div class="modal-content">
        <h2>Editar Produto</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" id="edit-id" name="id">
            <input type="text" id="edit-nome" name="nome" placeholder="Nome" required>
            <textarea id="edit-descricao" name="descricao" placeholder="Descrição"></textarea>
            <input type="number" step="0.01" id="edit-preco" name="preco" placeholder="Preço" required>
            <input type="number" id="edit-quantidade" name="quantidade" placeholder="Quantidade" required>
            <input type="file" name="imagem">
            <select id="edit-categoria" name="categoria_id" required>
                <!-- Adicione opções de categoria aqui, ex.: -->
                <option value="1">Categoria 1</option>
                <option value="2">Categoria 2</option>
                <option value="3">Categoria 3</option>
                <option value="4">Categoria 4</option>
                <option value="5">Categoria 5</option>
                <option value="6">Categoria 6</option>
                
                <!-- Ajuste conforme suas categorias -->
            </select>
            <button type="submit" name="editar_produto">Salvar</button>
            <button type="button" onclick="fecharModal('modalEdit')">Cancelar</button>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.item-menu').forEach(btn => {
    btn.addEventListener('click', () => {

        if (btn.getAttribute('href') !== 'logs.php') {
            document.querySelectorAll('.item-menu').forEach(b => b.classList.remove('ativo'));
            btn.classList.add('ativo');

            document.getElementById('secao-produtos').style.display = btn.dataset.secao === 'produtos' ? 'block' : 'none';
            document.getElementById('secao-usuarios').style.display = btn.dataset.secao === 'usuarios' ? 'block' : 'none';
        }
    });
});

function abrirModal(id){ document.getElementById(id).style.display='flex'; }
function fecharModal(id){ document.getElementById(id).style.display='none'; }

function editarProduto(p){
    abrirModal('modalEdit');
    document.getElementById('edit-id').value = p.id;
    document.getElementById('edit-nome').value = p.nome;
    document.getElementById('edit-descricao').value = p.descricao;
    document.getElementById('edit-preco').value = p.preco;
    document.getElementById('edit-quantidade').value = p.quantidade;
    document.getElementById('edit-categoria').value = p.categoria_id;
}
</script>

</body>
</html>

<?php $conn->close(); ?>
