-- ----------------------------------------------------------------------------------------------------------------
-- Thiago
-- 27/08/2018
-- Cria uma database inicial (vazia)
-- ----------------------------------------------------------------------------------------------------------------
-- seta uma database default ara uso
USE `mysql`;
-- deleta a database antiga
DROP DATABASE IF EXISTS `maklenrc`;
--  cria a database
-- CREATE DATABASE `autosolutions` CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci`;
CREATE DATABASE `maklenrc` DEFAULT CHARACTER SET utf8 ;
-- seta a nova database para uso
USE `maklenrc`;
-- ----------------------------------------------------------------------------------------------------------------
-- cria a tabela de clientes
CREATE TABLE `cliente` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(250) NOT NULL COMMENT 'Razao social ou nome do cliente',
  `nome_social` VARCHAR(250) COMMENT 'Nome fantasia ou apelido do cliente',
  `rg` VARCHAR(14),
  `documento` VARCHAR(14) COMMENT 'Documento pode ser usado para CPF ou CNPJ',
  `inscricao_estadual` VARCHAR(15),
  `sexo` ENUM('M', 'F') DEFAULT 'M',
  `data_nascimento` DATE,
  `data_cadastro` DATETIME NOT NULL,
  `estado_civil` TINYINT(1),
  `nome_conjuge` VARCHAR(250),
  `nome_pai` VARCHAR(250),
  `nome_mae` VARCHAR(250),
  `empresa` VARCHAR(250),
  `profissao` VARCHAR(100),
  `salario` DECIMAL(10,2),
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  `tipo` ENUM('F', 'J') NOT NULL DEFAULT 'F' COMMENT 'Flag que valida se o cliente é tipo fisico (F) ou juridico (J)'
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
-- cria a tabela de colaborador
CREATE TABLE `colaborador` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(250) NOT NULL,
  `username` VARCHAR(30) NOT NULL,
  `password` VARCHAR(30) NOT NULL,
  `authKey` CHAR(30),
  `cargo` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Consultar model para checar os cargos possiveis'
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
-- cria a tabela de telefone
CREATE TABLE `telefone` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `numero` VARCHAR(15) NOT NULL,
  `ramal` VARCHAR(4),
  `tipo` TINYINT(1) NOT NULL DEFAULT 1,
  `observacao` VARCHAR(100),
  `contato` ENUM('S', 'N') NOT NULL DEFAULT 'N' COMMENT 'Flag que valida de o numero e para contato',
  `whatsapp` ENUM('S', 'N') NOT NULL DEFAULT 'S' COMMENT 'Flag que valida se o numero possui whatsapp',
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  FOREIGN KEY (`id_cliente`) REFERENCES `cliente`(`id`)
);
-- cria a tabela de eventos
CREATE TABLE `acionamento` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `colaborador_id` INT NOT NULL,
  `titulo` VARCHAR(100) NOT NULL,
  `descricao` VARCHAR(250),
  `data` DATETIME,
  `telefone` VARCHAR(15),
  `tipo` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Consultar model para checar os tipos possiveis',
  `subtipo` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Consultar model para checar os subtipos possiveis',
  FOREIGN KEY (`id_cliente`) REFERENCES `cliente`(`id`),
  FOREIGN KEY (`colaborador_id`) REFERENCES `colaborador`(`id`)
);
-- cria a tabela de endereco
CREATE TABLE `endereco` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `logradouro` VARCHAR(100) NOT NULL,
  `numero` VARCHAR(10),
  `complemento` VARCHAR(50),
  `bairro` VARCHAR(100),
  `cep` CHAR(8),
  `cidade_id` INT,
  `estado_id` INT,
  `observacao` VARCHAR(250),
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  FOREIGN KEY (`id_cliente`) REFERENCES `cliente`(`id`)
);
-- cria a tabela de email
CREATE TABLE `email` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `observacao` VARCHAR(250),
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  FOREIGN KEY (`id_cliente`) REFERENCES `cliente`(`id`)
);
-- cria a tabela de referencia
CREATE TABLE `referencia` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `nome` VARCHAR(250) NOT NULL,
  `cpf` VARCHAR(11) NOT NULL,
  `observacao` VARCHAR(250),
  `tipo` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Consultar model para checar os tipos possiveis',
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  FOREIGN KEY (`id_cliente`) REFERENCES `cliente`(`id`)
);
-- cria a tabela de credor
CREATE TABLE `credor` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_campanha` INT,
  `nome` VARCHAR(250) NOT NULL,
  `tipo` TINYINT(1) DEFAULT '1',
  `tipo_cobranca` TINYINT(1) DEFAULT '1',
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  `razao_social` VARCHAR(250),
  `cnpj` VARCHAR(14) NOT NULL,
  `telefone` VARCHAR(15) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `logradouro` VARCHAR(100) NOT NULL,
  `numero` VARCHAR(10) NOT NULL,
  `complemento` VARCHAR(50),
  `bairro` VARCHAR(100),
  `cep` CHAR(8),
  `cidade_id` INT,
  `estado_id` INT,
  `logo` VARCHAR(250) COMMENT 'Caminho para a logo do credor',
  `codigo` VARCHAR(250),
  `sigla` VARCHAR(250)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
