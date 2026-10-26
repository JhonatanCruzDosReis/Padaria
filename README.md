# Encanto do Pão

E-commerce de padaria e restaurante desenvolvido em PHP e MySQL como projeto acadêmico do curso de Análise e Desenvolvimento de Sistemas (UNISUAM), de março a dezembro de 2025.

## Funcionalidades

- **Cardápio** com 70 produtos em 6 categorias (café, almoço, sobremesa, jantar, especial e gelados), filtro por categoria e busca.
- **Carrinho de compras** com controle de estoque e finalização do pedido.
- **Cadastro e login** com senhas protegidas por hash bcrypt (`password_hash` / `password_verify`).
- **Autenticação em duas etapas:** ao entrar, o cliente recebe um código de 6 dígitos por e-mail (PHPMailer + SMTP).
- **Painel administrativo** com perfil do administrador e log de acessos dos usuários (cadastro e último login).
- **Acessibilidade:** modo escuro e ajuste do tamanho da fonte, com preferência salva no navegador.
- Consultas ao banco com *prepared statements* (mysqli e PDO), protegendo contra SQL Injection.

## Tecnologias

| Camada | Tecnologia |
|---|---|
| Back-end | PHP 8, PDO, mysqli, sessões |
| Banco de dados | MySQL / MariaDB |
| Front-end | HTML5, CSS3, JavaScript, Bootstrap 5, Font Awesome |
| E-mail | PHPMailer (via Composer) |

## Estrutura

```
├── home.php, cardapio.php, carrinho.php   # Loja
├── finalizar_compra.php, conexao_compra.php
├── Perfil.php, perfil_mestre.php, logs.php # Perfis e painel do administrador
├── CSS/, JS/, imagens/
├── LoginPHP/                               # Cadastro, login e código de verificação
│   ├── config.example.php                  # Modelo das credenciais de e-mail
│   └── lib/composer.json                   # Dependência: PHPMailer
└── database/padaria.sql                    # Estrutura e dados de exemplo
```

## Como rodar (XAMPP)

1. Copie a pasta do projeto para `C:\xampp\htdocs\padaria`.
2. Inicie o **Apache** e o **MySQL** no XAMPP.
3. No phpMyAdmin, importe `database/padaria.sql` (o script cria o banco `padaria`).
4. Instale o PHPMailer:
   ```bash
   cd LoginPHP/lib
   composer install
   ```
5. Copie `LoginPHP/config.example.php` para `LoginPHP/config.php` e preencha com um e-mail e uma [senha de app do Gmail](https://myaccount.google.com/apppasswords). Ele é usado para enviar o código de verificação.
6. Acesse `http://localhost/padaria/home.php`.

### Logins de teste

| Perfil | Usuário | Senha |
|---|---|---|
| Administrador | `admin@encantodopao.com` | `admin123` |
| Cliente | `cliente@exemplo.com` | `teste123` |

Todos os dados de usuários do script são fictícios.

## Autor

**Jhonatan Cruz** · [GitHub](https://github.com/JhonatanCruzDosReis) · [LinkedIn](https://www.linkedin.com/in/jhonatan-cruz-279a113b8)
