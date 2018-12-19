<?php
$this->title = 'Alterar Cliente: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Alterar';
?>
<div class="cliente-update">
    <?= $this->render('_form', [
            'model' => $model,
    		'layout' => $layout,
            'estados' => $estados,
        ]);
    ?>
</div>
