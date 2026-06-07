/* SQL do banco de dados */
CREATE TABLE aluno (
    id_aluno INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    matricula VARCHAR(20) NOT NULL UNIQUE,
    curso VARCHAR(50) NOT NULL,
    serie INT NOT NULL,
    telefone_responsavel VARCHAR(13) NOT NULL
);

CREATE TABLE funcionario (
    id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(50) NOT NULL,
    email VARCHAR(30) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE ocorrencia (
    id_ocorrencia INT AUTO_INCREMENT PRIMARY KEY,
    data DATE NOT NULL,
    tipo_ocorrencia VARCHAR(20) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    
    fk_aluno_id_aluno INT,
    fk_funcionario_id_funcionario INT,

    FOREIGN KEY (fk_aluno_id_aluno) REFERENCES aluno(id_aluno),
    FOREIGN KEY (fk_funcionario_id_funcionario) REFERENCES funcionario(id_funcionario)
);