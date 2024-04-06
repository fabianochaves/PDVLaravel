@php
    use App\Http\Controllers\VendaController;
@endphp

@extends('layout.basico')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('titulo', 'Cadastro de Venda')

@section('conteudo')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('menu')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                @include('menu_top')
                <!-- Begin Page Content -->
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Nova Venda</h1>
                    <div id="app" data-obter-precos="{{ route('obterPrecos') }}">
                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                            
                                <form @submit.prevent="verificarItensGrid" method="POST" id="formulario" name="formulario">
                                    @csrf
                                    <div class="form-row" id="app">
                                        <div class="col-md-3">
                                            <label for="produto">Selecione um produto:</label>
                                            <select v-model="produtoSelecionado" class="form-control campos_novo_item" id="produto" name="produto" required>
                                                <option value="">Escolha...</option>
                                                @foreach ($produtos as $produto)
                                                <option value="{{ $produto->id_produto }}">{{ $produto->nome_produto }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="quantidade">Quantidade:</label>
                                            <input v-model="quantidade" type="number" class="form-control inputs_novo_item campos_novo_item" id="quantidade" name="quantidade" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="preco">Preço Unitário (R$):</label>
                                            <input v-model="precoUnitario" readonly type="text" class="form-control inputs_novo_item campos_novo_item" id="valor_unitario" name="valor_unitario">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="perc_imposto">% Imposto:</label>
                                            <input v-model="percentImposto" readonly type="text" class="form-control inputs_novo_item campos_novo_item" id="percent_imposto" name="percent_imposto">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="valor_produto">Total Item (R$):</label>
                                            <input v-model="valorTotal" readonly type="text" class="form-control inputs_novo_item campos_novo_item" id="valor_total_produto" name="valor_total_produto">
                                        </div>                                  
                                        <div class="col-md-1 align-self-end">
                                            <input type="submit" class="btn btn-primary btn-block" value="+" />
                                        </div>
                                    </div>
                                </form>

                                <div class="form-row m-0 font-weight-bold text-primary" >
                                    <div class="col-md-12">
                                        <span class="total-container">
                                            <span class="total-label">Total Venda:</span>
                                            <span class="total-valor-venda">R$  @{{ calcularTotalVenda() }}</span>
                                        </span>
                                    {{-- </div>
                                    <div class="col-md-3">
                                        <span class="total-container">
                                            <span class="total-label">Total Imposto:</span>
                                            <span class="total-valor-imposto">R$ 0,00</span>
                                        </span>
                                    </div> --}}
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 container-btn-finalizar">
                                        
                                    </div>
                                </div>
                                <br>
                            </div>                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tabela_itens_venda" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID Produto</th>
                                                <th>Produto</th>
                                                <th>Quantidade</th>
                                                <th>Valor Unitário</th>
                                                <th>% Imposto</th>
                                                {{-- <th>Valor Total Imposto (R$)</th> --}}
                                                <th>Valor Total Produto (R$)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in itens" :key="index">
                                                <td> 
                                                    <a href="#" class="btn btn-danger btn-circle excluirItem" @click="removerItem(item.id_item_venda)" :data-index="index">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                                <td>@{{ item.cod_produto_venda }}</td>
                                                <td>@{{ item.produto.nome_produto }}</td>
                                                <td>@{{ item.qtd_produto_venda }}</td>
                                                <td>@{{ item.valor_unitario_venda }}</td>
                                                <td>@{{ item.imposto_produto_venda }}%</td>
                                                {{-- <td>@{{ item.total_imposto_venda }}</td> --}}
                                                <td>@{{ item.total_produto_venda }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Totais</th>
                                                <th></th>
                                                <th></th>
                                                {{-- <th class="total-valor-imposto">R$ 0,00</th> --}}
                                                <th class="total-valor-venda">R$ @{{ calcularTotalVenda() }}</th>
                                            </tr>
                                        </tfoot>
                                        
                                    </table>
                                </div>
                                
                                
                            </div>
                        </div>
                        
                    </div>
                    <!-- /.container-fluid -->
                </div>
            </div>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    @endsection('conteudo')
    
    @section('scripts')
        <script src="js/scripts/Venda.js?t=<?php echo time(); ?>"></script>

    @endsection('scripts')

    @if(isset($msg_status) && $msg_status != "")
        <script>
            setTimeout(function() {
                window.location.href = '/ Venda';
            }, 1000); // 1000 milissegundos = 1 segundo
        </script>
    @endif



</body>

</html>