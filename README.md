# Sistema de Registro e Monitoramento de Ocorrências Disciplinares

Um sistema web simples para registrar, gerenciar e monitorar ocorrências disciplinares em uma instituição escolar. Desenvolvido em PHP com backend em arquivos PHP e banco de dados MySQL. Projetado para rodar em ambiente local (ex.: XAMPP) ou servidor LAMP.

## Funcionalidades

- Autenticação (login/logout)
- Perfis de acesso: Admin, Coordenação, Direção, DT
- Cadastro, edição e remoção de alunos e funcionários
- Registro e gerenciamento de ocorrências disciplinares
- Consultas e buscas de ocorrências

## Tecnologias

- PHP 7.x/8.x
- MySQL / MariaDB
- HTML, CSS e JavaScript
- Funciona via XAMPP/WAMP ou servidor LAMPP

## Estrutura do projeto

- [backend/](backend/) — scripts PHP do servidor (conexão, CRUD, login, etc.)
  - [backend/database.php](backend/database.php)
  - [backend/login.php](backend/login.php)
  - [backend/salvar_ocorrencia.php](backend/salvar_ocorrencia.php)
  - [backend/schema.sql](backend/schema.sql)
- [pages/](pages/) — interfaces e páginas por perfil
  - [pages/tela_login.php](pages/tela_login.php)
  - [pages/admin/](pages/admin/) — painel e páginas do admin
  - [pages/coordenacao/](pages/coordenacao/)
  - [pages/direcao/](pages/direcao/)
  - [pages/dt/](pages/dt/)
- [style/](style/) — estilos globais (`style.css`)
- [src/assets/](src/assets/) — fontes e imagens

## Requisitos

- PHP (7.4+ recomendado)
- MySQL ou MariaDB
- Servidor local (XAMPP, WAMP) ou servidor Apache/Nginx com PHP

## Instalação e execução (local)

1. Copie a pasta do projeto para o diretório público do servidor (ex.: `C:/xampp/htdocs/Sistema-de-Registro-e-Monitoramento-de-Ocorrencias-Disciplinares`).
2. Inicie o Apache e o MySQL via XAMPP/WAMP.
3. Crie o banco de dados e importe o arquivo de esquema:

   - Pelo phpMyAdmin: importe `backend/schema.sql`.
   - Ou via linha de comando MySQL:

4. Configure as credenciais do banco em [backend/database.php](backend/database.php) (usuário, senha, nome do banco).
   ### Use esse código para criar senha inicial do admin e colar a senha hash gerada diretamente no banco para logar e iniciar o sistema;
   
   ```bash
      $senhaHash = password_hash("--Senha escolhida--", PASSWORD_DEFAULT);
      echo $senhaHash;
    ```
6. Acesse no navegador: `http://localhost/Sistema-de-Registro-e-Monitoramento-de-Ocorrencias-Disciplinares/` (ou caminho conforme sua instalação).

## Uso

- Faça login na tela principal ([pages/tela_login.php](pages/tela_login.php)).
- Usuários com permissões adequadas podem acessar os painéis em `pages/admin`, `pages/coordenacao`, `pages/direcao` e `pages/dt`.
- Cadastre alunos/funcionários e registre ocorrências usando os formulários disponíveis.
- Use as páginas de consulta para buscar e filtrar ocorrências existentes.

## Arquivos relevantes

- [backend/cad_aluno.php](backend/cad_aluno.php) — cadastro de alunos
- [backend/cad_funcionario.php](backend/cad_funcionario.php) — cadastro de funcionários
- [pages/../ocorrencias.php](pages/.../ocorrencias.php) — exibição de ocorrências
- [pages/.../dashboard.php](pages/.../dashboard.php) — dashboard do sistema
- [backend/salvar_ocorrencia.php](backend/salvar_ocorrencia.php) — grava ocorrência
- [backend/consultas.php](backend/consultas.php) — rotinas de consulta
