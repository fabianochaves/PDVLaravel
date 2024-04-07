vm = new Vue({
    el: '#app',
    data: {
        nome_produto: '',
        tipo_produto: '',
        preco_venda_produto: '',
        preco_custo_produto: '',
        categorias: [],
        Produtos: [],
        loading: false,
        tipoOptions: [],
    },
    mounted() {
        this.listar();
        this.listarCategorias();
    },
    methods: {

        listarCategorias() {

            fetch(`/listarTipos`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao obter os Tipos de Produtos.');
                }
                return response.json();
            })
            .then(data => {
                console.log(data)
                this.categorias = data;
            })
            .catch(error => {
                console.error(error);
            });
        },

        formatarNumero(numero) {
            const formatter = new Intl.NumberFormat('pt-BR', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
            return formatter.format(numero);
        },

        abrirModalEdicao(produto) {

            fetch(`/listarTipos`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao obter os Tipos de Produtos.');
                }
                return response.json();
            })
            .then(data => {
              
                const tipoOptions = data
                .map((tipo) => {

                    const selected = tipo.id_tipo_produto == produto.tipo_produto ? 'selected' : '';
                    return `<option value="${tipo.id_tipo_produto}" ${selected}>${tipo.nome_tipo_produto}</option>`;
                })
                .join('');

                Swal.fire({
                    title: 'Editar Produto',
                    html: `
                        <div>
                            <div style="display: flex; flex-direction: column;">
                                <label for="nome_produto" style="margin-bottom: -20px;">Nome:</label>
                                <input id="nome_produto" class="swal2-input" value="${produto.nome_produto}" placeholder="Nome do Produto">
                                <br>
                                <label for="tipo_produto" style="margin-bottom: -20px;">Tipo:</label>
                                <select id="tipo_produto" class="swal2-select" style="border: 1px solid #ced4da;">
                                    ${tipoOptions}
                                </select>
                                <br>
                                <label for="preco_venda_produto" style="margin-bottom: -20px;">Preço de Venda:</label>
                                <input id="preco_venda_produto" class="swal2-input valorDecimal" value="${vm.formatarNumero(produto.preco_venda_produto)}" placeholder="Preço de Venda">
                                <br>
                                <label for="preco_custo_produto" style="margin-bottom: -20px;">Preço de Custo:</label>
                                <input id="preco_custo_produto" class="swal2-input valorDecimal" value="${vm.formatarNumero(produto.preco_custo_produto)}" placeholder="Preço de Custo">
                            </div>
                            <input type="hidden" id="id_produto" value="${produto.id_produto}">
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Salvar',
                    cancelButtonText: 'Cancelar',
                    didOpen: () => {
                        $('.valorDecimal').maskMoney({
                            prefix: '',
                            suffix: '',
                            allowZero: true,
                            decimal: ',',
                            thousands: '.',
                            precision: 2
                        });
                    },
                    preConfirm: () => {
                        const nome_produto = Swal.getPopup().querySelector('#nome_produto').value;
                        const tipo_produto = Swal.getPopup().querySelector('#tipo_produto').value;
                        const preco_venda_produto = Swal.getPopup().querySelector('#preco_venda_produto').value;
                        const preco_custo_produto = Swal.getPopup().querySelector('#preco_custo_produto').value;
                        const id_produto = Swal.getPopup().querySelector('#id_produto').value;
                    
                        if (!nome_produto || !tipo_produto || !preco_venda_produto || !preco_custo_produto || !id_produto) {
                            Swal.showValidationMessage("Por favor, preencha todos os campos!");
                            return false;
                        }
                    
                        this.salvarAlteracoes(tipo_produto, nome_produto, preco_venda_produto, preco_custo_produto, id_produto);
                    }
                    
                });
            })
            .catch(error => {
                console.error(error);
            });
        },

        salvarAlteracoes(tipo_produto, nome_produto, preco_venda_produto, preco_custo_produto, id_produto) {

            preco_venda_produto = preco_venda_produto.replaceAll(".", "").replaceAll(",", ".");
            preco_custo_produto = preco_custo_produto.replaceAll(".", "").replaceAll(",", ".");

            var form = new FormData();
            form.append('tipo_produto', tipo_produto);
            form.append('nome_produto', nome_produto);
            form.append('preco_venda_produto', preco_venda_produto);
            form.append('preco_custo_produto', preco_custo_produto);
            form.append('id_produto', id_produto);

            fetch(window.routes.salvarEdicaoProduto, {
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
                if (data.status === 1) {
                    Swal.fire('Sucesso', 'As alterações foram salvas com sucesso!', 'success');
                    vm.listar()
                } else {
                    Swal.fire('Erro', 'Ocorreu um erro ao salvar as alterações.', 'error');
                }
            })
            .catch(error => {
                console.error(error);
            });

        },

        listar() {

            fetch(`/listarProdutos`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao obter os Produtos.');
                }
                return response.json();
            })
            .then(data => {
                console.log(data)
                data.forEach(function(produto) {
                    produto.status_produto = produto.status_produto == 1 ? "Ativo" : "Inativo";
                    produto.status_css = produto.status_produto == "Ativo" ? "ativo" : "inativo";
                });
                vm.Produtos = data;
                setTimeout(() => {
                    $('#dataTable').DataTable();

                }, 500);
            })
            .catch(error => {
                console.error(error);
            });
        },

        status(produto) {
            let novoStatus = produto.status_produto === "Ativo" ? 0 : 1;
            let acao = produto.status_produto === "Ativo" ? "inativar" : "ativar";

            Swal.fire({
                title: 'Tem certeza?',
                text: `Esta ação irá ${acao} o registro. Deseja continuar?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Sim, ${acao}`,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.processarAlteracaoStatus(produto, novoStatus);
                }
            });
        },

        processarAlteracaoStatus(produto, novo_status) {
            
            this.loading = true;

            var form = new FormData();
            form.append('id_produto', produto.id_produto);
            form.append('novo_status', novo_status);

            fetch(window.routes.alterarStatusProduto, {
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
                if (data.status === 1) {
                    Swal.fire('Sucesso', 'As alterações foram salvas com sucesso!', 'success');
                    vm.listar()
                } else {
                    Swal.fire('Erro', 'Ocorreu um erro ao salvar as alterações.', 'error');
                }
                this.loading = false;
            })
            .catch(error => {
                console.error(error);
            });
            
        },
    }
});