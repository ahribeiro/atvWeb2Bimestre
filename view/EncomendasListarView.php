<script type="text/javascript">

	// varivável global no javascript
	var mensagem = '<?php echo @$mensagem; ?>';


	//------------------------------------------------------------------------------------------------
	function AbrirItensEncomenda(cod_item_encomenda, num_encomenda, cod_cliente)
	{
		$('#div_modal_itens_encomenda').html( 'ITENS ENCOMENDA: ');

		$('#div_form_modal_itens_encomenda').load('index.php?modulo=itensencomenda&acao=form', {cod_item_encomenda:cod_item_encomenda});

		$('#div_status_modal_itens_encomenda').html("<img src='view/_imagens/ajax-loader.gif'>Carregando, aguarde...");
	
		/**/
		$('#div_lista_modal_itens_encomenda').load('index.php?modulo=itensencomenda&acao=listar'
												, {cod_item_encomenda:cod_item_encomenda}
												, function(){
													$('#div_status_modal_itens_encomenda').html("");
												  }
										      );
		/**/

		// Abre a janela modal
		$('#modalform_itens_encomenda').modal('show');

	} // AbrirComposicao()

	
	//---------------------------------------------------------------
	function Incluir()
	{
		document.location="index.php?modulo=encomenda&acao=incluindo";
	} // Incluir

	//---------------------------------------------------------------
	function Alterar(num_encomenda)
	{
		document.location="index.php?modulo=pratos&acao=alterando&num_encomenda="+num_encomenda;
	} // Alterar

	//---------------------------------------------------------------
	function Excluir(num_encomenda)
	{
		if( confirm("Deseja realmente excluir este registro???") )
		{
			document.location='index.php?modulo=pratos&acao=excluir&num_encomenda='+num_encomenda;
		}

	} // Excluir


//-Quando a página estiver totalmente carregada --------
$(document).ready( function(){ 

	if( mensagem != "" )
	{
		$("#div_status").show();
		$("#div_status").html( mensagem );
	}

	// colocando o foco na caixa de edição da pesquisa 
	$('#pesquisa').focus();
	$('#pesquisa').select();
    
}); // ready

</script>

<div class="container">


	<div class="row">
		<div class="col-md-6">
			<div class="page-header">
				<h1>Encomendas <small>Listagem</small></h1>
			</div>	
		</div>

		<div class="col-md-6">
			<div class="text-bottom text-right">
				<a href="javascript:Incluir();"  class="btn btn-success">Novo Registro</a>
			</div>	
		</div>
	</div>	

	<div class="row" id="div_form_pesquisa">
		<div class="col-md-12">

			<div id="div_status" class="alert alert-danger" style="display: none;"></div>

			<form name="fpesquisa" id="fpesquisa" method="post" action="index.php?modulo=pratos"> <!--class="form-inline" -->

				<?php
				$pesquisa = @$_POST['pesquisa'];
				?>

				<div class="form-group">
					<label for="pesquisa">Pesquise pela encomenda</label>
					<input type="text" class="form-control" placeholder="Digite sua pesquisa" name="pesquisa" id="pesquisa" size="40" value="<?php echo $pesquisa; ?>">
				</div>

				<input type="submit" name="btpesquisar" id="btpesquisar" value="Pesquisar" class="btn btn-primary">

			</form>
		</div>
	</div>
	<br>


	<div class="row">
		<div class="col-md-12">


			<?php

			if( @$_GET['msg'] != '' )
			{
				echo '<div class="alert alert-danger">' . @$_GET['msg'] . '</div>';
			}


			echo '<table class="table table-hover">';
			echo '<tr>';
			echo ' <td class="text-center"><strong>Código</strong></td>';
			echo ' <td><strong>Descrição</strong></td>';
			echo ' <td  class="text-left"><strong>Categoria</strong></td>';
			echo ' <td  class="text-right"><strong>Valor Unitário</strong></td>';
			echo ' <td  class="text-center"><strong>Opções</strong></td>';
			echo '</tr>';

			// obtendo o próximo registro da consulta
			while( $dados = $lista_prato->fetch(PDO::FETCH_ASSOC)  )
			{
				echo '<tr class="active">';
				echo ' <td class="text-center">'.$dados['cod_prato'].'</td>';
				echo ' <td>'.$dados['descricao'] .'</td>';
				echo ' <td class="text-left">'.$dados['categoria'] .'</td>';
				echo ' <td class="text-right">'. number_format($dados['valor_unitario'],2,',','.') .'</td>';

				echo ' <td class="text-center">';


				echo '<a class="btn btn-primary btn-xs" href="javascript:AbrirComposicao('.$dados['cod_prato'].',\''.$dados['descricao'].'\');">Composição</a>';

				echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				
				echo '<a class="btn btn-warning btn-xs" href="javascript:Alterar('.$dados['cod_prato'].');">Alterar</a>';
				
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';

				//echo '<a href="pratos_gravar.php?acao=excluir&cod_prato='.$dados['cod_prato'].'">Excluir</a>';

				echo '<a class="btn btn-danger btn-xs" href="javascript:Excluir('.$dados['cod_prato'].');">Excluir</a>';

				echo '</td>';

				echo '</tr>';
			} // while

			echo '</table>';

			?>

		</div> <!-- col -->

	</div> <!-- row -->

</div> <!-- container -->



<!-- JANELA MODAL : https://getbootstrap.com/docs/4.0/components/modal/ -->

<!-- Modal -->

<!-- small
<div class="modal fade" id="modalform_composicao" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
 -->

 <!-- large -->
<div class="modal fade bd-example-modal-lg" id="modalform_composicao"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">
        	<b><div id="div_modal_nome_prato"></div></b>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      	<div class="modal-body" id="div_conteudo_modal_composicao">
	      	<div id="div_form_modal_composicao"></div>
	      	<div id="div_lista_modal_composicao"></div>
	      	<div id="div_status_modal_composicao"></div>
      	</div>
      <div class="modal-footer">
      	<!--
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
    	-->
    	<button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

