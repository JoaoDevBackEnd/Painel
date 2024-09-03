<?php
include("header.php");

$id_produto = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_categoria = isset($_POST['id_categoria']) ? intval($_POST['id_categoria']) : 0;
$id_linha = isset($_POST['id_linha']) ? intval($_POST['id_linha']) : 0;
$nome_produto = isset($_POST['nome_produto']) ? $_POST['nome_produto'] : '';
$descricao_produto = isset($_POST['descricao_produto']) ? $_POST['descricao_produto'] : '';
$imagem_atual = '';

$categorias = [];
$linhas = [];

if ($id_produto > 0) {
    // Carregar dados do produto para edição
    $sqlProduto = "
        SELECT * FROM produtos WHERE id = :id
    ";
    $stmtProduto = $db->prepare($sqlProduto);
    $stmtProduto->bindParam(':id', $id_produto, PDO::PARAM_INT);
    $stmtProduto->execute();
    $produto = $stmtProduto->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        $id_categoria = $produto['id_categoria'];
        $id_linha = $produto['id_linha'];
        $nome_produto = $produto['nome'];
        $descricao_produto = $produto['descricao'];
        $imagem_atual = $produto['imagem'];
    }
}

// Consultar todas as categorias
$sqlCategorias = "
    SELECT id, nome FROM categorias ORDER BY nome;
";
$stmtCategorias = $db->query($sqlCategorias);
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

// Consultar linhas baseadas na categoria selecionada
$sqlLinhas = "
    SELECT id, nome FROM linhas WHERE id_categoria = :id_categoria ORDER BY nome;
";
$stmtLinhas = $db->prepare($sqlLinhas);
$stmtLinhas->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
$stmtLinhas->execute();
$linhas = $stmtLinhas->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && $id_categoria > 0 && !empty($nome_produto) && !empty($descricao_produto)) {
    $imagem = $imagem_atual;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $file_name = $_FILES['imagem']['name'];
        $file_tmp = $_FILES['imagem']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $file_new_name = uniqid('', true) . '.' . $file_ext;
        $upload_path = 'uploads/' . $file_new_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            if ($imagem_atual && file_exists("uploads/" . $imagem_atual)) {
                unlink("uploads/" . $imagem_atual);
            }
            $imagem = $file_new_name;
        }
    }

    if ($id_produto > 0) {
        // Atualizar produto existente
        $sqlUpdate = "
            UPDATE produtos
            SET nome = :nome, id_categoria = :id_categoria, id_linha = :id_linha, descricao = :descricao, imagem = :imagem
            WHERE id = :id
        ";
        $stmtUpdate = $db->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':id', $id_produto, PDO::PARAM_INT);
    } else {
        // Inserir novo produto
        $sqlInsert = "
            INSERT INTO produtos (nome, id_categoria, id_linha, descricao, imagem)
            VALUES (:nome, :id_categoria, :id_linha, :descricao, :imagem)
        ";
        $stmtUpdate = $db->prepare($sqlInsert);
    }

    $stmtUpdate->bindParam(':nome', $nome_produto);
    $stmtUpdate->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':id_linha', $id_linha, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':descricao', $descricao_produto);
    $stmtUpdate->bindParam(':imagem', $imagem);

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Produto " . ($id_produto > 0 ? "atualizado" : "inserido") . " com sucesso!'); window.location.href='Produtos.php';</script>";
    } else {
        echo "<script>alert('Erro ao " . ($id_produto > 0 ? "atualizar" : "inserir") . " o produto.'); window.location.href='Produtos.php';</script>";
    }
}
?>

<div class="container">
    <label><?php echo $id_produto > 0 ? 'EDITAR PRODUTO' : 'CRIAR PRODUTO'; ?></label>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome_produto">NOME DO PRODUTO</label>
            <input type="text" class="form-control" id="nome_produto" name="nome_produto" value="<?php echo htmlspecialchars($nome_produto); ?>" placeholder="DIGITE O NOME DO PRODUTO" required style="text-transform: uppercase;">
        </div>

        <div class="mb-3">
            <label for="id_categoria">SELECIONE A CATEGORIA</label>
            <select name="id_categoria" id="id_categoria" class="form-control" onchange="this.form.submit()">
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>" <?php echo ($categoria['id'] == $id_categoria) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_linha">SELECIONE A LINHA</label>
            <select name="id_linha" id="id_linha" class="form-control">
                <option value="">Selecione uma linha</option>
                <?php foreach ($linhas as $linha): ?>
                    <option value="<?php echo $linha['id']; ?>" <?php echo ($linha['id'] == $id_linha) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($linha['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="descricao_produto">DESCRIÇÃO DO PRODUTO</label>
            <textarea class="form-control" id="descricao_produto" name="descricao_produto" rows="5" placeholder="DIGITE A DESCRIÇÃO DO PRODUTO"><?php echo htmlspecialchars($descricao_produto); ?></textarea>
        </div>

        <div class="form-group">
            <label for="imagem">IMAGEM DO PRODUTO</label>
            <input type="file" class="form-control" id="imagem" name="imagem">
            <?php if ($imagem_atual): ?>
                <p>Imagem atual: <img src="uploads/<?php echo htmlspecialchars($imagem_atual); ?>" alt="Imagem atual" style="max-width: 200px; height: auto;"></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary"><?php echo $id_produto > 0 ? 'Atualizar Produto' : 'Cadastrar Produto'; ?></button>
    </form>
</div>

<?php include("footer.php"); ?>
