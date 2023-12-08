-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/12/2023 às 02:25
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sischeck-bm`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `check_mnv`
--

CREATE TABLE `check_mnv` (
  `id` int(11) NOT NULL,
  `data_check` datetime NOT NULL DEFAULT current_timestamp(),
  `ok` tinyint(1) NOT NULL DEFAULT 1,
  `observacao` varchar(250) DEFAULT NULL,
  `resolvido` tinyint(1) NOT NULL DEFAULT 1,
  `idVerificador` int(11) NOT NULL,
  `idMateriais_no_veiculo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `compartimento`
--

CREATE TABLE `compartimento` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `idVeiculo` int(11) NOT NULL,
  `idCompartimento` int(11) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compartimento`
--

INSERT INTO `compartimento` (`id`, `nome`, `idVeiculo`, `idCompartimento`, `status`) VALUES
(1, 'Compartimento 1.1', 1, NULL, 'ativo'),
(2, 'Compartimento 1.2', 1, NULL, 'ativo'),
(3, 'Compartimento 2.1', 2, NULL, 'ativo'),
(4, 'Compartimento 2.2', 2, NULL, 'ativo'),
(5, 'Compartimento 3.1', 3, NULL, 'ativo'),
(6, 'Compartimento 3.2', 3, NULL, 'ativo'),
(7, 'Compartimento 4.1', 4, NULL, 'ativo'),
(8, 'Compartimento 4.2', 4, NULL, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `materiais_no_veiculo`
--

CREATE TABLE `materiais_no_veiculo` (
  `id` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'ativo',
  `idCompartimento` int(11) NOT NULL,
  `idMaterial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `materiais_no_veiculo`
--

INSERT INTO `materiais_no_veiculo` (`id`, `quantidade`, `observacao`, `status`, `idCompartimento`, `idMaterial`) VALUES
(1, 1, NULL, 'ativo', 1, 1),
(2, 2, 'Observado', 'ativo', 1, 2),
(3, NULL, NULL, 'ativo', 1, 3),
(4, NULL, 'Observado', 'ativo', 1, 4),
(5, NULL, NULL, 'ativo', 2, 1),
(6, NULL, 'Observado', 'ativo', 2, 2),
(7, 1, NULL, 'ativo', 2, 3),
(8, 2, 'Observado', 'ativo', 2, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `descricao` varchar(250) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `material`
--

INSERT INTO `material` (`id`, `descricao`, `status`) VALUES
(1, 'Mateiral 01', 'ativo'),
(2, 'Material 02', 'ativo'),
(3, 'Material 03', 'ativo'),
(4, 'Material 04', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `setor`
--

CREATE TABLE `setor` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `setor`
--

INSERT INTO `setor` (`id`, `nome`, `status`) VALUES
(1, 'Setor 01', 'ativo'),
(2, 'Setor 02', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `tipo`, `login`, `senha`, `status`) VALUES
(1, 'Gustavo', 'administrador', 'gukuma1', '.kividig1', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculo`
--

CREATE TABLE `veiculo` (
  `id` int(11) NOT NULL,
  `prefixo` varchar(10) NOT NULL,
  `posfixo` varchar(10) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'ativo',
  `idSetor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `veiculo`
--

INSERT INTO `veiculo` (`id`, `prefixo`, `posfixo`, `placa`, `marca`, `modelo`, `status`, `idSetor`) VALUES
(1, '01', '01', 'Veiculo 01', 'V', '01', 'ativo', 1),
(2, '02', '02', 'Veiculo 02', 'V', '02', 'ativo', 2),
(3, '03', '03', 'Veiculo 03', 'V', '03', 'ativo', 1),
(4, '04', '04', 'Veiculo 04', 'V', '04', 'ativo', 2);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `check_mnv`
--
ALTER TABLE `check_mnv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verificador_do_checkMnV` (`idVerificador`),
  ADD KEY `MnV_do_checkMnV` (`idMateriais_no_veiculo`);

--
-- Índices de tabela `compartimento`
--
ALTER TABLE `compartimento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `veiculo_do_compartimento` (`idVeiculo`),
  ADD KEY `compartimento_do_compartimento` (`idCompartimento`);

--
-- Índices de tabela `materiais_no_veiculo`
--
ALTER TABLE `materiais_no_veiculo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_do_MnV` (`idMaterial`),
  ADD KEY `compartimento_do_MnV` (`idCompartimento`);

--
-- Índices de tabela `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `setor`
--
ALTER TABLE `setor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `veiculo`
--
ALTER TABLE `veiculo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setor_do_veiculo` (`idSetor`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `check_mnv`
--
ALTER TABLE `check_mnv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `compartimento`
--
ALTER TABLE `compartimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `materiais_no_veiculo`
--
ALTER TABLE `materiais_no_veiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `setor`
--
ALTER TABLE `setor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `veiculo`
--
ALTER TABLE `veiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `check_mnv`
--
ALTER TABLE `check_mnv`
  ADD CONSTRAINT `MnV_do_checkMnV` FOREIGN KEY (`idMateriais_no_veiculo`) REFERENCES `materiais_no_veiculo` (`id`),
  ADD CONSTRAINT `verificador_do_checkMnV` FOREIGN KEY (`idVerificador`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `compartimento`
--
ALTER TABLE `compartimento`
  ADD CONSTRAINT `compartimento_do_compartimento` FOREIGN KEY (`idCompartimento`) REFERENCES `compartimento` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `veiculo_do_compartimento` FOREIGN KEY (`idVeiculo`) REFERENCES `veiculo` (`id`);

--
-- Restrições para tabelas `materiais_no_veiculo`
--
ALTER TABLE `materiais_no_veiculo`
  ADD CONSTRAINT `compartimento_do_MnV` FOREIGN KEY (`idCompartimento`) REFERENCES `compartimento` (`id`),
  ADD CONSTRAINT `material_do_MnV` FOREIGN KEY (`idMaterial`) REFERENCES `material` (`id`);

--
-- Restrições para tabelas `veiculo`
--
ALTER TABLE `veiculo`
  ADD CONSTRAINT `setor_do_veiculo` FOREIGN KEY (`idSetor`) REFERENCES `setor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
