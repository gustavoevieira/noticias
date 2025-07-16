# 📰 Portal de Notícias Simples

![Status do Projeto](https://img.shields.io/badge/Status-Concluído%20(Com%20Login)-brightgreen)
![HTML5](https://img.shields.io/badge/-HTML5-333333?style=flat&logo=HTML5)
![CSS3](https://img.shields.io/badge/-CSS-333333?style=flat&logo=CSS3&logoColor=1572B6)
![JavaScript](https://img.shields.io/badge/-JavaScript-333333?style=flat&logo=javascript)
![PHP](https://img.shields.io/badge/-PHP-333333?style=flat&logo=php&logoColor=777BB4)
![MySQL](https://img.shields.io/badge/-MySQL-333333?style=flat&logo=mysql&logoColor=4479A1)
![Git](https://img.shields.io/badge/-Git-333333?style=flat&logo=git&logoColor=F05032)
![GitHub](https://img.shields.io/badge/-GitHub-333333?style=flat&logo=github&logoColor=181717)

Um portal de notícias desenvolvido em PHP com MySQL, focado em demonstrar habilidades de desenvolvimento web Fullstack. O projeto inclui uma interface pública para visualização de notícias e um **painel administrativo seguro (CRUD)** para gerenciamento do conteúdo.

---

## 🚀 Tecnologias Utilizadas

Este projeto foi construído utilizando as seguintes tecnologias e ferramentas:

* **Frontend:**
    * `HTML5`: Estrutura semântica das páginas.
    * `CSS3`: Estilização e responsividade do layout.
    * `JavaScript`: (Pode ser adicionado para futuras interações, validações de formulário ou funcionalidades dinâmicas.)
* **Backend:**
    * `PHP`: Linguagem de programação para lógica do servidor e interação com o banco de dados.
    * `MySQL`: Sistema de gerenciamento de banco de dados relacional para armazenar as notícias e os dados de usuários administradores.
* **Controle de Versão:**
    * `Git`: Sistema de controle de versão distribuído.
    * `GitHub`: Plataforma de hospedagem de código para controle de versão e colaboração.
* **Ambiente de Desenvolvimento:**
    * `XAMPP`/`WAMP`/`MAMP`: Para configurar o servidor Apache e MySQL localmente.

---

## ✨ Funcionalidades

O site de notícias oferece as seguintes funcionalidades:

### Área Pública (Frontend)
* **Listagem de Notícias:** Exibe as últimas notícias na página inicial, ordenadas por data de publicação.
* **Detalhe da Notícia:** Permite visualizar o conteúdo completo de uma notícia ao clicar em seu título ou no botão "Leia Mais".
* **Design Responsivo:** Layout adaptável para diferentes tamanhos de tela (desktop, tablet, celular).

### Área Administrativa (Backend - CRUD)
* **Sistema de Autenticação:**
    * **Login:** Página de acesso segura com usuário e senha para administradores.
    * **Logout:** Funcionalidade para encerrar a sessão administrativa.
    * **Proteção de Rotas:** Todas as páginas da área administrativa são protegidas, exigindo login para acesso.
* **Gerenciamento de Notícias (CRUD):**
    * **Visualização:** Lista todas as notícias cadastradas em formato de tabela.
    * **Adicionar:** Formulário para criar novas notícias, incluindo título, subtítulo, conteúdo, autor e upload de imagem de destaque.
    * **Editar:** Formulário para atualizar informações de notícias existentes, com a opção de alterar ou remover a imagem de destaque.
    * **Excluir:** Funcionalidade para remover notícias permanentemente do banco de dados e seus respectivos arquivos de imagem.
* **Mensagens de Status:** Feedback visual para operações de sucesso (adição, edição, exclusão) ou erro.

---

## 🛠️ Como Rodar o Projeto Localmente

Siga estes passos para configurar e executar o projeto em sua máquina:

### Pré-requisitos
* Um servidor web com PHP (preferencialmente PHP 7.4+ ou 8.x).
* MySQL Server.
* Ferramentas como `XAMPP`, `WAMP` ou `MAMP` simplificam a instalação do Apache, PHP e MySQL.

### Configuração
1.  **Clone o Repositório:**
    ```bash
    git clone [https://github.com/seu-usuario/site-noticias.git](https://github.com/seu-usuario/site-noticias.git)
    ```
    Navegue até a pasta do projeto:
    ```bash
    cd site-noticias
    ```

2.  **Configure o Ambiente Web:**
    * Mova a pasta `site-noticias` para o diretório `htdocs` (XAMPP), `www` (WAMP) ou `htdocs` (MAMP) do seu servidor web.

3.  **Configuração do Banco de Dados (MySQL):**
    * Abra seu gerenciador MySQL (ex: phpMyAdmin, MySQL Workbench ou terminal).
    * Crie um novo banco de dados. Por exemplo: `site_noticias_db`.
        ```sql
        CREATE DATABASE site_noticias_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        ```
    * Selecione o banco de dados `site_noticias_db` e execute os seguintes scripts SQL para criar as tabelas `noticias` e `usuarios`:
        ```sql
        -- Tabela de Notícias
        CREATE TABLE noticias (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            subtitulo VARCHAR(255),
            conteudo TEXT NOT NULL,
            imagem_destaque VARCHAR(255),
            data_publicacao DATETIME DEFAULT CURRENT_TIMESTAMP,
            autor VARCHAR(100),
            slug VARCHAR(255) UNIQUE NOT NULL
        );

        -- Tabela de Usuários (para a área administrativa)
        CREATE TABLE usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome_usuario VARCHAR(50) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            nivel_acesso ENUM('admin', 'editor') DEFAULT 'editor'
        );
        ```
    * **Insira um Usuário Administrador:**
        Para testar o login, você precisará de um usuário. **Nunca insira a senha em texto simples no banco de dados.** Use PHP para gerar o hash da senha (ex: `password_hash("suasenha", PASSWORD_DEFAULT)`).
        Exemplo de como gerar o hash (crie um arquivo PHP temporário e execute no navegador):
        ```php
        <?php echo password_hash("admin123", PASSWORD_DEFAULT); ?>
        ```
        Com o hash gerado, insira o usuário:
        ```sql
        INSERT INTO usuarios (nome_usuario, senha, email, nivel_acesso) VALUES
        ('admin', 'COLE_O_HASH_GERADO_AQUI', 'admin@example.com', 'admin');
        ```
    * (Opcional) Insira algumas notícias de exemplo:
        ```sql
        INSERT INTO noticias (titulo, subtitulo, conteudo, imagem_destaque, autor, slug) VALUES
        ('Pesquisadores Brasileiros Desvendam Segredos da Floresta Amazônica com IA', 'Novas startups impulsionam a inovação sustentável no país.', 'Empresas brasileiras estão na vanguarda da tecnologia verde...', 'public/uploads/amazonia-ia.jpg', 'João Silva', 'pesquisadores-brasileiros-amazonia-ia');
        ```

4.  **Configure o Arquivo de Conexão com o Banco de Dados (`includes/database.php`):**
    * Abra o arquivo `site-noticias/includes/database.php`.
    * Ajuste as credenciais do banco de dados se forem diferentes das padrão (`root`, senha vazia).
    * **ADICIONE A INICIALIZAÇÃO DA SESSÃO:** Certifique-se de que o `session_start()` esteja incluído de forma segura, preferencialmente dentro de uma verificação para evitar múltiplos inícios de sessão:
        ```php
        <?php
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'root'); // Seu usuário do MySQL
        define('DB_PASSWORD', '');     // Sua senha do MySQL
        define('DB_NAME', 'site_noticias_db'); // Nome do BD que você criou

        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        $conn->set_charset("utf8mb4");

        // Garante que a sessão seja iniciada apenas uma vez
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // É uma boa prática omitir a tag de fechamento ?> em arquivos que só contêm código PHP.
        ```

5.  **Crie a Pasta de Uploads:**
    * Crie manualmente uma pasta chamada `uploads` dentro de `site-noticias/public/`.
    * Garanta que esta pasta tenha permissões de escrita para o seu servidor web (geralmente não é um problema em ambientes de desenvolvimento local).

6.  **Crie os Arquivos de Autenticação (`admin/login.php`, `admin/logout.php`, `admin/verificar_login.php`):**
    * Certifique-se de que esses arquivos existem na sua pasta `admin/` com o código fornecido nos passos anteriores de implementação do login.

7.  **Proteja as Páginas Administrativas:**
    * Em **todas as páginas PHP dentro da pasta `admin/` e suas subpastas** (ex: `admin/index.php`, `admin/adicionar.php`, `admin/editar.php`, `admin/excluir.php`), adicione as seguintes linhas no topo:
        ```php
        <?php
        require_once '../includes/database.php'; // Garante conexão e sessão
        require_once 'verificar_login.php';    // Redireciona se não estiver logado
        // ... (restante do código da página) ...
        ```
        **Atenção aos caminhos `../`** se a página estiver em uma subpasta (ex: `admin/produtos/index.php` precisaria de `../../includes/database.php` e `../verificar_login.php`).

### Acessando o Projeto
* **Site Público:** Abra seu navegador e acesse `http://localhost/site-noticias/`
* **Área Administrativa (Com Login):** Acesse `http://localhost/site-noticias/admin/`. Você será redirecionado para a página de login. Use o usuário e senha que você inseriu no banco de dados.

---

## 🎯 Melhorias Futuras (Roadmap)

Este projeto serve como uma base sólida. Futuras melhorias podem incluir:

* **Paginação:** Adicionar paginação para a listagem de notícias, tanto no frontend quanto no backend, para lidar com um grande volume de dados.
* **Categorias de Notícias:** Funcionalidade para categorizar as notícias e permitir filtragem por categoria.
* **Sistema de Busca:** Campo de busca para encontrar notícias por título ou conteúdo.
* **Melhorias de UI/UX:** Refinar o design do portal (frontend e painel admin) com um framework CSS (como Bootstrap ou Tailwind CSS) e adicionar mais interações JavaScript.
* **Validação de Formulários no Cliente:** Implementar validação de formulário via JavaScript para uma melhor experiência do usuário.
* **Sistema de Comentários:** Permitir que usuários comentem nas notícias.
* **Níveis de Acesso:** Expandir a funcionalidade de `nivel_acesso` para controlar permissões de diferentes tipos de usuários admin (ex: editor só pode criar/editar, mas não excluir).

---

## 👨‍💻 Autor

* **Mika** - [LinkedIn](https://www.linkedin.com/in/gustavo-ev) | [Portfólio](https://gustavoevieira.me) | [GitHub](https://github.com/gustavoevieira)

---

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE.md](LICENSE.md) para detalhes.
