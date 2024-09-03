<?php
include("header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['status_categoria'])) {
        // Atualizar status da categoria
        $id = $_POST['id'];
        $status_categoria = $_POST['status_categoria'];
        $novo_status = ($status_categoria == 'Y') ? 'N' : 'Y';

        //CHAMADA DE EDITAR!
        $sql = "UPDATE categorias SET status = :status_categoria WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':status_categoria', $novo_status);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            header("Location: Categoria.php");
            exit();
        }
    } elseif (isset($_POST['deletar_id'])) {
        // Deletar categoria
        $id = intval($_POST['deletar_id']);

        // Verificar se a categoria tem linhas e produtos associados
        $sqlVerificarLinhas = "SELECT COUNT(*) FROM linhas WHERE id_categoria = :id";
        $stmtVerificarLinhas = $db->prepare($sqlVerificarLinhas);
        $stmtVerificarLinhas->bindParam(':id', $id);
        $stmtVerificarLinhas->execute();
        $countLinhas = $stmtVerificarLinhas->fetchColumn();

        $sqlVerificarProdutos = "SELECT COUNT(*) FROM produtos WHERE id_categoria = :id";
        $stmtVerificarProdutos = $db->prepare($sqlVerificarProdutos);
        $stmtVerificarProdutos->bindParam(':id', $id);
        $stmtVerificarProdutos->execute();
        $countProdutos = $stmtVerificarProdutos->fetchColumn();

        if ($countLinhas > 0 || $countProdutos > 0) {
            echo "<script>alert('Não é possível excluir a categoria. Exclua as linhas e produtos associados antes de excluir a categoria.'); window.location.href='Categoria.php';</script>";
        } else {
            $sqlDeletar = "DELETE FROM categorias WHERE id = :id";
            $stmtDeletar = $db->prepare($sqlDeletar);
            $stmtDeletar->bindParam(':id', $id);
            if ($stmtDeletar->execute()) {
                echo "<script>alert('Categoria deletada com sucesso!'); window.location.href='Categoria.php';</script>";
            } else {
                echo "<script>alert('Erro ao deletar categoria.'); window.location.href='Categoria.php';</script>";
            }
        }
    }
}

// Consultar todas as categorias e associar contagens de linhas e produtos
$sqlCategorias = "SELECT * FROM categorias";
$stmtCategorias = $db->query($sqlCategorias);
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <label>PÁGINA CATEGORIA</label>
    <div class="col-lg-12" style="text-align: right;">
        <a href="CadastroCategoria.php" class="btn btn-success" role="button">
            CRIAR UMA NOVA CATEGORIA
        </a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">NOME</th>
                <th scope="col">Qtd. Linhas Cadastrada</th>
                <th scope="col">Qtd. Produtos Cadastrado</th>
                <th scope="col">STATUS</th>
                <th scope="col">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($categoria['id']); ?></th>
                    <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                    <td><?php 
                        $sqlCountLinhas = "SELECT COUNT(*) FROM linhas WHERE id_categoria = :id_categoria";
                        $stmtCountLinhas = $db->prepare($sqlCountLinhas);
                        $stmtCountLinhas->bindParam(':id_categoria', $categoria['id']);
                        $stmtCountLinhas->execute();
                        $categoria['qtd_linhas'] = $stmtCountLinhas->fetchColumn();
                        echo htmlspecialchars($categoria['qtd_linhas']); ?>
                    </td>
                    <td><?php
                        $sqlCountProdutos = "SELECT COUNT(*) FROM produtos WHERE id_categoria = :id_categoria";
                        $stmtCountProdutos = $db->prepare($sqlCountProdutos);
                        $stmtCountProdutos->bindParam(':id_categoria', $categoria['id']);
                        $stmtCountProdutos->execute();
                        $categoria['qtd_produtos'] = $stmtCountProdutos->fetchColumn();
                        echo htmlspecialchars($categoria['qtd_produtos']); 
                    ?></td>
                    <td>
                        <form method="post" action="Categoria.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($categoria['id']); ?>">
                            <input type="hidden" name="status_categoria" value="<?php echo htmlspecialchars($categoria['status']); ?>">
                            <button type="submit" class="btn btn-<?php echo $categoria['status'] == 'Y' ? 'success' : 'danger'; ?>">
                                <?php echo $categoria['status'] == 'Y' ? 'Ativado' : 'Desativado'; ?>
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="CadastroCategoria.php?id=<?php echo htmlspecialchars($categoria['id']); ?>" class="btn btn-warning">Editar</a>
                        <form method="post" action="Categoria.php" style="display:inline;">
                            <input type="hidden" name="deletar_id" value="<?php echo htmlspecialchars($categoria['id']); ?>">
                            <button type="submit" class="btn btn-danger">Deletar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("footer.php"); ?>
