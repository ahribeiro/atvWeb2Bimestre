<?php

    namespace Model;

    use \PDO;

    use \Lib\bd;

    class EncomendasModel {
        private $pdo;

        function __construct() {
            $meu_BD = new BD();
            $this->pdo = $meu_BD->pdo;
        }

        public function Get_encomenda($cod_encomenda) {
            $sql = "select * from encomendas
                    where num_encomenda = '$cod_encomenda' ";

            $r = $this->pdo->query($sql);
            return $r->fetch(PDO::FETCH_ASSOC);
        }
    }

?>