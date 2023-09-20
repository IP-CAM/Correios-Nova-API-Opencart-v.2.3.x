<?php echo $header; ?>

<!--
Módulo Comercial - Loja5.com.br
Proibido vender, revender ou distribuir o mesmo sem a devida licença de uso de acordo os termos da lei 9.609/98 e nº 9.610/98, que protegem o direito de autor sobre programas de computador.
https://www.loja5.com.br/termos-de-compra-suporte-loja5-i5.html 
-->

<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script>
$(function(){
	$(".dinheiro").maskMoney({thousands:'', decimal:'.', allowZero:true, suffix: ''});
});
</script>

<?php echo $column_left; ?>

<div id="content">

<div class="page-header">
<div class="container-fluid">
<div class="pull-right">
<a href="<?php echo $voltar; ?>" class="btn btn-default"><i class="fa fa-reply"></i> Voltar</a>
<a href="<?php echo $limpar_cache; ?>" onclick="return confirm('Confirma limpar o cache?')" class="btn btn-danger"><i class="fa fa-remove"></i> Limpar Cache</a>
</div>
<h1><?php echo $heading_title; ?></h1>
<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
</ul>
</div>
</div>

<div class="container-fluid">

<div class="alert alert-info" role="alert">Lista de servi&ccedil;os que tem suporte a tabelas offline na loja, por esta lista poder&aacute; atualizar, limpar ou consultar os dados de tabela offline, lembre-se sempre que adicionar, editar ou remover uma faixa de CEP dever&aacute; ser atualizado as tabelas e atualize somente os servi&ccedil;os qual realmente vai usar em sua loja e ativado no m&oacute;dulo.</div>

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-lista"></i> Tabelas</h3>
</div>
<div class="panel-body">

<table id="correiospro5-servicos" class="display table table-bordered" cellspacing="0" width="100%">
<thead>
<tr>
<th>ID Servi&ccedil;o</th>
<th>Nome</th>
<th>Faixas Atualizadas / Total</th>
<th></th>
</tr>
</thead>
<tbody>
<?php
if($lista_servicos->num_rows > 0){
?>
	<?php 
	foreach($lista_servicos->rows AS $k=>$v){
	?>
	<tr>
	<td><?php echo $v['id_servico'];?></td>
	<td><?php echo $v['nome'];?></td>
	<td><?php echo $v['cotacoes_cadastradas']?> / <?php echo $total_faixas->num_rows*31; ?></td>
	<td><a href="<?php echo $atualizar_tabela; ?>&servico=<?php echo $v['id_servico'];?>" class="btn btn-success btn-sm">Atualizar</a> <a href="<?php echo $valores_tabela; ?>&servico=<?php echo $v['id_servico'];?>" class="btn btn-info btn-sm">Valores</a> <a onclick="return confirm('Confirmar limpar a tabela?');" href="<?php echo $limpar_tabela; ?>&servico=<?php echo $v['id_servico'];?>" class="btn btn-danger btn-sm">Limpar</a></td>
	</tr>
	<?php
	}
	?>
<?php
}else{
?>
<tr>
	<td colspan="8">Ops, nenhum servi&ccedil;o cadastrado, clique em adicionar!</td>
</tr>
<?php 
}
?>
</tbody>
</table>

</div>
</div>

</div>
</div>

<?php echo $footer; ?>