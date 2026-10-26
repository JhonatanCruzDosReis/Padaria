<?php

session_start();
ob_start();

date_default_timezone_set('America/Sao_Paulo');

include_once "./conexao.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Document</title>
</head>
<body>
    <div class="login-container">
        <h2>Digite o código enviado no e-mail cadastrado</h2>
        <?php
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(!empty($dados['ValCodigo'])){

                // 🔍 Busca agora também o tipo_login (necessário para navbar)
                $query_usuario = "SELECT id, nome, usuario, senha_usuario, tipo_login 
                    FROM usuarios
                    WHERE id = :id
                    AND usuario = :usuario
                    AND codigo_autenticacao = :codigo_autenticacao
                    LIMIT 1";

                $result_usuario = $conn->prepare($query_usuario);
                $result_usuario->bindParam(':id', $_SESSION['id']);
                $result_usuario->bindParam(':usuario', $_SESSION['usuario']);
                $result_usuario->bindParam(':codigo_autenticacao', $dados['codigo_autenticacao']);
                $result_usuario->execute();

                if(($result_usuario) and ($result_usuario->rowCount() != 0)){
                    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);

                    $query_usuario = "UPDATE usuarios SET 
                            codigo_autenticacao = NULL,
                            data_codigo_autenticacao = NULL
                            WHERE id = :id
                            LIMIT 1";

                    $result_up_usuario = $conn->prepare($query_usuario);
                    $result_up_usuario->bindParam(':id', $_SESSION['id']);
                    $result_up_usuario->execute(); 
                     
                    // 🔥 SETA TUDO NA SESSÃO (necessário para navbar)
                    $_SESSION['nome'] = $row_usuario['nome'];
                    $_SESSION['tipo_login'] = $row_usuario['tipo_login']; // ADICIONADO
                    $_SESSION['codigo_autenticacao'] = true;

                    // 🔥 Redirecionamento universal (mantive igual)
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Código inválido</p>";
                }
            }

            if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
        ?>
        
        <form action="" method="post">
            <label for="">Código</label>
            <input type="text" name="codigo_autenticacao" placeholder="digite o código"><br><br>
            <input type="submit" name="ValCodigo" value="Validar"><br><br>
        </form><br>
    </div>
</body>
</html>
