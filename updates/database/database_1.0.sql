-- ----------------------------------------------------------------------------------------------------------------
-- Thiago
-- 27/08/2018
-- Cria uma database inicial (vazia)
-- ----------------------------------------------------------------------------------------------------------------
-- seta uma database default ara uso
USE `mysql`;
-- deleta a database antiga
DROP DATABASE IF EXISTS `exemplo_db`;
--  cria a database
-- CREATE DATABASE `autosolutions` CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci`;
CREATE DATABASE `exemplo_db` DEFAULT CHARACTER SET utf8 ;
-- seta a nova database para uso
USE `exemplo_db`;
-- ----------------------------------------------------------------------------------------------------------------
-- cria a tabela de clientes
CREATE TABLE `cliente` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(250) NOT NULL,
  `apelido` VARCHAR(100),
  `documento` VARCHAR(14) COMMENT 'Documento pode ser usado para CPF, RG ou CNPJ',
  `telefone` VARCHAR(11) NOT NULL, 
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
-- cria a tabela de usuario
CREATE TABLE `colaborador` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(250) NOT NULL,
  `username` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(60),
  `cargo` VARCHAR(100) COMMENT 'Cargo do usuario'
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;