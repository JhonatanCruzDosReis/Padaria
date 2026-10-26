<?php

session_start();
ob_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('America/Sao_Paulo');

include_once "./conexao.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <div class="login-container"> 
    <h2>Login</h2>
    
        <?php 
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(!empty($dados['SendLogin'])){

    $query_adm = "SELECT id_adm, nome, email, senha_adm, cpf
                  FROM administrador
                  WHERE email = :usuario OR cpf = :usuario
                  LIMIT 1";

    $result_adm = $conn->prepare($query_adm);
    $result_adm->bindParam(':usuario', $dados['usuario']);
    $result_adm->execute();

    if($result_adm->rowCount() != 0){
        $row_adm = $result_adm->fetch(PDO::FETCH_ASSOC);

        if (password_verify($dados['senha_usuario'], $row_adm['senha_adm'])) {

            $_SESSION['adm_id'] = $row_adm['id_adm'];
            $_SESSION['adm_nome'] = $row_adm['nome'];

            $_SESSION['tipo_login'] = "admin";

            /* 🔥 AQUI FOI ADICIONADO */
            $_SESSION['login_time'] = time();

            header("Location: ../home.php");
            exit;
        } else {
            $_SESSION['msg'] = "<p class='mensagem'>Erro: Usuário ou senha inválida!</p>";
        }
    }
}

            if(!empty($dados['SendLogin'])){
                $query_usuario= "SELECT id, nome, usuario, senha_usuario 
                FROM usuarios
                WHERE usuario =:usuario
                LIMIT 1";

                $result_usuario = $conn->prepare($query_usuario);
                $result_usuario->bindParam(':usuario', $dados['usuario']);
                $result_usuario->execute();

                if(($result_usuario) and ($result_usuario->rowCount() != 0)){
                    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);

                    if(password_verify($dados['senha_usuario'], $row_usuario['senha_usuario'])){
                        $_SESSION['id'] = $row_usuario['id'];
                        $_SESSION['usuario'] = $row_usuario['usuario'];

                        /* 🔥 IDENTIFICA COMO USUÁRIO NORMAL */
                        $_SESSION['tipo_login'] = "usuario";

                        /* 🔥 AQUI FOI ADICIONADO */
                        $_SESSION['login_time'] = time();

                        $data = date('Y-m-d H:i:s');
                        $codigo_autenticacao = mt_rand(100000, 999999);

                        $query_usuario= "UPDATE usuarios SET 
                                codigo_autenticacao =:codigo_autenticacao,
                                data_codigo_autenticacao=:data_codigo_autenticacao
                                where id =:id
                                LIMIT 1";

                        $result_up_usuario = $conn->prepare($query_usuario);
                        $result_up_usuario->bindParam(':codigo_autenticacao',$codigo_autenticacao);
                        $result_up_usuario->bindParam(':data_codigo_autenticacao',$data);
                        $result_up_usuario->bindParam(':id',$row_usuario['id']);
                        $result_up_usuario->execute();

                        require './lib/vendor/autoload.php';
                        // Credenciais de e-mail ficam fora do repositório (veja config.example.php)
                        $config = require __DIR__ . '/config.php';

                        $mail = new PHPMailer(true);

                        try {
                            $mail->CharSet = 'UTF-8';
                            $mail->isSMTP();
                            $mail->Host       = $config['smtp_host'];
                            $mail->SMTPAuth   = true;
                            $mail->Username   = $config['smtp_usuario'];
                            $mail->Password   = $config['smtp_senha'];
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = $config['smtp_porta'];
                            $mail->setFrom($config['smtp_usuario'], 'Atendimento');
                            $mail->addAddress($row_usuario['usuario'], $row_usuario['nome']); 
                            $mail->isHTML(true);
                            $mail->Subject = 'Aqui está o código de verificação de 6 dígitos que você solicitou';
                            $mail->Body    = "Olá " . $row_usuario['nome'] . ", Autenticação multifator.<br><br>Seu código de verificação é $codigo_autenticacao<br><br>";
                            $mail->send();

                            header('Location: validar_codigo.php');
                        } catch (Exception $e) {
                            $_SESSION['msg']="<p class='mensagem'>Erro: Usuário ou senha inválida!</p>";
                        }
                    } else{
                        $_SESSION['msg']="<p class='mensagem'>Erro: Usuário ou senha inválida!</p>";
                    }
                } else{
                    $_SESSION['msg']="<p class='mensagem'>Erro: Usuário ou senha inválida!</p>";
                }
            }

            if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }


            if (!empty($_SESSION['id'])) {
                $updateLogin = $conn->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id");
                $updateLogin->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
                $updateLogin->execute();
            }

        ?>

    <form action="" method="post">
        <label for="">Usuario</label>
        <input type="text" name="usuario" placeholder="Digite o usuario">
        <label for="">Senha</label>
        <input type="password" name="senha_usuario" placeholder="Digite a senha">
        <label><input type="checkbox" id="lembrar"> Lembrar-me</label>
        <input type="submit" name="SendLogin" value="LOGIN">
        <div class="link">
            <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
        </div>
    </form>
    </div>
</body>
</html>
