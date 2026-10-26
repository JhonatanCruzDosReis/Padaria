<?php
session_start();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="imagens/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <title>Cadastro direto</title>

    <style>
        body {
            background-color: #f8f6f3;
            color: #000; 
            font-family: 'Poppins', sans-serif;
            transition: background-color 0.5s ease, color 0.5s ease; 
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

        .cart-icon { position: fixed; top: 20px; left: 20px; background: #b38b59; color: #fff; border: none; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 1000; font-size: 18px; }
        .cart-count { position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px; }

        #main-content {
            transition: transform 0.3s ease;
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
            background-color: #fffaf4; 
        }
        body.dark-mode .modal-body {
            background-color: #1e1e1e;
        }

        .form-control {
            border: 1px solid #b38b59;
            border-radius: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #e4c49b;
            box-shadow: 0 0 8px rgba(228, 196, 155, 0.5);
        }
        .col-form-label {
            color: #b38b59;
            font-weight: 500;
        }
        .input-group .btn {
            border: 1px solid #b38b59;
            background: #fff;
            color: #b38b59;
        }
        .input-group .btn:hover {
            background: #e4c49b;
            color: #fff;
        }
        .btn-outline-success {
            background: linear-gradient(90deg, #b38b59, #e4c49b);
            border: none;
            color: #fff;
        }
        .btn-outline-success:hover {
            background: linear-gradient(90deg, #a47844, #d3b182);
        }
        .alert {
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.5rem;
            }
            .modal-body {
                padding: 1rem;
            }
        }
    </style>
</head>

<body id="main-content">
    <div class="container text-center mt-5">
        <?php
        if(isset($_SESSION['id']) && isset($_SESSION['nome'])){
            echo "Bem vindo " . $_SESSION['nome'] . "<br>";
            echo "<a href='sair.php'>Sair</a><br>";
        }
        ?>
        <div class="m-5">
            <span id="msgAlert"></span>
        </div>
    </div>

    <div class="modal fade" id="cadUsuarioModal" tabindex="-1" aria-labelledby="cadUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content card"> 
                <div class="modal-header">
                    <h5 class="modal-title section-title" id="cadUsuarioModalLabel">Cadastro de Perfil</h5> 
                    <button type="button" class="btn-close" aria-label="Close" onclick="window.location.href='login.php'"></button>
                </div>
                <div class="modal-body">
                    <form id="cad-usuario-form">
                        <span id="msgAlertErroCad"></span>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cadnome" class="col-form-label">Nome:</label>
                                <input type="text" name="cadnome" class="form-control" id="cadnome" placeholder="Digite o primeiro nome" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cadsobrenome" class="col-form-label">Sobrenome</label>
                                <input type="text" name="cadsobrenome" class="form-control" id="cadsobrenome" placeholder="Digite seu sobrenome" required>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cadcpf" class="col-form-label">CPF:</label>
                                <input type="text" 
                                name="cadcpf" class="form-control" id="cadcpf" autocomplete="on" placeholder="000.000.000-00"
                                maxlength="14"
                                required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="caddata" class="col-form-label">Data de Nascimento:</label>
                                <input type="date" 
                                name="caddata" class="form-control" id="caddata" autocomplete="on" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cadcelular" class="col-form-label">Celular:</label>
                                <input type="text" 
                                name="cadcelular" class="form-control" id="cadcelular" autocomplete="on" placeholder="(99) 99999-9999"
                                maxlength="15"
                                required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cadtelefone" class="col-form-label">Telefone extra:</label>
                                <input type="text" 
                                name="cadtelefone" class="form-control" id="cadtelefone" autocomplete="on" placeholder="(99) 99999-9999"
                                maxlength="15"
                                required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cadendereco" class="col-form-label">Endereço:</label>
                            <input type="text" 
                            name="cadendereco" class="form-control" id="cadendereco" autocomplete="on" 
                            placeholder="Cidade, Bairro, Rua"
                            required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cadlogradouro" class="col-form-label">Logradouro</label>
                                <input type="text" 
                                name="cadlogradouro" class="form-control" id="cadlogradouro" autocomplete="on" placeholder="Número, bloco etc."
                                required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cadcomplemento" class="col-form-label">Complemento</label>
                                <input type="text" 
                                name="cadcomplemento" class="form-control" id="cadcomplemento" autocomplete="on" placeholder="Apartamento, bloco, etc"
                                required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cademail" class="col-form-label">E-mail:</label>
                            <input type="email" name="cademail" class="form-control" id="cademail" placeholder="Digite o seu melhor e-mail" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cadsenha" class="col-form-label">Senha:</label>
                                <div class="input-group">
                                    <input type="password" name="cadsenha" class="form-control" id="cadsenha" autocomplete="on" placeholder="Digite a senha" required>
                                    <button type="button" class="btn btn-outline-secondary" id="toggleSenha1">
                                        <i class="bi bi-eye"></i>
                                    </button> 
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cadconfirma" class="col-form-label">Confirme a senha:</label>
                                <div class="input-group">
                                    <input type="password" name="cadconfirma" class="form-control" id="cadconfirma" autocomplete="on" placeholder="Digite a senha" required>
                                    <button type="button" class="btn btn-outline-secondary" id="toggleSenha2">
                                        <i class="bi bi-eye"></i>
                                    </button> 
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="submit" class="btn btn-primary" id="cad-usuario-btn" value="Cadastrar"> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function(){
        var cadModal = new bootstrap.Modal(document.getElementById('cadUsuarioModal'));
        cadModal.show();

        $('#cad-usuario-form').submit(function(e){
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "cadastrar.php",
                data: formData,
                success: function(response){
                    $('#msgAlertErroCad').html(response);
                    $('#cad-usuario-form')[0].reset();
                },
                error: function(){
                    $('#msgAlertErroCad').html("<div class='alert alert-danger'>Erro no servidor</div>");
                }
            });
        });
    });
    
    function toggleSenha(buttonId, inputId) {
        document.getElementById(buttonId).addEventListener('click', function() {
            const input = document.getElementById(inputId);
            const icon = this.querySelector('i');

            if(input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye'); 
            }
        });
    }

