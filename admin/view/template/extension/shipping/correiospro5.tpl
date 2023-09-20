<?php echo $header; ?>

    <!--
    Módulo Comercial - Loja5.com.br
    Proibido vender, revender ou distribuir o mesmo sem a devida licença de uso de acordo os termos da lei 9.609/98 e nº 9.610/98, que protegem o direito de autor sobre programas de computador.
    https://www.loja5.com.br/termos-de-compra-suporte-loja5-i5.html
    -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

    <script>
        $(function () {
            $(".dinheiro").maskMoney({thousands: '', decimal: '.', allowZero: true, suffix: ''});
        });

        function desativar_ativar_servico(servico, valor) {
            $.ajax({
                url: 'index.php?route=extension/shipping/correiospro5/ativar_desativar&token=' + getURLVar('token') + '&servico=' + servico + '&valor=' + valor,
                dataType: 'html',
                success: function (valor) {
                    toastr.info('Status do serviço atualizado!')
                }
            });
        }
    </script>

<?php echo $column_left; ?>

    <div id="content">

        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>"
                            class="btn btn-primary"><i class="fa fa-save"></i> Salvar
                    </button>
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

            <?php if (isset($erro_permissao) && $erro_permissao) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $erro_permissao; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php } ?>

            <?php if (isset($salvos) && $salvos) { ?>
                <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> Dados salvos com sucesso!
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> Configura&ccedil;&otilde;es</h3>
                </div>
                <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post" class="form-horizontal"
                          enctype="multipart/form-data" id="form">

                        <div role="tabpanel">

                            <!-- menu -->
                            <ul class="nav nav-tabs" id="tabelas" role="tablist">
                                <li role="presentation" class="active"><a href="#configuracoes" role="tab"
                                                                          data-toggle="tab">Configura&ccedil;&otilde;es</a>
                                </li>
                                <li role="presentation"><a href="#remetente" role="tab" data-toggle="tab">Remetente</a>
                                </li>
                                <li role="presentation"><a href="#servicos" role="tab" data-toggle="tab">Servi&ccedil;os
                                        Cadastrados</a></li>
                                <li role="presentation"><a href="#sobre" role="tab" data-toggle="tab">Sobre</a></li>
                            </ul>

                            <div class="tab-content">

                                <div role="tabpanel" class="tab-pane active" id="configuracoes">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Status</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="correiospro5_status">
                                                <?php if ($correiospro5_status) { ?>
                                                    <option value="1" selected="selected">Ativado</option>
                                                    <option value="0">Desativado</option>
                                                <?php } else { ?>
                                                    <option value="1">Ativado</option>
                                                    <option value="0" selected="selected">Desativado</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Nome de Exibicao</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="correiospro5_nome"
                                                   value="<?php echo $correiospro5_nome; ?>" class="form-control"/>
                                            <span id="helpBlock" class="help-block">Nome da transportadora para exibir ao cliente.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">CEP Origem</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="correiospro5_cep"
                                                   value="<?php echo $correiospro5_cep; ?>" class="form-control"/>
                                            <span id="helpBlock"
                                                  class="help-block">CEP de origem dos produtos na loja</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">API da Calculo</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="correiospro5_api">
                                                <?php if ($correiospro5_api == 1) { ?>
                                                    <option value="0">0 - API Antiga Correios (webservice)</option>
                                                    <option value="1" selected="selected">1 - Nova API Correios (rest)
                                                    </option>
                                                <?php } else { ?>
                                                    <option value="0" selected="selected">0 - API Antiga Correios
                                                        (webservice)
                                                    </option>
                                                    <option value="1">1 - Nova API Correios (rest)</option>
                                                <?php } ?>

                                            </select>
                                            <span id="helpBlock" class="help-block">Selecione a API a ser usada no calculo de frete na loja, <u>caso n&atilde;o tenha contrato com os Correios selecione a API Antiga</u>, caso j&aacute; tenha contrato selecione a API Nova, acesse <a
                                                        href="https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-"
                                                        target="_blank">https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-</a>.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">C&oacute;d. Administrativo /
                                            ID/Login MeusCorreios</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="correiospro5_cod"
                                                   value="<?php echo $correiospro5_cod; ?>" class="form-control"/>
                                            <span id="helpBlock" class="help-block">Dados do contrato Correios para calculo com contrato, informar os dados de acordo com a API selecionada acima (<u>Cod. Administrativo para API Antiga e ID/Login MeusCorreios para Nova API</u>), mais detalhes acesse <a
                                                        href="https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-"
                                                        target="_blank">https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-</a>.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Senha Webservice (api antiga)
                                            / Cod. de Acesso Obtido em MeusCorreios (nova api)</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="correiospro5_senha"
                                                   value="<?php echo $correiospro5_senha; ?>" class="form-control"/>
                                            <span id="helpBlock" class="help-block">Dados do contrato Correios para calculo com contrato, informar os dados de acordo com a API selecionada acima (<u>Senha para API Antiga e Senha MeusCorreios para Nova API</u>), mais detalhes acesse <a
                                                        href="https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-"
                                                        target="_blank">https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-</a>.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Cart&atilde;o de Postagem
                                            Correios</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="correiospro5_cartao"
                                                   value="<?php echo $correiospro5_cartao; ?>" class="form-control"/>
                                            <span id="helpBlock" class="help-block">Cart&atilde;o de postagem junto aos Correios, geralmente iniciado com 00 e <u>obrigatorio no uso da Nova API Correios</u>, mais detalhes acesse <a
                                                        href="https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-"
                                                        target="_blank">https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-</a>.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Contrato Correios</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="correiospro5_contrato"
                                                   value="<?php echo $correiospro5_contrato; ?>" class="form-control"/>
                                            <span id="helpBlock" class="help-block">Contrato de postagem junto aos Correios <u>obrigatorio no uso da Nova API Correios</u>, mais detalhes acesse <a
                                                        href="https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-"
                                                        target="_blank">https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-</a>.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">N&uacute;mero de DR
                                            Correios</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="correiospro5_dr"
                                                   value="<?php echo $correiospro5_dr; ?>" class="form-control"/>
                                            <span id="helpBlock" class="help-block">N&uacute;mero da DR de postagem junto aos Correios <u>obrigatorio no uso da Nova API Correios</u>, mais detalhes acesse <a
                                                        href="https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-"
                                                        target="_blank">https://loja5.zendesk.com/hc/pt-br/articles/10409646188813-Como-Testar-Credenciais-de-Acesso-a-Nova-API-dos-Correios-</a>.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Servi&ccedil;o M&atilde;o Pr&oacute;pria</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="correiospro5_status_mp">
                                                <?php if ($correiospro5_status_mp) { ?>
                                                    <option value="1" selected="selected">Ativado</option>
                                                    <option value="0">Desativado</option>
                                                <?php } else { ?>
                                                    <option value="1">Ativado</option>
                                                    <option value="0" selected="selected">Desativado</option>
                                                <?php } ?>
                                            </select>
                                            <span id="helpBlock" class="help-block">Servi&ccedil;o extra Correios caso queira usar.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Servi&ccedil;o Aviso de
                                            Recebimento</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="correiospro5_status_ar">
                                                <?php if ($correiospro5_status_ar) { ?>
                                                    <option value="1" selected="selected">Ativado</option>
                                                    <option value="0">Desativado</option>
                                                <?php } else { ?>
                                                    <option value="1">Ativado</option>
                                                    <option value="0" selected="selected">Desativado</option>
                                                <?php } ?>
                                            </select>
                                            <span id="helpBlock" class="help-block">Servi&ccedil;o extra Correios caso queira usar.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Servi&ccedil;o Valor
                                            Declarado</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="correiospro5_status_vd">
                                                <?php if ($correiospro5_status_vd) { ?>
                                                    <option value="1" selected="selected">Ativado</option>
                                                    <option value="0">Desativado</option>
                                                <?php } else { ?>
                                                    <option value="1">Ativado</option>
                                                    <option value="0" selected="selected">Desativado</option>
                                                <?php } ?>
                                            </select>
                                            <span id="helpBlock" class="help-block">Servi&ccedil;o extra Correios caso queira usar.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"
                                               for="input"><?php echo $entry_geo_zone; ?></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="correiospro5_geo_zone_id">
                                                <option value="0">Todas Zonas</option>
                                                <?php foreach ($geo_zones as $geo_zone) { ?>
                                                    <?php if ($geo_zone['geo_zone_id'] == $correiospro5_geo_zone_id) { ?>
                                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"
                                                                selected="selected"><?php echo $geo_zone['name']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <span id="helpBlock" class="help-block">Zona de envio usada (<u>Geralmente 'Todas as Zonas'</u>).</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Taxa</label>
                                        <div class="col-sm-10"><select class="form-control"
                                                                       name="correiospro5_tax_class_id">
                                                <option value="0"><?php echo $text_none; ?></option>
                                                <?php foreach ($tax_classes as $tax_class) { ?>
                                                    <?php if ($tax_class['tax_class_id'] == $correiospro5_tax_class_id) { ?>
                                                        <option value="<?php echo $tax_class['tax_class_id']; ?>"
                                                                selected="selected"><?php echo $tax_class['title']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select><span id="helpBlock"
                                                           class="help-block">Taxa de impostos usada.</span></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Ordem</label>
                                        <div class="col-sm-10"><input type="text" name="correiospro5_sort_order"
                                                                      value="<?php echo $correiospro5_sort_order; ?>"
                                                                      class="form-control"/><span id="helpBlock"
                                                                                                  class="help-block">Ordem a exibir na loja.</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Tempo de Cache</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="correiospro5_tempo_cache"
                                                   value="<?php echo(empty($correiospro5_tempo_cache) ? 5 : $correiospro5_tempo_cache); ?>"
                                                   class="form-control"/>
                                            <span id="helpBlock" class="help-block">Quantidade de dias que o sistema vai manter o cache de calculo salvos (Padr&atilde;o: 5)</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Modo Debug</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="correiospro5_debug">
                                                <?php if ($correiospro5_debug) { ?>
                                                    <option value="1" selected="selected">Ativado</option>
                                                    <option value="0">Desativado</option>
                                                <?php } else { ?>
                                                    <option value="1">Ativado</option>
                                                    <option value="0" selected="selected">Desativado</option>
                                                <?php } ?>
                                            </select>
                                            <span id="helpBlock" class="help-block">Ativa o modo debug para salvar logs de calculo e acesso as apis Correios (<u>Ver em Frete Correios > Logs</u>).</span>
                                        </div>
                                    </div>

                                </div>

                                <div role="tabpanel" class="tab-pane" id="remetente">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Nome do Remetente</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="correiospro5_remetente"
                                                   value="<?php echo $correiospro5_remetente; ?>" class="form-control"/>
                                            <span id="helpBlock" class="help-block">Nome de remetente a exibir na etiqueta de postagem</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Endere&ccedil;o do
                                            Remetente</label>
                                        <div class="col-sm-10">
                                            <textarea name="correiospro5_endereco_remetente"
                                                      class="form-control"><?php echo $correiospro5_endereco_remetente; ?></textarea>
                                            <span id="helpBlock" class="help-block">Endere&ccedil;o de remetente a exibir na etiqueta de postagem (logradouro, n&uacute;mero, bairro, cidade, uf, cep).</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Custom N&uacute;mero
                                            Cliente</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="correiospro5_custom_numero">
                                                <option value="0" selected="selected">Vai junto ao logradouro</option>
                                                <?php foreach ($campos as $campo) { ?>
                                                    <?php if ($campo['custom_field_id'] == $correiospro5_custom_numero) { ?>
                                                        <option value="<?php echo $campo['custom_field_id']; ?>"
                                                                selected="selected"><?php echo $campo['name']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $campo['custom_field_id']; ?>"><?php echo $campo['name']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input">Custom Complemento
                                            Cliente</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="correiospro5_custom_complemento">
                                                <option value="0" selected="selected">N&atilde;o obrigatorio</option>
                                                <?php foreach ($campos as $campo) { ?>
                                                    <?php if ($campo['custom_field_id'] == $correiospro5_custom_complemento) { ?>
                                                        <option value="<?php echo $campo['custom_field_id']; ?>"
                                                                selected="selected"><?php echo $campo['name']; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $campo['custom_field_id']; ?>"><?php echo $campo['name']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="servicos">
                                    <div class="alert alert-info">Abaixo vai exibir a lista de servi&ccedil;os
                                        disponiveis ativos e inativos em sua loja, lembre-de que podera ativar,
                                        desativar ou editar os mesmos de forma qual deseja usar, caso ainda n&atilde;o
                                        tenha nenhum servi&ccedil;o cadastrado clique em adicionar.
                                    </div>
                                    <div class="pull-right">
                                        <a class="btn btn-primary" href="<?php echo $add_servico; ?>"><i
                                                    class="fa fa-plus"></i> Adicionar</a>
                                    </div>
                                    <br><br><br>
                                    <table id="correiospro5-servicos" class="display table table-bordered"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>ID Servi&ccedil;o</th>
                                            <th>Nome</th>
                                            <th>Prazo Extra</th>
                                            <th>Valor Extra (+ / -)</th>
                                            <th>Min. Desconto</th>
                                            <th>Min. Frete Gratis</th>
                                            <th>Suporte Offline</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if ($lista_servicos->num_rows > 0) {
                                            ?>
                                            <?php
                                            foreach ($lista_servicos->rows as $k => $v) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $v['id_servico']; ?></td>
                                                    <td><?php echo $v['titulo']; ?></td>
                                                    <td><?php echo $v['prazo_extra']; ?> dia(s)</td>
                                                    <td>
                                                        <?php echo $v['valor_extra']; ?>
                                                        <?php
                                                        if ($v['real_porcentagem'] == 1) {
                                                            ?>
                                                            % +
                                                            <?php
                                                        } elseif ($v['real_porcentagem'] == 2) {
                                                            ?>
                                                            % -
                                                            <?php
                                                        } elseif ($v['real_porcentagem'] == 3) {
                                                            ?>
                                                            R$ -
                                                            <?php
                                                        } else {
                                                            ?>
                                                            R$ +
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>R$ <?php echo $v['total_minimo']; ?></td>
                                                    <td>R$ <?php echo $v['total_minimo_frete']; ?>**</td>
                                                    <td><?php echo ($v['suporte_offline']) ? 'Sim*' : 'N&atilde;o'; ?></td>
                                                    <td>
                                                        <select onchange="desativar_ativar_servico('<?php echo $v['id_servico']; ?>',this.value)"
                                                                class="form-control">
                                                            <option value="0"<?php echo ($v['status'] == 0) ? ' selected="selected"' : ''; ?>>
                                                                Desativado
                                                            </option>
                                                            <option value="1"<?php echo ($v['status'] == 1) ? ' selected="selected"' : ''; ?>>
                                                                Ativado Online
                                                            </option>
                                                            <?php if ($v['suporte_offline']) { ?>
                                                                <option value="2"<?php echo ($v['status'] == 2) ? ' selected="selected"' : ''; ?>>
                                                                    Ativado Offline
                                                                </option>
                                                                <option value="3"<?php echo ($v['status'] == 3) ? ' selected="selected"' : ''; ?>>
                                                                    Ativado Offline se Webservice Falhar
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $editar_servico; ?>&servico=<?php echo $v['id_servico']; ?>"
                                                           class="btn btn-info btn-sm">Editar</a> <a
                                                                onclick="return confirm('Confirma remover?');"
                                                                href="<?php echo $remover_servico; ?>&servico=<?php echo $v['id_servico']; ?>"
                                                                class="btn btn-danger btn-sm">Remover</a></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="8">Ops, nenhum servi&ccedil;o cadastrado, clique em
                                                    adicionar!
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <p>
                                        *A funcionalidade de offline exige que a tabelas j&aacute; tenham sido
                                        devidamente atualizadas e lembre-se que calculos de frete offline n&atilde;o tem
                                        suporte a servi&ccedil;os extras como valor declarado, aviso de recebimento e m&atilde;o
                                        propria.<br>
                                        **Para as faixas de CEPs configuradas no servi&ccedil;o.
                                    </p>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="sobre">
                                    <img src="data:image/jpg;base64,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"/>
                                    <br><br>
                                    <b>Vers&atilde;o:</b> 3.0<br>
                                    <b>Data:</b> 18/09/2023<br>
                                    <br>
                                    Suporte: <a href="http://www.loja5.com.br/suporte/" target="_blank">http://www.loja5.com.br/suporte/</a><br>
                                    Contato/Vendas: <u>suportedaloja@gmail.com</u><br>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            <?php if(isset($tab)){ ?>
            $('#tabelas a[href="#<?php echo $tab;?>"]').tab('show');
            <?php } ?>
        });
    </script>

<?php echo $footer; ?>
