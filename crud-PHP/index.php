<?php
include 'conexao.php';

// Criação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome']) && isset($_POST['email'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $conn->query("INSERT INTO usuarios (nome, email) VALUES ('$nome', '$email')");
}

// Leitura
$usuarios = $conn->query("SELECT * FROM usuarios");

// Exclusão
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM usuarios WHERE id=$id");
    header("Location: index.php");
}

// (carrega os dados do usuário a ser editado)
$usuarioAtual = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM usuarios WHERE id=$id");
    $usuarioAtual = $result->fetch_assoc();
}
// (salva alterações do usuário editado)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $conn->query("UPDATE usuarios SET nome='$nome', email='$email' WHERE id=$id");
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD PHP</title>
</head>
<body>

<h2>Adicionar Usuário</h2>
<form action="index.php" method="POST">
    <input type="text" name="nome" value="<?= $usuarioAtual['nome'] ?? '' ?>" placeholder="Nome" required>
    <input type="email" name="email" value="<?= $usuarioAtual['email'] ?? '' ?>" placeholder="Email" required>
    <button type="submit"><?= $usuarioAtual ? 'Atualizar' : 'Adicionar' ?></button>
    <?php if ($usuarioAtual): ?>
        <a href="index.php">Cancelar</a>
    <?php endif; ?>
</form>

<h2>Lista de Usuários</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Ações</th>
    </tr>
    <?php while ($usuario = $usuarios->fetch_assoc()): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= $usuario['nome'] ?></td>
            <td><?= $usuario['email'] ?></td>
            <td>
                <a href="?edit=<?= $usuario['id'] ?>">Editar</a> | 
                <a href="?delete=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
