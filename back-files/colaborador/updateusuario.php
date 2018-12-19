<?php
use app\base\Util;

$this->title = 'Alterando Dados Pessoais';
$this->params['breadcrumbs'][] = ['label' => 'Colaboradores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="colaborador-update-perfil">
    <?= $this->render('_form', [
        	'model'        => $model,
        	'modelUser'    => $modelUser,
        	'cidades'      => $cidades,
            'class'        => Util::CLASS_UPDATE,
            'widgets'      => $widgets,
            'updatePerfil' => true, // alteracao no perfil do proprio usuario logado (nao exibe todas as info)
        ]);
    ?>
</div>