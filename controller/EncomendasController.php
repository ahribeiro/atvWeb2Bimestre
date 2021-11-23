<?php

    namespace Controller;

    use \PDO;
    use \Model\EncomendasModel;
    use \Model\ClientesModel;

    class EncomendasController {

        public function Listar($mensagem) {
            $model = new EncomendasModel();
            $lista_prato = $model->Get_lista(@$_POST['pesquisa']);

            $arquivo = "view/EncomendasListarView.php";

            include_once("view/IndexView.php");
        }

        public function Excluir($num_encomenda) {
            $model = new EncomendasModel();

            $resultado = $model->Excluir($num_encomenda);

            $this->Listar($resultado);
        }

        public function Formulario($num_encomenda) {
            if ($num_encomenda != '') {
                $model = new EncomendasModel();
                $dados = $model->Get_encomenda($num_encomenda);

                $cod_cliente = $dados['cod_cliente'];
                $data = dataUSA($dados['data']);
                $valor_total = floatBR($dados['valor_total']);

                $acao = 'alterar';
                
            } else {
                $cod_cliente = "";
                $data = "";
                $valor_total = "";

                $acao = 'incluir';
            }

            $model_clientes = new ClientesModel();

            $lista_de_clientes = $model_clientes->Get_lista('');

            $arquivo = "view/EncomendasFormularioView.php";
            include_once("view/IndexView.php");
        }

        public function Incluir() {
            $model = new EncomendasModel();
            $model->Incluir($_POST);

            $_POST['pesquisa'] = '';

            $this->Listar("");
        }

        public function Alterar() {
            $model = new EncomendasModel();
            $model->Alterar($_POST);

            $_POST['pesquisa'] = '';

            $this->Listar(""); 
        }

        public function VerDuplicidade($dados) {
            $model = new EncomendasModel();
            $dados = $model->VerDuplicidade($dados);

            if($dados['total'] > 0) {
                echo 'Essa encomenda já está cadastrada!';
            }
        }
    }


?>