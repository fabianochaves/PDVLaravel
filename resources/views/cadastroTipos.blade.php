@extends('layout.basico')

@section('titulo', 'Cadastro de Tipos de Produtos')

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
                            <h1 class="h3 mb-0 text-gray-800">Cadastro de Tipos de Produto</h1>
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
                                        <form action={{ route('cadastrarTipo') }} class="formulario" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label>Nome do Tipo</label>
                                                        <input autocomplete="off" type="text" class="form-control"
                                                            v-model="nome_tipo_produto" id="nome_tipo_produto"
                                                            name="nome_tipo_produto" placeholder="Digite o Nome do Tipo...">
                                                            <span class="error-message">{{ $errors->has('nome_tipo_produto') ? $errors->first('nome_tipo_produto') : '' }}</span>
                                                        </div>

                                                    <div class="col-md-6">
                                                        <label>Imposto (%)</label>
                                                        <input autocomplete="off" required type="text"
                                                            class="form-control percentual" v-model="imposto_tipo_produto"
                                                            id="imposto_tipo_produto" name="imposto_tipo_produto"
                                                            placeholder="Digite o Imposto...">
                                                            <span class="error-message">{{ $errors->has('imposto_tipo_produto') ? $errors->first('imposto_tipo_produto') : '' }}</span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <input type="submit"
                                                        class="btn btn-primary btn-block col-md-4 offset-md-4"
                                                        value="Salvar" />
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
                    <!-- /.container-fluid -->

                </div>


            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->
    @endsection('conteudo')

    @if(isset($msg_status) && $msg_status != "")
        <script>
            setTimeout(function() {
                window.location.href = '/cadastroTipos';
            }, 1000); // 1000 milissegundos = 1 segundo
        </script>
    @endif



</body>

</html>
