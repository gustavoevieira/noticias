<?php
require_once '../includes/database.php';
require_once 'verificar_login.php';

$noticia = null;
$mensagem = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT id, titulo, subtitulo, conteudo, imagem_destaque, autor, slug FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $noticia = $result->fetch_assoc();
    } else {
        $mensagem = "Notícia não encontrada.";
        header("Location: index.php?status=noticia_nao_encontrada");
        exit();
    }
    $stmt->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $subtitulo = htmlspecialchars(trim($_POST['subtitulo']));
    $conteudo = htmlspecialchars(trim($_POST['conteudo']));
    $autor = htmlspecialchars(trim($_POST['autor']));
    $imagem_existente = htmlspecialchars(trim($_POST['imagem_existente'] ?? ''));

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));

    $imagem_destaque = $imagem_existente;

    if (isset($_FILES['nova_imagem_destaque']) && $_FILES['nova_imagem_destaque']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../public/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($_FILES['nova_imagem_destaque']['name'], PATHINFO_EXTENSION));
        $unique_name = uniqid('img_') . '.' . $imageFileType;
        $target_file = $target_dir . $unique_name;

        $check = getimagesize($_FILES['nova_imagem_destaque']['tmp_name']);
        if ($check !== false) {
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $mensagem = "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos para a nova imagem.";
            } else {
                if (move_uploaded_file($_FILES['nova_imagem_destaque']['tmp_name'], $target_file)) {
                    if (!empty($imagem_existente) && file_exists('../' . $imagem_existente)) {
                        unlink('../' . $imagem_existente);
                    }
                    $imagem_destaque = 'public/uploads/' . $unique_name;
                } else {
                    $mensagem = "Desculpe, houve um erro ao fazer upload da nova imagem.";
                }
            }
        } else {
            $mensagem = "O arquivo enviado não é uma imagem válida.";
        }
    } else if (isset($_POST['remover_imagem']) && $_POST['remover_imagem'] == 'sim') {
        if (!empty($imagem_existente) && file_exists('../' . $imagem_existente)) {
            unlink('../' . $imagem_existente);
        }
        $imagem_destaque = '';
    }

    $sql = "UPDATE noticias SET titulo = ?, subtitulo = ?, conteudo = ?, imagem_destaque = ?, autor = ?, slug = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssi", $titulo, $subtitulo, $conteudo, $imagem_destaque, $autor, $slug, $id);

        if ($stmt->execute()) {
            header("Location: index.php?status=noticia_atualizada_sucesso");
            $stmt_recarregar = $conn->prepare("SELECT id, titulo, subtitulo, conteudo, imagem_destaque, autor, slug FROM noticias WHERE id = ?");
            $stmt_recarregar->bind_param("i", $id);
            $stmt_recarregar->execute();
            $result_recarregar = $stmt_recarregar->get_result();
            $noticia = $result_recarregar->fetch_assoc();
            $stmt_recarregar->close();
        } else {
            $mensagem = "Erro ao atualizar notícia: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $mensagem = "Erro na preparação da consulta: " . $conn->error;
    }
} else {
    header("Location: index.php?status=id_nao_fornecido");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Editar Notícia</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-group input[type="file"] {
            padding: 5px 0;
        }

        .form-actions {
            margin-top: 20px;
        }

        .form-actions button {
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .form-actions button:hover {
            background: #0056b3;
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

        .current-image {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .current-image img {
            max-width: 200px;
            height: auto;
            border: 1px solid #eee;
            border-radius: 5px;
            display: block;
            margin: 0 auto;
        }

        .current-image p {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
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
        <h2>Editar Notícia: <?php echo htmlspecialchars($noticia['titulo'] ?? 'Carregando...'); ?></h2>

        <?php if ($mensagem): ?>
            <div class="message <?php echo (strpos($mensagem, 'sucesso') !== false) ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <?php if ($noticia): ?>
            <form action="editar.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($noticia['id']); ?>">
                <input type="hidden" name="imagem_existente" value="<?php echo htmlspecialchars($noticia['imagem_destaque'] ?? ''); ?>">

                <div class="form-group">
                    <label for="titulo">Título da Notícia:</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="subtitulo">Subtítulo:</label>
                    <input type="text" id="subtitulo" name="subtitulo" value="<?php echo htmlspecialchars($noticia['subtitulo']); ?>">
                </div>
                <div class="form-group">
                    <label for="conteudo">Conteúdo:</label>
                    <textarea id="conteudo" name="conteudo" required><?php echo htmlspecialchars($noticia['conteudo']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Imagem de Destaque Atual:</label>
                    <?php if (!empty($noticia['imagem_destaque'])): ?>
                        <div class="current-image">
                            <img src="../<?php echo htmlspecialchars($noticia['imagem_destaque']); ?>" alt="Imagem atual">
                            <p>Caminho: <?php echo htmlspecialchars($noticia['imagem_destaque']); ?></p>
                            <input type="checkbox" name="remover_imagem" id="remover_imagem" value="sim">
                            <label for="remover_imagem">Remover imagem atual</label>
                        </div>
                    <?php else: ?>
                        <p>Nenhuma imagem de destaque definida.</p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="nova_imagem_destaque">Carregar Nova Imagem (substituirá a atual):</label>
                    <input type="file" id="nova_imagem_destaque" name="nova_imagem_destaque" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="autor">Autor:</label>
                    <input type="text" id="autor" name="autor" value="<?php echo htmlspecialchars($noticia['autor']); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit">Atualizar Notícia</button>
                </div>
            </form>
        <?php else: ?>
            <p>Não foi possível carregar a notícia para edição.</p>
            <p><a href="index.php">Voltar para Gerenciar Notícias</a></p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Portal de Notícias - Admin.</p>
    </footer>
</body>

</html>