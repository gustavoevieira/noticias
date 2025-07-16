<?php
// ATENÇÃO: Habilita exibição de erros - REMOVA EM PRODUÇÃO!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui a conexão com o banco de dados e garante que a sessão seja iniciada.
// O database.php precisa ter 'if (session_status() == PHP_SESSION_NONE) { session_start(); }'
require_once '../includes/database.php';

$mensagem_login = '';

// Verifica se a requisição é POST (quando o formulário é submetido)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_usuario = htmlspecialchars(trim($_POST['nome_usuario']));
    $senha = trim($_POST['senha']); // A senha não é sanitizada com htmlspecialchars aqui, pois será usada para verificação com password_verify

    if (empty($nome_usuario) || empty($senha)) {
        $mensagem_login = '<div class="message error">Por favor, preencha usuário e senha.</div>';
    } else {
        // Prepara e executa a consulta para buscar o usuário pelo nome de usuário
        $stmt = $conn->prepare("SELECT id, nome_usuario, senha, nivel_acesso FROM usuarios WHERE nome_usuario = ?");
        if ($stmt) {
            $stmt->bind_param("s", $nome_usuario); // "s" indica que o parâmetro é uma string
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                // Verifica a senha fornecida pelo usuário com o hash armazenado no banco de dados
                if (password_verify($senha, $usuario['senha'])) {
                    // Login bem-sucedido: define variáveis de sessão
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['username'] = $usuario['nome_usuario'];
                    $_SESSION['access_level'] = $usuario['nivel_acesso'];

                    // Redireciona para o dashboard administrativo
                    header("Location: index.php");
                    exit(); // É CRUCIAL para garantir que o script pare a execução após o redirecionamento
                } else {
                    $mensagem_login = '<div class="message error">Usuário ou senha incorretos.</div>';
                }
            } else {
                $mensagem_login = '<div class="message error">Usuário ou senha incorretos.</div>';
            }
            $stmt->close(); // Fecha o statement
        } else {
            $mensagem_login = '<div class="message error">Erro interno do servidor ao preparar consulta.</div>';
        }
    }
}

$conn->close(); // Fecha a conexão com o banco de dados
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Login</title>
    <link rel="stylesheet" href="../public/css/style.css"> <style>
        /* Estilos específicos para a página de login */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h2 {
            color: #28a745; /* Cor verde para o título */
            margin-bottom: 25px;
            font-size: 2em;
        }
        .login-form .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .login-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }
        .login-form button {
            background: #007bff; /* Cor azul para o botão */
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .login-form button:hover {
            background: #0056b3; /* Tom mais escuro no hover */
        }
        .message { /* Estilos para mensagens de feedback */
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            font-size: 0.95em;
        }
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Administrativo</h2>
        <?php echo $mensagem_login; ?>
        <form action="login.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="nome_usuario">Usuário:</label>
                <input type="text" id="nome_usuario" name="nome_usuario" required autofocus>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Entrar</button>  
        </form>
        <br>
        <a href="../index.php" class="login-form button-return"><button>Voltar ao Site</button></a>
    </div>
</body>
</html>