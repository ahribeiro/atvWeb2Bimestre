<?php 

namespace Model;

use \PDO; // necessário para utilizar recursos da classe PDO

use \lib\bd;

class IngredientesModel
{
	private $pdo;

	//-----------------------------------------------------
	function __construct()	{		
		$meu_BD = new BD();	
		$this->pdo = $meu_BD->pdo;
	}
	
	
	//-----------------------------------------------------
	public function Get_ingrediente($cod_ingrediente)	{

		$sql = " select * 
				 from ingredientes
				 where cod_ingrediente = '$cod_ingrediente' ";				  

		$r = $this->pdo->query($sql);
		return $r->fetch(PDO::FETCH_ASSOC);

	} // alterar

	//-----------------------------------------------------
	public function Get_lista($pesquisa)	{

		$sql = " select i.*, u.descricao as unidade
				 from 	ingredientes i
				 		left outer join unidades u on (i.cod_unidade = u.cod_unidade)
				 where i.descricao like '%$pesquisa%' 
				 order by i.descricao ";

		return $this->pdo->query($sql);

	} // alterar
	
	//-----------------------------------------------------	
	public function Incluir($dados)	{	

		$sql = " select * from ingredientes where descricao = '".$dados['descricao']."' ";
		$r = $this->pdo->query($sql);

		if( !$d = $r->fetch(PDO::FETCH_ASSOC) )
		{

			$sql = "insert into ingredientes (descricao, cod_unidade, valor_unitario) values (:descricao, :cod_unidade, :valor_unitario) ";

			$cmd = $this->pdo->prepare($sql);
			$cmd->bindValue(':descricao',$dados['descricao']);
			$cmd->bindValue(':cod_unidade',$dados['cod_unidade']);
			$cmd->bindValue(':valor_unitario', floatUSA($dados['valor_unitario']));
			$cmd->execute();

			return '';
		}
		else 
		{
			return 'O ingrediente <b>'.$dados['descricao'].'</b> já está cadastrado!';
		}

	} // incluir

	//-----------------------------------------------------
	public function Alterar($dados)	{			

		$sql = " update ingredientes set 
					descricao = :descricao,
					cod_unidade   = :cod_unidade,
					valor_unitario = :valor_unitario
				 where cod_ingrediente = :cod_ingrediente
			   ";

		$cmd = $this->pdo->prepare($sql);
		$cmd->bindValue(':descricao',$dados['descricao']);
		$cmd->bindValue(':cod_unidade',$dados['cod_unidade']);
		$cmd->bindValue(':valor_unitario', floatUSA($dados['valor_unitario']));
		$cmd->bindValue(':cod_ingrediente',$dados['cod_ingrediente']);
		$cmd->execute();


	} // alterar

	//-----------------------------------------------------
	public function Excluir($cod_ingrediente)	{			

		$sql = " select count(*) as total from composicao where cod_ingrediente = '$cod_ingrediente' ";

		$r = $this->pdo->query($sql);
		$d = $r->fetch(PDO::FETCH_ASSOC);

		if( $d['total'] == 0 )
		{
			$sql = " delete from ingredientes where cod_ingrediente = :cod_ingrediente ";

			$cmd = $this->pdo->prepare($sql);
			$cmd->bindValue(':cod_ingrediente',$cod_ingrediente);
			$cmd->execute();
			
			return '';
		}
		else 
		{
			return 'Este ingrediente não pode ser excluído porque está relacionado a registros de composição de prato!';
		}


	} // excluir
}