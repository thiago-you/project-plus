<?php 
use yii\helpers\Url;
use yii\bootstrap\BootstrapAsset;
use app\base\Helper;

$this->title = 'Home';
?>
<div class="site-index">
	<section class="flex-container">
		<div class="card left">
			<div class="card-body">
            	<h3 class="card-title"><i class="fa fa-user-plus"></i>&nbsp; Novos Clientes</h3>
            	<br>
            	<?php if (!empty($novosClientes)): ?>
                	<canvas id="card-cliente-chart" width="430" height="180"></canvas>
                	<!-- ./grafico -->
                	<div class="card-cliente-content hidden">
                		<?php if (isset($novosClientes) && is_array($novosClientes)): ?>
                			<?php foreach ($novosClientes as $cliente): ?>
                				<span class="novos-clientes mes-<?= $cliente['mes'] ?>" data-quant="<?= $cliente['quant']; ?>"></span>
                			<?php endforeach; ?>
                		<?php endif; ?>
                	</div>
                	<!-- ./data do grafico -->
            	<?php else: ?>
            		<h4 class="text-center font16">
            			<i class="fa fa-user-circle fa-fw fa-2x"></i>
            			<br><br>
            			Sem novos clientes no últimos 5 meses
        			</h4>
            	<?php endif; ?>
			</div>
		</div>
		<!-- ./card clientes -->
		<div class="card card-agendamento">
			<div class="card-header">
				<h3 class="card-title"><i class="fa fa-comment-alt"></i>&nbsp; Agendamentos</h3>
			</div>
			<!-- ./card-header -->
			<div class="card-body">
            	<?php if (!empty($agendamentos)): ?>
					<?php foreach ($agendamentos as $agendamento): ?>
						<div class="row">
							<?php if ($agendamento == $agendamentos[0] && $agendamento->data_agendamento <= date('Y-m-d 23:59:59')): ?>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
									<small>
										<b>Agendamentos para Hoje</b>
									</small>
        						</div>
        						<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 text-right">
            						<small>
            							<b>Data:</b>&nbsp; <?= Helper::dateMask($agendamento->data_agendamento, Helper::DATE_DATETIME); ?>
            						</small>
        						</div>
							<?php elseif (!$labelProximoAgendamento && $agendamento->data_agendamento > date('Y-m-d 23:59:59')): ?>
								<?php $labelProximoAgendamento = true; ?>
								<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
									<hr>
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
    								<small>
    									<b>Próximos Agendamentos</b>
    								</small>
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 text-right">
            						<small>
            							<b>Data:</b>&nbsp; <?= Helper::dateMask($agendamento->data_agendamento, Helper::DATE_DATETIME); ?>
            						</small>
        						</div>
    						<?php else: ?>
    							<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 text-right">
            						<small>
            							<b>Data:</b>&nbsp; <?= Helper::dateMask($agendamento->data_agendamento, Helper::DATE_DATETIME); ?>
            						</small>
        						</div>
							<?php endif; ?>
						</div>
						<!-- ./row -->
						<div class="item">
							<small>
								<b>Tipo:</b>&nbsp; <i><?= $agendamento->getTipo(); ?></i>
        						<?php if (!empty($agendamento->subtipo)): ?>
            						&nbsp; | &nbsp;
            						<b>Subtipo:</b>&nbsp; <i><?= $agendamento->getSubtipo(); ?></i>
        						<?php endif; ?>
    						</small>	
    						<br><br>
    						<p><?= $agendamento->descricao; ?></p>								
						</div>		
						<!-- ./item -->					
					<?php endforeach; ?>
            	<?php else: ?>
            		<h4 class="text-center font16">
            			<i class="fa fa-comment-slash fa-fw fa-2x"></i>
            			<br><br>
            			Sem novos agendamentos
        			</h4>
            	<?php endif; ?>
			</div>
		</div>
		<!-- ./card agendamentos -->
	</section>
	<!-- ./container -->
</div>
<?php
$js = <<< JS
    $(document).ready(function() {
        // seleciona os elementos que vão conter os gráfcos
        const ctx = document.getElementById('card-cliente-chart');

        // monsta a lista dos ultimos 5 meses
        const months = {1: 'Janeiro', 2: 'Fevereiro', 3: 'Março', 4: 'Abril', 5: 'Maio', 6: 'Junho', 7: 'Julho', 8: 'Agosto', 9: 'Setembro', 10: 'Outubro', 11: 'Novembro', 12: 'Dezembro'};
        const today = new Date();
        const lastMonthsLabels = [];
        const lastMonthsQuant = [];

        // lop para montar a lista dos ultimos seis meses
        for (let i = 4; i >= 0; i--) {
            const temp = new Date(today.getFullYear(), today.getMonth() - i, 1);
            lastMonthsLabels.push(months[temp.getMonth()+1]);

            // pega a quantidade do mes
            const mesQaunt = $('.novos-clientes.mes-'+(temp.getMonth()+1)).data('quant');

            // valida a quantidade
            if (mesQaunt != undefined && mesQaunt > 0) {
                lastMonthsQuant.push(mesQaunt);
            } else {
                lastMonthsQuant.push(0);
            }
        }

        let chartCliente = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: lastMonthsLabels,
                datasets: [{
                    label: ' Quantidade de Clientes',
                    data: lastMonthsQuant,
                    backgroundColor: [
                        'rgba(38, 222, 129, 0.8)',
                        'rgba(136, 84, 208, 0.8)',
                        'rgba(255, 165, 2, 0.8)',
                        'rgba(255, 71, 87, 0.8)',
                        'rgba(0, 168, 255, 0.8)',
                        'rgba(85, 239, 196, 0.8)'
                    ],
                    borderColor: [
                        'rgb(38, 222, 129)',
                        'rgb(136, 84, 208)',
                        'rgb(255, 165, 2)',
                        'rgb(255, 71, 87)',
                        'rgb(0, 168, 255)',
                        'rgb(85, 239, 196)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        },
                        gridLines: {
                            color: "rgba(0, 0, 0, 0)",
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: "rgba(0, 0, 0, 0)",
                        }
                    }]
                }
            }
        });
    });
JS;
// JS
$this->registerJs($js);
// CSS
$this->registerCssFile(Url::home().'app/css/dashboard.css', ['depends' => [BootstrapAsset::className()]]);
?>


