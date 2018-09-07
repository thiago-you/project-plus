use `exemplo_db`;

CREATE TABLE estado(
 id_estado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
 codigo_ibge VARCHAR(4) NOT NULL,
 sigla CHAR(2) NOT NULL,
 nome VARCHAR(30) NOT NULL
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci;   

Insert Into estado (codigo_ibge,sigla,nome) Values(12,'AC','Acre');  
Insert Into estado (codigo_ibge,sigla,nome) Values(27,'AL','Alagoas');  
Insert Into estado (codigo_ibge,sigla,nome) Values(13,'AM','Amazonas');
Insert Into estado (codigo_ibge,sigla,nome) Values(16,'AP','Amapá');
Insert Into estado (codigo_ibge,sigla,nome) Values(29,'BA','Bahia');
Insert Into estado (codigo_ibge,sigla,nome) Values(23,'CE','Ceará');
Insert Into estado (codigo_ibge,sigla,nome) Values(53,'DF','Distrito Federal');
Insert Into estado (codigo_ibge,sigla,nome) Values(32,'ES','Espírito Santo');
Insert Into estado (codigo_ibge,sigla,nome) Values(52,'GO','Goiás');
Insert Into estado (codigo_ibge,sigla,nome) Values(21,'MA','Maranhão');
Insert Into estado (codigo_ibge,sigla,nome) Values(31,'MG','Minas Gerais');
Insert Into estado (codigo_ibge,sigla,nome) Values(50,'MS','Mato Grosso do Sul');
Insert Into estado (codigo_ibge,sigla,nome) Values(51,'MT','Mato Grosso');
Insert Into estado (codigo_ibge,sigla,nome) Values(15,'PA','Pará');
Insert Into estado (codigo_ibge,sigla,nome) Values(25,'PB','Paraíba');
Insert Into estado (codigo_ibge,sigla,nome) Values(26,'PE','Pernambuco');
Insert Into estado (codigo_ibge,sigla,nome) Values(22,'PI','Piauí');
Insert Into estado (codigo_ibge,sigla,nome) Values(41,'PR','Paraná');
Insert Into estado (codigo_ibge,sigla,nome) Values(33,'RJ','Rio de Janeiro');
Insert Into estado (codigo_ibge,sigla,nome) Values(24,'RN','Rio Grande do Norte');
Insert Into estado (codigo_ibge,sigla,nome) Values(11,'RO','Rondônia');
Insert Into estado (codigo_ibge,sigla,nome) Values(14,'RR','Roraima');
Insert Into estado (codigo_ibge,sigla,nome) Values(43,'RS','Rio Grande do Sul');
Insert Into estado (codigo_ibge,sigla,nome) Values(42,'SC','Santa Catarina');
Insert Into estado (codigo_ibge,sigla,nome) Values(28,'SE','Sergipe');
Insert Into estado (codigo_ibge,sigla,nome) Values(35,'SP','São Paulo');
Insert Into estado (codigo_ibge,sigla,nome) Values(17,'TO','Tocantins');
