<?php

include_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Método de requisição inválido! Use POST.</div>"];
    echo json_encode($retorna, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

error_log("Dados recebidos: " . print_r($dados, true));

if (empty($dados) || !is_array($dados)) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Dados do formulário não foram recebidos corretamente!</div>"];
} elseif (empty($dados['cadnome'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo nome!</div>"];
} elseif (empty($dados['cadsobrenome'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o sobrenome</div>"];
} elseif (empty($dados['cademail'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo e-mail!</div>"];
} elseif (empty($dados['cadsenha'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Necessário preencher o campo senha!</div>"];
} elseif (empty($dados['cadcpf'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger'>Erro: Necessário preencher o campo CPF!</div>"];
} elseif (empty($dados['caddata'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger'>Erro: Necessário preencher o campo data!</div>"];
} elseif (empty($dados['cadcelular'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger'>Erro: Necessário preencher o campo celular!</div>"];
} elseif (empty($dados['cadtelefone'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger'>Erro: Necessário preencher o campo telefone!</div>"];
} elseif (empty($dados['cadendereco'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger'>Erro: Necessário preencher o campo endereço!</div>"];
} elseif (empty($dados['cadlogradouro'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger'>Erro: Necessário preencher o campo Logradouro!</div>"];
} elseif (empty($dados['cadcomplemento'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger'>Erro: Necessário preencher o campo Complemento!</div>"];
} elseif (empty($dados['cadconfirma'])) {
    $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger'>Erro: Necessário preencher o campo Confirme a senha</div>"];
} else {

    $senha = $_POST['cadsenha'] ?? '';
    $confirma = $_POST['cadconfirma'] ?? '';

    if ($senha !== $confirma) {
        $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger'>As senhas não coincidem!</div>"];
    } else {

        $query_usuario_pes = "SELECT id FROM usuarios WHERE usuario=:usuario LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario_pes);
        $result_usuario->bindParam(':usuario', $dados['cademail'], PDO::PARAM_STR);
        $result_usuario->execute();

        $cpf_somente_numeros = preg_replace('/\D/', '', $dados['cadcpf']);

        if (($result_usuario) && ($result_usuario->rowCount() != 0)) {
            $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: O e-mail já está cadastrado!</div>"];
        } else {

            // 🔥 CORRIGIDO: Adicionado data_cadastro na query principal para registrar horário de cadastro nos logs
            $query_usuario = "INSERT INTO usuarios (
                nome,
                sobrenome,
                cpf,
                data_nascimento,
                celular,
                telefone_extra,
                endereco,
                logradouro,
                complemento,
                usuario,
                senha_usuario,
                data_cadastro
            ) VALUES (
                :nome,
                :sobrenome,
                :cpf,
                :data_nascimento,
                :celular,
                :telefone,
                :endereco,
                :logradouro,
                :complemento,
                :usuario,
                :senha_usuario,
                NOW()
            )";

            $cad_usuario = $conn->prepare($query_usuario);

            $cad_usuario->bindParam(':nome', $dados['cadnome'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':sobrenome', $dados['cadsobrenome'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':cpf', $cpf_somente_numeros, PDO::PARAM_STR);
            $cad_usuario->bindParam(':data_nascimento', $dados['caddata'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':celular', $dados['cadcelular'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':telefone', $dados['cadtelefone'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':endereco', $dados['cadendereco'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':logradouro', $dados['cadlogradouro'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':complemento', $dados['cadcomplemento'], PDO::PARAM_STR);
            $cad_usuario->bindParam(':usuario', $dados['cademail'], PDO::PARAM_STR);

            $senha_cript = password_hash($dados['cadsenha'], PASSWORD_DEFAULT);
            $cad_usuario->bindParam(':senha_usuario', $senha_cript, PDO::PARAM_STR);

            $cad_usuario->execute();

            if ($cad_usuario->errorCode() != '00000') {

                $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Erro ao cadastrar usuário!</div>"];

            } elseif ($cad_usuario->rowCount()) {

                // 🔥 REMOVIDO: Bloco duplicado que causava o erro de duplicata
                // Não há necessidade de inserir novamente

                $retorna = [
                    'erro' => false,
                    'msg' => "<div class='alert alert-success' role='alert'>Usuário cadastrado com sucesso! Redirecionando...</div>
                              <script>setTimeout(function(){ window.location.href=' ../home.php'; }, 1000);</script>"
                ];

            } else {
                $retorna = ['erro' => true, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Usuário não cadastrado com sucesso!</div>"];
            }
        }
    }
}

echo json_encode($retorna, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
