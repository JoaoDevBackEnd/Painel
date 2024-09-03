<?php
include("header.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nome_representante = '';
$ddd = '';
$telefone = '';
$email = '';
$estado = '';
$cidade = '';
$status = 'N';

if ($id > 0) {
    $sql = "SELECT nome, ddd, telefone, email, estado, cidade FROM representantes WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $representante = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($representante) {
        $nome_representante = $representante['nome'];
        $ddd = $representante['ddd'];
        $telefone = $representante['telefone'];
        $email = $representante['email'];
        $estado = $representante['estado'];
        $cidade = $representante['cidade'];
    } else {
        header("Location: Categoria.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_representante = trim($_POST['nome_representante']);
    $ddd = trim($_POST['ddd']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $estado = trim($_POST['estado']);
    $cidade = trim($_POST['cidade']);
    
    if (!empty($nome_representante) && !empty($ddd) && !empty($telefone) && !empty($estado) && !empty($cidade)) {
        if ($id > 0) {
            // Atualizar representante existente
            $query = "UPDATE representantes SET nome = :nome, ddd = :ddd, telefone = :telefone, email = :email, estado = :estado, cidade = :cidade WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nome', $nome_representante);
            $stmt->bindParam(':ddd', $ddd);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':cidade', $cidade);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Representante atualizado com sucesso!</div>";
            }
        } else {
            // Cadastrar novo representante
            $query = "INSERT INTO representantes (nome, ddd, telefone, email, estado, cidade) VALUES (:nome, :ddd, :telefone, :email, :estado, :cidade)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nome', $nome_representante);
            $stmt->bindParam(':ddd', $ddd);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':cidade', $cidade);
            
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Representante cadastrado com sucesso!</div>";
            }
        }
    } else {
        echo "<div class='alert alert-warning'>Por favor, preencha todos os campos obrigatórios.</div>";
    }
}
?>

<div class="container">
    <h2><?php echo $id > 0 ? 'EDITAR REPRESENTANTE' : 'CRIAR REPRESENTANTE'; ?></h2>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome_representante">NOME DO REPRESENTANTE</label>
            <input type="text" class="form-control" id="nome_representante" name="nome_representante" placeholder="DIGITE O NOME DO REPRESENTANTE" value="<?php echo htmlspecialchars($nome_representante); ?>" required style="text-transform: uppercase;">
        </div>

        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="ddd">DDD</label>
                <input type="text" class="form-control" id="ddd" name="ddd" placeholder="DDD" value="<?php echo htmlspecialchars($ddd); ?>" required>
            </div>
            <div class="form-group col-md-4">
                <label for="telefone">TELEFONE</label>
                <input type="text" class="form-control" id="telefone" name="telefone" placeholder="DIGITE O TELEFONE" value="<?php echo htmlspecialchars($telefone); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email">EMAIL</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="DIGITE O EMAIL" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="estado">ESTADO</label>
                <select id="estado" name="estado">
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                    <option value="EX">Estrangeiro</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="cidade">CIDADE</label>
                <input type="text" class="form-control" id="cidade" name="cidade" placeholder="DIGITE O NOME DA CIDADE" value="<?php echo htmlspecialchars($cidade); ?>" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? 'Atualizar' : 'Cadastrar'; ?></button>
    </form>
</div>

<?php include("footer.php"); ?>
