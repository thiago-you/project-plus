<?php
use app\base\Util;

$this->title = 'Cadastrar Colaborador';
$this->params['breadcrumbs'][] = ['label' => 'Colaborador', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Cadastrar';
?>
<div class="colaborador-create">
    <?= $this->render('_form', [
        	'model'     => $model,
        	'modelUser' => $modelUser,
        	'cidades'   => [],
        	'empresas'  => $empresas,
        	'class'     => Util::CLASS_CREATE,
            'widgets'   => $widgets,
        ]);
    ?>
</div>

