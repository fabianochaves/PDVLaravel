@extends('layout.basico')

@section('titulo', 'Cadastro de Produtos')

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
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Cadastro de Produtos</h1>
                    </div>
                    <div class="row">
                        <!-- Content Column -->
                        <div class="col-lg-8 offset-md-2 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Formulário</h6>
                                </div>
                                <div class="card-body">
                                    <div id="app">
                                        <form class="formulario" action={{ route('cadastrarProduto') }} method="POST" class="needs-validation" novalidate>
                                            @csrf
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label>Nome do Produto</label>
                                                        <input autocomplete="off" required type="text" class="form-control form-control" v-model="nome_produto" id="nome_produto" name="nome_produto" placeholder="Digite o Nome do Produto...">
                                                        <span class="error-message">{{ $errors->has('nome_produto') ? $errors->first('nome_produto') : '' }}</span>
                                                        
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Tipo</label>
                                                        <select class="form-control" name="tipo_produto">
                                                            <option value="" disabled selected>Selecione a categoria</option>
                                                            @foreach($tipos as $tipo)
                                                                <option value="{{ $tipo->id_tipo_produto }}">{{ $tipo->nome_tipo_produto }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error-message">{{ $errors->has('tipo_produto') ? $errors->first('tipo_produto') : '' }}</span>

                                                    </div>
                                               
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label>Preço de Venda Unitário (R$)</label>
                                                        <input autocomplete="off" required type="text" class="form-control form-control valorDecimal" v-model="preco_venda_produto" id="preco_venda_produto" name="preco_venda_produto" placeholder="Digite o Valor de Venda...">
                                                        <span class="error-message">{{ $errors->has('preco_venda_produto') ? $errors->first('preco_venda_produto') : '' }}</span>
                                                        
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Preço de Custo Unitário (R$)</label>
                                                        <input autocomplete="off" required type="text" class="form-control form-control valorDecimal" v-model="preco_custo_produto" id="preco_custo_produto" name="preco_custo_produto" placeholder="Digite o Valor de Custo...">
                                                        <span class="error-message">{{ $errors->has('preco_custo_produto') ? $errors->first('preco_custo_produto') : '' }}</span>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <input type="submit" class="btn btn-primary btn-block col-md-4 offset-md-4" value="Salvar" />
                                                </div>
                                            </div>
                                            <hr>
                                        </form>
                                        <div class="error-container">
                                            <span class=" {{ isset($msg_status) && $msg_status == 'Cadastrado com Sucesso!' ? 'success_msg' : 'error-message ' }}">{{ isset($msg_status) && $msg_status != '' ? $msg_status : '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
          

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    @endsection('conteudo')
    
    @section('scripts')
        <script src="js/scripts/Produtos.js?t=<?php echo time(); ?>"></script>
    @endsection('scripts')

    @if(isset($msg_status) && $msg_status != "")
        <script>
            setTimeout(function() {
                window.location.href = '/cadastroProduto';
            }, 1000); // 1000 milissegundos = 1 segundo
        </script>
    @endif

</body>

</html>