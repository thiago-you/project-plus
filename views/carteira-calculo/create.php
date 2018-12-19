<?php
$this->title = 'Cadastrar Faixa de Cálculo';
$this->params['breadcrumbs'][] = ['label' => 'Carteira Calculos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carteira-calculo-create">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
