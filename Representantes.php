<?php
include("header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['status_representante'])) {
        $id = $_POST['id'];
        $status_representante = $_POST['status_representante'];
        $novo_status = ($status_representante == 'Y') ? 'N' : 'Y';

        $sql = "UPDATE representantes SET status = :status_representante WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status_representante', $novo_status);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            header("Location: Representantes.php");
            exit();
        }
    } elseif (isset($_POST['deletar_id'])) {
        $id = intval($_POST['deletar_id']);

        $sqlDeletar = "DELETE FROM representantes WHERE id = :id";
        $stmtDeletar = $db->prepare($sqlDeletar);
        $stmtDeletar->bindParam(':id', $id);
        if ($stmtDeletar->execute()) {
            echo "<script>alert('Representante deletado com sucesso!'); window.location.href='Representantes.php';</script>";
        }
    }
}

$sqlRepresentantes = "SELECT * FROM representantes";
$stmtRepresentantes = $db->query($sqlRepresentantes);
$representantes = $stmtRepresentantes->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <label>PÁGINA REPRESENTANTES</label>
    <div class="col-lg-12" style="text-align: right;">
        <a href="CadastrarRepresentantes.php" class="btn btn-success" role="button">
            NOVO REPRESENTANTE
        </a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">NOME</th>
                <th scope="col">TELEFONE</th>
                <th scope="col">EMAIL</th>
                <th scope="col">ESTADO</th>
                <th scope="col">CIDADE</th>
                <th scope="col">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($representantes as $representante): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($representante['id']); ?></th>
                    <td><?php echo htmlspecialchars($representante['nome']); ?></td>
                    <td><?php echo sprintf("(%s) %s", htmlspecialchars($representante['ddd']), htmlspecialchars($representante['telefone'])); ?></td>
                    <td><?php echo htmlspecialchars($representante['email']); ?></td>
                    <td><?php echo htmlspecialchars($representante['estado']); ?></td>
                    <td><?php echo htmlspecialchars($representante['cidade']); ?></td>
                    <td>
                        <form method="post" action="Representantes.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($representante['id']); ?>">
                        </form>
                    </td>
                    <td>
                        <a href="CadastrarRepresentantes.php?id=<?php echo htmlspecialchars($representante['id']); ?>" class="btn btn-warning">Editar</a>
                        <form method="post" action="Representantes.php" style="display:inline;">
                            <input type="hidden" name="deletar_id" value="<?php echo htmlspecialchars($representante['id']); ?>">
                            <button type="submit" class="btn btn-danger">Deletar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("footer.php"); ?>
