<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CredorCampanha */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="credor-campanha-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_credor')->textInput() ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vigencia_inicial')->textInput() ?>

    <?= $form->field($model, 'vigencia_final')->textInput() ?>

    <?= $form->field($model, 'prioridade')->textInput() ?>

    <?= $form->field($model, 'por_parcela')->dropDownList([ 'S' => 'S', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'por_portal')->dropDownList([ 'S' => 'S', 'N' => 'N', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
