<?php
require_once '../includes/database.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt_select_image = $conn->prepare("SELECT imagem_destaque FROM noticias WHERE id = ?");
    if ($stmt_select_image) {
        $stmt_select_image->bind_param("i", $id);
        $stmt_select_image->execute();
        $result_select_image = $stmt_select_image->get_result();

        if ($result_select_image->num_rows > 0) {
            $row = $result_select_image->fetch_assoc();
            $imagem_para_excluir = $row['imagem_destaque'];

            if (!empty($imagem_para_excluir) && file_exists('../' . $imagem_para_excluir)) {
                if (unlink('../' . $imagem_para_excluir)) {
                    // echo "Imagem excluída com sucesso.<br>"; // debug
                } else {
                    // echo "Erro ao excluir a imagem do servidor.<br>"; //debug
                }
            }
        }
        $stmt_select_image->close();
    } else {
        // echo "Erro na preparação da consulta de imagem: " . $conn->error . "<br>"; // debug
    }

    $stmt_delete_news = $conn->prepare("DELETE FROM noticias WHERE id = ?");
    if ($stmt_delete_news) {
        $stmt_delete_news->bind_param("i", $id);

        if ($stmt_delete_news->execute()) {
            if ($stmt_delete_news->affected_rows > 0) {
                header("Location: index.php?status=noticia_excluida_sucesso");
                exit();
            } else {
                header("Location: index.php?status=noticia_nao_encontrada");
                exit();
            }
        } else {
            header("Location: index.php?status=erro_excluir&msg=" . urlencode($stmt_delete_news->error));
            exit();
        }
        $stmt_delete_news->close();
    } else {
        header("Location: index.php?status=erro_query_delete&msg=" . urlencode($conn->error));
        exit();
    }

} else {
    header("Location: index.php?status=id_nao_fornecido");
    exit();
}

$conn->close();
?>