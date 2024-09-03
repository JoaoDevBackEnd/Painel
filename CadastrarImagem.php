<?php 
	include_once("header.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_imagem = trim($_POST['nome_imagem']);
    $status = 'N';

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $file_name = $_FILES['imagem']['name'];
        $file_tmp = $_FILES['imagem']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $file_new_name = uniqid('', true) . '.' . $file_ext;
        $upload_path = 'uploads/' . $file_new_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $sql = "INSERT INTO imagens (nome, imagem, status) VALUES (:nome_imagem, :imagem, :status)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nome_imagem', $nome_imagem);
            $stmt->bindParam(':imagem', $file_new_name);
            $stmt->bindParam(':status', $status);

            if ($stmt->execute()) {
                echo "Imagem cadastrada com sucesso!";
            } else {
                echo "Erro ao cadastrar a imagem no banco de dados.";
            }
        }
    } 
}
?>
<div class="container">
    <label>SALVAR IMAGEM</label>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome_imagem">NOME DA IMAGEM</label>
            <input type="text" class="form-control" id="nome_imagem" name="nome_imagem" placeholder="DIGITE O NOME DA CATEGORIA" required style="text-transform: uppercase;">
        </div>
        <div class="form-group">
            <label for="imagem">Selecione a imagem:</label>
            <input type="file" class="form-control" id="imagem" name="imagem" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>
<?php include_once("footer.php"); ?>
