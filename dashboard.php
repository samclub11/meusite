<?php
session_start();
include_once('config.php');

if (!isset($_SESSION['usuario']) || $_SESSION['nivel'] !== 'admin') {
    header("Location: form_login.html");
    exit();
}

// Alterar nível de acesso
if (isset($_POST['alterar_nivel'])) {
    $id = intval($_POST['id_usuario']);
    $novo_nivel = $_POST['novo_nivel'];

    if (in_array($novo_nivel, ['admin', 'usuario'])) {
        $stmt = $conn->prepare("UPDATE usuarios SET nivel = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_nivel, $id);
        $stmt->execute();
    }
}

// Alterar senha
if (isset($_POST['alterar_senha'])) {
    $id = intval($_POST['id_usuario']);
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $stmt->bind_param("si", $nova_senha, $id);
    $stmt->execute();
}

$result = $conn->query("SELECT id, nome, email, nivel FROM usuarios ORDER BY nome DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    

    <style>
        body {
            background-image: linear-gradient(90deg,rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        
        .container {
            margin-top: 100px;
        }

        .table th, .table td {
            text-align: center;
            background: rgba(0, 0, 0, 0.3);
            color: white;
        }

        .table th {
            border-radius: 15px 15px 0 0;
        }

 a {
            color: #007bff;
            text-decoration: none;
            margin: 6px 5px;
            margin-right: 5px;
            
        }

        a:hover {
            text-decoration: none;
        }

        nav {
            background-color: #574bd7;
            overflow: hidden;
            position: fixed;
            width: 100%;
            top: 0;
            
            box-shadow: rgba(0, 0, 0, 0.15) 0px 48px 100px 0px;
            border: 2px solid rgba(255, 255, 255, .2);
            border-radius: 10px;
            backdrop-filter: blur(120px);
        }

        nav .logo {
            float: left;
            display: block;
            padding: 14px 16px;
            color: white;
            font-weight: bold;
        }

        nav a {
            color: white;
            float: right;
            text-decoration: none;
            display: block;
            padding: 14px 16px;
        }

        nav a:hover {
            color: gray;
            transition: 1s;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: purple;
        }

        h2 {
            margin-top: 30px;
        }

        .form-inline {
            display: flex;
            gap: 5px;
        }

        .form-inline input, .form-inline select {
            width: auto;
        }

        .search-box1{
    position: absolute;
    top: 0%;
    left: 50%;
    margin-top: 80px;
    transform: translate(-50%,-50%);
    background: gray;
    height: 59px;
    border-radius: 54px;
    padding: 10px;

    
}

.search-btn{
    color: blue;
    float: right;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: 2s;
    
}

    </style>
</head>
<body>
    <nav>
        <div class="logo">SamClub Admin</div>
        <a class="btn btn-danger" href="logout.php">Sair</a>
    </nav>

    <div class="container text-center">
        <h2>Painel de Administração</h2>
        <p>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</p>

        
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-dark">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Nível</th>
                        <th>Alterar Nível</th>
                        <th>Senha</th>
                        <!-- <th>Alterar Senha</th> -->
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                    
                    <tr>
                        
                        <td><?= $user['id'] ?></td>
                        
                        <td><?= htmlspecialchars($user['nome']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        
                        <td style="color: <?= $user['nivel'] === 'admin' ? 'gold' : 'white' ?>"><?= $user['nivel'] ?></td>
                        

                        <td>
                            
                            <form method="POST" class="form-inline">
                                <input type="hidden" name="id_usuario" value="<?= $user['id'] ?>">
                                <select name="novo_nivel" class="form-select form-select-sm">
                                    <option value="usuario" <?= $user['nivel'] == 'usuario' ? 'selected' : '' ?>>Usuário</option>
                                    <option value="admin" <?= $user['nivel'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit" name="alterar_nivel" class="btn btn-sm btn-warning">Atualizar</button>
                            </form>
                        </td>
                        <td>••••••••</td>
                        
                        <td>
                            <a class="btn btn-sm btn-primary" href="editar_usuario.php?id=<?= $user['id'] ?>">Editar</a>
                            <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $user['id'] ?>">Excluir</a>
                        </td>
                        
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
