vm = new Vue({
    el: '#appSignIn',
    data: {
        user: '',
        password: '',
    },
    methods: {

        logar() {
            if (this.user == "" || this.password == "") {
                alerta("warning", "Atenção", "Preencha todos os Campos!", "");
                return false
            } else {
                var classe = "Login";
                var funcao = "logar";

                jQuery.ajax({
                    type: "POST",
                    url: urlBackEnd + "index.php",
                    data: { classe: classe, funcao: funcao, user: this.user, password: this.password },
                    success: function(data) {

                        if (data.status == 1) {
                            location.href = "home"
                        } else {
                            alerta("error", "Atenção!", data.body, "", 0, 0);
                        }

                        return false;

                    }
                });

                return false;
            }
        },
    }
});