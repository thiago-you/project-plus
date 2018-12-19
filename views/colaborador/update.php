<?php
$this->title = 'Update Colaborador: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Colaboradors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="colaborador-update">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
