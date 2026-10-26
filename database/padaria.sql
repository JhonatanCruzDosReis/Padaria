-- Banco de dados: `padaria` (MariaDB/MySQL)
-- Dados de usuários e administrador são FICTÍCIOS, apenas para demonstração.
-- Logins de teste:
--   Administrador: admin@encantodopao.com / admin123
--   Cliente:       cliente@exemplo.com    / teste123

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `padaria` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `padaria`;

-- --------------------------------------------------------
-- Tabela `administrador`
-- --------------------------------------------------------
CREATE TABLE `administrador` (
  `ID_ADM` int(11) NOT NULL,
  `NOME` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `CPF` varchar(20) NOT NULL,
  `senha_adm` varchar(255) DEFAULT NULL,
  `TEL` varchar(20) NOT NULL,
  `TEL_AUXILIAR` varchar(20) NOT NULL,
  `ENDERECO` varchar(150) NOT NULL,
  `LOGADOURO` varchar(100) NOT NULL,
  `DATA_NASC` date NOT NULL,
  `H_TRAB` int(11) NOT NULL,
  `SALARIO` decimal(10,2) NOT NULL,
  `tipo_login` varchar(20) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `administrador` (`ID_ADM`, `NOME`, `email`, `CPF`, `senha_adm`, `TEL`, `TEL_AUXILIAR`, `ENDERECO`, `LOGADOURO`, `DATA_NASC`, `H_TRAB`, `SALARIO`, `tipo_login`) VALUES
(1, 'Administrador Demo', 'admin@encantodopao.com', '000.000.000-00', '$2y$10$45OKG1uHmGLFpzWp3T/iL.8bcX.Tl/CiMT/uHLzYjqDJHxMYmofDC', '(00) 00000-0000', '(00) 00000-0000', 'Rua Exemplo', '100', '1990-01-01', 40, 3000.00, 'adm');

-- --------------------------------------------------------
-- Tabela `categorias`
-- --------------------------------------------------------
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categorias` (`id`, `nome`) VALUES
(1, 'Café'),
(2, 'Almoço'),
(3, 'Sobremesa'),
(4, 'Jantar'),
(5, 'Especial'),
(6, 'Gelados');

-- --------------------------------------------------------
-- Tabela `estoque`
-- --------------------------------------------------------
CREATE TABLE `estoque` (
  `ID_ESTOQUE` int(11) NOT NULL,
  `ID_ADM` int(11) NOT NULL,
  `ID_SETOR` int(11) NOT NULL,
  `UNIDADE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabela `funcionario`
-- --------------------------------------------------------
CREATE TABLE `funcionario` (
  `ID_FUNF` int(11) NOT NULL,
  `NOME` varchar(100) NOT NULL,
  `CPF` varchar(20) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `TEL` varchar(20) NOT NULL,
  `TEL_AUXI` varchar(20) NOT NULL,
  `ENDER` varchar(150) NOT NULL,
  `LOGADOURO` varchar(100) NOT NULL,
  `DATA_NASC` date NOT NULL,
  `H_TRAB` int(11) NOT NULL,
  `SALARIO` decimal(10,2) NOT NULL,
  `SETOR` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabela `produtos`
-- --------------------------------------------------------
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `imagem`, `quantidade`, `categoria_id`) VALUES
(1, 'Café Brasileiro', 'O clássico café brasileiro composto com um pão francês com ovo frito e uma xícara de café expresso ou um suco à sua escolha.', 12.50, 'https://img.freepik.com/fotos-gratis/sanduiche-com-ovo-cozido_2829-6399.jpg?semt=ais_hybrid&w=740&q=80', 10, 1),
(2, 'Café Americano', 'O café da manhã americano feito por um verdadeiro estadunidense, composto por 2 ovos fritos, bacon, salsicha, panquecas com manteiga ou xarope de boldo e rabanada americana.', 20.50, 'https://sitecontent.kumon.com.br/site/general/638534739636867866_cafe-da-manha-nos-estados-unidos-quantidade.jpg?width=100', 7, 1),
(3, 'Café Argentino', 'O café argentino, também conhecido como desayuno, refletindo o estilo argentino. Café triplo expresso ou suco à sua escolha, medialunas e torradas.', 22.50, 'https://curitidoce.com.br/novo/wp-content/uploads/2023/05/Abasto-1024x683.jpg', 10, 1),
(4, 'Café Canadense', 'O café canadense para sentir um gostinho do Canadá, é composto por panquecas com maple syrup ou manteiga, ovos mexidos, hash browns, torradas, café ou suco natural.', 35.00, 'https://preview.redd.it/7ew24mjovuq51.jpg?width=1080&crop=smart&auto=webp&s=db5c770f76c193908b4eddba0a959d09f1ef7f52', 10, 1),
(5, 'Café Inglês', 'Para aqueles que querem comer igual a um Gentlemen. Duas linguiças assadas, tomate, cogumelos, dois ovos, linguiça preta, bacon, feijão branco, torradas e chá.', 42.50, 'https://media.istockphoto.com/id/641845826/pt/foto/pequeno-almo%C3%A7o-ingl%C3%AAs.jpg?s=612x612&w=0&k=20&c=AKeu8ZJfFXmmuTCdSEgndXxpXYzqjJ4wPDRRvhF9Lvg=', 10, 1),
(6, 'Café Mexicano', 'O gostinho da manhã da América Central. Tortilhas fritas, cozidas em molho vermelho ou verde, cobertas com queijo, creme e salsa, acompanhado com um ovo frito.', 39.90, 'https://staticcookist.akamaized.net/wp-content/uploads/sites/22/2022/01/chilaquiles.jpg', 10, 1),
(7, 'Almoço Encanto', 'Um delicioso prato do Encanto do Pão com arroz, feijão, carne e salada, ideal para o almoço do dia.', 25.00, 'https://friburgofm.com.br/wp-content/uploads/2024/08/almoco.jpg', 10, 2),
(8, 'Sobremesa do Encanto', 'Sobremesas artesanais feitas com todo carinho da casa: bolos, tortas e doces selecionados.', 14.90, 'https://guiadacozinha.com.br/wp-content/uploads/2019/10/minitortinha-morango.jpg', 10, 3),
(9, 'Almoço Brasileirinho', 'Nada melhor do que um clássico almoço brasileiro. Composto por arroz soltinho, feijão fresquinho, isca de carne, farofa, salada de alface, batata frita e um suco in natura.', 22.00, 'https://www.shutterstock.com/image-photo/traditional-brazilian-dish-lunch-famous-600nw-2448836661.jpg', 9, 2),
(10, 'Almoço do Norte', 'O almoço da região do Norte é típico por conter diversos sabores únicos desta região. Caldo de tucupi, goma de mandioca, camarão seco, jambu, chicória e alho.', 25.75, 'https://receitasdepesos.com.br/wp-content/uploads/2024/09/Tacaca_Santarem.jpg.webp', 9, 2),
(11, 'Almoço Nordestino', 'O almoço nordestino é marcado por sabores fortes: arroz soltinho, vatapá com camarão extra, pimenta e um suco à sua escolha.', 22.50, 'https://vovocristina.com.br/wp-content/uploads/2020/06/103958821_1303338286539939_1404854529991371787_n.jpg', 10, 2),
(12, 'Almoço do Sul', 'Para aqueles que querem almoçar igual a um gaúcho. Arroz misturado com picanha assada, cebolinha, pimenta, alho e linguiça calabresa acompanhado com farofa fresquinha.', 25.00, 'https://www.receiteria.com.br/wp-content/uploads/arroz-carreteiro.jpeg', 6, 2),
(13, 'Feijoada Carioca', 'O clássico do carioca: feijoada com bastante linguiça e carne, arroz, farofa, couve-flor, salada de cenoura e vagem.', 30.50, 'https://alegrafoods.com.br/wp-content/uploads/2023/06/composicao-kit-feijoada-scaled.jpg', 4, 4),
(14, 'Filé Oswaldo Aranha', 'Para aqueles que querem almoçar igual a um diplomata. Filé com alho frito, farofa, arroz e batata.', 20.50, 'https://diariodorio.com/wp-content/uploads/2013/11/Fil-a-Oswaldo-Aranha1.jpg', 5, 2),
(15, 'Jantar Brasileirinho', 'Nada melhor do que um clássico jantar brasileiro. Composto por arroz soltinho, feijão fresquinho, isca de carne, farofa, salada de alface, batata frita e um suco in natura.', 22.00, 'https://media.istockphoto.com/id/1493482723/pt/foto/rice-beans-grilled-chicken-steak-salad.jpg?s=612x612&w=0&k=20&c=vRfHmxp7BIOoMf529VtyXGW-Dd7tIWDk4fkFJOI-EoU=', 9, 4),
(16, 'Jantar Americano', 'Um jantar completo inspirado no estilo americano: bife grelhado, batata assada com manteiga, legumes cozidos e molho barbecue artesanal.', 45.50, 'https://preview.redd.it/v8spygk0uei51.jpg?width=1080&crop=smart&auto=webp&s=d34ff16a32745e76ddd13076cf54bf653255f20d', 10, 4),
(17, 'Jantar Argentino', 'O tradicional jantar argentino com carne grelhada, batatas rústicas, pimentões assados e chimichurri, acompanhado de um bom vinho (opcional).', 55.00, 'https://vidasemparedes.com.br/wp-content/uploads/2020/04/comidastipicasargentina-vidasemparedes-6.jpg', 10, 4),
(18, 'Jantar Canadense', 'Para os amantes de sabores do norte: peito de frango grelhado com bacon, purê de batata, legumes salteados e molho especial da casa.', 49.99, 'https://img.firebrandlab.com/fit-in/1920x1280/5b04620235ec91ae73e93f7e/assets/5e288f648710520012f7ce68/blt_chicken_023_hero_image.jpg', 10, 4),
(19, 'Pad Thai', 'Noodles salteados no wok com rebentos de soja, camarão, ovo e cebola, temperados com molho de soja, açúcar e malagueta, servidos à moda tailandesa.', 20.50, 'https://images.ctfassets.net/uexfe9h31g3m/3HRQmwGA3Ke4y8ukoy4aEk/e7da70846538442c4cb3fd8fcd9dcc96/PadThai_1024x768.jpg?w=2000&h=1125&fm=webp&fit=thumb&q=100', 10, 5),
(20, 'Queijo Mussarela', 'Queijo mussarela fresco e cremoso, perfeito para pizzas, sanduíches e pratos italianos, com textura macia e sabor suave.', 70.50, 'https://dorselecao.com.br/wp-content/uploads/elementor/thumbs/mussarela-qwvnlc575b553i6o8nzvvxgmjd6ebtyfhupgy1jbbc.jpeg', 10, 6),
(21, 'Queijo prato', 'Queijo prato semiduro e versátil, ideal para sanduíches, saladas e pratos quentes, com sabor equilibrado e textura firme.', 50.00, 'https://images.tcdn.com.br/img/img_prod/1049139/queijo_prato_fatiado_150g_d_or_603_1_d9a6770e63d8bc4319f8a61771adfb1d.jpg', 10, 6),
(22, 'Queijo Cheddar', 'Queijo cheddar envelhecido, com sabor intenso e textura firme, ideal para lanches, hambúrgueres e pratos quentes.', 47.50, 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEiuKuus4EXV9fqCCN14xgqxn6fZPTE-IPMNGlGf6NVU71L_Lo_ayAlenq378FcH0ILebxSkGXaEJzWvipQWGEQmg8Vggnd3ZF5K9AgkPGI6EMFqMFW1rLx4FeFXazee0G_wvfRzD_G0d-o/s1600/CHEDDAR.jpg', 10, 6),
(23, 'Mortadela (Kg)', 'Mortadela italiana, feita com carne suína selecionada e temperos especiais, fatiada fina para sanduíches e aperitivos.', 15.70, 'https://www.seara.com.br/wp-content/uploads/2025/09/mortadela-portal-minha-receitaa.webp', 10, 6),
(24, 'Mortadela defumada (Kg)', 'Mortadela defumada, com sabor defumado característico e textura suculenta, perfeita para charcutaria e pratos especiais.', 32.50, 'https://s3-sa-east-1.amazonaws.com/deliveryon-uploads/products/faculdadedopao/74_5fa073976d3d3.jpg', 10, 6),
(25, 'Salame', 'Salame artesanal, curado e temperado com ervas, ideal para charcutaria, pizzas e pratos italianos tradicionais.', 12.00, 'https://t3.ftcdn.net/jpg/01/85/00/64/360_F_185006449_b9ndMVuDgJuW4TS5eCFzfeEGoYKJMFFn.jpg', 10, 6),
(26, 'Peito de peru (Kg)', '', 12.50, 'https://conteudo.imguol.com.br/c/entretenimento/35/2018/02/28/peito-de-peru-1519843996181_v2_1x1.jpg', 10, 6),
(27, 'Presunto (Kg)', '', 22.70, 'https://cptstatic.s3.amazonaws.com/imagens/enviadas/materias/materia17798/quer-fazer-seu-proprio-presunto-para-consumo-e-tambem-vender-e-muito-facil-cpt67.jpg', 10, 6),
(28, 'Ganache de Chocolate', '', 9.50, 'https://www.estadao.com.br/resizer/D8aB8rAo17huaAJ0Us0rzVP5f98=/arc-anglerfish-arc2-prod-estadao/public/RWXIQPL3ANB6LD4VLC66RW3SQY.jpg', 10, 6),
(29, 'Creme de Amendoin Guimarães', '', 8.00, 'https://img.irroba.com.br/fit-in/600x600/filters:fill(fff):quality(80)/shopnova/catalog/api/app-4577/6457a583d033b.jpg', 10, 6),
(30, 'Manteiga', '', 10.50, 'https://images.tcdn.com.br/img/img_prod/1049139/manteiga_extra_c_sal_200g_d_or_1248_1_4c30ca642c6c7834cc0f86ef72636975.jpg', 10, 6),
(31, 'Doce de leite', '', 17.50, 'https://receitatodahora.com.br/wp-content/uploads/2024/08/doce-de-leite-cremoso-3-2708.jpg', 10, 6),
(32, 'Pão Francês', '', 0.50, 'https://canaldareceita.com.br/wp-content/uploads/2024/09/PAO-FRANCES.jpg', 10, 1),
(33, 'Baguete', '', 2.70, 'https://www.kingarthurbaking.com/sites/default/files/recipe_legacy/8-3-large.jpg', 10, 1),
(34, 'Pão Sourdough Integral', '', 20.50, 'https://paonapanela.com.br/wp-content/uploads/2019/04/semi-integral-destaque.jpg', 10, 1),
(35, 'Pão Australiano', '', 6.50, 'https://www.sabornamesa.com.br/media/k2/items/cache/96bf577e2682bc7925dad067c2022909_XL.jpg', 10, 1),
(36, 'Pão de Farro', '', 6.00, 'https://idata.hiloved.com/imgs/1b1728aa2d9c3382-1024x681.jpg', 10, 1),
(37, 'Pão Italiano', '', 13.00, 'https://imageswscdn.wslojas.com.br/files/26706/produto-pao-italiano-vegano-sem-gluten-raizes-2624.jpg', 10, 1),
(38, 'Pão Artesanal', '', 10.00, 'https://www.receitadevovo.com.br/_next/image?url=https%3A%2F%2Fd2qcpt1idvpipw.cloudfront.net%2Frecipes%2F2024%2F04%2Fpao-de-batata-com-azeitona-caseiro.jpeg&w=3840&q=75', 10, 1),
(39, 'Pão Sírio', '', 8.50, 'https://bakeandcakegourmet.com.br/uploads/site/receitas/pao-sirio-2-gaftl2bi.jpg', 10, 1),
(40, 'Pepsi', '', 8.50, 'https://admin.cnnbrasil.com.br/wp-content/uploads/sites/12/2023/03/230327115441-embargoed-02-pepsi-new-logo-2023.webp?w=1024', 10, 6),
(41, 'Coca-Cola', '', 9.00, 'https://istoedinheiro.com.br/wp-content/uploads/sites/17/Reuters_Direct_Media/BrazilOnlineReportBusinessNews/tagreuters.com2022binary_LYNXMPEI190UN-BASEIMAGE-e1644513830952.jpg', 10, 6),
(42, 'Fanta', '', 5.50, 'https://barbebidaepropaganda.wordpress.com/wp-content/uploads/2010/08/dsc00894.jpg', 10, 6),
(43, 'Guaraná-Antarctica', '', 6.50, 'https://gkpb.com.br/wp-content/uploads/2023/12/guarana-antarctica-zero.jpg', 10, 6),
(44, 'Heinkein', '', 12.50, 'https://filescms.s3.sa-east-1.amazonaws.com/2022/04/alcohol-alcoholic-alcoholic-beverage-beer-338711-1.jpg', 10, 6),
(45, 'Patagonia', '', 8.00, 'https://engarrafadormoderno.com.br/wp-content/uploads/2020/08/Materia-principal-Patagonia-24.7-chega-ao-Brasil_1.jpg', 10, 6),
(46, 'Budweiser', '', 5.00, 'https://st3.depositphotos.com/22265358/34275/i/450/depositphotos_342757820-stock-photo-january-10-2020-brazil-budweiser.jpg', 10, 6),
(47, 'Polar', '', 7.50, 'https://cdn.awsli.com.br/2500x2500/2688/2688531/produto/342022994/c596e7e89a357cba80e364f1c55555e8-46uuf7fubw.jpg', 10, 6),
(48, 'Antartica', '', 3.00, 'https://www.shutterstock.com/image-photo/paulo-brazil-may-9-2025-600nw-2631050679.jpg', 9, 6),
(49, 'Cappucino', '', 7.50, 'https://blog.cafeamerica.com.br/wp-content/uploads/2024/09/cappuccino.jpg', 10, 6),
(50, 'Frappucinno', '', 22.00, 'https://cdn0.tudoreceitas.com/pt/posts/3/8/0/frappuccino_de_chocolate_do_starbucks_3083_orig.jpg', 10, 6),
(51, 'Affogato', '', 15.00, 'https://www.academiaassai.com.br/sites/default/files/affogato_-_academia_assai_4.jpg', 10, 1),
(52, 'Frappe', '', 10.50, 'https://coppercowcoffee.com/cdn/shop/articles/untitled-design-20-1660926483787.png?v=1684275832', 10, 6),
(53, 'Pingado', '', 10.00, 'https://www.grupoutam.com.br/_temas/t_site/imagens/pingado.jpg', 10, 6),
(54, 'Latte', '', 7.00, 'https://uniquecafes.com.br/wp-content/uploads/2021/08/Destaque-cafe-Latte.jpg', 10, 6),
(55, 'Espresso panna', '', 7.50, 'https://www.foodrepublic.com/img/gallery/what-it-means-to-order-espresso-con-panna-at-starbucks/l-intro-1704886179.jpg', 10, 6),
(56, 'Iced coffee', '', 7.70, 'https://foodisafourletterword.com/wp-content/uploads/2021/10/Easy_Organic_Vietnamese_Coffee_Recipe.jpg', 10, 6),
(57, 'Café com leite', '', 4.50, 'https://conteudo.imguol.com.br/c/entretenimento/03/2021/09/23/coracao-no-topo-do-cafe-com-leite-1632406002977_v2_4x3.jpg', 10, 6),
(58, 'Café-Preto ', '', 2.50, 'https://upload.wikimedia.org/wikipedia/commons/4/45/A_small_cup_of_coffee.JPG', 10, 6),
(59, 'Café Turco', '', 70.50, 'https://upload.wikimedia.org/wikipedia/commons/2/2f/Turkishcoffee.jpg', 5, 6),
(60, 'Café Arabe', '', 50.50, 'https://anamariareceitas.com.br/wp-content/uploads/2023/02/Cafe-arabe.jpg', 10, 6),
(61, 'Vinho tinto Giacomin', '', 127.50, 'https://images.vivino.com/labels/c6ofPr-CT2OddEe7rBTWOQ.jpg', 10, 6),
(62, 'Anpan', '', 15.00, 'https://mikami.com.br/wp-content/uploads/2015/10/anpan-o-pao-japones-mikami.cv_.jpg', 10, 1),
(63, 'Vinho branco Cuvelier', '', 122.00, 'https://static.wixstatic.com/media/277946_0b3d6d65b7f44cbdaa4dde1a7a13fe59~mv2.png/v1/fill/w_568,h_758,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/277946_0b3d6d65b7f44cbdaa4dde1a7a13fe59~mv2.png', 10, 6),
(64, 'Rosé Portas do Oriente', '', 135.00, 'https://images.vivino.com/labels/_PgKOgUjSkmQWoyt5XnCJw.jpg', 10, 6),
(65, 'Espumante Quinta do Morgado', '', 150.50, 'https://cdn.awsli.com.br/800x800/2080/2080181/produto/186341320/3f5eb9c8ae.jpg', 10, 6),
(66, 'Vinho Pérgola seco', '', 180.50, 'https://cdn.awsli.com.br/300x300/1984/1984410/produto/255105068e18ecf204c.jpg', 10, 6),
(67, 'Vinho branco Conde Vimioso', '', 177.00, 'https://images.vivino.com/thumbs/4XU8pye6Q4eosV9ld7CubQ_pl_480x640.png', 10, 6),
(68, 'Verde Portugal Casal Mendes', '', 67.50, 'https://images.tcdn.com.br/img/img_prod/1218868/vinho_verde_casal_mendes_doc_750ml_quinta_da_bacalhoa_5899_1_28f41d23965821cb9765fb85acb02d20.jpg', 10, 6),
(69, 'Espumante Dom giovanni', '', 79.70, 'https://d2j6dbq0eux0bg.cloudfront.net/images/75426532/3796393411.png', 14, 6),
(70, 'Vinho licoroso SOZO', '', 95.50, 'https://cdn.awsli.com.br/300x300/2600/2600237/produto/220122685/vinhos-vinho-tin-04wafr7lsc.png', 17, 6);

-- --------------------------------------------------------
-- Tabela `setor`
-- --------------------------------------------------------
CREATE TABLE `setor` (
  `ID_SETOR` int(11) NOT NULL,
  `ID_ESTOQUE` int(11) DEFAULT NULL,
  `ID_FUNF` int(11) DEFAULT NULL,
  `TIPO_SETOR` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabela `usuarios`
-- --------------------------------------------------------
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `cpf` char(14) NOT NULL,
  `data_nascimento` date NOT NULL,
  `celular` varchar(15) NOT NULL,
  `telefone_extra` varchar(15) DEFAULT NULL,
  `endereco` varchar(300) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `usuario` varchar(220) NOT NULL,
  `senha_usuario` varchar(220) NOT NULL,
  `codigo_autenticacao` int(11) DEFAULT NULL,
  `data_codigo_autenticacao` datetime DEFAULT NULL,
  `tipo_login` varchar(20) NOT NULL DEFAULT 'usuario',
  `foto_perfil` varchar(255) DEFAULT 'padrao.png',
  `ultimo_login` datetime DEFAULT NULL COMMENT 'Último login do usuário',
  `paginas_visitadas` text DEFAULT NULL COMMENT 'Lista de páginas visitadas (ex.: home.php, produtos.php)',
  `compras` text DEFAULT NULL COMMENT 'Lista de compras realizadas (ex.: Produto1, Produto2 ou JSON)',
  `tempo_ficou` int(11) DEFAULT NULL COMMENT 'Tempo que o usuário ficou logado (em minutos)',
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` (`id`, `nome`, `sobrenome`, `cpf`, `data_nascimento`, `celular`, `telefone_extra`, `endereco`, `logradouro`, `complemento`, `usuario`, `senha_usuario`, `codigo_autenticacao`, `data_codigo_autenticacao`, `tipo_login`, `foto_perfil`, `ultimo_login`, `paginas_visitadas`, `compras`, `tempo_ficou`, `data_cadastro`) VALUES
(1, 'Cliente', 'Demo', '111.111.111-11', '2000-01-01', '(00) 00000-0000', NULL, 'Rua Exemplo', '10', NULL, 'cliente@exemplo.com', '$2y$10$N9Oa7DAsEC2ez.UX3uG4Fezr15v7BLpDSjhYpv1c78O5kpcR5eShy', NULL, NULL, 'usuario', 'padrao.png', NULL, NULL, NULL, NULL, '2025-11-22 20:55:15');

-- --------------------------------------------------------
-- Índices
-- --------------------------------------------------------
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`ID_ADM`),
  ADD UNIQUE KEY `CPF` (`CPF`);

ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `estoque`
  ADD PRIMARY KEY (`ID_ESTOQUE`),
  ADD KEY `fk_estoque_adm` (`ID_ADM`),
  ADD KEY `fk_estoque_setor` (`ID_SETOR`);

ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`ID_FUNF`);

ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categoria` (`categoria_id`);

ALTER TABLE `setor`
  ADD PRIMARY KEY (`ID_SETOR`),
  ADD KEY `fk_setor_estoque` (`ID_ESTOQUE`),
  ADD KEY `fk_setor_func` (`ID_FUNF`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `cpf` (`cpf`);

-- --------------------------------------------------------
-- AUTO_INCREMENT
-- --------------------------------------------------------
ALTER TABLE `administrador`
  MODIFY `ID_ADM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `estoque`
  MODIFY `ID_ESTOQUE` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `funcionario`
  MODIFY `ID_FUNF` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

ALTER TABLE `setor`
  MODIFY `ID_SETOR` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------
-- Chaves estrangeiras
-- --------------------------------------------------------
ALTER TABLE `estoque`
  ADD CONSTRAINT `fk_estoque_adm` FOREIGN KEY (`ID_ADM`) REFERENCES `administrador` (`ID_ADM`),
  ADD CONSTRAINT `fk_estoque_setor` FOREIGN KEY (`ID_SETOR`) REFERENCES `setor` (`ID_SETOR`);

ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

ALTER TABLE `setor`
  ADD CONSTRAINT `fk_setor_estoque` FOREIGN KEY (`ID_ESTOQUE`) REFERENCES `estoque` (`ID_ESTOQUE`),
  ADD CONSTRAINT `fk_setor_func` FOREIGN KEY (`ID_FUNF`) REFERENCES `funcionario` (`ID_FUNF`);

COMMIT;
