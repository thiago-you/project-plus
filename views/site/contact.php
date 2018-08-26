<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contato';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary panel-box">
    <div class="panel-body">
    	<h1><?= Html::encode($this->title) ?></h1>
        <p>
            Se você tem assuntos comerciais ou outras perguntas, favor preencha o formulário abaixo para entrar em contato.
            Obrigado.
        </p>
        <div class="row">
            <div class="col-md-5">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                    <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label('Nome'); ?>
                    <?= $form->field($model, 'email')->label('E-mail');?>
                    <?= $form->field($model, 'subject')->label('Assunto');?>
                    <?= $form->field($model, 'body')->textarea(['rows' => 6])->label('Conteúdo'); ?>
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                            'template' => '<div class="row"><div class="col-md-3">{image}</div><div class="col-md-6">{input}</div></div>',
                        ])->label('Captch');;
                    ?>
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-send"></i>&nbsp; Enviar', ['class' => 'btn btn-primary btn-flat', 'name' => 'contact-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
	</div>
	<!-- ./panel-body -->
</div>
<!-- ./panel -->
