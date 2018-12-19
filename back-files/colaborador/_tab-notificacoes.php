<?php 
use app\models\NotificacaoTipo;
use kartik\switchinput\SwitchInput;
use app\models\ColaboradorNotificacao;

$notificacoes_tipo = NotificacaoTipo::findAtivas();
?>
<fieldset>
	<legend>Notificações</legend>
	<div class="row">
		<div class="col-md-12">
			<p class="well">
				<b>°</b> Selecione os tipos de notificações que desejá receber. <br/>
				<b>°</b> O tipo "Global" e "Geral" não podem ser desativados. <br/>
			</p>
		</div>
	</div>
	<!-- ./header -->
	<div class="row">
		<div class="col-md-12 tour12">
        	<div class="row">
        		<div class="col-md-12">
        			<div class="form-group">
        				<label class="control-label"> Marcar/Desmarcar Todos </label>
        	   			<?= SwitchInput::widget([
        	   			        'name' => "toggleAllSwitch",
        	   			        'value' => false,
        		   			    'pluginOptions' => [
        		   			        'onText' => '<i class="fa fa-check"></i>',
        		   			        'offText' => '<i class="fa fa-times"></i>',
        		   			    ],
            	   			    'options' => [
            	   			        'class' => 'toggleAllSwitch',
            	   			    ],
        		   			    'pluginEvents' => [
        		   			        'switchChange.bootstrapSwitch' => "function() { 
                                        let state;
                                        if ($(this).parents('.bootstrap-switch-on').length == 1) {
                                            state = true;
                                        } else {
                                            state = false;
                                        }
                                        $('.tipo-switch').each(function() {
                                            $(this).bootstrapSwitch('state', state, true);
                                        }); 
                                    }",    
        		   			    ],
        	   			    ]);
        	   			?>
        	   			<p class="hint-block"><?= $tipo->observacao; ?></p>
        			</div>
        		</div>
        	</div>
        	<!-- ./button toggle all -->
        	<hr/>
        	<div class="row">			
        		<?php foreach($notificacoes_tipo as $tipo): ?>
        			<div class="col-md-3">
        				<div class="form-group">
        					<label class="control-label"> <?= $tipo->descricao; ?> </label>
        		   			<?= SwitchInput::widget([
        		   			        'name' => "NotificacaoTipo[{$tipo->id}]",
        		   			        'value' => 
        		   			            ($tipo->id == NotificacaoTipo::TIPO_GLOBAL || $tipo->id == NotificacaoTipo::TIPO_GERAL) ? true : 
        		   			            (
        		   			                ColaboradorNotificacao::getColaboradorNotficacao($model->id, $tipo->id, 'ativo')->ativo == 'S'
        		   			                ? true : false
        	   			                ),
            		   			    'pluginOptions' => [
            		   			        'onText' => 'Sim',
            		   			        'offText' => 'Não',
            		   			    ],
            		   			    'options' => [
            		   			        'class' => ($tipo->id == NotificacaoTipo::TIPO_GLOBAL || $tipo->id == NotificacaoTipo::TIPO_GERAL) ? ' switch-disabled' : 'tipo-switch',
            		   			    ],
            		   			    'disabled' => ($tipo->id == NotificacaoTipo::TIPO_GLOBAL || $tipo->id == NotificacaoTipo::TIPO_GERAL) ? true : false,
            		   			    'pluginEvents' => [
            		   			        'switchChange.bootstrapSwitch' => "function() { 
                                            if ($(this).parents('.bootstrap-switch-off').length == 1) {
                                                $('.toggleAllSwitch').bootstrapSwitch('state', false, true);
                                            } else {
                                                if($('.bootstrap-switch-off .tipo-switch').length == 0) {
                                                    $('.toggleAllSwitch').bootstrapSwitch('state', true, true);
                                                }
                                            }
                                        }",    
            		   			    ],
        		   			    ]);
        		   			?>
        		   			<p class="hint-block"><?= $tipo->observacao; ?></p>
        				</div>
        			</div>
        		<?php endforeach; ?>
        	</div>
        	<!-- ./row - tipos de notificacoes -->
    	</div>
	</div>
	<!-- ./row tour -->
</fieldset>
<!-- ./fieldset -->