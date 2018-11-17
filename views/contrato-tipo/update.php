<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ContratoTipo */

$this->title = 'Update Contrato Tipo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contrato Tipos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contrato-tipo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
