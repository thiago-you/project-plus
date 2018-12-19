<?php
$this->title = 'Cadastrar Novo Tipo de Contrato';
$this->params['breadcrumbs'][] = ['label' => 'Contrato Tipos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contrato-tipo-create">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
