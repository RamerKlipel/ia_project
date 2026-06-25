<?php
use Core\html;
$arrPermCRUD = $this->getArrPermCrud();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->getNmPage() ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./public/css/style.css" >
    <link rel="icon" href="data:,">
    <?php foreach ($this->getArrCss() as $css): ?>
            <link rel="stylesheet" href="./public/css/<?= $css ?>.css" >
    <?php endforeach; ?>

</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <?php include_once "./view/navbar.view.php";?>
    <div class="inline-primary-div">
        <div class="inline-card-div box-shadow" >
            <div class="inline-title">
                <h3><?= $this->getNmPage() ?></h3>
            <?php if (($arrPermCRUD['c'] ?? false) && empty($this->action)): ?>
                <a href="<?= $this->server["REDIRECT_URL"]?>?action=c" class="btn btn-primary" style="margin: 10px 0;" type="button" name="btnCreate" title="Create" id="btnCreate">new registration</a>
            <?php endif; ?>
            <?php if (!empty($this->action)): ?>
                <a href="<?= $this->server["REDIRECT_URL"]?>" class="btn btn-primary" style="margin: 10px 0;" type="button" name="btnCreate" id="btnCreate">Voltar</a>
            <?php endif; ?>
            </div>
        <?php echo $this->getViewContent();?>
        </div>
    </div>

    <?php foreach ($this->getArrJs() as $js): ?>
        <?= $js ?>
    <?php endforeach; ?>
</body>
</html>
