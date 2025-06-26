<?php
session_start();
include_once('config.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: form_login.html");
    exit();
}

// Verifica se o ID foi passado pela URL
if (!isset($_GET['id'])) {
    echo "❌ ID do usuário não fornecido.";
    exit();
}

$id = intval($_GET['id']);

// Busca os dados do usuário
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "❌ Usuário não encontrado.";
    exit();
}

$usuario = $result->fetch_assoc();
$mensagem = "";

// Atualização de nome e email
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['salvar_dados'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    if (!empty($nome) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $email, $id);

        if ($stmt->execute()) {
            $mensagem = "<div class='alert alert-success mt-3'>✅ Dados atualizados com sucesso!</div>";
        } else {
            $mensagem = "<div class='alert alert-danger mt-3'>❌ Erro ao atualizar dados.</div>";
        }
    } else {
        $mensagem = "<div class='alert alert-warning mt-3'>❌ Dados inválidos.</div>";
    }
}

// Alteração de senha
if (isset($_POST['alterar_senha'])) {
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if (strlen($nova_senha) < 6) {
        $mensagem = "<div class='alert alert-warning mt-3'>❌ A senha deve ter pelo menos 6 caracteres.</div>";
    } elseif ($nova_senha !== $confirmar_senha) {
        $mensagem = "<div class='alert alert-warning mt-3'>❌ As senhas não coincidem.</div>";
    } else {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->bind_param("si", $senha_hash, $id);

        if ($stmt->execute()) {
            $mensagem = "<div class='alert alert-success mt-3'>✅ Senha atualizada com sucesso!</div>";
        } else {
            $mensagem = "<div class='alert alert-danger mt-3'>❌ Erro ao atualizar a senha.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
</head>
<body class="bg-dark text-white d-flex flex-column align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card p-4 bg-secondary" style="width: 100%; max-width: 500px;">
        <h3 class="mb-4">Editar Usuário</h3>

        <?php echo $mensagem; ?>

        <!-- Formulário de dados -->
        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <button type="submit" name="salvar_dados" class="btn btn-success">Salvar</button>
            <a href="dashboard.php" class="btn btn-light">Voltar</a>
        </form>

        <!-- Formulário de alteração de senha -->
        <hr class="text-white mt-4 mb-4">
        <h4 class="mb-3">Alterar Senha</h4>
        <form method="POST">
            <div class="mb-3">
                <label for="nova_senha" class="form-label">Nova Senha:</label>
                <input type="password" class="form-control" name="nova_senha" required>
            </div>
            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                <input type="password" class="form-control" name="confirmar_senha" required>
            </div>
            <button type="submit" name="alterar_senha" class="btn btn-warning">Alterar Senha</button>
        </form>
    </div>
</body>
</html>
