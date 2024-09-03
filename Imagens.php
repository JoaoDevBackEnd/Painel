<?php
require_once("header.php");

// Função para deletar imagem
if (isset($_GET['deletar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM imagens WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo "<script>alert('Imagem deletada com sucesso!'); window.location.href='Imagens.php';</script>";
    } 
}

// Verifica o número de imagens ativadas
$sqlCount = "SELECT COUNT(*) as count FROM imagens WHERE status = 'Y'";
$stmtCount = $db->query($sqlCount);
$countResult = $stmtCount->fetch(PDO::FETCH_ASSOC);
$activeCount = $countResult['count'];

// Atualizar status da imagem
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['imagem_status'])) {
    $id = $_POST['id'];
    $status_imagem = $_POST['imagem_status'];

    //NUMERO DE IMAGENS PERMITIDAS CASO QUERIA AUMENTAR SÓ MUDAR O NÚMERO
    if ($status_imagem == 'N' && $activeCount >= 4) {
        echo "<script>alert('Número máximo de 2 imagens ativadas atingido!');</script>";
    } else {
        $novo_status = ($status_imagem == 'Y') ? 'N' : 'Y';
    
        $sql = "UPDATE imagens SET status = :status_imagem WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status_imagem', $novo_status);
        $stmt->bindParam(':id', $id);
    
        if ($stmt->execute()) {
            header("Location: Imagens.php");
            exit();
        }
    }
}

// Consultar todas as imagens
$sql = "SELECT * FROM imagens";
$stmt = $db->query($sql);
$imagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <label>PÁGINA CATEGORIA</label>
    <div class="col-lg-12" style="text-align: right;">
        <a href="CadastrarImagem.php" class="btn btn-success" role="button">
            CRIAR UMA NOVA IMAGEM
        </a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">IMAGEM</th>
                <th scope="col">NOME</th>
                <th scope="col">STATUS</th>
                <th scope="col">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($imagens as $imagem): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($imagem['id']); ?></th>
                    <td>
                        <img src="uploads/<?php echo htmlspecialchars($imagem['imagem']); ?>" alt="Imagem" style="width: 100px; height: auto;">
                    </td>
                    <td><?php echo htmlspecialchars($imagem['nome']); ?></td>
                    <td>
                        <form method="post" action="Imagens.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($imagem['id']); ?>">
                            <button type="submit" name="imagem_status" value="<?php echo htmlspecialchars($imagem['status']); ?>"
                                class="btn btn-<?php echo $imagem['status'] == 'Y' ? 'success' : 'danger'; ?>">
                                <?php echo $imagem['status'] == 'Y' ? 'Ativado' : 'Desativado'; ?>
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="Imagens.php?deletar=true&id=<?php echo $imagem['id']; ?>" class="btn btn-danger">Deletar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once("footer.php"); ?>
