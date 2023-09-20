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
</div>
<h1>Logs Correios Online e Offline Pro</h1>
<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
</ul>

<div style="max-width:20%;" class="pull-right">
<a onclick="return confirm('Confirma limpar os logs?')" href="<?php echo $link_remover; ?>" class="btn btn-danger"><i class="fa fa-times"></i> Limpar</a>
<a href="<?php echo $link_configurar; ?>" class="btn btn-info"><i class="fa fa-cog"></i> Configurar</a>
</div>

</div>
</div>

<div class="container-fluid">

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-file"></i> Logs</h3>
</div>
<div class="panel-body">
<textarea wrap="off" rows="15" readonly class="form-control"><?php echo $log; ?></textarea>
</div>
</div>

</div>
</div>

<?php echo $footer; ?>