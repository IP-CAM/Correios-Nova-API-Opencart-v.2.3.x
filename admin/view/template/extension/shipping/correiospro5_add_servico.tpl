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

<div class="alert alert-info" role="alert">Cadastre o servi&ccedil;o de entrega Correios que deseja ativar em sua loja, fique atento a todos os detalhes e configure de forma Correta, lembre-se que ao ativar um metodo de envio com contrato &eacute; obrigatorio que j&aacute; tenha contrato Correios configurado, em caso de uso de op&ccedil;&otilde;es de faixas de CEPs pode cidades, baixos e estados e n&atilde;o sabe como as consultar <a href="https://loja5.zendesk.com/hc/pt-br/articles/360027736452-Lista-de-Faixas-de-CEPs-por-Estados-Cidades-e-Bairros" target="_blank">clique aqui</a>.</div>

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-plus"></i> Cadastro de Servi&ccedil;os</h3>
</div>
<div class="panel-body">

<form method="post" action="<?php echo $salvar_add_servico;?>" class="form-horizontal">

<div class="form-group">
<label class="col-sm-2 control-label" for="input">Servi&ccedil;o Correios</label>
<div class="col-sm-10">
<select class="form-control" name="servico" required>
<?php foreach ($lista_servicos->rows as $servico) { ?>
<option value="<?php echo $servico['id_servico']; ?>"><?php echo $servico['id_servico']; ?> - <?php echo $servico['nome']; ?> <?php echo ($servico['suporte_offline'])?'(Suporta Offline)':''; ?></option>
<?php } ?>
</select>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" for="input">Nome de Exibicao</label>
<div class="col-sm-10">
<input type="text" name="nome" value="" placeholder="Ex: PAC" class="form-control" required />
<span id="helpBlock" class="help-block">Nome do m&eacute;todo a exibir ao cliente.</span>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" for="input">Prazo Extra</label>
<div class="col-sm-10">
<input type="text" name="prazo_extra" value="0" class="form-control" />
<span id="helpBlock" class="help-block">Prazo extra a somar aos dos Correios.</span>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" for="input">Valor Extra / Desconto</label>
<div class="col-sm-10">
<input type="text" name="valor_extra" value="0.00" class="dinheiro form-control" />
<span id="helpBlock" class="help-block">Valor extra a somar ou descontar ao real total dos Correios.</span>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" for="input">Tipo</label>
<div class="col-sm-10">
<select class="form-control" name="tipo_valor_extra">
<option value="0" selected>Acrescimo Real - R$</option>
<option value="1">Acrescimo Porcentagem - %</option>
<option value="2">Desconto Porcentagem - %</option>
<option value="3">Desconto Real - R$</option>
</select>
</div>
</div>

<input type="hidden" name="peso_maximo" value="30.00" class="dinheiro form-control" />
<input type="hidden" name="total_maximo" value="10000.00" class="dinheiro form-control" />

<div class="form-group">
<label class="col-sm-2 control-label" for="input">Min. Desconto</label>
<div class="col-sm-10">
<input type="text" name="minimo" value="0.00" class="dinheiro form-control" />
<span id="helpBlock" class="help-block">Valor minimo que um pedido precisa ter para ter descontos (0.00 para n&atilde;o ativar).</span>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" for="input">Frete Gr&aacute;tis aos CEPs (<a href="https://loja5.zendesk.com/hc/pt-br/articles/360027736452-Lista-de-Faixas-de-CEPs-por-Estados-Cidades-e-Bairros" target="_blank">?</a>)</label>
<div class="col-sm-10">
<textarea placeholder="" name="ceps" class="form-control"></textarea>
<span id="helpBlock" class="help-block">Informe as faixas de CEP que deseja aplicar frete gratuito baseado na regra de faixa de CEP e o total minimo informado abaixo, lembre-se que os CEPs devem ser separados por virgula sendo cada faixa em uma linha, deixe vazio para aceitar em todos locais.<br>Exemplo: 22450000,22450999</span>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" for="input">Min. Frete Gratuito</label>
<div class="col-sm-10">
<input type="text" name="minimo_frete" value="0.00" class="dinheiro form-control" />
<span id="helpBlock" class="help-block">Valor minimo que um pedido precisa ter para ter frete gratis (0.00 para n&atilde;o ativar).</span>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" for="input">CEPs a Excluir Entrega (<a href="https://loja5.zendesk.com/hc/pt-br/articles/360027736452-Lista-de-Faixas-de-CEPs-por-Estados-Cidades-e-Bairros" target="_blank">?</a>)</label>
<div class="col-sm-10">
<textarea placeholder="" name="ceps_excluir" class="form-control"></textarea>
<span id="helpBlock" class="help-block">Informe as faixas de CEP caso queira limitar os locais de entrega, use o CEP inicial e o final (somente n&uacute;meros) separados por virgula sendo cada faixa em uma linha, os ceps informados seram excluidos de usar o metodo de entrega em quest&atilde;o.<br>Exemplo: 22450000,22450999</span>
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
</div>
</div>

</form>

</div>
</div>

</div>
</div>

<?php echo $footer; ?>