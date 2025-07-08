<?php
require_once 'includes/database.php';
$noticia = null;

if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = $_GET['slug'];

    $stmt = $conn->prepare("SELECT id, titulo, subtitulo, conteudo, imagem_destaque, data_publicacao, autor FROM noticias WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $noticia = $result->fetch_assoc();
    } else {
        header("Location: index.php?status=noticia_nao_encontrada");
        exit();
    }

    $stmt->close();
} else {
    header("Location: index.php?status=slug_nao_fornecido");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($noticia['titulo'] ?? 'Notícia Não Encontrada'); ?> - Portal de Notícias</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <h1>Portal de Notícias</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="admin/">Área Administrativa</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <?php if ($noticia): ?>
            <article class="full-news-item">
                <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                <p class="subtitle"><?php echo htmlspecialchars($noticia['subtitulo']); ?></p>
                <?php if (!empty($noticia['imagem_destaque'])): ?>
                    <img src="<?php echo htmlspecialchars($noticia['imagem_destaque']); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                <?php endif; ?>
                <p class="meta">Publicado em: <?php echo date('d/m/Y H:i', strtotime($noticia['data_publicacao'])); ?> por <?php echo htmlspecialchars($noticia['autor']); ?></p>
                <div class="content">
                    <?php echo nl2br(htmlspecialchars($noticia['conteudo'])); ?>
                </div>
                <p><a href="index.php">&larr; Voltar para as Notícias</a></p>
            </article>
        <?php else: ?>
            <p>A notícia que você está procurando não foi encontrada.</p>
            <p><a href="index.php">Voltar para a página inicial</a></p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Portal de Notícias. Todos os direitos reservados.</p>
    </footer>
</body>
</html>