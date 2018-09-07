<?php
$this->title = 'Create Credor';
$this->params['breadcrumbs'][] = ['label' => 'Credors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-create">
    <?= $this->render('_form', [
            'model' => $model,
        ]);
    ?>
</div>
