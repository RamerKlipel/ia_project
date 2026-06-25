
<?php
use Core\html;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="icon" href="data:,">
    <?php foreach ($this->getArrCss() as $css): ?>
            <link rel="stylesheet" href="./public/css/<?= $css ?>.css" >
    <?php endforeach; ?>
</head>
<body>
    <main class="chat-shell">
        <div class="chat-card">
            <section class="chat-header">
                <div>
                    <h1 class="badge"><i class="fa-solid fa-robot"></i>Chat</h1>
                </div>
            </section>

            <div class="chat-body">
                <div class="chat-main">
                    <div class="conversation" id="conversation"></div>

                    <section class="chat-footer">
                        <form action="<?= htmlspecialchars($this->server['REDIRECT_URL'] ?? '') ?>" method="post" class="chat-form">
                            <?= html::addInput('text', 'idMessage', '', [
                                'class' => 'form-control chat-input',
                                'placeholder' => 'Digite sua mensagem aqui...',
                                'aria-label' => 'Mensagem'
                            ], [
                                'class' => 'chat-input-wrapper'
                            ], '') ?>
                            <button id="idSubmitMessage" type="button" class="btn btn-primary btn-send"><i class="fa-solid fa-paper-plane"></i> Enviar</button>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.6/marked.min.js"></script>
    <?php foreach ($this->getArrJs() as $js):  ?>
        <?= $js ?>
    <?php endforeach; ?>
</body>
</html>
