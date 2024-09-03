<?php
include("header.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nome_categoria = '';
$status = 'N';
$estrutura = '';
$pintura = '';
$estofados = '';
$acabamento = '';
$resistencia = '';
$sobre_mim = '';

if ($id > 0) {
    $sql = "SELECT nome, status, estrutura, pintura, estofados, acabamento, resistencia, sobremim FROM categorias WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($categoria) {
        $nome_categoria = $categoria['nome'];
        $status = $categoria['status'];
        $estrutura = $categoria['estrutura'];
        $pintura = $categoria['pintura'];
        $estofados = $categoria['estofados'];
        $acabamento = $categoria['acabamento'];
        $resistencia = $categoria['resistencia'];
        $sobremim = $categoria['sobremim'];
    } else {
        header("Location: Categoria.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_categoria = trim($_POST['nome_categoria']);
    $estrutura = trim($_POST['estrutura']);
    $pintura = trim($_POST['pintura']);
    $estofados = trim($_POST['estofados']);
    $acabamento = trim($_POST['acabamento']);
    $resistencia = trim($_POST['resistencia']);
    $sobremim = trim($_POST['sobremim']);
    
    if (!empty($nome_categoria)) {
        if ($id > 0) {
            // Atualizar categoria existente
            $query = "UPDATE categorias SET nome = :nome, status = :status, estrutura = :estrutura, pintura = :pintura, estofados = :estofados, acabamento = :acabamento, resistencia = :resistencia, sobremim = :sobremim WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nome', $nome_categoria);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':estrutura', $estrutura);
            $stmt->bindParam(':pintura', $pintura);
            $stmt->bindParam(':estofados', $estofados);
            $stmt->bindParam(':acabamento', $acabamento);
            $stmt->bindParam(':resistencia', $resistencia);
            $stmt->bindParam(':sobremim', $sobremim);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Categoria atualizada com sucesso!</div>";
            } 
        } else {
            // Cadastrar nova categoria
            $query = "INSERT INTO categorias (nome, status, estrutura, pintura, estofados, acabamento, resistencia, sobremim) VALUES (:nome, :status, :estrutura, :pintura, :estofados, :acabamento, :resistencia, :sobremim)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nome', $nome_categoria);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':estrutura', $estrutura);
            $stmt->bindParam(':pintura', $pintura);
            $stmt->bindParam(':estofados', $estofados);
            $stmt->bindParam(':acabamento', $acabamento);
            $stmt->bindParam(':resistencia', $resistencia);
            $stmt->bindParam(':sobremim', $sobremim);
            
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Categoria cadastrada com sucesso!</div>";
            } 
        }
    } else {
        echo "<div class='alert alert-warning'>Por favor, preencha o nome da categoria.</div>";
    }
}
?>

<div class="container">
    <label><?php echo $id > 0 ? 'EDITAR CATEGORIA' : 'CRIAR CATEGORIA'; ?></label>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome_categoria">NOME DA CATEGORIA</label>
            <input type="text" class="form-control" id="nome_categoria" name="nome_categoria" placeholder="DIGITE O NOME DA CATEGORIA" value="<?php echo htmlspecialchars($nome_categoria); ?>" required style="text-transform: uppercase;">
        </div>
        <div class="form-group">
            <label for="estrutura">Estrutura</label>
            <input type="text" class="form-control" id="estrutura" name="estrutura" value="<?php echo htmlspecialchars($estrutura); ?>">
        </div>
        <div class="form-group">
            <label for="pintura">Pintura</label>
            <input type="text" class="form-control" id="pintura" name="pintura" value="<?php echo htmlspecialchars($pintura); ?>">
        </div>
        <div class="form-group">
            <label for="estofados">Estofados</label>
            <input type="text" class="form-control" id="estofados" name="estofados" value="<?php echo htmlspecialchars($estofados); ?>">
        </div>
        <div class="form-group">
            <label for="acabamento">Acabamento</label>
            <input type="text" class="form-control" id="acabamento" name="acabamento" value="<?php echo htmlspecialchars($acabamento); ?>">
        </div>
        <div class="form-group">
            <label for="resistencia">Resistência</label>
            <input type="text" class="form-control" id="resistencia" name="resistencia" value="<?php echo htmlspecialchars($resistencia); ?>">
        </div>
        <div class="form-group">
            <label for="sobre_mim">Sobre Mim</label>
            <textarea class="form-control" id="sobremim" name="sobremim" rows="4"><?php echo htmlspecialchars($sobremim); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? 'Atualizar' : 'Cadastrar'; ?></button>
    </form>
</div>

<?php include("footer.php"); ?>
