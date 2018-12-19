<?php
$this->title = 'Cadastrar Carteira';
$this->params['breadcrumbs'][] = ['label' => 'Carteiraes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carteira-create">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
