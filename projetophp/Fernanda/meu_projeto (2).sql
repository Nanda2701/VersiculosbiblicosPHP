-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09-Out-2026 às 14:56
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dados: `meu_projeto`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `administrador` tinyint(1) NOT NULL DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `administrador`, `criado_em`) VALUES
(1, 'Admin Phernnannda', 'admin@Pher.com', '$2y$10$wE3.8lH9L8.wGqD.5qI9xO0XG2O9pXWp3.sY3eF5G7H8I9J0K1L2M', 0, '2026-09-24 20:04:03'),
(2, 'fernandamello@', 'nandamelo032@hotmail.com', '$2y$10$bau.upbzteRvcahg214Q0uRzldH7tLRrwsjR8wxCVbbE.YjYSt9vG', 1, '2026-10-01 08:50:10');

-- --------------------------------------------------------

--
-- Estrutura da tabela `versiculo`
--

CREATE TABLE `versiculo` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `versiculo` text NOT NULL,
  `foto` varchar(2000) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `versiculo`
--

INSERT INTO `versiculo` (`id`, `usuario_id`, `versiculo`, `foto`, `criado_em`, `atualizado_em`) VALUES
(10, 2, '“Educa a criança no caminho em que deve andar.” Para traduzir este versículo, também se pode usar a expressão ‘ensinar a criança a entrar no bom caminho’. O livro de Provérbios incentiva muitas vezes os pais a, desde cedo, ensinarem os seus filhos sobre o certo e o errado. (Provérbios 19:18; 22:15; 29:15) No entanto, pais amorosos reconhecem que os seus filhos, tal como os adultos, têm liberdade de escolha. Por isso, em vez de simplesmente dizerem aos filhos o que devem fazer, raciocinam com eles. Isso ajuda os filhos a tornarem-se adultos maduros e responsáveis, que são capazes de tomar boas decisões por si próprios.', '210bca93823cd2454de142b3a0b450bb.jpg', '2026-10-06 09:37:45', '2026-10-06 09:49:30'),
(11, 2, '• A Confissão: Significa admitir e reconhecer os nossos erros diante de Deus com sinceridade, sem tentar esconder ou justificar o que fizemos.\r\n• A Fidelidade e Justiça de Deus: Deus é fiel porque cumpre a Sua promessa de amor, e é justo porque o preço pelos nossos pecados já foi pago por Jesus Cristo na cruz. \r\n• O Perdão e a Purificação: Quando abrimos o coração e confessamos, Deus não apenas apaga a culpa do passado (perdão), mas também limpa a nossa vida e nos renova por dentro (purificação). 1 João 1:9', '766c0a580d1c3fe0058c82db9c529c31.jpg', '2026-10-06 09:57:46', '2026-10-06 10:15:31'),
(13, 2, '• O versículo centraliza o amor: O apóstolo Paulo ensina que o amor cristão é a principal virtude que um crente deve ter.\r\n• \"Revistam-se\": É uma metáfora que compara o amor a uma roupa que escolhemos vestir todos os dias de maneira consciente. \r\n• \"Elo perfeito\" (ou vínculo da perfeição): Significa que o amor é a \"cola\" ou a base que une todas as outras qualidades cristãs (como perdão, paciência e bondade), mantendo a união e a harmonia entre as pessoas.  Colossenses 3:14', 'b3dfc317433b653c73c1cc19a5bd2095.jpg', '2026-10-06 10:12:56', '2026-10-06 10:16:41'),
(14, 2, '• Jesus é o Bom Pastor: Ele se identifica como o guia verdadeiro e amoroso que cuida de cada pessoa.\r\n• Amor e Sacrifício: Ao contrário de um empregado ou líder falso que abandona o grupo no perigo, Jesus entrega a sua própria vida voluntariamente para salvar e proteger os seus seguidores.\r\n• Relação Íntima: Ele conhece profundamente cada seguidor (como as ovelhas conhecem a voz do dono), oferecendo segurança, orientação e vida plena. João 10:11', '401364cae77f608687b29698fd35084b.jpg', '2026-10-06 10:14:33', '2026-10-06 10:14:33'),
(15, 2, '• O contexto: Jesus estava a conversar com uma mulher samaritana junto a um poço e pediu-lhe de beber. Ela estranhou porque judeus e samaritanos não se relacionavam.\r\n• O \"Dom de Deus\": É a graça divina e a salvação oferecidas por Jesus, capazes de saciar a sede espiritual mais profunda do ser humano.\r\n• Quem pedia água: A mulher não sabia que estava diante do próprio Messias (o Filho de Deus). Se soubesse, trocaria a sua posição de quem ouve para quem suplica.\r\n• A \"Água Viva\": Jesus usa a água física do poço como metáfora para a vida eterna e o Espírito Santo, que preenchem o vazio da alma para sempre. João 4:10', '5ec5493776d6c33478f9bb85ab7ebff7.jpg', '2026-10-06 10:18:32', '2026-10-06 10:18:32'),
(16, 2, '• Esperar no Senhor: Significa ter uma confiança ativa em Deus, e não ficar parado. É entregar os problemas e alinhar-se ao tempo Dele.\r\n• Renovar as forças: Mostra que a energia humana acaba, mas Deus fornece uma fonte infinita de poder divino para substituir o esgotamento.\r\n• Asas como águias: Representa a capacidade de voar acima dos problemas, usando o vento do alto (o Espírito Santo) para planar sem desespero.\r\n• Correr e caminhar sem se cansar: Simboliza a resistência nas grandes crises (correr) e na rotina diária (caminhar) com a ajuda de Deus. Isaías 40:31', 'c2ed7b563746718054dbda7f78835030.jpg', '2026-10-06 10:32:36', '2026-10-06 10:32:36'),
(17, 2, 'O uso das palavras Caminho, Verdade e Vida indica três dimensões da relação com Deus que se realizam de modo completo em Jesus:\r\nCaminho: Jesus é o meio pelo qual se chega a Deus; é a direção para a vida em comunhão com o Pai, não um conjunto de regras ou obras humanas.\r\nVerdade: Jesus revela quem Deus é e como Ele age; não apenas ensina sobre Deus, mas é a revelação plena da verdade divina.\r\nVida: Jesus concede vida que começa na relação com Deus e contempla a vida eterna; é vida que transborda na prática da fé e na comunidade.  João 14:6', 'eb4c341ccc66efae33c7d6fb9910b56e.jpg', '2026-10-06 19:38:48', '2026-10-06 19:39:12');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `versiculo`
--
ALTER TABLE `versiculo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_versiculo_usuario` (`usuario_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `versiculo`
--
ALTER TABLE `versiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `versiculo`
--
ALTER TABLE `versiculo`
  ADD CONSTRAINT `fk_versiculo_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
