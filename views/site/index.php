<?php 
$this->title = 'Home';
?>
<div class="site-index">
	<section>
		<div class="card">
			<div class="card-body">
            	<h3 class="card-title"><i class="fa fa-user-plus"></i>&nbsp; Novos Clientes</h3>
            	<br>
            	<canvas id="card-cliente-chart" width="300" height="120"></canvas>
            	<!-- ./grafico -->
            	<div class="card-cliente-content hidden"></div>
            	<!-- ./data do grafico -->
			</div>
		</div>
		<!-- ./card -->
	</section>
	<!-- ./container -->
</div>
<?php
$js = <<< JS
    $(document).ready(function() {
        // seleciona os elementos que vão conter os gráfcos
        const ctx = document.getElementById('card-cliente-chart');

        // monsta a lista dos ultimos 5 meses
        const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        const today = new Date();
        const lastMonths = [];

        // lop para montar a lista dos ultimos seis meses
        for (let i = 5; i >= 0; i--) {
            let temp = new Date(today.getFullYear(), today.getMonth() - i, 1);
            lastMonths.push(months[temp.getMonth()]);
        }

        let chartCliente = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: lastMonths,
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