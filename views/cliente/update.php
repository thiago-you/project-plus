<?php
$this->title = 'Alterar Cliente: ' . $model->id_cliente;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_cliente, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Alterar';
?>
<div class="cliente-update">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
