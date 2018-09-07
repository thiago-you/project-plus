<?php
$this->title = 'Alterar Credor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Credores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="credor-update">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
