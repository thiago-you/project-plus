<?php 
$this->title = 'Home';
?>
<div class='site-index'>
	<section>
		<div class='card'>
			<div class='card-body'>
            	<h3 class='card-title'><i class='fa fa-user-plus'></i>&nbsp; Novos Clientes</h3>
            	<br>
            	<canvas id='card-cliente-chart' width="300" height="120"></canvas>
			</div>
		</div>
		<!-- ./card -->
	</section>
	<!-- ./container -->
    <div class='jumbotron'>
        <h3><i class='fa fa-cog fa-spin'></i>&nbsp; Aplicação ainda em desenvolvimento ...</h3>
    </div>
</div>
<?php
$js = <<< JS
    $(document).ready(function() {
        let ctx = document.getElementById('card-cliente-chart');
        let chartCliente = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto'],
                datasets: [{
                    label: ' Quantidade de Clientes',
                    data: [6, 4, 11, 5, 7, 10],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
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

$this->registerJs($js);