toggleSenha('toggleSenha1', 'cadsenha');
toggleSenha('toggleSenha2', 'cadconfirma');

    const cpfInput = document.getElementById('cadcpf');
    cpfInput.addEventListener('input', function() {
    this.value = mascaraCPF(this.value);
    });

    function mascaraCPF(value) {
    return value
        .replace(/\D/g, "")
        .replace(/(\d{3})(\d)/, "$1.$2")
        .replace(/(\d{3})(\d)/, "$1.$2")
        .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    }

    const celularInput = document.getElementById('cadcelular');
    const telefoneInput = document.getElementById('cadtelefone');

    celularInput.addEventListener('input', function() {
        this.value = mascaraTelefone(this.value);
    });

    telefoneInput.addEventListener('input', function() {
        this.value = mascaraTelefone(this.value);
    });

    function mascaraTelefone(value) {
        value = value.replace(/\D/g, ""); 
        if (value.length > 11) value = value.slice(0, 11); 
        if (value.length > 10) {
            return value.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
        } else {
            return value.replace(/^(\d{2})(\d{4})(\d{0,4})$/, "($1) $2-$3").replace(/-$/, "");
        }
    }

        document.getElementById('cad-usuario-form').addEventListener('submit', function(e) {
        const email = document.getElementById('cademail').value.trim();
        const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!regexEmail.test(email)) {
            e.preventDefault(); 
            document.getElementById('msgAlertErroCad').innerHTML = 
            "<div class='alert alert-danger'>Digite um e-mail válido.</div>";
            return false;
        }
        });

        document.getElementById('cad-usuario-form').addEventListener('submit', function(e) {
            const senha = document.getElementById('cadsenha').value.trim();
            const confirma = document.getElementById('cadconfirma').value.trim();

            if (senha !== confirma) {
                e.preventDefault(); 
                document.getElementById('msgAlertErroCad').innerHTML = 
                    "<div class='alert alert-danger'>As senhas não coincidem!</div>";
                return false;
            }
        });

    </script>
</body>
</html>