-- tabela de campanha de calculo do credor
CREATE TABLE `credor_campanha` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_credor` INT NOT NULL,
  `nome` VARCHAR(250) NOT NULL,
  `vigencia_inicial` DATE NOT NULL,
  `vigencia_final` DATE,
  `prioridade` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Nivel de prioridade da campanha',
  `por_parcela` ENUM('S', 'N'),
  `por_portal` ENUM('S', 'N'),
  `tipo` ENUM('V', 'P') NOT NULL DEFAULT 'V' COMMENT 'Tipo do calculo => V: A vista / P: Parcelado',
  FOREIGN KEY (`id_credor`) REFERENCES `credor`(`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
-- tabela de calculo do credor
CREATE TABLE `credor_calculo` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_campanha` INT NOT NULL,
  `atraso_inicio` VARCHAR(3),
  `atraso_fim` VARCHAR(3),
  `multa` DECIMAL(7,4) DEFAULT 0.0000,
  `juros` DECIMAL(7,4) DEFAULT 0.0000,
  `honorario` DECIMAL(7,4) DEFAULT 0.0000,
  `parcela_num` INT COMMENT 'Numero da parcela qunado o tipo for parcelado',
  FOREIGN KEY (`id_campanha`) REFERENCES `credor_campanha`(`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
-- cria a tabela de contrato
CREATE TABLE `contrato` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_cliente` INT NOT NULL,
  `id_credor` INT,
  `codigo_cliente` VARCHAR(50),
  `codigo_contrato` VARCHAR(50),
  `num_contrato` VARCHAR(50),
  `num_plano` VARCHAR(50),
  `valor` DECIMAL(10,2),
  `data_cadastro` DATE NOT NULL,
  `data_vencimento` DATE,
  `data_negociacao` DATE,
  `tipo` TINYINT(1) DEFAULT '1',
  `regiao` VARCHAR(50),
  `filial` VARCHAR(50),
  `observacao` VARCHAR(250),
  `situacao` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Consultar model para checar as situacoes possiveis',
  FOREIGN KEY (`id_cliente`) REFERENCES `cliente`(`id`),
  FOREIGN KEY (`id_credor`) REFERENCES `credor`(`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
-- cria a tabela de negociacao
CREATE TABLE `negociacao` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_contrato` INT NOT NULL,
  `data_negociacao` DATE NOT NULL,
  `data_cadastro` DATETIME NOT NULL,
  FOREIGN KEY (`id_contrato`) REFERENCES `contrato`(`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
-- cria a tabela de parcela do contrato
CREATE TABLE `contrato_parcela` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_contrato` INT NOT NULL,
  `num_parcela` INT,
  `data_cadastro` DATETIME NOT NULL,
  `data_vencimento` DATE NOT NULL,
  `valor` DECIMAL(10,2),
  `observacao` VARCHAR(250),
  `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Consultar model para checar as situacoes possiveis',
  FOREIGN KEY (`id_contrato`) REFERENCES `contrato`(`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;