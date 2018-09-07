<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CredorCalculo */

$this->title = 'Create Credor Calculo';
$this->params['breadcrumbs'][] = ['label' => 'Credor Calculos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-calculo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
