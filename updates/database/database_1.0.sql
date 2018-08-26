-- ----------------------------------------------------------------------------------------------------------------
-- Thiago
-- 04/09/2017
-- Cria uma database inicial (vazia)
-- ----------------------------------------------------------------------------------------------------------------
-- seta uma database default ara uso
USE `mysql`;
-- deleta a database antiga
DROP DATABASE IF EXISTS `autosolutions`;
--  cria a database
CREATE DATABASE `autosolutions` CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci`;
-- seta a nova database para uso
USE `autosolutions`;
-- ----------------------------------------------------------------------------------------------------------------
-- cria a tabela de clientes
CREATE TABLE `cliente` (
  `id_cliente` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `sobrenome` VARCHAR(150) NOT NULL,
  `apelido` VARCHAR(100),
  `documento` VARCHAR(14) COMMENT 'Documento pode ser usado para CPF, RG ou CNPJ',
  `sexo` ENUM('M', 'F') DEFAULT 'M',
  `data_nascimento` DATETIME,
  `data_cadastro` DATETIME NOT NULL,
  `cep` CHAR(8),
  `endereco` VARCHAR(50),
  `numero` VARCHAR(5),
  `complemento` VARCHAR(20),
  `bairro` VARCHAR(30),
  `id_cidade` INT,
  `id_estado` INT,
  `email` VARCHAR(100),
  `situacao` INT,
  `tipo` CHAR(1)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- cria a tabela de telefone
CREATE TABLE `telefone` (
  `id_cliente` INT NOT NULL,
  `fone` CHAR(11) NOT NULL,
  `descricao` VARCHAR(100) NOT NULL,
  `tipo` ENUM('M', 'F') NOT NULL DEFAULT 'M',
  PRIMARY KEY (`id_cliente`, `fone`),
  FOREIGN KEY (`id_cliente`) REFERENCES `cliente`(`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- cria a tabela de veiculo
CREATE TABLE `veiculo` (
  `id_veiculo` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `placa` CHAR(7) NOT NULL,
  `marca` VARCHAR(100),
  `modelo` VARCHAR(100),
  `ano` DATETIME,
  `cor` VARCHAR(100),
  `detalhes` VARCHAR(250) COMMENT 'Detalhes e caracteristicas adicionais do veiculo'
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

-- cria a tabela de relação entre cliente e veiculo
CREATE TABLE `cliente_veiculo` (
  `id_cliente` INT NOT NULL,
  `id_veiculo` INT NOT NULL,
  PRIMARY KEY (`id_cliente`, `id_veiculo`),
  FOREIGN KEY (`id_cliente`) REFERENCES `cliente`(`id_cliente`),
  FOREIGN KEY (`id_veiculo`) REFERENCES `veiculo`(`id_veiculo`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
