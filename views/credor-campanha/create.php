<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CredorCampanha */

$this->title = 'Create Credor Campanha';
$this->params['breadcrumbs'][] = ['label' => 'Credor Campanhas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-campanha-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
