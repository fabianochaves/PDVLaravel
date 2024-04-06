const urlObterPrecos = document.getElementById('app').dataset.obterPrecos;

vm = new Vue({
    el: '#app',
    data: {
        itens: [],
        Vendas: [],
        loading: false,
        produtoSelecionado: '',
        quantidade: '',
        precoUnitario: '',
        percentImposto: '',
        valorTotal: '',
        urlObterPrecos: urlObterPrecos
    },
    mounted() {
        console.log("Teste" + this.itens);
        this.listar();
    },
    methods: {

        verificarItensGrid: function () {

            var tabelaItensVenda = document.getElementById('tabela_itens_venda');
            var linhas = tabelaItensVenda.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            if (linhas.length === 0) {
                var tabelaVazia = 1;
            } else {
                var tabelaVazia = 0;
            }

            var form = new FormData(); // Cria um novo FormData
            form.append('tabela_vazia', tabelaVazia);
            form.append('produto', this.produtoSelecionado);
            form.append('quantidade', this.quantidade);
            form.append('valor_unitario', this.precoUnitario);
            form.append('percent_imposto', this.percentImposto);
            form.append('valor_total_produto', this.valorTotal);

            this.enviarItemVenda(form);
        },

        enviarItemVenda: function (form) {

            fetch(window.routes.cadastrarVenda, {
                method: 'POST',
                body: form,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao enviar dados para o controlador.');
                }
                return response.json();
            })
            .then(data => {

                this.listar(data.id_venda);
                console.log(data); // Exibe a resposta do controlador
            })
            .catch(error => {
                console.error(error);
            });
        },
        
        
        obterPrecos: function() {
            axios.post(this.urlObterPrecos, {
                    produto_id: this.produtoSelecionado,
                    quantidade: this.quantidade,
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    this.precoUnitario = response.data.preco_unitario;
                    this.percentImposto = response.data.imposto;
                    this.valorTotal = response.data.valor_total;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        

        formatarData(datetime) {
            const data = new Date(datetime);

            const dia = data.getDate().toString().padStart(2, '0');
            const mes = (data.getMonth() + 1).toString().padStart(2, '0');
            const ano = data.getFullYear();
            const hora = data.getHours().toString().padStart(2, '0');
            const minuto = data.getMinutes().toString().padStart(2, '0');
            const segundo = data.getSeconds().toString().padStart(2, '0');

            return `${dia}/${mes}/${ano} ${hora}:${minuto}:${segundo}`;
        },

        formatarNumero(numero) {
            const formatter = new Intl.NumberFormat('pt-BR', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
            return formatter.format(numero);
        },

        verItens(venda) {
            var classe = "Venda";
            var funcao = "verItens";

            jQuery.ajax({
                type: "POST",
                url: urlBackEnd + "index.php",
                data: { classe: classe, funcao: funcao, id_venda: venda.id_venda },
                success: function(data) {
                    if (data.status) {
                        var tableHTML = '<div class="table-responsive">' + // Adicione a classe table-responsive ao elemento pai
                            '<table class="table table-bordered table-striped">' +
                            '<thead>' +
                            '<tr>' +
                            '<th>ID</th>' +
                            '<th>Nome</th>' +
                            '<th>Quantidade</th>' +
                            '<th>Valor Unitário (R$)</th>' +
                            '<th>Total do Produto</th>' +
                            '<th>% Imposto</th>' +
                            '<th>Total de Imposto</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody>';

                        data.itens.forEach(function(item) {
                            tableHTML += '<tr>' +
                                '<td>' + item.cod_produto_venda + '</td>' +
                                '<td>' + item.nome_produto + '</td>' +
                                '<td>' + item.qtd_produto_venda + '</td>' +
                                '<td>R$' + vm.formatarNumero(item.valor_unitario_venda) + '</td>' +
                                '<td>R$' + vm.formatarNumero(item.total_produto_venda) + '</td>' +
                                '<td>' + vm.formatarNumero(item.imposto_produto_venda) + '%</td>' +
                                '<td>R$' + vm.formatarNumero(item.total_imposto_venda) + '</td>' +
                                '</tr>';
                        });

                        tableHTML += '</tbody>' +
                            '</table>' +
                            '</div>';

                        Swal.fire({
                            icon: 'info',
                            title: 'Itens da Venda',
                            html: tableHTML,
                            width: 1200,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: data.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Ocorreu um erro na requisição AJAX. Tente novamente mais tarde.'
                    });
                }
            });
        },

        listar(idVenda) {
         
            // Obter os itens da venda específica
            fetch(`/venda/itens/${idVenda}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao obter os itens da venda.');
                    }
                    return response.json();
                })
                .then(data => {
                    this.produtoSelecionado = '';
                    this.quantidade = '';
                    this.precoUnitario = '';
                    this.percentImposto = '';
                    this.valorTotal = '';
                    this.itens = data;
                })
                .catch(error => {
                    console.error(error);
                });

        },

        finalizarVenda() {
            Swal.fire({
                title: 'Finalizar Venda',
                text: 'Deseja salvar a venda?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.salvarVenda();
                    return false;
                }
            });
        },

        salvarVenda() {

            const itensVenda = [];
            this.itens.forEach((item) => {
                const id_produto = item.id_produto;
                const quantidade = item.quantidade;
                const valor_unitario = item.valor_unitario.replaceAll(".", "").replaceAll(",", ".");
                const percent_imposto = item.percent_imposto.replaceAll(".", "").replaceAll(",", ".");
                const valor_imposto = item.valor_imposto.replaceAll(".", "").replaceAll(",", ".");
                const valor_total_produto = item.valor_total_produto.replaceAll(".", "").replaceAll(",", ".");

                itensVenda.push({
                    id_produto,
                    quantidade,
                    valor_unitario,
                    percent_imposto,
                    valor_imposto,
                    valor_total_produto,
                });
            });

            const totalValorVenda = this.calcularTotalVenda();
            const totalValorImposto = this.calcularTotalImposto();

            const dadosVenda = {
                itens: itensVenda,
                totalValorVenda,
                totalValorImposto,
            };

            jQuery.ajax({
                type: "POST",
                url: urlBackEnd + "index.php",
                data: {
                    classe: "Venda",
                    funcao: "cadastrar",
                    itens: itensVenda,
                    totalValorVenda: totalValorVenda,
                    totalValorImposto: totalValorImposto,
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire('Venda Salva', 'A venda foi salva com sucesso.', 'success');
                        vm.itens = [];
                        vm.calcularTotais();
                        var finalizarVendaButton = document.querySelector('.btn-finalizar-venda');
                        if (finalizarVendaButton) {
                            finalizarVendaButton.remove();
                        }
                    } else {
                        Swal.fire('Erro', 'Houve um erro ao salvar a venda.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Erro', 'Houve um erro ao salvar a venda.', 'error');
                }
            });
        },

        calcularTotalVenda() {
            let totalValorVenda = 0;
            this.itens.forEach((item) => {
                totalValorVenda += parseFloat(item.valor_total_produto.replace('R$', '').trim().replace('.', '').replace(',', '.'));
            });
            return totalValorVenda.toFixed(2);
        },

        calcularTotalImposto() {
            let totalValorImposto = 0;
            this.itens.forEach((item) => {
                totalValorImposto += parseFloat(item.valor_imposto.replace('R$', '').trim().replace('.', '').replace(',', '.'));
            });
            return totalValorImposto.toFixed(2);
        },

        removerItem(index) {
            this.itens.splice(index, 1);

            const buttons = document.querySelectorAll('.excluirItem');
            for (let i = 0; i < buttons.length; i++) {
                const dataIndex = buttons[i].getAttribute('data-index');
                if (dataIndex === index.toString()) {
                    const row = buttons[i].closest('tr');
                    row.remove();
                    break;
                }
            }

            if (this.itens.length === 0) {
                const finalizarVendaButton = document.querySelector('.btn-finalizar-venda');
                if (finalizarVendaButton) {
                    finalizarVendaButton.remove();
                }
            }

            this.calcularTotais();
        },

        adicionarItem() {
            const id_produto = $('#produto').val();
            const nome_produto = $('#produto option:selected').text();
            const quantidade = $('#quantidade').val();
            const valor_unitario = $('#valor_unitario').val();
            const percent_imposto = $('#percent_imposto').val();
            const valor_imposto = $('#valor_imposto').val();
            const valor_total_produto = $('#valor_total_produto').val();

            const novoItem = {
                id_produto: id_produto,
                nome_produto: nome_produto,
                quantidade: quantidade,
                valor_unitario: valor_unitario,
                percent_imposto: percent_imposto,
                valor_imposto: valor_imposto,
                valor_total_produto: valor_total_produto,
            };
            this.itens.push(novoItem);
            this.calcularTotais();
            Swal.close();

            var finalizarVendaButton = document.querySelector('.btn-finalizar-venda');
            if (!finalizarVendaButton) {
                finalizarVendaButton = document.createElement('a');
                finalizarVendaButton.href = '#';
                finalizarVendaButton.className = 'btn btn-primary btn-icon-split float-right btn-finalizar-venda';
                finalizarVendaButton.innerHTML = `
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Finalizar Venda</span>
                `;

                finalizarVendaButton.addEventListener('click', () => {
                    this.finalizarVenda()
                });

                var cardHeader = document.querySelector('.container-btn-finalizar');
                cardHeader.appendChild(finalizarVendaButton);
            }

        },

        calcularTotais() {
            let totalValorImposto = 0;
            let totalValorProduto = 0;

            this.itens.forEach((item) => {
                const valorImposto = item.valor_imposto.replace('R$', '').trim();
                const valorTotalProduto = item.valor_total_produto.replace('R$', '').trim();

                totalValorImposto += parseFloat(valorImposto.replace('.', '').replace(',', '.'));
                totalValorProduto += parseFloat(valorTotalProduto.replace('.', '').replace(',', '.'));
            });

            $(".total-valor-venda").empty();
            $(".total-valor-venda").html('R$ ' + this.formatarNumero(totalValorProduto.toFixed(2)));

            $(".total-valor-imposto").empty();
            $(".total-valor-imposto").html('R$ ' + this.formatarNumero(totalValorImposto.toFixed(2)));

        },

        async abrirNovoItem() {
            
            try {
                jQuery.ajax({
                    type: "POST",
                    url: urlBackEnd + "index.php",
                    data: { classe: "Venda", funcao: "carregarDadosVenda" },
                    success: function(data) {
                        Swal.fire({
                            title: 'Adicionar Item',
                            html: data,
                            width: '800px',
                            showCancelButton: true,
                            confirmButtonText: 'Confirmar',
                            cancelButtonText: 'Cancelar',
                            didOpen: () => {

                                $('#produto').on('change', function() {
                                    vm.changeProduto($(this).val());
                                });
                                $('#quantidade').on('input', function() {
                                    vm.changeProduto($('#produto').val());
                                });

                            },
                            preConfirm: () => {
                                const campos = document.querySelectorAll('.campos_novo_item');
                                let camposVazios = 0;

                                campos.forEach((campo) => {
                                    if (!campo.value) {
                                        camposVazios++;
                                    }
                                });

                                if (camposVazios > 0) {
                                    Swal.showValidationMessage('Preencha todos os campos antes de continuar.');
                                } else {
                                    vm.adicionarItem();
                                }
                            }
                        });

                    }
                });
            } catch (error) {
                console.error('Erro ao buscar produtos:', error);
            }
        },
        changeProduto(id_produto) {
            if (id_produto != "") {
                vm.buscaDadosProduto(id_produto)
                    .then(dadosProduto => {

                        var id_produto = $("#produto").val();
                        var quantidade_produto = $("#quantidade").val();

                        if (quantidade_produto > 0 && id_produto > 0) {

                            $("#percent_imposto").val(this.formatarNumero(dadosProduto[0].imposto_tipo_produto));
                            $("#valor_unitario").val(this.formatarNumero(dadosProduto[0].preco_venda_produto));

                            var valor_total_produto = parseFloat(dadosProduto[0].preco_venda_produto) * parseFloat(quantidade_produto);

                            var valor_total_imposto = parseFloat(valor_total_produto) * parseFloat(dadosProduto[0].imposto_tipo_produto / 100);
                            var valor_total_imposto = this.formatarNumero(valor_total_imposto);

                            var valor_total_produto = this.formatarNumero(valor_total_produto);

                            $("#valor_imposto").val(valor_total_imposto);
                            $("#valor_total_produto").val(valor_total_produto);

                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar dados do produto:', error);
                    });
            } else {
                $(".inputs_novo_item").val("");
            }
        },

        buscaDadosProduto(id_produto) {
            return new Promise((resolve, reject) => {
                jQuery.ajax({
                    type: "POST",
                    url: urlBackEnd + "index.php",
                    data: { classe: "Produtos", funcao: "buscaDadosProduto", id_produto: id_produto },
                    success: function(data) {
                        try {
                            const parsedData = JSON.parse(data);
                            resolve(parsedData);
                        } catch (error) {
                            reject(error);
                        }
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        },
    },
    watch: {

        produtoSelecionado: 'obterPrecos',
        quantidade: 'obterPrecos'
    }
});