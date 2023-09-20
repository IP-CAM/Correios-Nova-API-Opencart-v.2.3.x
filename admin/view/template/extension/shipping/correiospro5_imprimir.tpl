<html>
<head>
<title>Etiquetas Correios</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
{literal}
@media print {
	.quebrapagina {page-break-after: always;}
}
hr.linha {
  border:none;
  border-top:1px dashed #000;
  height:1px;
  width:90%;
}
@media print
{
.no-print, .no-print * {
display: none !important;
}
}
.style1 {
font-family: Arial, Helvetica, sans-serif;
font-size: 13px;
}
.style2 {
font-family: Arial, Helvetica, sans-serif;
font-size: 10px;
}
.style4 {
font-family: Verdana, Arial, Helvetica, sans-serif;
font-size: 10px;
}
.style6 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; }
{/literal}
</style>
</head>
<body>
<center>
<img onclick="self.print();" class="no-print" style="cursor:pointer" src="../image/correios/img/imprimir.jpg" border="0">

<?php
$i=0;
foreach($pedidos as $pedido){
$i=$i+1;
?>

<fieldset style="width:80%;border-style: dashed;margin-bottom:5px;padding: 20px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="100%"  valign="top">
<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">

<tr>
<td colspan="2" valign="top">
<img width="120" src="../image/correios/img/destinatario.gif" border="0">
</td>
</tr>

<tr>
<td valign="top">
<span class="style6">
<b><?php echo $pedido['shipping_firstname'];?> <?php echo $pedido['shipping_lastname'];?></b><br>
<?php echo $pedido['shipping_address_1'];?> <?php echo isset($pedido['shipping_custom_field'][$config['correiospro5_custom_numero']])?$pedido['shipping_custom_field'][$config['correiospro5_custom_numero']]:'';?> <?php echo isset($pedido['shipping_custom_field'][$config['correiospro5_custom_complemento']])?$pedido['shipping_custom_field'][$config['correiospro5_custom_complemento']]:'';?><br>
<?php echo $pedido['shipping_address_2'];?><br>
<?php echo $pedido['shipping_city'];?>/<?php echo $pedido['shipping_zone_code'];?><br>
<b>CEP:</b> <?php echo $pedido['shipping_postcode'];?>
</span>
</td>
<td width="100" align="right" valign="top">
<?php if (!empty($pedido['chancela'])){ ?>
<img src="<?php echo $pedido['chancela']; ?>" width="90">
<?php } ?>
</td>
</tr>

<tr>
<td colspan="2" valign="top">
<div align="center" class="style4">
<br>
<img src="../correios/barcode.php?text=<?php echo $pedido['shipping_postcode'];?>&codetype=code128&size=40">
<br>
<b><?php echo $pedido['shipping_postcode'];?></b>
</div>
<hr align="center" width="100%" style="border-style: dashed;" size="1">
<div class="style2">
<b>Remetente:</b><br> <?php echo $config['correiospro5_remetente']; ?>
<br>
<?php echo nl2br($config['correiospro5_endereco_remetente']); ?>
</div>
</td>
</tr>

</table>
</td>
</tr>
</table>
</fieldset>

<?php if($i%3==0){ ?>
<div class="quebrapagina"></div>
<?php } ?>

<?php
}
?>

</center>
</body>
</html>
