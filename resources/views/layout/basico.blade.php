<!DOCTYPE html>
<html lang="pt-br">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">



        <title>Sistema de Vendas - @yield('titulo')</title>

        <!-- Custom fonts for this template-->
        <link href="basicos/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css') }}">

        <style>
        .error-container {
            display: flex;
            justify-content: center; /* Centraliza horizontalmente */
            align-items: center; /* Centraliza verticalmente */
            height: 100%; /* Ajusta a altura conforme necessário */
        }
        .error-message {
            color: red;
        }
        .success_msg {
            color: green; /* Define a cor do texto como verde */
        }

        </style>

    <link href="basicos/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .total-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-top: 10px;
        }
        
        .total-label {
            font-weight: bold;
        }
    </style>

    </head>


        @yield('conteudo')

          <!-- Bootstrap core JavaScript-->
        <script src="basicos/jquery/jquery.min.js"></script>
        <script src="basicos/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="basicos/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

        <!-- Vue.js -->
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

        <!-- SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">

        <!-- Page level plugins -->
        <script src="basicos/datatables/jquery.dataTables.min.js"></script>
        <script src="basicos/datatables/dataTables.bootstrap4.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <style>
            .ativo {
                color: green;
                font-weight: bold;
            }
    
            .inativo {
                color: red;
                font-weight: bold;
            }
    
            .acao-botoes {
                width: 1%;
                white-space: nowrap;
            }
    
            .acao-botoes button {
                margin-right: 5px;
            }
            .total-venda {
            color: green;
            font-weight: bold;
        }

        .total-imposto {
            color: red;
            font-weight: bold;
        }

        .total-liquido {
            color: blue;
            font-weight: bold;
        }

        .totals {
            text-align: center;
        }
        .totais {
            text-align: center;
            margin-top: 20px; /* Espaço superior para centralizar na página */
        }
    
        </style>

        
        <!-- JS da Tela -->
        <script src="js/config.js?v=<?=time();?>"></script>

        <script>
            window.routes = {
                cadastrarVenda: '{{ route("cadastrarVenda") }}',
                deletarItemVenda: '{{ route("deletarItemVenda") }}',
                finalizarVenda: '{{ route("finalizarVenda") }}',
                salvarEdicaoTipo: '{{ route("salvarEdicaoTipo") }}',
                salvarEdicaoProduto: '{{ route("salvarEdicaoProduto") }}',
                alterarStatusTipo: '{{ route("alterarStatusTipo") }}',
                alterarStatusProduto: '{{ route("alterarStatusProduto") }}',
                listarTipos: '{{ route("listarTipos") }}',
                listarProdutos: '{{ route("listarProdutos") }}',
                dashboardVendasMes: '{{ route("dashboardVendasMes") }}',
                dashboardVendasSemana: '{{ route("dashboardVendasSemana") }}',
                dashboardVendasHoje: '{{ route("dashboardVendasHoje") }}',
                dashboardVendasMesaMes: '{{ route("dashboardVendasMesaMes") }}'

            };

        </script>
        
        @yield('scripts')

</html>
