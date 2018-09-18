<?php
use app\base\Util;

$this->title = "Alterar Colaborador: {$model->id}";
$this->params['breadcrumbs'][] = ['label' => 'Colaboradores', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Alterar';
?>
<div class="colaborador-update">
    <?= $this->render('_form', [
        	'model'     => $model,
        	'modelUser' => $modelUser,
        	'cidades'   => [],
        	'empresas'  => $empresas,
        	'class'     => Util::CLASS_UPDATE,
            'widgets'   => $widgets,
        ]);
    ?>
</div>