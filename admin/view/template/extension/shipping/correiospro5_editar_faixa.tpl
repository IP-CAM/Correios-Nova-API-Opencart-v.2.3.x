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

<div class="alert alert-info" role="alert">Adicione as faixas de cep de acordo deseja usar em sua loja, fique atento para n&atilde;o adicionar faixas duplicadas, lembrando que sempre que atualizar qualquer faixa de cep dever&aacute; atualizar as tabelas de frete, em caso de uso de op&ccedil;&otilde;es de faixas de CEPs pode cidades, baixos e estados e n&atilde;o sabe como as consultar <a href="https://loja5.zendesk.com/hc/pt-br/articles/360027736452-Lista-de-Faixas-de-CEPs-por-Estados-Cidades-e-Bairros" target="_blank">clique aqui</a>.</div>

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-plus"></i> Adicionar Faixa</h3>
</div>
<div class="panel-body">

<form method="post" action="<?php echo $salvar_faixa;?>" class="form-horizontal">

<input type="hidden" name="id" value="<?php echo $faixa['id']; ?>">

<div class="form-group">
<label for="inputEmail3" class="col-sm-3 control-label">Estado (UF)</label>
<div class="col-sm-9">
<input style="width:50%" type="text" maxlength="2" onkeyup="this.value = this.value.toUpperCase();" class="form-control" id="uf" name="uf" placeholder="Ex: SP" value="<?php echo $faixa['uf']; ?>" required>
<span id="helpBlock" class="help-block">UF do estado correspondente a faixa de CEP.</span>
</div>
</div>

<div class="form-group">
<label for="inputEmail3" class="col-sm-3 control-label">Detalhes</label>
<div class="col-sm-9">
<input type="text" class="form-control" id="detalhes" name="detalhes" value="<?php echo $faixa['detalhes']; ?>" placeholder="Ex: Capital do estado X">
<span id="helpBlock" class="help-block">Detalhes da faixa.</span>
</div>
</div>

<div class="form-group">
<label for="inputEmail3" class="col-sm-3 control-label">CEP Inicial</label>
<div class="col-sm-9">
<div style="width:50%" class="input-group">
<input type="text" value="<?php echo $faixa['inicio']; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="5" class="form-control" id="cep_inicio" name="cep_inicio" placeholder="Somente numeros!" required>
<span class="input-group-addon" id="basic-addon2">-000</span>
</div>
<span id="helpBlock" class="help-block">Os 5 digitos iniciais do CEP.</span>
</div>
</div>

<div class="form-group">
<label for="inputEmail3" class="col-sm-3 control-label">CEP Final</label>
<div class="col-sm-9">

<div style="width:50%" class="input-group">
<input type="text" value="<?php echo $faixa['fim']; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="5" class="form-control" id="cep_final" name="cep_final" placeholder="Somente numeros!" required>
<span class="input-group-addon" id="basic-addon2">-999</span>
</div>
<span id="helpBlock" class="help-block">Os 5 digitos iniciais do CEP.</span>

</div>
</div>
<div class="form-group">
<label for="inputEmail3" class="col-sm-3 control-label">CEP Base</label>
<div class="col-sm-9">
<input style="width:50%" type="text" value="<?php echo $faixa['base_cep']; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="8" class="form-control" id="cep_base" name="cep_base" placeholder="Somente numeros!" required>
<span id="helpBlock" class="help-block">Este CEP ser&aacute; o base usado para calculo do frete junto aos correios para toda faixa.</span>
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-3 col-sm-10">
<button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar Faixa</button>
</div>
</div>

</form>

</div>
</div>

</div>
</div>

<?php echo $footer; ?>