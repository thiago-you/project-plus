<?php
use yii\web\View;
use app\base\Util;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CredorCampanha;
use yii\helpers\ArrayHelper;

$this->title = 'Configuração de Cálculo';
$this->params['breadcrumbs'][] = ['label' => 'Credores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-primary panel-box">
		<div class="panel-body">
      		<?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
        	<!-- ./hidden id_credor -->
      		<div class="row">
      			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                	<?= $form->field($model, 'id_campanha')->dropDownList(ArrayHelper::map($model->credorCampanhas, 'id', 'nome'), [
        	                'prompt' => 'Selecione a Campanha ...',
                    	]); 
                	?>
    			</div>
    			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <?= Html::label('Ações'); ?>
                    <div class="form-group">
                    	<?= Html::button('<i class="fa fa-eye fa-fw"></i>', [
            	                'id' => 'btn-view-campanha',
            	                'class' => Util::BTN_COLOR_INFO.' campanha-action-button',
            	                'disabled' => $model->id_campanha ? false : true,
            	                'title' => 'Visualizar Campanha',
            	                'data-toggle' => 'tooltip',
                        	]); 
                    	?>
                    	<?= Html::button('<i class="fa fa-plus fa-fw"></i>', [
            	                'id' => 'btn-nova-campanha',
            	                'class' => Util::BTN_COLOR_EMERALD,
            	                'title' => 'Cadastrar Nova Campanha',
            	                'data-toggle' => 'tooltip',
                        	]); 
                    	?>
                    	<?= Html::button('<i class="fa fa-edit fa-fw"></i>', [
            	                'id' => 'btn-editar-campanha',
            	                'class' => Util::BTN_COLOR_WARNING.' campanha-action-button',
    	                       'disabled' => $model->id_campanha ? false : true,
            	                'title' => 'Alterar Campanha',
            	                'data-toggle' => 'tooltip',
                        	]); 
                    	?>
                    	<?= Html::button('<i class="fa fa-times fa-fw"></i>', [
            	                'id' => 'btn-deletar-campanha',
            	                'class' => Util::BTN_COLOR_DANGER.' campanha-action-button',
            	                'disabled' => $model->id_campanha ? false : true,
            	                'title' => 'Excluír Campanha',
            	                'data-toggle' => 'tooltip',
                    	   ]);
                    	?>
                    </div>
    			</div>
    			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
    				<?= Html::label('Cálculo'); ?>
        			<?= Html::dropDownList('CredorCampanha[tipo]', null, [
                            CredorCampanha::CALCULO_A_VISTA => 'À Vista',
    		                CredorCampanha::CALCULO_PARCELADO => 'Parcelado',
        			    ], [
        	                'id' => 'credor-campanha-tipo',
                	        'class' => 'form-control', 
                        ]); 
                	?>
            	</div>
      		</div>
      		<!-- ./row -->
      		<div class="row">
      			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
      				<?= Html::button('<i class="fa fa-plus"></i>&nbsp; Nova Faixa', [
    		                'id' => 'nova-faixa', 
    		                'class' => Util::BTN_COLOR_EMERALD.' btn-block campanha-action-button',
                            'disabled' => $model->id_campanha ? false : true,
      				   ]); 
      				?>
      			</div>
      		</div>
      		<div class="row">
      			<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
      				<div id="lista-faixas">
						<?= $this->render('/credor-calculo/index', [
				                'model' => $model->id_campanha ? $model->credorCampanha->credorCalculos : [],        
                            ]); 
						?>
      				</div>
      				<!-- ./lista de faixas -->
      			</div>
      		</div>
      		<!-- ./row -->
        </div>
        <!-- ./painel-body -->
        <div class="panel-footer">
    		<div class="row">
    			<div class="col-md-3">
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-save"></i>&nbsp; Salvar', [
                                'class' => Util::BTN_COLOR_SUCCESS.' btn-block'
                            ]);
                        ?>
                    </div>
    			</div>
    			<div class="col-md-3 pull-right">
                    <div class="form-group">
                        <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/credor'], [
                                'class' => Util::BTN_COLOR_DEFAULT.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    		</div>
        </div>
        <!-- ./panel-footer -->
	</div>
	<!-- ./box -->
