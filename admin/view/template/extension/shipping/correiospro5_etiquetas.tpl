<?php echo $header; ?>

<?php echo $column_left; ?>

<div id="content">

<div class="page-header">
<div class="container-fluid">
<h1>Etiquetas de Postagem</h1>
<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
</ul>
</div>
</div>

<div class="container-fluid">

<div class="well">
<div class="row">

<div class="col-sm-6">
<div class="form-group">
<label class="control-label" for="input-order-id">Pedido ID</label>
<input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="ID" id="input-order-id" class="form-control" />
</div>
<div class="form-group">
<label class="control-label" for="input-customer">Cliente</label>
<input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="Cliente" id="input-customer" class="form-control" />
</div>
</div>

<div class="col-sm-6">
<div class="form-group">
<label class="control-label" for="input-order-status">Status</label>
<select name="filter_order_status" id="input-order-status" class="form-control">
<option value="*">Todos</option>
<?php foreach ($order_statuses as $order_status) { ?>
<?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
<?php } else { ?>
<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
<?php } ?>
<?php } ?>
</select>
</div>
<div class="form-group text-center">
<div class="btn-group" role="group" aria-label="true">
<button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> Filtrar Pedidos</button>
<button type="button" onclick="imprimirEtiquetas()" class="btn btn-warning"><i class="fa fa-print"></i> Imprimir Etiquetas</button>
</div>
<br><br>
<i>As etiquetas de postagem n&atilde;o &eacute; e nem possue integra&ccedil;&atilde;o SIGEP!</i>
</div>
</div>

</div>
</div>
        

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-list"></i> Pedidos (<?php echo $total_registro;?> registros)</h3>
</div>
<div class="panel-body">

<form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
<div class="table-responsive">
<table class="table table-bordered table-hover">
<thead>

<tr>
<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
<td class="text-right">Pedido ID</td>
<td class="text-left">Cliente</td>
<td class="text-left">Total</td>
<td class="text-left">CEP</td>
<td class="text-left">Metodo</td>
<td class="text-left">Status</td>
<td class="text-left">Data</td>
</tr>

</thead>
<tbody>
<?php if ($orders) { ?>
<?php foreach ($orders as $order) { ?>
<tr>
<td class="text-center">
<input type="checkbox" name="selected" value="<?php echo $order['order_id']; ?>" />
</td>
<td class="text-right" style="width:100px"><?php echo $order['order_id']; ?></td>
<td class="text-left"><?php echo $order['customer']; ?></td>
<td class="text-left"><?php echo $order['total']; ?></td>
<td class="text-left"><?php echo $order['cep']; ?></td>
<td class="text-left"><?php echo $order['meio']['meio']; ?> (<?php echo $order['meio']['total']; ?>)</td>
<td class="text-left"><?php echo $order['status']; ?></td>
<td class="text-left" style="width:100px"><?php echo $order['date_added']; ?></td>
</tr>
<?php } ?>
<?php } else { ?>
<tr>
<td class="text-center" colspan="8">Nenhum registro disponivel!</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</form>

<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>

</div>
</div>

</div>
</div>

<script type="text/javascript">
function popupwindow(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}

function imprimirEtiquetas(){
	var checkedVals = $('input[name^=\'selected\']:checked').map(function() {
    return this.value;
	}).get();
	if(!checkedVals || checkedVals==''){
		alert('Selecione no minimo um pedido!');
		return false;
	}
	var selecionados = checkedVals.join(",");
	console.log(selecionados);
	return popupwindow('index.php?route=extension/shipping/correiospro5/imprimir&token=<?php echo $token; ?>&pedidos='+selecionados, 'Imprimir etiquetas', 800, 600);
}

$('#button-filter').on('click', function() {
	url = 'index.php?route=extension/shipping/correiospro5/etiquetas&token=<?php echo $token; ?>';
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	if (filter_order_id) {
	url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}
	var filter_customer = $('input[name=\'filter_customer\']').val();
	if (filter_customer) {
	url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	var filter_order_status = $('select[name=\'filter_order_status\']').val();
	if (filter_order_status != '*') {
	url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}
location = url;
});

$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
	$.ajax({
	url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
	dataType: 'json',			
	success: function(json) {
	response($.map(json, function(item) {
	return {
	label: item['name'],
	value: item['customer_id']
	}
	}));
	}
	});
	},
	'select': function(item) {
	$('input[name=\'filter_customer\']').val(item['label']);
	}	
});
</script> 

<?php echo $footer; ?>