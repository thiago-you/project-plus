<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ContratoTipo */

$this->title = 'Create Contrato Tipo';
$this->params['breadcrumbs'][] = ['label' => 'Contrato Tipos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contrato-tipo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
