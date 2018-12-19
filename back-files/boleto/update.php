<?php
$this->title = 'Alterar Boleto Avulso: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Boletos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';

?>

<div class="boleto-update">
    <?= $this->render('_form', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    ?>
</div>
