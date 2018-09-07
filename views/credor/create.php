<?php
$this->title = 'Cadastrar Credor';
$this->params['breadcrumbs'][] = ['label' => 'Credores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-create">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
