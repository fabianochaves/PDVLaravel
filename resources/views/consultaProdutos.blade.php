@extends('layout.basico')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('titulo', 'Consulta de Produtos')

@section('conteudo')
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        @include("menu")

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @include("menu_top")

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Consulta de Produtos</h1>
                    <div id="app">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Produtos</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table v-if="Produtos.length > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <!-- Cabeçalho da tabela -->
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Status</th>
                                                <th>Nome</th>
                                                <th>Tipo</th>
                                                <th>Preço Venda</th>
                                                <th>Preço Custo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(produto, index) in Produtos" :key="index">
                                                <td class="acao-botoes"> 
                                                    <a href="#" class="btn btn-circle alterarStatus" :class="{'btn-danger': produto.status_produto === 'Ativo', 'btn-success': produto.status_produto === 'Inativo'}" @click="status(produto)">
                                                        <i :class="{'fas fa-times': produto.status_produto === 'Ativo', 'fas fa-check': produto.status_produto === 'Inativo'}"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-primary btn-circle editarRegistro" @click="abrirModalEdicao(produto)">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td>@{{ produto.id_produto }}</td>
                                                <td :class="produto.status_css">@{{ produto.status_produto }}</td>
                                                <td>@{{ produto.nome_produto }}</td>
                                                <td>@{{ produto.tipo_produto.nome_tipo_produto }}</td>
                                                <td>@{{ formatarNumero(produto.preco_venda_produto) }}</td>
                                                <td>@{{ formatarNumero(produto.preco_custo_produto) }}</td>
                                            </tr>
                                            <tr v-if="loading">
                                                <td colspan="5" class="text-center">Carregando...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p v-else>Nenhum dado disponível.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    @endsection('conteudo')
    
    @section('scripts')
    <script src="js/scripts/Produtos.js?t=<?php echo time(); ?>"></script>
    @endsection('scripts')

</body>

</html>