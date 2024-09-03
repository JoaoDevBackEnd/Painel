<?php include("header.php"); ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Galeria de Imagens</h2>
    <div class="row">
        <?php       
        $sql = "SELECT imagem FROM imagens WHERE status = 'Y'";
        $stmt = $db->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="uploads/' . htmlspecialchars($row['imagem']) . '" class="card-img-top" alt="' . '">
                </div>
            </div>';
        }
        ?>
    </div>
</div>

<?php include("footer.php"); ?>
