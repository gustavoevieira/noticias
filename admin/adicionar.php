<?php
require_once '../includes/database.php';

$mensagem = '';

$titulo = '';
$subtitulo = '';
$conteudo = '';
$autor = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = htmlspecialchars(trim($_POST['titulo']));
    $subtitulo = htmlspecialchars(trim($_POST['subtitulo']));
    $conteudo = htmlspecialchars(trim($_POST['conteudo']));
    $autor = htmlspecialchars(trim($_POST['autor']));

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));

    $imagem_destaque = '';
    $uploadOk = 1;

    if (isset($_FILES['imagem_destaque']) && $_FILES['imagem_destaque']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../public/uploads/";

        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                $mensagem = "Erro: Não foi possível criar o diretório de uploads.";
                $uploadOk = 0;
            }
        }

        if ($uploadOk) {
            $imageFileType = strtolower(pathinfo($_FILES['imagem_destaque']['name'], PATHINFO_EXTENSION));
            $unique_name = uniqid('img_') . '.' . $imageFileType;
            $target_file = $target_dir . $unique_name;

            $check = getimagesize($_FILES['imagem_destaque']['tmp_name']);
            if($check === false) {
                $mensagem = "O arquivo enviado não é uma imagem válida.";
                $uploadOk = 0;
            }

            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $mensagem = "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
                $uploadOk = 0;
            }

            if ($_FILES['imagem_destaque']['size'] > 5000000) { 
                $mensagem = "Desculpe, sua imagem é muito grande. Tamanho máximo: 5MB.";
                $uploadOk = 0;
            }

            if ($uploadOk) {
                if (move_uploaded_file($_FILES['imagem_destaque']['tmp_name'], $target_file)) {
                    $imagem_destaque = 'public/uploads/' . $unique_name;
                } else {
                    $mensagem = "Desculpe, houve um erro ao fazer upload da sua imagem. Verifique as permissões da pasta uploads.";
                    $uploadOk = 0;
                }
            }
        }
    } else if (isset($_FILES['imagem_destaque']) && $_FILES['imagem_destaque']['error'] !== UPLOAD_ERR_NO_FILE) {
        $mensagem = "Erro no upload da imagem: " . $_FILES['imagem_destaque']['error'];
        $uploadOk = 0;
    }

    if ($uploadOk || ($_FILES['imagem_destaque']['error'] === UPLOAD_ERR_NO_FILE)) {
        $sql = "INSERT INTO noticias (titulo, subtitulo, conteudo, imagem_destaque, autor, slug) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssss", $titulo, $subtitulo, $conteudo, $imagem_destaque, $autor, $slug);

            if ($stmt->execute()) {
                header("Location: index.php?status=noticia_adicionada_sucesso");
                exit();
            } else {
                $mensagem = "Erro ao adicionar notícia: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $mensagem = "Erro na preparação da consulta: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Adicionar Notícia</title>
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
            </ul>
        </nav>
    </header>

    <main class="container">
        <h2>Adicionar Nova Notícia</h2>

        <?php if ($mensagem): ?>
            <div class="message <?php echo (strpos($mensagem, 'sucesso') !== false || $uploadOk) ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form action="adicionar.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título da Notícia:</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
            </div>
            <div class="form-group">
                <label for="subtitulo">Subtítulo:</label>
                <input type="text" id="subtitulo" name="subtitulo" value="<?php echo htmlspecialchars($subtitulo); ?>">
            </div>
            <div class="form-group">
                <label for="conteudo">Conteúdo:</label>
                <textarea id="conteudo" name="conteudo" required><?php echo htmlspecialchars($conteudo); ?></textarea>
            </div>
            <div class="form-group">
                <label for="imagem_destaque">Imagem de Destaque:</label>
                <input type="file" id="imagem_destaque" name="imagem_destaque" accept="image/*">
            </div>
            <div class="form-group">
                <label for="autor">Autor:</label>
                <input type="text" id="autor" name="autor" value="<?php echo htmlspecialchars($autor); ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit">Adicionar Notícia</button>
            </div>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Portal de Notícias - Admin.</p>
    </footer>
</body>
</html>