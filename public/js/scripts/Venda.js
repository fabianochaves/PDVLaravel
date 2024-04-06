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
       
        this.listar();
       
    },
    methods: {

        calcularTotalVenda() {
            let total = 0;
            for (let item of this.itens) {
                let valorFormatado = item.total_produto_venda.replaceAll('.', '').replace(",",".");
                total += parseFloat(valorFormatado);
            }
       
            if (total) {
                total = total.toFixed(2).replace('.', ',');
            } else {
                total = '0,00';
            }
            return total;
        },

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
                var finalizarVendaButton = document.querySelector('.btn-finalizar-venda');
                if (!finalizarVendaButton) {
                    finalizarVendaButton = document.createElement('a');
                    finalizarVendaButton.href = '#';
                    finalizarVendaButton.className = 'btn btn-success btn-icon-split float-right btn-finalizar-venda';
                    finalizarVendaButton.innerHTML = `
                        <span class="icon text-white-50">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="text">Finalizar Venda</span>
                    `;
    
                    finalizarVendaButton.addEventListener('click', () => {
                        this.finalizarVenda(data.id_venda)
                    });
    
                    var cardHeader = document.querySelector('.container-btn-finalizar');
                    cardHeader.appendChild(finalizarVendaButton);
                }
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

        listar(idVenda) {
            
            fetch(`/venda/itens/${idVenda}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao obter os itens da venda.');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data)
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

        finalizarVenda(id_venda) {
            Swal.fire({
                title: 'Finalizar Venda',
                text: 'Deseja salvar a venda?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.salvarVenda(id_venda);
                    return false;
                }
            });
        },

        salvarVenda(id_venda) {

            var form = new FormData();
            form.append('id_venda', id_venda);
         
            fetch(window.routes.finalizarVenda, {
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
                window.location.reload();
            })
            .catch(error => {
                console.error(error);
            });
        },

        deletarItem: function (id_item_venda) {
            var form = new FormData();
            form.append('id_item_venda', id_item_venda);
         
            fetch(window.routes.deletarItemVenda, {
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
                this.calcularTotalVenda();
            })
            .catch(error => {
                console.error(error);
            });
        },

        removerItem(id_item_venda) {

            const index = this.itens.findIndex(item => item.id_item_venda === id_item_venda);
        
            if (index !== -1) {
                this.itens.splice(index, 1);
            }
        
            const buttons = document.querySelectorAll('.excluirItem');
            for (let i = 0; i < buttons.length; i++) {
                const dataIndex = buttons[i].getAttribute('data-index');
                if (dataIndex === id_item_venda.toString()) {
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
        
            this.deletarItem(id_item_venda);
            this.calcularTotalVenda();
        },
        
    },
    watch: {

        produtoSelecionado: 'obterPrecos',
        quantidade: 'obterPrecos'
    }
});