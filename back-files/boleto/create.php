<?php
$this->title = 'Boleto Avulso';
$this->params['breadcrumbs'][] = ['label' => 'Boletos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="boleto-create">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
