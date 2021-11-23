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

        public function Get_lista($pesquisa) {
            $sql = "select e.*, c.nome
                    from ENCOMENDAS e
                    left outer join CLIENTES C on (e.cod_cliente = c.cod_cliente)
                    where c.nome like '%$pesquisa%' 
                    order by e.num_encomenda";
            return $this->pdo->query($sql);
        }

        //ExecutarSQL
        protected function ExecutarSQL($dados, $acao, $sql) {

            $cmd = $this->pdo->prepare($sql);
            
            $cod_cliente = $dados['cod_cliente'];
            $data = trim($dados['data']) == "" ? null : dataUSA($dados['data']);
            $valor_total = trim($dados['valor_total']) == "" ? null : floatUSA($dados['valor_total']); 
            
            $cmd->bindValue(":cod_cliente", $cod_cliente);
            $cmd->bindValue(":data", $data);
            $cmd->bindValue(":valor_total", $valor_total);

            if( $acao == 'alterar' )
            {
                $cmd->bindValue(':num_encomenda',$dados['num_encomenda']);	
            }
            
            $cmd->execute();
        }

        //Incluir
        public function Incluir($dados) {
            $sql = "insert into encomendas (cod_cliente,data,valor_total)
                    VALUES (:cod_cliente,:data,:valor_total)";
            
            $this->ExecutarSQL($dados, 'incluir', $sql);
        }
        
        //Alterar
        public function Alterar($dados) {
            $sql = "update encomendas set
                           cod_cliente = :cod_cliente,
                           data        = :data,
                           valor_total = :valor_total
                    where num_encomenda = :num_encomenda";
            
            $this->ExecutarSQL($dados, 'alterar', $sql);
        }

        //Excluir
        public function Excluir($num_encomenda) {

            // verificando a integridade referencial -----
            $sql = "select count(*) as total from itens_encomenda where num_encomenda = :num_encomenda";

            $cmd = $this->pdo->prepare($sql);
            $cmd->bindValue(':num_encomenda', $num_encomenda);
            $cmd->execute();

            $d = $cmd->fetch(PDO::FETCH_ASSOC);

            if($d['total'] > 0) {
                return 'Não é possivel excluir essa encomenda porque não possui nenhum item incluso!';
            }

            //---------------------------
            $sql = "delete from encomendas where num_encomenda = :num_encomenda";

            $cmd = $this->pdo->prepare($sql);
            $cmd->bindValue(':num_encomenda', $num_encomenda);
            $cmd->execute();

            return '';
        }

        //Ver duplicidade
        public function VerDuplicidade($dados) {

            $nome = trim(@$dados['nome']);
            $num_encomenda = @$dados['num_encomenda'];

            $sql = "select COUNT(e.num_encomenda) as total, c.nome 
                    FROM encomendas e
                    INNER JOIN clientes c ON (e.cod_cliente = c.cod_cliente)";

            $r = $this->pdo->query($sql);
            
            return $r->fetch(PDO::FETCH_ASSOC);
        }
    }

?>