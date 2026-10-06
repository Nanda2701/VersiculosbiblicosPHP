-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/10/2026 às 11:33
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `meu_projeto`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `criado_em`) VALUES
(1, 'Admin Phernnannda', 'admin@Pher.com', '$2y$10$wE3.8lH9L8.wGqD.5qI9xO0XG2O9pXWp3.sY3eF5G7H8I9J0K1L2M', '2026-09-24 20:04:03'),
(2, 'fernandamello@', 'nandamelo032@hotmail.com', '$2y$10$bau.upbzteRvcahg214Q0uRzldH7tLRrwsjR8wxCVbbE.YjYSt9vG', '2026-10-01 08:50:10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `versiculo`
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
-- Despejando dados para a tabela `versiculo`
--

INSERT INTO `versiculo` (`id`, `usuario_id`, `versiculo`, `foto`, `criado_em`, `atualizado_em`) VALUES
(1, 1, '\"O treinamento de uma pessoa sábia é a obediência às ordens do Senhor. Quem se humilha está no caminho certo para ser honrado e respeitado.\"', '8118fb36f57a7c06e9aee488c5a37dcb.jpg', '2026-09-30 19:10:08', '2026-09-30 19:10:08'),
(8, 1, '\"Jesus respondeu: — Eu sou o caminho, a verdade e a vida; ninguém pode chegar até o Pai a não ser por mim.\"', 'fccb19e2ab63e5249e2272b80e1c39a1.jpg', '2026-09-30 19:47:22', '2026-09-30 19:47:22'),
(9, 1, '\"Mas, para mim, bom é aproximar-me de Deus; pus a minha confiança no Senhor Deus, para anunciar todas as tuas obras.\"', '0e9f4ca20586593b346fe22777120fe3.jpg', '2026-09-30 19:49:47', '2026-09-30 19:49:47');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `versiculo`
--
ALTER TABLE `versiculo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_versiculo_usuario` (`usuario_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `versiculo`
--
ALTER TABLE `versiculo`
  ADD CONSTRAINT `fk_versiculo_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
