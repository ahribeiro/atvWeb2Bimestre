<?php

    namespace Controller;

    use \Model\EncomendasModel;
    use Model\ItensEncomendaModel;
    use \Model\PratosModel;

    class ItensEncomendaController {

        public function Form() {
            $model = new PratosModel();
            $lista_pratos = $model->Get_lista('');

            include_once("view/ItensEncomendaFormView.php");
        }// Index()

        public function Listar($cod_item_encomenda) {
            $model = new ItensEncomendaModel();
            $lista_itens = $model->Get_lista($cod_item_encomenda);

            include_once("view/ComposicaoListarView.php");
        }

        public function Incluir($dados) {
            $model = new ItensEncomendaModel();

            $erro = $model->Incluir($dados);

            echo $erro;
        }

        public function Excluir($cod_item_encomenda, $num_encomenda, $cod_prato) {
            
            $model = new ItensEncomendaModel();

            $erro = $model->Excluir($cod_item_encomenda, $num_encomenda, $cod_prato);

            echo $erro;
        }
    }

?>