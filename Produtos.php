<?php
include("header.php");
if (isset($_GET['id']) && !empty($_GET['id'])) {
    deletarProduto($db);
}

// Consultar produtos com suas respectivas categorias e linhas
$sql = "
    SELECT produtos.id, produtos.imagem, produtos.nome, produtos.status, produtos.descricao,
           linhas.nome AS nome_linha, categorias.nome AS nome_categoria
    FROM produtos
    INNER JOIN linhas ON produtos.id_linha = linhas.id
    INNER JOIN categorias ON linhas.id_categoria = categorias.id
";
$stmt = $db->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

function deletarProduto($db) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0) {
        $sqlSelectImage = "SELECT imagem FROM produtos WHERE id = :id";
        $stmtSelectImage = $db->prepare($sqlSelectImage);
        $stmtSelectImage->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtSelectImage->execute();
        $produto = $stmtSelectImage->fetch(PDO::FETCH_ASSOC);

        if ($produto) {
            $imagem = $produto['imagem'];
            $sqlDelete = "DELETE FROM produtos WHERE id = :id";
            $stmtDelete = $db->prepare($sqlDelete);
            $stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmtDelete->execute()) {
                if ($imagem && file_exists("uploads/" . $imagem)) {
                    unlink("uploads/" . $imagem);
                }
                echo "<script>alert('Produto deletado com sucesso!'); window.location.href='Produtos.php';</script>";
            }
        }
    }
}
?>
<div class="container">
    <label>PÁGINA PRODUTOS</label>
    <div class="col-lg-12" style="text-align: right;">
        <a href="CadastrarProduto.php" class="btn btn-success" role="button">
            CRIAR UM NOVO PRODUTO
        </a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">IMAGEM</th>
                <th scope="col">Nome</th>
                <th scope="col">CATEGORIA</th>
                <th scope="col">LINHA</th>
                <th scope="col">AÇÕES</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($produto['id']); ?></th>
                    <td><img src="uploads/<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Imagem" style="width: 100px; height: auto;"></td>
                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                    <td><?php echo htmlspecialchars($produto['nome_categoria']); ?></td>
                    <td><?php echo htmlspecialchars($produto['nome_linha']); ?></td>
                    <td>
                        <a href="CadastrarProduto.php?acao=editar&id=<?php echo $produto['id']; ?>" class="btn btn-warning">Editar</a>
                        <a href="Produtos.php?id=<?php echo $produto['id']; ?>" class="btn btn-danger">Deletar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("footer.php"); ?>
