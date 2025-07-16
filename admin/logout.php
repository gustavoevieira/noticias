<?php
// Inicia a sessão se ainda não estiver iniciada (para poder destruí-la)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destroi todas as variáveis de sessão
$_SESSION = array();

// Se for preciso matar completamente a sessão, apague também o cookie de sessão.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão
session_destroy();

// Redireciona para a página de login
header("Location: login.php");
exit();
?>