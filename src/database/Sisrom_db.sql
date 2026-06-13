SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- O nome do banco de dados é sisrom

CREATE TABLE aluno (
  id_aluno int(11) NOT NULL,
  nome varchar(100) NOT NULL,
  matricula varchar(20) NOT NULL,
  curso varchar(50) NOT NULL,
  serie int(11) NOT NULL,
  telefone_responsavel varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE funcionario (
  id_funcionario int(11) NOT NULL,
  nome varchar(100) NOT NULL,
  cargo varchar(50) NOT NULL,
  email varchar(30) NOT NULL,
  senha varchar(150) NOT NULL,
  serie int(11) DEFAULT NULL,
  curso varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE ocorrencia (
  id_ocorrencia int(11) NOT NULL,
  data date NOT NULL,
  tipo_ocorrencia varchar(20) NOT NULL,
  descricao varchar(200),
  fk_aluno_id_aluno int(11) DEFAULT NULL,
  fk_funcionario_id_funcionario int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE aluno
  ADD PRIMARY KEY (id_aluno),
  ADD UNIQUE KEY matricula (matricula);

ALTER TABLE funcionario
  ADD PRIMARY KEY (id_funcionario);

ALTER TABLE ocorrencia
  ADD PRIMARY KEY (id_ocorrencia),
  ADD KEY fk_aluno_id_aluno (fk_aluno_id_aluno),
  ADD KEY fk_funcionario_id_funcionario (fk_funcionario_id_funcionario);


ALTER TABLE aluno
  MODIFY id_aluno int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE funcionario
  MODIFY id_funcionario int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE ocorrencia
  MODIFY id_ocorrencia int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE ocorrencia
  ADD CONSTRAINT ocorrencia_ibfk_1 FOREIGN KEY (fk_aluno_id_aluno) REFERENCES aluno (id_aluno),
  ADD CONSTRAINT ocorrencia_ibfk_2 FOREIGN KEY (fk_funcionario_id_funcionario) REFERENCES funcionario (id_funcionario);

DELIMITER $$
CREATE DEFINER=root@localhost EVENT evt_atualizar_ano_letivo ON SCHEDULE EVERY 1 YEAR STARTS '2027-01-01 01:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE aluno
  SET serie = CASE
    WHEN serie = 3 THEN 0
    ELSE serie + 1
  END
  WHERE serie BETWEEN 1 AND 3$$

DELIMITER ;
COMMIT;