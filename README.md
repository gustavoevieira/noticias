# 📰 Portal de Notícias Simples

![Status do Projeto](https://img.shields.io/badge/Status-Concluído%20(MVP)-brightgreen)
Um portal de notícias desenvolvido em PHP com MySQL, focado em demonstrar habilidades de desenvolvimento web Fullstack. O projeto inclui uma interface pública para visualização de notícias e um painel administrativo completo (CRUD) para gerenciamento do conteúdo.

---

## 🚀 Tecnologias Utilizadas

Este projeto foi construído utilizando as seguintes tecnologias e ferramentas:

* **Frontend:**
    * `HTML5`: Estrutura semântica das páginas.
    * `CSS3`: Estilização e responsividade do layout.
* **Backend:**
    * `PHP`: Linguagem de programação para lógica do servidor e interação com o banco de dados.
    * `MySQL`: Sistema de gerenciamento de banco de dados relacional para armazenar as notícias.
* **Controle de Versão:**
    * `Git`: Sistema de controle de versão distribuído.
    * `GitHub`: Plataforma de hospedagem de código para controle de versão e colaboração.
* **Ambiente de Desenvolvimento:**
    * `XAMPP`: Para configurar o servidor Apache e MySQL localmente.

---

## ✨ Funcionalidades

O site de notícias oferece as seguintes funcionalidades:

### Área Pública (Frontend)
* **Listagem de Notícias:** Exibe as últimas notícias na página inicial, ordenadas por data de publicação.
* **Detalhe da Notícia:** Permite visualizar o conteúdo completo de uma notícia ao clicar em seu título ou no botão "Leia Mais".
* **Design Responsivo:** (Se você o fez, mencione!) Layout adaptável para diferentes tamanhos de tela (desktop, tablet, celular).

### Área Administrativa (Backend - CRUD)
* **Visualização de Notícias:** Lista todas as notícias cadastradas em formato de tabela.
* **Adicionar Notícia:** Formulário para criar novas notícias, incluindo título, subtítulo, conteúdo, autor e upload de imagem de destaque.
* **Editar Notícia:** Formulário para atualizar informações de notícias existentes, com a opção de alterar ou remover a imagem de destaque.
* **Excluir Notícia:** Funcionalidade para remover notícias permanentemente do banco de dados e seus respectivos arquivos de imagem.
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
    * Selecione o banco de dados `site_noticias_db` e execute o seguinte script SQL para criar a tabela `noticias`:
        ```sql
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
        ```
    * (Opcional) Insira algumas notícias de exemplo:
        ```sql
        INSERT INTO noticias (titulo, subtitulo, conteudo, imagem_destaque, autor, slug) VALUES
        ('Pesquisadores Brasileiros Desvendam Segredos da Floresta Amazônica com IA', 'Nova abordagem tecnológica promete revolucionar a preservação ambiental e o estudo da biodiversidade.', 'Uma equipe multidisciplinar de pesquisadores brasileiros...', 'public/uploads/amazonia-ia.jpg', 'Equipe de Redação', 'pesquisadores-brasileiros-amazonia-ia'),
        ('Crescimento do Setor de E-sports no Brasil Atinge Níveis Recordes', 'Investimentos em infraestrutura e o surgimento de novos talentos impulsionam a indústria.', 'O Brasil consolida sua posição como um dos principais mercados emergentes...', 'public/uploads/esports-br.jpg', 'Equipe de Redação', 'crescimento-e-sports-brasil-recordes');
        ```

4.  **Configure o Arquivo de Conexão com o Banco de Dados:**
    * Abra o arquivo `site-noticias/includes/database.php`.
    * Ajuste as credenciais do banco de dados se forem diferentes das padrão (`root`, senha vazia):
        ```php
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'root'); // Seu usuário do MySQL
        define('DB_PASSWORD', '');     // Sua senha do MySQL
        define('DB_NAME', 'site_noticias_db'); // Nome do BD que você criou
        ```

5.  **Crie a Pasta de Uploads:**
    * Crie manualmente uma pasta chamada `uploads` dentro de `site-noticias/public/`.
    * Garanta que esta pasta tenha permissões de escrita para o seu servidor web (geralmente não é um problema em ambientes de desenvolvimento local, mas pode ser necessário em servidores reais).

### Acessando o Projeto
* **Site Público:** Abra seu navegador e acesse `http://localhost/site-noticias/`
* **Área Administrativa:** Acesse `http://localhost/site-noticias/admin/`

---

## 🎯 Melhorias Futuras (Roadmap)

Este projeto serve como uma base sólida. Futuras melhorias podem incluir:

* **Autenticação de Usuários:** Implementar um sistema de login e logout para proteger a área administrativa.
* **Paginação:** Adicionar paginação para a listagem de notícias, tanto no frontend quanto no backend, para lidar com um grande volume de dados.
* **Categorias de Notícias:** Funcionalidade para categorizar as notícias e permitir filtragem por categoria.
* **Sistema de Busca:** Campo de busca para encontrar notícias por título ou conteúdo.
* **Melhorias de UI/UX:** Refinar o design com um framework CSS (como Bootstrap ou Tailwind CSS) e adicionar mais interações JavaScript.
* **Validação de Formulários no Cliente:** Implementar validação de formulário via JavaScript para uma melhor experiência do usuário.
* **Sistema de Comentários:** Permitir que usuários comentem nas notícias.

---

## 👨‍💻 Autor

* **Mika** - [linkedIn(https://www.linkedin.com/in/gustavo-ev) | gustavoevieira.com.br | https://github.com/gustavoevieira

---

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE.md](LICENSE.md) para detalhes.
