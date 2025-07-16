<?php
$senha_clara = "admin123"; // Defina a senha que você quer usar para o login
$hash_da_senha = password_hash($senha_clara, PASSWORD_DEFAULT);
echo "A senha em texto claro é: " . $senha_clara . "<br>";
echo "O HASH correspondente para usar no banco de dados é: <strong>" . $hash_da_senha . "</strong>";
?>