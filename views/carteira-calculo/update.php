<?php
$this->title = 'Alterar Faixa de Cálculo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Carteira Calculos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="carteira-calculo-update">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
