<?php echo $header; ?>

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

<div class="alert alert-info" role="alert">Lista de valores cadastrados e atualizados para a tabela selecionada, lembre-se que os valores e prazo s&atilde;o apenas estimativas baseadas no CEP base X Cep Origem para cada faixa de CEP correspondente.</div>

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-lista"></i> Valores de Tabelas</h3>
</div>
<div class="panel-body">

<div class="table-responsive">
<table class="table table-bordered table-hover">
<thead>

<tr>
<td>ID</td>
<td>Servi&ccedil;o</td>
<td>Erro</td>
<td>Valor</td>
<td>Prazo</td>
<td>Peso</td>
<td>Faixa</td>
<td>Base</td>
<td>Atualizado</td>
</tr>

</thead>
<tbody>
<?php 
if ($valores->num_rows > 0) {
?>
<?php foreach ($valores->rows as $k => $v) { ?>
<tr>
<td><?php echo str_pad($v['id'], 5, "0", STR_PAD_LEFT); ?></td>
<td><?php echo $v['id_servico']; ?> - <?php echo $v['nome']; ?></td>
<td><?php echo $v['erro']; ?></td>
<td><?php echo $v['valor']; ?> R$</td>
<td><?php echo $v['prazo']; ?> dia(s)</td>
<td><?php echo $v['peso']; ?> kg</td>
<td><?php echo str_pad($v['cep_inicio'], 5, "0", STR_PAD_LEFT); ?>-000 at&eacute; <?php echo str_pad($v['cep_fim'], 5, "0", STR_PAD_LEFT); ?>-999</td>
<td><?php echo str_pad($v['cep_base'], 8, "0", STR_PAD_LEFT); ?></td>
<td><?php echo $v['atualizado']; ?></td>
</tr>
<?php } ?>
<?php } else { ?>
<tr>
<td class="text-center" colspan="10">Nenhum registro disponivel!</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>

</div>
</div>

</div>
</div>

<?php echo $footer; ?>