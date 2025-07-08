<?php
require_once 'includes/database.php';

$sql = "SELECT id, titulo, subtitulo, imagem_destaque, data_publicacao, autor, slug FROM noticias ORDER BY data_publicacao DESC";
$result = $conn->query($sql);

$noticias = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
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
    <title>Portal de Notícias - Início</title>
    <link rel="stylesheet" href="public/css/style.css">
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
        <h2>Últimas Notícias</h2>
        <div class="news-list">
            <?php if (!empty($noticias)): ?>
                <?php foreach ($noticias as $noticia): ?>
                    <article class="news-item">
                        <?php if (!empty($noticia['imagem_destaque'])): ?>
                            <img src="<?php echo htmlspecialchars($noticia['imagem_destaque']); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                        <?php endif; ?>
                        <h3><a href="noticia.php?slug=<?php echo htmlspecialchars($noticia['slug']); ?>"><?php echo htmlspecialchars($noticia['titulo']); ?></a></h3>
                        <p class="subtitle"><?php echo htmlspecialchars($noticia['subtitulo']); ?></p>
                        <p class="meta">Publicado em: <?php echo date('d/m/Y H:i', strtotime($noticia['data_publicacao'])); ?> por <?php echo htmlspecialchars($noticia['autor']); ?></p>
                        <a href="noticia.php?slug=<?php echo htmlspecialchars($noticia['slug']); ?>" class="read-more">Leia Mais &rarr;</a>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma notícia encontrada no momento.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Portal de Notícias. Todos os direitos reservados.</p>
    </footer>
</body>
</html>