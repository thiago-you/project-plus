<?php
$this->title = 'Alterar Carteira: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Carteiraes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="carteira-update">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
