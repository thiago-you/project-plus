<?php
$this->title = 'Cadastrar Novo Cliente';
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cliente-create">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
