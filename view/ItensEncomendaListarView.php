<script type="text/javascript">

	//---------------------------------------------------------------
	function Excluir_Item(cod_item_encomenda, num_encomenda, cod_cliente)
	{

		if( confirm("Deseja realmente excluir este registro ???") )
		{
		
			$('#div_status_modal_itensencomenda').html("<img src='view/_imagens/ajax-loader.gif'>Excluindo item, aguarde...");

			$.post('index.php?modulo=itensencomenda&acao=excluir'
					, { cod_item_encomenda: cod_item_encomenda, 
						num_encomenda:num_encomenda,
						cod_cliente:cod_cliente
					  }

					, function(erro){

						if( erro == '' )
						{
							$('#div_status_modal_itensencomenda').html("<img src='view/_imagens/ajax-loader.gif'>Carregando, aguarde...");
							
							$('#div_lista_modal_itensencomenda').load('index.php?modulo=itensencomenda&acao=listar'
																	, {cod_item_encomenda:cod_item_encomenda}
																	, function(){
																		$('#div_status_modal_itensencomenda').html("");
																	  }
															      );

						} // não houve erro na exclusão
						else
						{
							$('#div_status_modal_itensencomenda').html( '<span style="color:#F00;">'  + erro + "</span>");
						}						

					  } // function(erro){

	               ); // $.post('index.php?modulo=composicao&acao=incluir'

		} // se confirmou a exclusão
		
		

	} // Excluir


</script>


<div class="row">
	<div class="col-md-12">

		<?php

		$dados = $lista_itens->fetch(PDO::FETCH_ASSOC);

		// se houver ingredientes nesta composição
		if( $dados )
		{
			echo '<table class="table table-hover">';
			echo '<tr>';
			echo ' <td><strong>Prato</strong></td>';
			echo ' <td><strong>Quantidade</strong></td>';
			echo ' <td align="right"><strong>Vl. Unit&aacute;rio</strong></td>';
			echo ' <td align="right"><strong>Vl. Total</strong></td>';
			echo '</tr>';

			$vl_total_custo = 0;
			$vl_prato = $dados['valor_prato'];

			// obtendo o próximo registro da consulta
			while( $dados )
			{
				$vl_total_custo += $dados['valor_custo'];

				echo '<tr class="active">';
				
				echo ' <td>'.$dados['prato'] .'</td>';
				echo ' <td align="right">'.number_format($dados['qde'],2,',','.') .'</td>';
				echo ' <td align="left">'. $dados['quantidade'] .'</td>';
				echo ' <td align="right">'.number_format($dados['valor_unitario'],2,',','.') .'</td>';
				echo ' <td align="right">'.number_format($dados['total'],2,',','.') .'</td>';

				echo ' <td class="text-center">';
				
				echo '<a class="btn btn-danger btn-xs" href="javascript:Excluir_Item('.$dados['cod_num_encomenda'].',' .$dados['cod_prato'].');">Excluir</a>';

				echo '</td>';

				echo '</tr>';

				$dados = $lista_itens->fetch(PDO::FETCH_ASSOC);

			} // while

			// echo '<tr class="active">';
			// echo '  <td colspan="3"><b>CUSTO DO PRATO</b></td>';
			// echo '  <td colspan="3" align="right"><b> <span style="color:#F00">R$ ' . number_format($vl_total_custo,2,',','.') . '</span></b></td>';
			// echo '</tr>';

			// echo '<tr class="active">';
			// echo '  <td colspan="3"><b>VALOR DE VENDA DO PRATO</b></td>';
			// echo '  <td colspan="3" align="right"><b> <span style="color:#00F">R$ ' . number_format($vl_prato,2,',','.') . '</span></b></td>';
			// echo '</tr>';

			// if( $vl_prato - $vl_total_custo > 0 )
			// {
			// 	echo '<tr class="active">';
			// 	echo '  <td colspan="3"><b><span style="color:#00F">LUCRO DO PRATO</span></b></td>';
			// 	echo '  <td colspan="3" align="right"><b> <span style="color:#00F">R$ ' . number_format($vl_prato - $vl_total_custo,2,',','.') . '</span></b></td>';
			// 	echo '</tr>';
			// }
			// else
			// {
			// 	echo '<tr class="active">';
			// 	echo '  <td colspan="3"><b><span style="color:#F00">PREJUIZO DO PRATO</span></b></td>';
			// 	echo '  <td colspan="3" align="right"><b> <span style="color:#F00">R$ ' . number_format($vl_total_custo-$vl_prato,2,',','.') . '</span></b></td>';
			// 	echo '</tr>';
			// }


			echo '</table>';

		} // if( $dados )
		else
		{
			echo '<p>Não há itens relacionados com está encomenda !!!</p>';
		}

		


		?>

	</div>
</div>
