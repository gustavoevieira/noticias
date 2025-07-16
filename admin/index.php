<?php
require_once '../includes/database.php';
require_once 'verificar_login.php';

$mensagem_status = '';
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'noticia_adicionada_sucesso':
            $mensagem_status = '<div class="message success">Notícia adicionada com sucesso!</div>';
            break;
        case 'noticia_atualizada_sucesso':
            $mensagem_status = '<div class="message success">Notícia atualizada com sucesso!</div>';
            break;
        case 'noticia_excluida_sucesso':
            $mensagem_status = '<div class="message success">Notícia excluída com sucesso!</div>';
            break;
        case 'noticia_nao_encontrada':
            $mensagem_status = '<div class="message error">Notícia não encontrada ou ID inválido.</div>';
            break;
        case 'id_nao_fornecido':
            $mensagem_status = '<div class="message error">ID da notícia não fornecido.</div>';
            break;
        case 'erro_excluir':
        case 'erro_query_delete':
            $mensagem_status = '<div class="message error">Erro ao processar a requisição: ' . htmlspecialchars($_GET['msg'] ?? 'Detalhes não disponíveis.') . '</div>';
            break;
        default:
            $mensagem_status = '';
            break;
    }
}

$sql = "SELECT id, titulo, data_publicacao, autor FROM noticias ORDER BY data_publicacao DESC";
$result = $conn->query($sql);

$noticias = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $noticias[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Notícias</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .admin-table th,
        .admin-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .admin-table th {
            background-color: #f2f2f2;
        }

        .admin-actions a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .admin-actions a:hover {
            text-decoration: underline;
        }

        .add-news-btn {
            display: inline-block;
            background: #28a745;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .add-news-btn:hover {
            background: #218838;
        }

        .delete-btn {
            color: #dc3545;
        }

        .delete-btn:hover {
            text-decoration: underline;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
    </style>
</head>

<body>
    <header>
        <h1>Área Administrativa</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Ver Site</a></li>
                <li><a href="index.php">Gerenciar Notícias</a></li>
                <li><a href="adicionar.php">Adicionar Notícia</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <?php echo $mensagem_status; ?>

        <h2>Gerenciar Notícias</h2>
        <a href="adicionar.php" class="add-news-btn">Adicionar Nova Notícia</a>

        <?php if (!empty($noticias)): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Data Publicação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($noticias as $noticia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($noticia['id']); ?></td>
                            <td><?php echo htmlspecialchars($noticia['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($noticia['autor']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($noticia['data_publicacao'])); ?></td>
                            <td class="admin-actions">
                                <a href="editar.php?id=<?php echo htmlspecialchars($noticia['id']); ?>">Editar</a>
                                <a href="excluir.php?id=<?php echo htmlspecialchars($noticia['id']); ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja excluir esta notícia?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma notícia cadastrada ainda.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Portal de Notícias - Admin.</p>
    </footer>
</body>

</html>