<?php ActiveForm::end(); ?>
<!-- ./form -->
<div id="modal-campanha" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    			<h4 class="modal-title">Campanha</h4>
  			</div>
  			<!-- ./modal-header -->
  			<div class="modal-body">
    			<br><br><h1 class="text-center text-primary"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando</h1><br><br>
  			</div>
  			<!-- ./modal-body -->
		</div>
	</div>
</div>
<!-- /.modal campanha -->
<div id="modal-faixa-calculo" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    			<h4 class="modal-title">Faixa de Cálculo</h4>
  			</div>
  			<!-- ./modal-header -->
  			<div class="modal-body">
    			<br><br><h1 class="text-center text-primary"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando</h1><br><br>
  			</div>
  			<!-- ./modal-body -->
		</div>
	</div>
</div>
<!-- /.modal faixa de calculo -->
<?php 
$script = <<<JS
$(document).ready(function() {
    // insere a animação quando a modal for dechada
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('.modal-body').html('<br><br><h1 class="text-center text-primary"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando</h1><br><br>');
    });

    // abre a modal de nova campanha
    $('body').on('click', '#btn-nova-campanha', function() {
        // seleciona a modal
        let modal = $('#modal-campanha');        

        // exibe a modal
        modal.modal('show');

        // envia o post para renderizar o form
        $.post(BASE_PATH + 'credor-campanha/create', function(response) {
            modal.find('.modal-body').html(response).find('form input#credorcampanha-id_credor').val($('#credor-id').val());
        });
    });

    // abre a modal de edicao da campanha
    $('body').on('click', '#btn-editar-campanha', function() {
        // seleciona a modal
        let modal = $('#modal-campanha');        

        // exibe a modal
        modal.modal('show');

        // envia o post para renderizar o form
        $.post(BASE_PATH + 'credor-campanha/update?id='+$('#credor-id_campanha').val(), function(response) {
            modal.find('.modal-body').html(response);
        });
    });

    // abre a modal de visualizacao da campanha
    $('body').on('click', '#btn-view-campanha', function() {
        // seleciona a modal
        let modal = $('#modal-campanha');        

        // exibe a modal
        modal.modal('show');

        // envia o post para renderizar o form
        $.post(BASE_PATH + 'credor-campanha/view?id='+$('#credor-id_campanha').val(), function(result) {
            modal.find('.modal-body').html(result);
        });
    });

    // exclui uma campanha
    $('body').on('click', '#btn-deletar-campanha', function() {
        this.blur();
        let campanhaId = $('#credor-id_campanha').val();

        // mensagem de confirmacao
        $.confirm({
            content: 'Você deseja mesmo exclúir este item?',
            backgroundDismiss: true,
            buttons: {
				ok: { 
					action: function() {
                        $.post(BASE_PATH + 'credor-campanha/delete?id='+campanhaId, function(response) {
                            let data = JSON.parse(response);

                            // verifica se a campanha foi deletada
                            if (data.success == true) {
                                toastr.success('A campanha foi deletada com sucesso.');
                                $('#credor-id_campanha').val('').trigger('change').find('option[value='+campanhaId+']').remove();
                            } else {
                                toastr.error('Não foi possível excluír a campanha. Por favor, tente novamente mais tarde.');
                            }
                        }).fail(function() {
                            toastr.error('Não foi possível excluír a campanha. Por favor, tente novamente mais tarde.');
                        });
                    },
				},
			},
        });
    });

    // evento quando o select de campanha for alterado
    $('body').on('change', '#credor-id_campanha', function() {
        if (this.value != '') {
            $('.campanha-action-button').prop('disabled', false);
        } else {
            $('.campanha-action-button').prop('disabled', true);
        }    

        // monta os params
        let postData = {
            'id_credor': $('#credor-id').val(),
            'id_campanha': $('#credor-id_campanha').val(),
        };

        // envia a requisicao para atualizar a lista
        $('#credor-id_campanha').prop('disabled', true);
        $.post(BASE_PATH + 'credor/update-campanha', postData, function(response) {
            let data = JSON.parse(response);
            if (data.success == false) {
                toastr.error('Não foi possível atualizar o credor. Por favor, tente novamente mais tarde.');
            }

            // exibe animação de carregamento
            $('#lista-faixas').html('<br><br><h1 class="text-primary text-center"><i class="fa fa-spinner fa-spin"></i>&nbsp; Carregando...</h1><br><br>');

            // renderiza a lista de faixas
            $.get(BASE_PATH + 'credor-calculo/index?id='+$('#credor-id_campanha').val(), function(response) {
                $('#lista-faixas').html(response);
            });
        }).fail(function() {
            toastr.error('Não foi possível atualizar o credor. Por favor, tente novamente mais tarde.');
        }).always(function() {
            $('#credor-id_campanha').prop('disabled', false);
        });    
    });

    // registra a campanha por ajax
	$('body').on('submit', '#form-campanha', function(e) {
        e.preventDefault();			

        // pega a lista e o id            
        let action = $('#form-campanha').attr('action');
        let params = $('#form-campanha').serializeArray();

        // envia a requisicao para cadastrar a campanha
        $.post(action, params, function(response) {
            let data = JSON.parse(response);
            
            if (data.success == true) {
                if (data.newRecord == true) {
                    // seta a option no select
                    $('#credor-id_campanha').append('<option value="'+data.id+'">'+data.nome+'</option>').val(data.id).trigger('change');    
                } else {
                    // pega a option do select
                    let option = $('#credor-id_campanha').find('option[value='+data.id+']');
                    // altera o nome da option
                    option.text(data.nome);
                }

                // fecha a modal
                $('#modal-campanha').modal('hide');
                // exibe a mensagem de sucesso
                toastr.success('A campanha foi cadastrada com sucesso.');  
            } else {
                toastr.error('Não foi possível adicionar a campanha. Por favor, tente novamente mais tarde.');
            }
        }).fail(function() {
            toastr.error('Não foi possível adicionar a campanha. Por favor, tente novamente mais tarde.');
        });

        return false;
	});

    // cadastra uma nova faixa de calculo
    $('body').on('click', '#nova-faixa', function() {
        // busca e exibe a modal
        let modal = $('#modal-faixa-calculo'); 
        modal.modal('show');       
        
        // exibe a modal
        $.post(BASE_PATH + 'credor-calculo/create', function(response) {
            modal.find('.modal-body').html(response).find('form input#credorcalculo-id_campanha').val($('#credor-id_campanha').val());
        });
    });

    // registra a faixa de calculo por ajax
	$('body').on('submit', '#form-calculo', function(e) {
        e.preventDefault();			

        // pega a lista e o id            
        let action = $('#form-calculo').attr('action');
        let params = $('#form-calculo').serializeArray();

        // envia a requisicao para cadastrar a campanha
        $.post(action, params, function(response) {
            let data = JSON.parse(response);
            
            if (data.success == true) {
                // fecha a modal
                $('#modal-faixa-calculo').modal('hide');
                // exibe a mensagem de sucesso
                toastr.success('A faixa de cálculo foi cadastrada com sucesso.');  

                // exibe animação de carregamento
                $('#lista-faixas').html('<br><br><h1 class="text-primary text-center"><i class="fa fa-spinner fa-spin"></i>&nbsp; Carregando...</h1><br><br>');

                // renderiza a lista de faixas
                $.get(BASE_PATH + 'credor-calculo/index?id='+$('#credor-id_campanha').val(), function(response) {
                    $('#lista-faixas').html(response);
                });
            } else {
                toastr.error('Não foi possível adicionar a faixa de cálculo. Por favor, tente novamente mais tarde.');
            }
        }).fail(function() {
            toastr.error('Não foi possível adicionar a faixa de cálculo. Por favor, tente novamente mais tarde.');
        });

        return false;
	});
});
JS;
$this->registerJs($script, View::POS_LOAD);
?>




