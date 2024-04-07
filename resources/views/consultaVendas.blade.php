@extends('layout.basico')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('titulo', 'Consulta de Vendas')

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
                    <h1 class="h3 mb-2 text-gray-800">Consulta de Vendas</h1>
                    <div id="app">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Vendas</h6>
                                <div class="totais">
                                    <p><strong>Total das Vendas:</strong> <span class="total-venda" id="totalVendas">0.00</span></p>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table v-if="Vendas.length > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <!-- Cabeçalho da tabela -->
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Data</th>
                                                <th>Valor Total (R$)</th>
                                                <th>Valor Imposto (R$)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(venda, index) in Vendas" :key="index">
                                                <td class="acao-botoes"> 
                                                    <a href="#" class="btn btn-primary btn-circle verItens" @click="verItens(venda)">
                                                        <i class="fas fa-search"></i>
                                                    </a>
                                                </td>
                                                <td>@{{ venda.id_venda }}</td>
                                                <td>@{{ formatarData(venda.datetime_venda) }}</td>
                                                <td>R$@{{ formatarNumero(venda.valor_total_venda) }}</td>
                                                <td>R$@{{ formatarNumero(venda.valor_imposto_venda) }}</td>
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
    <script src="js/scripts/Venda.js?t=<?php echo time(); ?>"></script>
    @endsection('scripts')

</body>

</html>