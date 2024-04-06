@extends('layout.basico')

@section('titulo', 'Login')

<body class="bg-gradient-primary">

    @section('conteudo')
        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Bem Vindo!</h1>
                                        </div>

                                        <form class="formulario" action={{ route('autenticar') }} method="POST">
                                            @csrf
                                            <!--- csrf gera um input hidden com um token para o envio do formuláro !--->
                                            <div class="form-group">
                                                <input type="user" value="{{ old('user') }}"
                                                    class="form-control form-control-user" v-model="user" id="user"
                                                    name="user" placeholder="Digite o Usuário...">
                                                <span
                                                    class="error-message">{{ $errors->has('user') ? $errors->first('user') : '' }}</span>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" value="{{ old('password') }}"
                                                    class="form-control form-control-user" v-model="password" id="password"
                                                    name="password" placeholder="Digite a Senha...">
                                                <span
                                                    class="error-message">{{ $errors->has('password') ? $errors->first('password') : '' }}</span>

                                            </div>
                                            <input type="submit" class="btn btn-primary btn-user btn-block"
                                                value="Logar" />
                                            <hr>
                                        </form>
                                    
                                        <span class="error-message"> {{ isset($error) && $error != '' ? $error : '' }}</span>


                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    @endsection('conteudo')
</body>

@section('scripts')

    <script src="js/scripts/Login.js?v=<?= time() ?>"></script>

@endsection('scripts')
