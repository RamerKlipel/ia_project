<?php
use Core\html;
?>
<link rel="stylesheet" href="./public/css/error.css">
<link rel="icon" href="data:,">

<div id="full-error-screen" class="error-overlay">
    <div class="error-box">
        <div class="error-header">
            <div class="error-code-badge">Erro <span id="e-code"> <?= $code ?></span></div>
            <h2>Falha no Sistema</h2>
        </div>

        <div class="error-body">
            <p id="e-message" class="error-message"> <?= $arrInfo['message'] ?></p>

            <div class="error-file-info">
                <span><strong>Arquivo:</strong> <span id="e-file"><?= $arrInfo['file'] ?></span></span>
                <span><strong>Linha:</strong> <span id="e-line"><?= $arrInfo['line'] ?></span></span>
            </div>

            <div class="error-trace-wrapper">
                <strong>Stack Trace:</strong>
                <pre id="e-trace" class="error-trace"><?= htmlspecialchars($arrInfo['trace']) ?></pre>
            </div>

            <div class="center-flex">
                <?= html::addButton('button', "modalConfirmBtn", "Ok", ['class' => "btn-error"]); ?>
            </div>
        </div>
    </div>
</div>
