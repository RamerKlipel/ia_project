<?php
use Core\html;

$arrTable = $this->getArrTable();
$arrTh = $this->getArrTh();
$arrPermCRUD = $this->getArrPermCrud();
$arrPermIcon = $this->getArrPermIcon();
$arrPermTitle = $this->getArrPermTitle();
$arrData = $this->getArrData();
$idTable = "ID".strtoupper($this->model->getSqlTable());
?>

<table class="table table-bordered table-hover table-responsive table-striped">
    <thead>
    <?php if (!empty($arrTh)): ?>
        <?php foreach($arrTh as $th): ?>
            <th> <?= $th ?> </th>
        <?php endforeach; ?>
    <?php endif; ?>
        <th></th>
    </thead>
    <tbody>
        <?php if (!empty($arrData)): ?>
            <?php foreach($arrData as $key => $arrTableData): ?>
                <tr>
                    <?php foreach($arrTable as $key => $arrDados): ?>
                        <td <?= $arrTable[$key] ?? '' ?>><?= $arrTableData[$key] ?? '' ?></td>
                    <?php endforeach; ?>
                    <td>
                        <div class="btn-group-action">
                            <?php foreach($arrPermCRUD as $strCRUD => $perm): ?>
                                <?php if ($perm && $strCRUD != "c"): ?>
                                    <a id="btn<?= $arrPermTitle[$strCRUD] ?>" title="<?= $arrPermTitle[$strCRUD] ?>" class="btn-action btn" style="border: #00000045;" href="<?= $this->server["REDIRECT_URL"] ?>?action=<?= $strCRUD ?>&id=<?= $arrTableData[$idTable] ?>" data-action="<?= $strCRUD?>"><i class="<?= $arrPermIcon[$strCRUD]?>"></i></a>
                                <?php endif;?>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
