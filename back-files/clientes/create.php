<?php
use app\base\Util;

use yii\bootstrap\Modal;

 /* @var $this yii\web\View */
 /* @var $model app\models\Clientes */

 $this->title = 'Cadastrar Cliente';
 $this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
 $this->params['breadcrumbs'][] = 'Cadastrar';
 $modelFaturas = array();
?>

<div class="clientes-create">
	
	<?php
        Modal::begin([
            		'id' => 'modal',
            		'size' => 'modal-lg',
        ]);
        
            echo "<div id='modalContent'></div>";
        
        Modal::end();
    ?>
    
    
    <?= $this->render('_form', [
            'model' => $model,
        	'erros' => $erros,
            'errotab' => $errotab,
        	'class' => Util::CLASS_CREATE,
        ]);
    ?>

</div>
