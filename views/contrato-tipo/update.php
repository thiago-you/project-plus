<?php
$this->title = 'Alterar Tipo de Contrato: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contrato Tipos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contrato-tipo-update">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
