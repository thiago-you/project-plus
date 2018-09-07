<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Credor */

$this->title = 'Create Credor';
$this->params['breadcrumbs'][] = ['label' => 'Credors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
