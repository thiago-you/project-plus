<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ContratoParcela */

$this->title = 'Create Contrato Parcela';
$this->params['breadcrumbs'][] = ['label' => 'Contrato Parcelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contrato-parcela-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
