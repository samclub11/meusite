<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['nivel'] !== 'usuario') {
    header("Location: form_login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <title>Home</title>
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

        .form-inline {
            display: flex;
            gap: 5px;
        }

        .form-inline input, .form-inline select {
            width: auto;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">SamClub</div>
        <a class="btn btn-danger" href="logout.php">Sair</a>
    </nav>

    <div class="container text-center">
     <h2>Bem-vindo Usuário, <?php echo $_SESSION['nome']; ?>!</h2>   
    </div>
</body>
</html>


 
