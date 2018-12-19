<?php
$this->title = 'Create Colaborador';
$this->params['breadcrumbs'][] = ['label' => 'Colaboradors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="colaborador-create">
	<?= $this->render('_form', [
	        'model' => $model,
	    ]);
    ?>
</div>
