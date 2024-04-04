vm = new Vue({
    el: '#app',
    data: {},
    mounted() {
        this.atualizarDashboards();
    },
    methods: {

        atualizarDashboards() {
            $.ajax({
                type: 'POST',
                url: urlBackEnd + 'index.php',
                data: {
                    classe: 'Dashboard',
                    funcao: 'buscaTotalVendasMes'
                },
                success: function(data) {
                    $('#totalVendasMes').text("R$" + data);
                }
            });

            $.ajax({
                type: 'POST',
                url: urlBackEnd + 'index.php',
                data: {
                    classe: 'Dashboard',
                    funcao: 'buscaTotalVendasUltimos7Dias'
                },
                success: function(data) {
                    $('#totalVendasUltimos7Dias').text("R$" + data);
                }
            });

            $.ajax({
                type: 'POST',
                url: urlBackEnd + 'index.php',
                data: {
                    classe: 'Dashboard',
                    funcao: 'buscaTotalVendasHoje'
                },
                success: function(data) {
                    $('#totalVendasHoje').text("R$" + data);
                }
            });

            $.ajax({
                type: 'POST',
                url: urlBackEnd + 'index.php',
                data: {
                    classe: 'Dashboard',
                    funcao: 'buscaVendasMesAMes'
                },
                success: function(data) {
                    var dados = JSON.parse(data);
                    const mesesNomes = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

                    const meses = dados.map(function(item) {
                        return mesesNomes[item.mes - 1];
                    });

                    var valores = dados.map(function(item) {
                        return parseFloat(item.total);
                    });

                    var ctx = document.getElementById('ChartMesAMes').getContext('2d');
                    var ChartMesAMes = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: meses,
                            datasets: [{
                                label: 'Vendas Mês a Mês',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                data: valores,
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return "R$" + value.toFixed(2);
                                        }
                                    }
                                }
                            },
                            maintainAspectRatio: false,
                            responsive: true
                        }
                    });
                }
            });
        },
    }
});