<?php

use app\base\Util;
use yii\bootstrap\Modal;

 /* @var $this yii\web\View */
 /* @var $model app\models\Clientes */

 $this->title = 'Alterar Cliente: '.$model->id_cliente;
 $this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
 $this->params['breadcrumbs'][] = 'Alterar';
?>
<div class="clientes-update">
	
	<?php
        Modal::begin([
            		'id' => 'modal_compras',
            		'size' => 'modal-lg',
        ]);
            
            echo "<div id='modalCompras'></div>";
        
        Modal::end();
    ?>

    <?= $this->render('_form', [
            'model' => $model,
        	'erros' => $erros,
            'errotab' => $errotab,
            'class' => Util::CLASS_UPDATE,
        ]);
    ?>

</div>
