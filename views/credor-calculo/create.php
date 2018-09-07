<?php
$this->title = 'Cadastrar Faixa de Cálculo';
$this->params['breadcrumbs'][] = ['label' => 'Credor Calculos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-calculo-create">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
