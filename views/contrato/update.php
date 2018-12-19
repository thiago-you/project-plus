<?php
$this->title = 'Alterar Contrato: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contratos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contrato-update">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
