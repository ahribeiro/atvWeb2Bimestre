<?php

    namespace Model;

    use \PDO;
    use \Lib\bd;

    class ItensEncomendaModel {
        private $pdo;

        function __construct() {
            $meu_BD = new BD();
            $this->pdo = $meu_BD->pdo;
        }


        public function Get_lista($cod_item_encomenda) {
            $sql = " SELECT p.descricao, i.quantidade, p.valor_unitario AS valor_prato, i.valor_unitario AS valor_entrega
                    FROM pratos p
                    LEFT OUTER JOIN itens_encomenda i ON (p.cod_prato = i.cod_prato)";
        }
    }

?>