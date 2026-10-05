-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Tempo de geração: 05/10/2026 às 22:14
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `locadora_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varbinary(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `cpf`, `telefone`, `email`) VALUES
(1, 'Ana Souza', 0xba95c57653a06ae5f6ab31fef1c7b0f1, '11911111111', 'ana@email.com'),
(2, 'João Lima', 0x7bef4c9705d004d5f59409040c2c9a1c, '11922222222', 'joao@email.com'),
(3, 'Carla Dias', 0x76401fc6e8fe68d854338cee9f875565, '11933333333', 'carla@email.com'),
(4, 'maria', 0x7e4c0ae04d434ae5c34c8d5a7bb7761f, '81989821812', 'maria@email.com'),
(5, 'maria cecília', 0x89edd0517637f544799b6e74e5408fc4, '81 983456789', 'cjwmkiedjdoaled.@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `locacoes`
--

CREATE TABLE `locacoes` (
  `id_locacao` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_veiculo` int(11) NOT NULL,
  `data_retirada` date NOT NULL,
  `data_devolucao` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'aberta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `locacoes`
--

INSERT INTO `locacoes` (`id_locacao`, `id_usuario`, `id_cliente`, `id_veiculo`, `data_retirada`, `data_devolucao`, `status`) VALUES
(1, 1, 1, 1, '2026-09-28', '2026-10-05', 'devolvida'),
(2, 2, 2, 2, '2026-09-10', '2026-09-15', 'devolvida'),
(3, 3, 3, 3, '2026-09-01', '2026-09-05', 'devolvida'),
(4, 1, 4, 4, '2026-10-05', NULL, 'aberta'),
(5, 1, 5, 2, '2026-10-05', '2026-10-05', 'devolvida'),
(6, 1, 2, 3, '2026-10-05', NULL, 'aberta');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `expiracao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `expiracao`) VALUES
(1, 'Admin', 'admin@loc.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', '2026-10-05 22:13:50'),
(2, 'Maria', 'maria@loc.com', '626e3c805e77eeb472c42c6be607be2af7ac5c08fd7050f278e0330fe81abf57', NULL),
(3, 'Pedro', 'pedro@loc.com', '2702cb34ee041711b9df0c67a8d5c9de02110c80e3fc966ba8341456dbc9ef2b', NULL),
(4, 'Admin', 'admin@loc.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', NULL),
(5, 'Maria', 'maria@loc.com', '626e3c805e77eeb472c42c6be607be2af7ac5c08fd7050f278e0330fe81abf57', NULL),
(6, 'Pedro', 'pedro@loc.com', '2702cb34ee041711b9df0c67a8d5c9de02110c80e3fc966ba8341456dbc9ef2b', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id_veiculo` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `placa` varchar(8) NOT NULL,
  `ano` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'disponivel'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `veiculos`
--

INSERT INTO `veiculos` (`id_veiculo`, `marca`, `modelo`, `placa`, `ano`, `status`) VALUES
(1, 'Fiat', 'Argo', 'ABC1D23', 2022, 'disponivel'),
(2, 'Chevrolet', 'Onix', 'EFG4H56', 2023, 'disponivel'),
(3, 'VW', 'Polo', 'IJK7L89', 2021, 'locado'),
(4, 'fiat', 'uno', '27165621', 2012, 'locado');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices de tabela `locacoes`
--
ALTER TABLE `locacoes`
  ADD PRIMARY KEY (`id_locacao`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_veiculo` (`id_veiculo`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Índices de tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id_veiculo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `locacoes`
--
ALTER TABLE `locacoes`
  MODIFY `id_locacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id_veiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `locacoes`
--
ALTER TABLE `locacoes`
  ADD CONSTRAINT `locacoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `locacoes_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `locacoes_ibfk_3` FOREIGN KEY (`id_veiculo`) REFERENCES `veiculos` (`id_veiculo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
