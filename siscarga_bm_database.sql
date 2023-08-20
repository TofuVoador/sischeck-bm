-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30-Jun-2023 às 00:39
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `siscarga_bm`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `check_mnv`
--

CREATE TABLE `check_mnv` (
  `id` int(11) NOT NULL,
  `data_check` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `observacao` varchar(250) DEFAULT NULL,
  `resolvido` tinyint(1) NOT NULL DEFAULT 1,
  `idUsuario` int(11) NOT NULL,
  `idMateriais_no_veiculo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `compartimento`
--

CREATE TABLE `compartimento` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `ordem_verificacao` int(11) NOT NULL DEFAULT 99,
  `idVeiculo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `materiais_no_veiculo`
--

CREATE TABLE `materiais_no_veiculo` (
  `id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `status` varchar(10) NOT NULL DEFAULT 'ativo',
  `idCompartimento` int(11) NOT NULL,
  `idMaterial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `descricao` varchar(250) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `patrimonio` varchar(10) DEFAULT NULL,
  `origem_patrimonio` varchar(4) DEFAULT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `status` varchar(10) NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `setor`
--

CREATE TABLE `setor` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `veiculo`
--

CREATE TABLE `veiculo` (
  `id` int(11) NOT NULL,
  `prefixo` varchar(10) NOT NULL,
  `posfixo` varchar(10) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `renavan` varchar(20) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'ativo',
  `idSetor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `check_mnv`
--
ALTER TABLE `check_mnv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verificador_do_checkMnV` (`idUsuario`),
  ADD KEY `MnV_do_checkMnV` (`idMateriais_no_veiculo`);

--
-- Índices para tabela `compartimento`
--
ALTER TABLE `compartimento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `veiculo_do_compartimento` (`idVeiculo`);

--
-- Índices para tabela `materiais_no_veiculo`
--
ALTER TABLE `materiais_no_veiculo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_do_MnV` (`idMaterial`),
  ADD KEY `compartimento_do_MnV` (`idCompartimento`);

--
-- Índices para tabela `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `setor`
--
ALTER TABLE `setor`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `veiculo`
--
ALTER TABLE `veiculo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setor_do_veiculo` (`idSetor`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `materiais_no_veiculo`
--
ALTER TABLE `materiais_no_veiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `setor`
--
ALTER TABLE `setor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `veiculo`
--
ALTER TABLE `veiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `check_mnv`
--
ALTER TABLE `check_mnv`
  ADD CONSTRAINT `MnV_do_checkMnV` FOREIGN KEY (`idMateriais_no_veiculo`) REFERENCES `materiais_no_veiculo` (`id`),
  ADD CONSTRAINT `verificador_do_checkMnV` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`);

--
-- Limitadores para a tabela `compartimento`
--
ALTER TABLE `compartimento`
  ADD CONSTRAINT `veiculo_do_compartimento` FOREIGN KEY (`idVeiculo`) REFERENCES `veiculo` (`id`);

--
-- Limitadores para a tabela `materiais_no_veiculo`
--
ALTER TABLE `materiais_no_veiculo`
  ADD CONSTRAINT `compartimento_do_MnV` FOREIGN KEY (`idCompartimento`) REFERENCES `compartimento` (`id`),
  ADD CONSTRAINT `material_do_MnV` FOREIGN KEY (`idMaterial`) REFERENCES `material` (`id`);

--
-- Limitadores para a tabela `veiculo`
--
ALTER TABLE `veiculo`
  ADD CONSTRAINT `setor_do_veiculo` FOREIGN KEY (`idSetor`) REFERENCES `setor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
