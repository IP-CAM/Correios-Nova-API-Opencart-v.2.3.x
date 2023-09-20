<?php echo $header; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<!--
Módulo Comercial - Loja5.com.br
Proibido vender, revender ou distribuir o mesmo sem a devida licença de uso de acordo os termos da lei 9.609/98 e nº 9.610/98, que protegem o direito de autor sobre programas de computador.
https://www.loja5.com.br/termos-de-compra-suporte-loja5-i5.html 
-->

<?php echo $column_left; ?>

<div id="content">

<div class="page-header">
<div class="container-fluid">
<div class="pull-right">
<a href="<?php echo $voltar; ?>" class="btn btn-default"><i class="fa fa-reply"></i> Voltar</a></div>
<h1><?php echo $heading_title; ?></h1>
<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
</ul>
</div>
</div>

<div class="container-fluid">

<div class="alert alert-info" role="alert">Ao iniciar o processo de atualiza&ccedil;&atilde;o dever&aacute; aguardar o procedimento at&eacute; que o mesmo seja concluido, n&atilde;o feche esta janela e lembre-se que servi&ccedil;os com contrato s&oacute; funciona caso tenha contrato Correios e o mesmo esteja devidamente configurado, lembre-se que os valores calculados s&atilde;o apenas estimativas para uso em contig&ecirc;ncia, recomendamos sempre atualizar durante a noite ou madrugada pois os correios api funciona melhor neste horario, o processo de atualiza&ccedil;&atilde;o dever&aacute; ser feito at&eacute; as cota&ccedil;&otilde;es se igualarem ao limite total.</div>

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-upload"></i> Atualizar Tabelas (<?php echo $id_servico;?>)</h3>
</div>
<div class="panel-body">

<div id="barra-progresso" style="height: 35px; border: 1px solid #CCC; border-radius: 5px;">
<div id="atualizar-progresso" style="border-radius: 5px; line-height: 35px; width: 0%; background-color: #8BC34A; text-align: center; color: #FFF;">0%</div>
</div>
<br>
<button type="button" onclick="calcular_fretes(0)" id="botao-atualizar-faixas" class="btn btn-info">Confirmar e Atualizar</button>

</div>
</div>

</div>
</div>

<script>
//faixas ceps base
var faixa_ceps = [];
<?php foreach($faixas as $k=>$v){?>
faixa_ceps.push('<?php echo str_pad($v['base_cep'], 8, "0", STR_PAD_LEFT); ?>');
<?php } ?>

//faixas ceps de
var faixa_ceps_inicio = [];
<?php foreach($faixas as $k=>$v){?>
faixa_ceps_inicio.push('<?php echo str_pad($v['inicio'], 5, "0", STR_PAD_LEFT); ?>');
<?php } ?>

//faixas ceps para
var faixa_ceps_fim = [];
<?php foreach($faixas as $k=>$v){?>
faixa_ceps_fim.push('<?php echo str_pad($v['fim'], 5, "0", STR_PAD_LEFT); ?>');
<?php } ?>

//faixas custom
var faixa_custom = [];
<?php foreach($faixas as $k=>$v){?>
faixa_custom.push('<?php echo $v['custom']; ?>');
<?php } ?>

//calcula os fretes
var progresso = 0;
function calcular_fretes(inicio){
    jQuery('#botao-atualizar-faixas').attr("disabled","disabled").html('Aguarde o termino e n&atilde;o feche a p&aacute;gina!');
    var incremento = inicio;
    var arrayStop = faixa_ceps.length-1;
    var pulo = <?php echo number_format((100/count($faixas)), 2, '.', '');?>;
	jQuery.ajax({
		method: "POST",
		url: "<?php echo $api; ?>",
		data: { custom: faixa_custom[inicio], cep: faixa_ceps[inicio], index: inicio, servico: '<?php echo $id_servico;?>', de: faixa_ceps_inicio[inicio], para: faixa_ceps_fim[inicio] }
	}).done(function( ret ) {
		
		//incrementa o bar
		console.log(progresso);
		progresso += pulo;
		
		//regra para nao passar de 100
		if(progresso >= 100){
			progresso = 100;
		}
		
		//alimenta o bar
		jQuery('#atualizar-progresso').css('width', progresso.toFixed(2)+'%'); 
		jQuery('#atualizar-progresso').html(progresso.toFixed(2)+'%'); 
		
		//verifica se e a ultima
		incremento += 1;
		if(inicio < arrayStop){
			calcular_fretes(incremento);
		}else{
			console.log('fim');
			jQuery('#atualizar-progresso').css('width', '100%'); 
			jQuery('#atualizar-progresso').html('100%'); 
			jQuery('#botao-atualizar-faixas').attr("disabled","disabled").html('Concluido');
			setTimeout(function(){location.href='<?php echo $tabelas; ?>';}, 10000);
		}
	}).fail(function(jqXHR, textStatus) {
		toastr.erro( "Ops ocorreu um erro ao atualizar a tabela, o sistema de webservice dos Correios podera esta lento ao fora de servico, tente novamente mas tarde!" );
		jQuery('#botao-atualizar-faixas').removeAttr("disabled").html('Confirmar e Atualizar');
		progresso = incremento-1;
		//calcular_fretes(incremento-1);
	});
}
</script>

<?php echo $footer; ?>