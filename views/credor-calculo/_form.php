<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CredorCalculo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="credor-calculo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_credor')->textInput() ?>

    <?= $form->field($model, 'atraso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'multa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'juros')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo')->dropDownList([ 'V' => 'V', 'P' => 'P', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'parcela_num')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
