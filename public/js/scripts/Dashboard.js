vm = new Vue({
    el: '#app',
    data: {},
    mounted() {
        this.atualizarVendasMes();
        this.atualizarVendasSemana();
        this.atualizarVendasHoje();
        this.atualizarVendasMesaMes();
    },
    methods: {

        atualizarVendasMes() {

            fetch(`/dashboardVendasMes`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao obter as Vendas Mês.');
                }
                return response.json();
            })
            .then(data => {
                $('#totalVendasMes').text("R$" + data.total_vendas_mes);
            })
            .catch(error => {
                console.error(error);
            });

        },
        atualizarVendasSemana() {

            fetch(`/dashboardVendasSemana`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao obter as Vendas Semana.');
                }
                return response.json();
            })
            .then(data => {
                $('#totalVendasUltimos7Dias').text("R$" + data.total_vendas_ultimos_7_dias);
            })
            .catch(error => {
                console.error(error);
            });

        },
        atualizarVendasHoje() {

            fetch(`/dashboardVendasHoje`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao obter as Vendas Hoje.');
                }
                return response.json();
            })
            .then(data => {
                $('#totalVendasHoje').text("R$" + data.total_vendas_hoje);
            })
            .catch(error => {
                console.error(error);
            });

        },
        atualizarVendasMesaMes() {

            fetch(`/dashboardVendasMesaMes`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao obter as Vendas Mês a Mês.');
                }
                return response.json();
            })
            .then(data => {
                var dados = data;
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
            })
            .catch(error => {
                console.error(error);
            });

        },
    }
});