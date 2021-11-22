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
            $sql = "select p.cod_prato, p.descricao, i.quantidade, p.valor_unitario as valor_prato,
                    i.quantidade * p.valor_unitario AS valor_total
                    from pratos p
                    left outer join itens_encomenda i on (p.cod_prato = i.cod_prato)
                    where i.cod_item_encomenda = '$cod_item_encomenda'
                    order by p.descricao";
            
            return $this->pdo->query($sql);
        }

        //Incluir
        public function incluir($dados) {

            $cod_item_encomenda = $dados['cod_item_encomenda'];
            $num_encomenda = $dados['num_encomenda'];
            $cod_prato = $dados['cod_prato'];

            $sql = "select count(*) as total from itens_encomenda where cod_item_encomenda = '$cod_item_encomenda'
                   and num_encomenda = '$num_encomenda' and cod_prato = '$cod_prato' ";
            $r = $this->pdo->query($sql);
            $d = $r->fetch(PDO::FETCH_ASSOC);

            if($d['total'] == 0) {
                $sql = "insert into itens_encomenda (cod_item_encomenda,num_encomenda,cod_prato,quantidade,valor_unitario)
                        VALUES (:co:num_encomenda,:cod_prato,quantidade,:valor_unitario)";
                
                $cmd = $this->pdo->prepare($sql);
                $cmd->bindValue(':cod_item_encomenda',$dados['cod_item_encomenda']);
                $cmd->bindValue(':num_encomenda',$dados['num_encomenda']);
                $cmd->bindValue(':cod_prato',$dados['cod_prato']);
                $cmd->bindValue(':quantidade',$dados['quantidade']);
                $cmd->bindValue(':valor_unitario',$dados['valor_unitario']);
                $cmd->execute();

                return '';
            } else {
                return 'Esta prato já está cadastrado nesta encomenda!';
            }
        }

        //Excluir
        public function Excluir($cod_item_encomenda, $num_encomenda) {
            $sql = "delete from itens_encomenda where cod_item_encomenda = :cod_item_encomenda and num_encomenda = :num_encomenda";

            $cmd = $this->pdo->prepare($sql);
            $cmd->bindValue(':cod_item_encomenda',$cod_item_encomenda);
            $cmd->bindValue(':num_encomenda',$num_encomenda);

            if($cmd->execute()) {
                return '';
            } else {
                return 'Houve algum erro com a transação do banco de dados!';
            }
        }
    }

?>