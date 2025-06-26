<?php
if (isset($_POST['submit'])) {
    include_once('config.php');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sss", $nome, $email, $senha);
        $stmt->execute() ? print("✅ Cadastro feito com sucesso!") : print("❌ Erro: " . $stmt->error);
        $stmt->close();
    } else {
        echo "Erro na query: " . $conn->error;
    }

    $conn->close();
}
?>