<?php
// Inicia a sessão se ainda não estiver iniciada.
// É crucial que esta verificação esteja aqui e que database.php também tenha ela.
// Ou, que database.php seja sempre incluído ANTES de verificar_login.php nas páginas admin.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: ../admin/login.php"); // Caminho relativo para a página de login
    exit();
}

// Opcional: Você pode adicionar verificação de nível de acesso aqui
// if ($_SESSION['access_level'] !== 'admin') {
//     header("Location: ../admin/login.php?erro=acesso_negado");
//     exit();
// }
?>