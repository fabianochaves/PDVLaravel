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
            var classe = "Produtos";
            var funcao = "listarCategorias";

            $.ajax({
                type: 'POST',
                url: urlBackEnd + "index.php",
                data: { classe: classe, funcao: funcao, nome_tipo_produto: this.nome_tipo_produto, imposto_tipo_produto: this.imposto_tipo_produto },
                success: data => {
                    this.categorias = data;
                },
                error: error => {
                    console.error('Erro ao buscar categorias: ' + error);
                },
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

            var classe = "Tipos"
            var funcao = "listar"

            jQuery.ajax({
                type: 'POST',
                url: urlBackEnd + 'index.php',
                data: { classe: classe, funcao: funcao },
                success: (tiposProduto) => {

                    const tipoOptions = tiposProduto
                        .map((tipo) => {

                            const selected = tipo.id_tipo_produto === produto.tipo_produto ? 'selected' : '';
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

                            this.salvarAlteracoes(tipo_produto, nome_produto, preco_venda_produto, preco_custo_produto, id_produto);
                        }
                    });
                },
                error: (error) => {
                    console.error('Erro ao buscar tipos de produtos: ' + error);
                    Swal.fire('Erro', 'Ocorreu um erro ao buscar os tipos de produtos.', 'error');
                },
            });
        },

        salvarAlteracoes(tipo_produto, nome_produto, preco_venda_produto, preco_custo_produto, id_produto) {
            var classe = "Produtos";
            var funcao = "editar";

            preco_venda_produto = preco_venda_produto.replaceAll(".", "").replaceAll(",", ".");
            preco_custo_produto = preco_custo_produto.replaceAll(".", "").replaceAll(",", ".");

            jQuery.ajax({
                type: "POST",
                url: urlBackEnd + "index.php",
                data: {
                    classe: classe,
                    funcao: funcao,
                    tipo_produto: tipo_produto,
                    nome_produto: nome_produto,
                    preco_venda_produto: preco_venda_produto,
                    preco_custo_produto: preco_custo_produto,
                    id_produto: id_produto,
                },
                success: function(response) {

                    if (response.status === 1) {
                        Swal.fire('Sucesso', 'As alterações foram salvas com sucesso!', 'success');
                        vm.listar()
                    } else {
                        Swal.fire('Erro', 'Ocorreu um erro ao salvar as alterações.', 'error');
                    }
                },
                error: function(error) {
                    console.error("Erro ao editar tipo: " + error);
                    Swal.fire('Erro', 'Ocorreu um erro ao salvar as alterações.', 'error');
                }
            });
        },

        listar() {
            var classe = "Produtos";
            var funcao = "listarProdutos";
            var parametros = {
                is_ativo: ""
            };

            jQuery.ajax({
                type: "POST",
                url: urlBackEnd + "index.php",
                data: { classe: classe, funcao: funcao, is_ativo: parametros },
                success: function(data) {

                    data.forEach(function(produto) {
                        produto.status_produto = produto.status_produto == 1 ? "Ativo" : "Inativo";
                        produto.status_css = produto.status_produto == "Ativo" ? "ativo" : "inativo";
                    });

                    vm.Produtos = data;
                    setTimeout(() => {
                        $('#dataTable').DataTable();

                    }, 500);
                }
            });
        },

        status(produto) {
            let novoStatus = produto.status_produto === "Ativo" ? "Inativo" : "Ativo";
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
            const id_produto = produto.id_produto;
            const classe = "Produtos";
            const funcao = "alterarStatus";

            const data = {
                classe: classe,
                funcao: funcao,
                id_produto: id_produto,
                novo_status: novo_status
            };

            this.loading = true;

            jQuery.ajax({
                type: "POST",
                url: urlBackEnd + "index.php",
                data: data,
                success: () => {
                    this.listar();
                    this.loading = false;
                },
                error: (error) => {
                    console.error("Erro ao alterar status: " + error);
                    this.loading = false;
                }
            });
        },

        cadastrar() {
            this.preco_venda_produto = $("#preco_venda_produto").val();
            this.preco_custo_produto = $("#preco_custo_produto").val();
            if (this.nome_produto == "" || this.tipo_produto == "" || this.preco_venda_produto == "" || this.preco_custo_produto == "") {
                alerta("warning", "Atenção", "Preencha todos os Campos!", "", 0);
                return false
            } else {
                var classe = "Produtos";
                var funcao = "cadastrar";

                jQuery.ajax({
                    type: "POST",
                    url: urlBackEnd + "index.php",
                    data: {
                        classe: classe,
                        funcao: funcao,
                        nome_produto: this.nome_produto,
                        tipo_produto: this.tipo_produto,
                        preco_venda_produto: this.preco_venda_produto,
                        preco_custo_produto: this.preco_custo_produto
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            alerta("success", "Sucesso!", data.body, "", 1);
                        } else {
                            alerta("error", "Atenção!", data.body, "", 0);
                        }

                        return false;
                    }
                });

                return false;
            }
        },
    }
});