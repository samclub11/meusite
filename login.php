<?php
session_start();
include_once('config.php');

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario['email'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['nivel'] = $usuario['nivel']; // <- importante

            if ($usuario['nivel'] === 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: painel_usuario.php");
            }
            exit();
        } else {
            echo "❌ Senha incorreta.";
        }
    } else {
        echo "❌ Usuário não encontrado.";
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: form_login.html');
    exit();
}

?>
