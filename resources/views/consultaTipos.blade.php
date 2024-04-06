@extends('layout.basico')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('titulo', 'Tipos de Produtos')

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
                    <h1 class="h3 mb-2 text-gray-800">Consulta de Tipos de Produtos</h1>
                    <div id="app">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Tipos de produtos</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table v-if="tiposProdutos.length > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <!-- Cabeçalho da tabela -->
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Status</th>
                                                <th>Nome</th>
                                                <th>Imposto (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(tipo, index) in tiposProdutos" :key="index">
                                                <td class="acao-botoes"> 
                                                    <a href="#" class="btn btn-circle alterarStatus" :class="{'btn-danger': tipo.status_tipo_produto === 'Ativo', 'btn-success': tipo.status_tipo_produto === 'Inativo'}" @click="status(tipo)">
                                                        <i :class="{'fas fa-times': tipo.status_tipo_produto === 'Ativo', 'fas fa-check': tipo.status_tipo_produto === 'Inativo'}"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-primary btn-circle editarRegistro" @click="abrirModalEdicao(tipo)">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td>@{{ tipo.id_tipo_produto }}</td>
                                                <td :class="tipo.status_css">@{{ tipo.status_tipo_produto }}</td>
                                                <td>@{{ tipo.nome_tipo_produto }}</td>
                                                <td>@{{ formatarNumero(tipo.imposto_tipo_produto) }}</td>
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
        <script src="js/scripts/Tipos.js?t=<?php echo time(); ?>"></script>
    @endsection('scripts')
</body>

</html>