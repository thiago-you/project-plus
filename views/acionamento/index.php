<?php 
use app\base\Helper;
use app\models\Colaborador;
use app\models\Acionamento;
use app\models\Negociacao;
?>
<?php foreach ($acionamentos as $acionamento): ?>
	<div class="acionamento-box">
		<div class="acionamento-values hidden">
			<input class="tipo" value="<?= $acionamento->tipo; ?>"/>
			<input class="subtipo" value="<?= $acionamento->subtipo; ?>"/>
			<input class="data-agendamento" value="<?= $acionamento->data_agendamento; ?>"/>
			<input class="colab-agendamento" value="<?= $acionamento->colaborador_agendamento; ?>"/>
			<input class="descricao" value="<?= $acionamento->descricao; ?>"/>
		</div>
		<!-- ./guarda os values do acionamento -->
		<div class="acionamento-body">
    		<div class="row">
    			<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
    				<span class="dropdown">
						<button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        	<i class="fa fa-chevron-down"></i>&nbsp; Ações
                      	</button>
                      	<span class="dropdown-menu" aria-labelledby="dropActions">
                      		<?php if ($acionamento->subtipo == Acionamento::SUBTIPO_NEGOCIACAO): ?>
                  				<span class="action-faturar-negociacao dropdown-item" data-action="faturar">
                  					<?= $negociacao->status == Negociacao::STATUS_FECHADA ? 'Faturar' : 'Estornar'; ?> Negociação	
                  				</span>
                            	<span class="action-quebrar-negociacao dropdown-item" data-action="quebrar">Quebrar Negociação</span>
                            	<span class="action-fechar-negociacao dropdown-item" data-action="fechar">Fechar Negociação</span>
                        	<?php endif; ?>
                        	<?php if ($acionamento->tipo != Acionamento::TIPO_SISTEMA && \Yii::$app->user->identity->cargo == Colaborador::CARGO_ADMINISTRADOR): ?>
                        		<span class="action-edit-acionamento dropdown-item" data-id="<?= $acionamento->id; ?>">Editar Acionamento</span>
                            	<span class="action-delete-acionamento dropdown-item" data-id="<?= $acionamento->id; ?>">Deletar Acionamento</span>
                        	<?php endif; ?>
                      	</span>
                    </span>
                    <!-- ./dropdown -->
					<small>
						<b>Tipo:</b>&nbsp; <i><?= $acionamento->getTipo(); ?></i>
						<?php if (!empty($acionamento->subtipo)): ?>
    						&nbsp; | &nbsp;
    						<b>Subtipo:</b>&nbsp; <i><?= $acionamento->getSubtipo(); ?></i>
						<?php endif; ?>
					</small>
    			</div>
    			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 text-right">
    				<br>
    				<small><?= Helper::dateMask($acionamento->data, Helper::DATE_DATETIME); ?></small>
    			</div>
    		</div>
    		<!-- ./row -->
    		<div class="row">
    			<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
    				<p><?= $acionamento->descricao; ?></p>    				
    				<?php if ($acionamento->colaboradorAgendamento): ?>
    					<p>
    						<b>OBS.:</b> Agendamento do próximo contato para o colaborador(a) <b>"<?= $acionamento->colaboradorAgendamento->nome; ?>"</b> na data <b><?= Helper::dateMask($acionamento->data_agendamento, Helper::DATE_DATETIME); ?></b>. 
    					</p>
    				<?php endif; ?>
    			</div>
    		</div>
    		<!-- ./row -->
		</div>
		<!-- ./acionamento-body -->
		<div class="acionamento-footer">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
					<small><b>Usuário:</b>&nbsp; <i><?= $acionamento->colaborador->nome; ?></i></small>
    			</div>
    		</div>
    		<!-- ./row -->
		</div>
		<!-- ./acionamento-footer -->
	</div>
<?php endforeach; ?>