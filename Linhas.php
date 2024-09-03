<?php
include("header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['status_linha'])) {
        // Atualizar status da linha
        $id = $_POST['id'];
        $status_linha = $_POST['status_linha'];
        $novo_status = ($status_linha == 'Y') ? 'N' : 'Y';

        $sql = "UPDATE linhas SET status = :status WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status', $novo_status);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            header("Location: Linhas.php");
            exit();
        }
    } elseif (isset($_POST['deletar_id'])) {
        // Deletar linha
        $id = intval($_POST['deletar_id']);

        // Verificar se a linha tem produtos associados
        $sqlVerificarProdutos = "SELECT COUNT(*) FROM produtos WHERE id_linha = :id";
        $stmtVerificarProdutos = $db->prepare($sqlVerificarProdutos);
        $stmtVerificarProdutos->bindParam(':id', $id);
        $stmtVerificarProdutos->execute();
        $countProdutos = $stmtVerificarProdutos->fetchColumn();

        if ($countProdutos > 0) {
            echo "<script>alert('Não é possível excluir a linha. Exclua os produtos associados antes de excluir a linha.'); window.location.href='Linhas.php';</script>";
        } else {
            $sqlDeletar = "DELETE FROM linhas WHERE id = :id";
            $stmtDeletar = $db->prepare($sqlDeletar);
            $stmtDeletar->bindParam(':id', $id);
            if ($stmtDeletar->execute()) {
                echo "<script>alert('Linha deletada com sucesso!'); window.location.href='Linhas.php';</script>";
            } else {
                echo "<script>alert('Erro ao deletar linha.'); window.location.href='Linhas.php';</script>";
            }
        }
    }
}

// Consultar todas as linhas e associar contagens de produtos
$sql = "
    SELECT linhas.id, linhas.nome, linhas.status, categorias.nome AS nome_categoria 
    FROM linhas 
    INNER JOIN categorias ON linhas.id_categoria = categorias.id
";
$stmt = $db->query($sql);
$linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <label>PÁGINA LINHA</label>
    <div class="col-lg-12" style="text-align: right;">
        <a href="CadastroLinha.php" class="btn btn-success" role="button">
            CRIAR UMA NOVA LINHA
        </a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">NOME</th>
                <th scope="col">CATEGORIA</th>
                <th scope="col">Qtd. Produtos Cadastrado</th>
                <th scope="col">STATUS</th>
                <th scope="col">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($linhas as $linha): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($linha['id']); ?></th>
                    <td><?php echo htmlspecialchars($linha['nome']); ?></td>
                    <td><?php echo htmlspecialchars($linha['nome_categoria']); ?></td> 
                    <td><?php
                        $sqlCountProdutos = "SELECT COUNT(*) FROM produtos WHERE id_linha = :id_linha";
                        $stmtCountProdutos = $db->prepare($sqlCountProdutos);
                        $stmtCountProdutos->bindParam(':id_linha', $linha['id']);
                        $stmtCountProdutos->execute();
                        $qtd_produtos = $stmtCountProdutos->fetchColumn();
                        echo htmlspecialchars($qtd_produtos); 
                    ?></td>
                    <td>
                        <form method="post" action="Linhas.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($linha['id']); ?>">
                            <input type="hidden" name="status_linha" value="<?php echo htmlspecialchars($linha['status']); ?>">
                            <button type="submit" class="btn btn-<?php echo $linha['status'] == 'Y' ? 'success' : 'danger'; ?>">
                                <?php echo $linha['status'] == 'Y' ? 'Ativado' : 'Desativado'; ?>
                            </button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="Linhas.php" style="display:inline;">
                            <input type="hidden" name="deletar_id" value="<?php echo htmlspecialchars($linha['id']); ?>">
                            <button type="submit" class="btn btn-danger">Deletar</button>
                        </form>
                        <a href="CadastroLinha.php?id=<?php echo htmlspecialchars($linha['id']); ?>" class="btn btn-warning">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("footer.php"); ?>
