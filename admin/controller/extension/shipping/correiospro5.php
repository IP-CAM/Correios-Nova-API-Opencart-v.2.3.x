<?php

class ControllerExtensionShippingCorreiosPro5 extends Controller
{

    private $error = [];

    public function install()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correiospro5_tokens` (
			`id` INT(15) NOT NULL AUTO_INCREMENT,
			`token` TEXT NOT NULL,
			`atualizado` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`id`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=1;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correiospro5_prazos` (
			`id` INT(15) NOT NULL AUTO_INCREMENT,
			`id_servico` INT(5) NULL DEFAULT NULL,
			`prazo` INT(15) NOT NULL DEFAULT '0',
			`cep_origem` VARCHAR(9) NOT NULL,
			`cep_fim` VARCHAR(9) NOT NULL,
			`data` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`id`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=1;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correiospro5_servicos_cadastrados` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
			`id_servico` VARCHAR(5) NOT NULL DEFAULT '',
			`titulo` VARCHAR(50) NOT NULL DEFAULT '',
			`prazo_extra` INT(11) NOT NULL DEFAULT '0',
			`valor_extra` FLOAT(10,2) NOT NULL DEFAULT '0.00',
			`peso_maximo` FLOAT(10,2) NOT NULL DEFAULT '30.00',
			`total_maximo` FLOAT(10,2) NOT NULL DEFAULT '10000.00',
			`total_minimo` FLOAT(10,2) NOT NULL DEFAULT '0.00',
			`total_minimo_frete` FLOAT(10,2) NOT NULL DEFAULT '0.00',
			`real_porcentagem` INT(1) NOT NULL DEFAULT '0',
			`ceps` TEXT NOT NULL,
			`ceps_excluir` TEXT NOT NULL,
			`status` INT(1) NOT NULL DEFAULT '1',
			PRIMARY KEY (`id`)
        )
        ENGINE=InnoDB;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correiospro5_servicos` (
            `id_unico_servico` INT(11) NOT NULL AUTO_INCREMENT,
			`id_servico` VARCHAR(5) NOT NULL DEFAULT '',
			`nome` VARCHAR(50) NOT NULL DEFAULT '',
			`suporte_offline` INT(1) NOT NULL DEFAULT '0',
			`atualizado` TIMESTAMP NULL DEFAULT NULL,
			PRIMARY KEY (`id_unico_servico`),
			UNIQUE INDEX `id_servico` (`id_servico`)
        )
        ENGINE=InnoDB;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correiospro5_base` (
			`id` INT(15) NOT NULL AUTO_INCREMENT,
			`uf` VARCHAR(2) NOT NULL,
			`detalhes` VARCHAR(60) NOT NULL,
			`inicio` INT(5) NOT NULL,
			`fim` INT(5) NOT NULL,
			`base_cep` INT(8) NOT NULL,
			`custom` INT(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=1;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correiospro5_cotacoes` (
			`id` INT(15) NOT NULL AUTO_INCREMENT,
			`id_servico` CHAR(5) NULL DEFAULT NULL,
			`erro` CHAR(50) NULL DEFAULT NULL,
			`valor` FLOAT(10,2) NOT NULL DEFAULT '0.00',
			`peso` FLOAT(10,2) NOT NULL DEFAULT '0.00',
			`prazo` INT(15) NOT NULL DEFAULT '0',
			`cep_base` VARCHAR(9) NOT NULL,
			`cep_inicio` VARCHAR(9) NOT NULL,
			`cep_fim` VARCHAR(9) NOT NULL,
			`atualizado` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
			`custom` INT(1) NOT NULL DEFAULT '0',
			`cliente` INT(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=1;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "correiospro5_cache` (
			`id` INT(15) NOT NULL AUTO_INCREMENT,
			`hash` CHAR(50) NULL DEFAULT NULL,
			`peso` FLOAT(10,2) NULL DEFAULT NULL,
			`total` FLOAT(10,2) NULL DEFAULT NULL,
			`cep_destino` CHAR(10) NULL DEFAULT NULL,
			`json` TEXT NULL,
			`atualizado` DATETIME NULL DEFAULT NULL,
			PRIMARY KEY (`id`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=1;");

        $this->db->query("DELETE FROM `" . DB_PREFIX . "correiospro5_base` WHERE custom = 0");
        $this->db->query("INSERT INTO `" . DB_PREFIX . "correiospro5_base` (`id`, `uf`, `detalhes`, `inicio`, `fim`, `base_cep`, `custom`) VALUES
		(1, 'SP', 'SÃO PAULO - CAPITAL 1', 1000, 5999, 5999999, 0),
		(3, 'SP', 'SÃO PAULO - REGIÃO METROPOLITANA 1', 8500, 9999, 9999999, 0),
		(4, 'SP', 'SÃO PAULO - LITORAL', 10000, 11999, 11999999, 0),
		(5, 'SP', 'SÃO PAULO - INTERIOR 1', 12000, 12999, 12999999, 0),
		(6, 'RJ', 'RIO DE JANEIRO - CAPITAL', 20000, 23799, 23799999, 0),
		(7, 'RJ', 'RIO DE JANEIRO - REGIÃO METROPOLITANA', 23800, 26600, 26600999, 0),
		(8, 'RJ', 'RIO DE JANEIRO - INTERIOR 1', 26601, 27999, 27999999, 0),
		(9, 'ES', 'ESPIRITO SANTO - CAPITAL', 29000, 29099, 29099999, 0),
		(10, 'ES', 'ESPIRITO SANTO - INTERIOR', 29100, 29999, 29999999, 0),
		(11, 'MG', 'MINAS GERAIS - CAPITAL', 30000, 31999, 31999999, 0),
		(12, 'MG', 'MINAS GERAIS - REGIÃO METROPOLITANA', 32000, 34999, 34999999, 0),
		(13, 'MG', 'MINAS GERAIS - INTERIOR 1', 35000, 35999, 35990999, 0),
		(14, 'BA', 'BAHIA - CAPITAL', 40000, 42599, 42599999, 0),
		(15, 'BA', 'BAHIA - REGIÃO METROPOLITANA', 42600, 44470, 44470999, 0),
		(16, 'BA', 'BAHIA - INTERIOR 1', 44471, 44999, 44999999, 0),
		(17, 'SE', 'SERGIPE - CAPITAL', 49000, 49099, 49098999, 0),
		(18, 'SE', 'SERGIPE - INTERIOR', 49100, 49999, 49999999, 0),
		(19, 'PE', 'PERNAMBUCO - CAPITAL', 50000, 52999, 52999999, 0),
		(20, 'PE', 'PERNAMBUCO - REGIÃO METROPOLITANA', 53000, 54999, 54999999, 0),
		(21, 'PE', 'PERNAMBUCO - INTERIOR 1', 55000, 55999, 55999999, 0),
		(22, 'AL', 'ALAGOAS - CAPITAL', 57000, 57099, 57099999, 0),
		(23, 'AL', 'ALAGOAS - INTERIOR', 57100, 57999, 57999999, 0),
		(24, 'PB', 'PARAIBA - CAPITAL', 58000, 58099, 58099999, 0),
		(25, 'PB', 'PARAIBA - INTERIOR', 58100, 58999, 58990999, 0),
		(26, 'RN', 'RIO GRANDE DO NORTE - CAPITAL', 59000, 59139, 59139999, 0),
		(27, 'RN', 'RIO GRANDE DO NORTE - INTERIOR', 59140, 59999, 59999999, 0),
		(28, 'CE', 'CEARA - CAPITAL 1', 60000, 60999, 60999999, 0),
		(29, 'CE', 'CEARA - REGIÃO METROPOLITANA', 61600, 61900, 61900999, 0),
		(30, 'CE', 'CEARA - INTERIOR 1', 61901, 61999, 61999999, 0),
		(31, 'PI', 'PIAUI - CAPITAL', 64000, 64099, 64099999, 0),
		(32, 'PI', 'PIAUI - INTERIOR', 64100, 64999, 64999999, 0),
		(33, 'MA', 'MARANHAO - CAPITAL 1', 65000, 65099, 65099999, 0),
		(34, 'MA', 'MARANHAO - INTERIOR', 65110, 65999, 65999999, 0),
		(35, 'PA', 'PARA - CAPITAL', 66000, 66999, 66999999, 0),
		(36, 'PA', 'PARA - REGIÃO METROPOLITANA', 67000, 67999, 67999999, 0),
		(37, 'PA', 'PARA - INTERIOR', 68000, 68899, 68899999, 0),
		(38, 'AP', 'AMAPA - CAPITAL 1', 68900, 68911, 68911999, 0),
		(39, 'AP', 'AMAPA - INTERIOR', 68915, 68999, 68999999, 0),
		(40, 'AM', 'AMAZONAS - CAPITAL', 69000, 69099, 69099999, 0),
		(41, 'AM', 'AMAZONAS - INTERIOR', 69100, 69299, 69299999, 0),
		(42, 'RR', 'RORAIMA - CAPITAL', 69300, 69339, 69339999, 0),
		(43, 'RR', 'RORAIMA - INTERIOR', 69340, 69399, 69399999, 0),
		(44, 'AC', 'ACRE - CAPITAL', 69900, 69924, 69924999, 0),
		(45, 'AC', 'ACRE - INTERIOR', 69925, 69999, 69999999, 0),
		(46, 'DF', 'BRASILIA 1', 70000, 72799, 72799999, 0),
		(47, 'DF', 'BRASILIA 2', 73000, 73699, 73699999, 0),
		(48, 'GO', 'GOAIS - CAPITAL', 74000, 74899, 74899999, 0),
		(49, 'GO', 'GOAIS - INTERIOR 1', 72800, 72999, 72980999, 0),
		(50, 'TO', 'TOCANTINS - CAPITAL 1', 77000, 77249, 77249999, 0),
		(51, 'TO', 'TOCANTINS - INTERIOR', 77300, 77999, 77999999, 0),
		(52, 'MT', 'MATO GROSSO - CAPITAL 1', 78000, 78099, 78099999, 0),
		(53, 'MT', 'MARO GROSSO - INTERIOR', 78110, 78899, 78899999, 0),
		(54, 'RO', 'RONDONIA - CAPITAL 1', 76800, 76834, 76834999, 0),
		(55, 'RO', 'RONDONIA - INTERIOR', 76850, 76999, 76999999, 0),
		(56, 'MS', 'MATO GROSSO DO SUL - CAPITAL 1', 79000, 79124, 79124999, 0),
		(57, 'MS', 'MATO GROSSO DO SUL - INTERIOR', 79130, 79999, 79999999, 0),
		(58, 'PR', 'PARANA - CAPITAL', 80000, 82999, 82999999, 0),
		(59, 'PR', 'PARANA - REGIAO METROPOLITANA', 83000, 83800, 83800999, 0),
		(60, 'PR', 'PARANA - INTERIOR 1', 83801, 83999, 83999999, 0),
		(61, 'SC', 'SANTA CATARINA - CAPITAL', 88000, 88099, 88099999, 0),
		(62, 'SC', 'SANTA CATARINA - REGIÃO METROPOLITANA', 88100, 88469, 88469999, 0),
		(63, 'SC', 'SANTA CATARINA - INTERIOR 1', 88470, 88999, 88999999, 0),
		(64, 'RS', 'RIO GRANDE DO SUL - CAPITAL', 90000, 91999, 91999999, 0),
		(65, 'RS', 'RIO GRANDE DO SUL - REGIÃO METROPOLITANA', 92000, 94900, 94900999, 0),
		(66, 'RS', 'RIO GRANDE DO SUL - INTERIOR 1', 94901, 94999, 94999999, 0),
		(67, 'SP', 'SÃO PAULO - CAPITAL 2', 8000, 8499, 8499999, 0),
		(68, 'SP', 'SÃO PAULO - REGIÃO METROPOLITANA 2', 6000, 7999, 7999999, 0),
		(69, 'GO', 'GOAIS - INTERIOR 2', 73700, 73999, 73999999, 0),
		(70, 'GO', 'GOIAS - INTERIOR 3', 74900, 76799, 76750999, 0),
		(71, 'CE', 'CEARA - CAPITAL 2', 61000, 61599, 61599999, 0),
		(72, 'MA', 'MARANHAO - CAPITAL 2', 65100, 65109, 65109999, 0),
		(73, 'AP', 'AMAPA - CAPITAL 2', 68912, 68914, 64914999, 0),
		(74, 'RO', 'RONDONIA - CAPITAL 2', 76835, 76849, 76849999, 0),
		(75, 'TO', 'TOCANTINS - CAPITAL 2', 77250, 77299, 77299999, 0),
		(76, 'MT', 'MATO GROSSO - CAPITAL 2', 78100, 78109, 78109999, 0),
		(77, 'MS', 'MATO GROSSO DO SUL - CAPITAL 2', 79125, 79129, 79129999, 0),
		(78, 'SP', 'SÃO PAULO - INTERIOR 2', 13000, 13999, 13999999, 0),
		(79, 'SP', 'SÃO PAULO - INTERIOR 3', 14000, 14999, 14999999, 0),
		(80, 'SP', 'SÃO PAULO - INTERIOR 4', 15000, 15999, 15999999, 0),
		(81, 'SP', 'SÃO PAULO - INTERIOR 5', 16000, 16999, 16999999, 0),
		(82, 'SP', 'SÃO PAULO - INTERIOR 6', 17000, 17999, 17999999, 0),
		(83, 'SP', 'SÃO PAULO - INTERIOR 7', 18000, 18999, 18999999, 0),
		(84, 'SP', 'SÃO PAULO - INTERIOR 8', 19000, 19999, 19999999, 0),
		(85, 'RJ', 'RIO DE JANEIRO - INTERIOR 2', 28000, 28999, 28999999, 0),
		(87, 'BA', 'BAHIA - INTERIOR 2', 45000, 45999, 45999999, 0),
		(88, 'BA', 'BAHIA - INTERIOR 3', 46000, 46999, 46999999, 0),
		(89, 'BA', 'BAHIA - INTERIOR 4', 47000, 47999, 47999999, 0),
		(90, 'BA', 'BAHIA - INTERIOR 5', 48000, 48999, 48999999, 0),
		(91, 'CE', 'CEARA - INTERIOR 2', 62000, 62999, 62999999, 0),
		(92, 'CE', 'CEARA - INTERIOR 3', 63000, 63999, 63999999, 0),
		(93, 'MG', 'MINAS GERAIS - INTERIOR 2', 36000, 36999, 36999999, 0),
		(94, 'MG', 'MINAS GERAIS - INTERIOR 3', 37000, 37999, 37999999, 0),
		(95, 'MG', 'MINAS GERAIS - INTERIOR 4', 38000, 38999, 38999999, 0),
		(96, 'MG', 'MINAS GERAIS - INTERIOR 5', 39000, 39999, 39990000, 0),
		(97, 'PE', 'PERNAMBUCO - INTERIOR 2', 56000, 56999, 56999999, 0),
		(98, 'PR', 'PARANA - INTERIOR 2', 84000, 84999, 84999999, 0),
		(99, 'PR', 'PARANA - INTERIOR 3', 85000, 85999, 85999999, 0),
		(100, 'PR', 'PARANA - INTERIOR 4', 86000, 86999, 86999999, 0),
		(101, 'PR', 'PARANA - INTERIOR 5', 87000, 87999, 87999999, 0),
		(102, 'SC', 'SANTA CATARINA - INTERIOR 2', 89000, 89999, 89999999, 0),
		(103, 'RS', 'RIO GRANDE DO SUL - INTERIOR 2', 95000, 95999, 95999999, 0),
		(104, 'RS', 'RIO GRANDE DO SUL - INTERIOR 3', 96000, 96999, 96999999, 0),
		(105, 'RS', 'RIO GRANDE DO SUL - INTERIOR 4', 97000, 97999, 97999999, 0),
		(106, 'RS', 'RIO GRANDE DO SUL - INTERIOR 5', 98000, 98999, 98999999, 0),
		(107, 'RS', 'RIO GRANDE DO SUL - INTERIOR 6', 99000, 99999, 99999999, 0);");

        $this->db->query("DELETE FROM `" . DB_PREFIX . "correiospro5_servicos`");
        $this->db->query("INSERT INTO `" . DB_PREFIX . "correiospro5_servicos` (`id_unico_servico`, `id_servico`, `nome`, `suporte_offline`, `atualizado`) VALUES
		(1, '04162', 'SEDEX CONTRATO AGENCIA', 1, NULL),
		(2, '04553', 'SEDEX CONTRATO AGENCIA TA', 1, NULL),
		(3, '04669', 'PAC CONTRATO AGENCIA', 1, NULL),
		(4, '04596', 'PAC CONTRATO AGENCIA TA', 1, NULL),
		(5, '04510', 'PAC SEM CONTRATO', 1, '2019-05-21 10:36:13'),
		(6, '04014', 'SEDEX SEM CONTRATO', 1, '2019-05-07 13:19:57'),
		(7, '40045', 'SEDEX A COBRAR SEM CONTRATO', 0, NULL),
		(8, '40126', 'SEDEX A COBRAR COM CONTRATO', 0, NULL),
		(9, '40215', 'SEDEX 10', 0, NULL),
		(10, '40290', 'SEDEX HOJE', 0, NULL),
		(11, '04367', 'PAC CONTRATO AGENCIA LM', 1, NULL),
		(12, '04154', 'SEDEX CONTRATO AGENCIA LM', 1, NULL),
		(13, '03085', 'PAC CONTRATO 03085', 1, NULL),
		(14, '03050', 'SEDEX CONTRATO 03050', 1, NULL),
		(15, '03298', 'PAC CONTRATO 03298', 1, NULL),
		(16, '03220', 'SEDEX CONTRATO 03220', 1, NULL),
		(17, '03140', 'SEDEX 12 03140', 0, NULL),
		(18, '03158', 'SEDEX 10 03158', 0, NULL),
		(19, '03204', 'SEDEX HOJE 03204', 0, NULL),
		(20, '04227', 'PAC MINI CONTRATO', 0, NULL);");
    }

    private function salvar_log($dados_log)
    {
        $log = new Log('correiospro5-' . md5($this->config->get('correiospro5_serial')) . '-' . date('d.m.Y') . '.log');
        $log->write($dados_log);

        return true;
    }

    public function logs()
    {
        $this->language->load('extension/shipping/correiospro5');
        $this->document->setTitle('Logs');

        $data['breadcrumbs'] = [];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];
        $data['breadcrumbs'][] = [
            'text' => 'Logs',
            'href' => $this->url->link('extension/shipping/correiospro5/logs', 'token=' . $this->session->data['token'], 'SSL'),
        ];
        $data['link_configurar'] = $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL');
        $url = '';

        //token
        $data['token'] = $this->session->data['token'];

        //erro
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $file = DIR_LOGS . 'correiospro5-' . md5($this->config->get('correiospro5_serial')) . '-' . date('dmY') . '.log';

        //remove
        if (isset($_GET['remover'])) {
            if (file_exists($file)) {
                unlink($file);
            }
            $this->response->redirect($this->url->link('extension/shipping/correiospro5/logs', 'token=' . $this->session->data['token'], 'SSL'));
            exit;
        }

        if (file_exists($file)) {
            $data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
        } else {
            $data['log'] = '';
        }

        //aplica ao template
        $data['link_configurar'] = $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL');
        $data['link_remover'] = $this->url->link('extension/shipping/correiospro5/logs', 'remover=true&token=' . $this->session->data['token'], 'SSL');
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $tema = 'extension/shipping/correiospro5_logs';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function salvar_editar_servico()
    {
        if (isset($_POST['servico'])) {
            $this->db->query("UPDATE `" . DB_PREFIX . "correiospro5_servicos_cadastrados` SET titulo = '" . $_POST['nome'] . "', prazo_extra = '" . (int)$_POST['prazo_extra'] . "', valor_extra = '" . $_POST['valor_extra'] . "', total_minimo = '" . $_POST['minimo'] . "', total_minimo_frete = '" . $_POST['minimo_frete'] . "', total_maximo = '" . $_POST['total_maximo'] . "', peso_maximo = '" . $_POST['peso_maximo'] . "', real_porcentagem = '" . $_POST['tipo_valor_extra'] . "', ceps = '" . $_POST['ceps'] . "', ceps_excluir = '" . $_POST['ceps_excluir'] . "' WHERE id_servico = '" . $_POST['servico'] . "'");
            $this->db->query("DELETE FROM  `" . DB_PREFIX . "correiospro5_cache`");
        }
        $this->response->redirect($this->url->link('extension/shipping/correiospro5', 'tab=servicos&token=' . $this->session->data['token'], 'SSL'));
    }

    public function salvar_servico()
    {
        $this->db->query("INSERT INTO  `" . DB_PREFIX . "correiospro5_servicos_cadastrados` SET id_servico = '" . $_POST['servico'] . "', titulo = '" . $_POST['nome'] . "', prazo_extra = '" . (int)$_POST['prazo_extra'] . "', valor_extra = '" . $_POST['valor_extra'] . "', total_minimo = '" . $_POST['minimo'] . "', total_minimo_frete = '" . $_POST['minimo_frete'] . "', total_maximo = '" . $_POST['total_maximo'] . "', peso_maximo = '" . $_POST['peso_maximo'] . "', real_porcentagem = '" . $_POST['tipo_valor_extra'] . "', ceps = '" . $_POST['ceps'] . "', ceps_excluir = '" . $_POST['ceps_excluir'] . "'");
        $this->response->redirect($this->url->link('extension/shipping/correiospro5', 'tab=servicos&token=' . $this->session->data['token'], 'SSL'));
    }

    public function salvar_faixa()
    {
        if (isset($_POST['uf'])) {
            $this->db->query("INSERT INTO  `" . DB_PREFIX . "correiospro5_base` SET uf = '" . $_POST['uf'] . "', detalhes = '" . $_POST['detalhes'] . "', inicio = '" . (int)$_POST['cep_inicio'] . "', fim = '" . (int)$_POST['cep_final'] . "', base_cep = '" . $_POST['cep_base'] . "', custom = '1'");
        }
        $this->response->redirect($this->url->link('extension/shipping/correiospro5/faixas', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function salvar_editar_faixa()
    {
        if (isset($_POST['id'])) {
            $this->db->query("UPDATE `" . DB_PREFIX . "correiospro5_base` SET uf = '" . $_POST['uf'] . "', detalhes = '" . $_POST['detalhes'] . "', inicio = '" . (int)$_POST['cep_inicio'] . "', fim = '" . (int)$_POST['cep_final'] . "', base_cep = '" . $_POST['cep_base'] . "' WHERE id = '" . (int)$_POST['id'] . "'");
        }
        $this->response->redirect($this->url->link('extension/shipping/correiospro5/faixas', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function remover_servicos()
    {
        if (isset($_GET['servico'])) {
            $this->db->query("DELETE FROM  `" . DB_PREFIX . "correiospro5_servicos_cadastrados` WHERE id_servico = '" . $this->db->escape($_GET['servico']) . "'");
        }
        $this->response->redirect($this->url->link('extension/shipping/correiospro5', 'tab=servicos&token=' . $this->session->data['token'], 'SSL'));
    }

    public function remover_faixa()
    {
        if (isset($_GET['faixa'])) {
            $this->db->query("DELETE FROM  `" . DB_PREFIX . "correiospro5_base` WHERE id = '" . $this->db->escape($_GET['faixa']) . "' AND custom = 1");
        }
        $this->response->redirect($this->url->link('extension/shipping/correiospro5/faixas', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function limpar()
    {
        if (isset($_GET['servico'])) {
            $this->db->query("DELETE FROM  `" . DB_PREFIX . "correiospro5_cotacoes` WHERE id_servico = '" . $this->db->escape($_GET['servico']) . "'");
        }
        $this->response->redirect($this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function limpar_cache()
    {
        $this->db->query("DELETE FROM  `" . DB_PREFIX . "correiospro5_cache`");
        $this->response->redirect($this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL'));
    }

    public function ativar_desativar()
    {
        $this->db->query("UPDATE  `" . DB_PREFIX . "correiospro5_servicos_cadastrados` SET status = '" . (int)$_GET['valor'] . "' WHERE id_servico = '" . $_GET['servico'] . "'");
        die($_GET['valor']);
    }

    public function servicosAtivados()
    {
        $query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "correiospro5_servicos_cadastrados` WHERE status > 0");
        $servicos = [];
        foreach ($query->rows as $k => $v) {
            $servicos[$v['id_servico']] = $v;
        }

        return $servicos;
    }

    public function servicosCadastrados()
    {
        $query = $this->db->query("SELECT id_servico FROM  `" . DB_PREFIX . "correiospro5_servicos_cadastrados`");
        $servicos = [];
        foreach ($query->rows as $k => $v) {
            $servicos[] = $v['id_servico'];
        }

        return implode("','", $servicos);
    }

    public function token_de_acesso_correios()
    {
        //timeout
        $timeut_correios = 10;
        //verifica se existe token já cadastrado
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "correiospro5_tokens` WHERE atualizado > NOW() ORDER BY atualizado DESC");
        $token_offline = $query->row;
        if (isset($token_offline['token'])) {
            return ['status' => 200, 'dados' => ['token' => $token_offline['token']], 'url' => 'token retornado de base de dados!'];
        }
        //dados e ambiente
        $login = trim($this->config->get('correiospro5_cod'));
        $senha = trim($this->config->get('correiospro5_senha'));
        $cartao = trim($this->config->get('correiospro5_cartao'));
        //header
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode(trim($login) . ':' . trim($senha)) . "",
        ];
        //gera o token
        $dados = [];
        //endpoint
        if (!empty($cartao)) {
            $dados['numero'] = $cartao;
            $urlweb = "https://api.correios.com.br/token/v1/autentica/cartaopostagem";
        } else {
            $urlweb = "https://api.correios.com.br/token/v1/autentica";
        }
        //curl
        $sessao_curl = curl_init($urlweb);
        curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($sessao_curl, CURLOPT_POST, true);
        curl_setopt($sessao_curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($sessao_curl, CURLOPT_POSTFIELDS, json_encode($dados));
        curl_setopt($sessao_curl, CURLOPT_CONNECTTIMEOUT, $timeut_correios);
        curl_setopt($sessao_curl, CURLOPT_TIMEOUT, 40);
        curl_setopt($sessao_curl, CURLOPT_RETURNTRANSFER, true);
        $resultado = curl_exec($sessao_curl);
        $status = curl_getinfo($sessao_curl, CURLINFO_HTTP_CODE);
        curl_close($sessao_curl);
        $token = json_decode($resultado, true);
        //cria o token no bd
        if (isset($token['token'])) {
            $sql = "INSERT INTO `" . DB_PREFIX . "correiospro5_tokens` (`token`, `atualizado`) VALUES ('" . $token['token'] . "', '" . $token['expiraEm'] . "');";
            $this->db->query($sql);
        }

        return ['status' => $status, 'dados' => $token, 'url' => $urlweb];
    }

    public function get_frete_correios($servico, $dados, $token)
    {
        //timeout
        $timeut_correios = 10;
        //url
        $urlweb = 'https://api.correios.com.br/preco/v1/nacional/' . $servico . '?' . http_build_query($dados);
        //header
        $headers = [
            "Authorization: Bearer " . trim($token) . "",
            "Content-Type: application/json",
        ];
        //curl
        $sessao_curl2 = curl_init();
        curl_setopt($sessao_curl2, CURLOPT_URL, $urlweb);
        curl_setopt($sessao_curl2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($sessao_curl2, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($sessao_curl2, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($sessao_curl2, CURLOPT_POST, false);
        curl_setopt($sessao_curl2, CURLOPT_CONNECTTIMEOUT, $timeut_correios);
        curl_setopt($sessao_curl2, CURLOPT_TIMEOUT, 20);
        curl_setopt($sessao_curl2, CURLOPT_RETURNTRANSFER, true);
        $resultado = curl_exec($sessao_curl2);
        $resultado = @json_decode($resultado, true);
        $status = curl_getinfo($sessao_curl2, CURLINFO_HTTP_CODE);
        curl_close($sessao_curl2);

        return ['status' => $status, 'dados' => $resultado, 'uri' => $urlweb];
    }

    public function get_prazo_correios($servico, $dados, $token)
    {
        //timeout
        $timeut_correios = 10;
        //verifica se existe prazo cadastrado
        $cep_origem = $dados['cepOrigem'];
        $cep_destino = $dados['cepDestino'];
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "correiospro5_prazos` WHERE id_servico = '" . (int)$servico . "' AND cep_origem = '" . $cep_origem . "' AND cep_fim = '" . $cep_destino . "' AND DATEDIFF(NOW(),data) <= 30 ORDER BY data DESC");
        $prazo_offline = $query->row;
        if (isset($prazo_offline['prazo'])) {
            return ['status' => 200, 'dados' => ['prazoEntrega' => $prazo_offline['prazo']], 'url' => 'token retornado de base de dados!'];
        }
        //url
        $urlweb = 'https://api.correios.com.br/prazo/v1/nacional/' . $servico . '?' . http_build_query($dados);
        //header
        $headers = [
            "Authorization: Bearer " . trim($token) . "",
            "Content-Type: application/json",
        ];
        //curl
        $sessao_curl2 = curl_init();
        curl_setopt($sessao_curl2, CURLOPT_URL, $urlweb);
        curl_setopt($sessao_curl2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($sessao_curl2, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($sessao_curl2, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($sessao_curl2, CURLOPT_POST, false);
        curl_setopt($sessao_curl2, CURLOPT_CONNECTTIMEOUT, $timeut_correios);
        curl_setopt($sessao_curl2, CURLOPT_TIMEOUT, 20);
        curl_setopt($sessao_curl2, CURLOPT_RETURNTRANSFER, true);
        $resultado = curl_exec($sessao_curl2);
        $resultado = @json_decode($resultado, true);
        $status = curl_getinfo($sessao_curl2, CURLINFO_HTTP_CODE);
        curl_close($sessao_curl2);
        //cria o prazo no bd
        if (isset($resultado['prazoEntrega'])) {
            $sql = "INSERT INTO `" . DB_PREFIX . "correiospro5_prazos` (`id_servico`, `prazo`, `cep_origem`, `cep_fim`, `data`) VALUES ('" . (int)$servico . "', '" . $resultado['prazoEntrega'] . "', '" . $cep_origem . "', '" . $cep_destino . "', NOW());";
            $this->db->query($sql);
        }

        return ['status' => $status, 'dados' => $resultado, 'uri' => $urlweb];
    }

    public function calcular_frete($servico, $peso, $para)
    {
        //medidas
        $lados = round(sqrt($peso), 2);
        $alt = ($lados >= 2) ? $lados : 2;
        $lar = ($lados >= 11) ? $lados : 11;
        $com = ($lados >= 16) ? $lados : 16;
        //dados correios
        $vars_correios = [
            'nCdServico' => str_pad($servico, 5, "0", STR_PAD_LEFT),
            'nCdEmpresa' => trim($this->config->get('correiospro5_cod')),
            'sDsSenha' => trim($this->config->get('correiospro5_senha')),
            'sCepDestino' => preg_replace('/\D/', '', $para),
            'sCepOrigem' => preg_replace('/\D/', '', $this->config->get('correiospro5_cep')),
            'nVlAltura' => number_format($alt, 2, ',', ''),
            'nVlLargura' => number_format($lar, 2, ',', ''),
            'nVlDiametro' => 0,
            'nVlComprimento' => number_format($com, 2, ',', ''),
            'nVlPeso' => number_format($peso, 2, ',', ''),
            'nCdFormato' => 1,
            'sCdMaoPropria' => 'N',
            'nVlValorDeclarado' => '0',
            'sCdAvisoRecebimento' => 'N',
            'StrRetorno' => 'xml',
        ];
        //api antiga correios
        if ($this->config->get('correiospro5_api') == 0) {
            //monta a url de calculo
            $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?';
            $url .= http_build_query($vars_correios);
            //curl
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            if (curl_errno($ch)) {
                return ['erro' => true, 'erro_curl' => curl_error($ch), 'resultado' => null, 'url' => $url];
            } else {
                $resultado = curl_exec($ch);
                $curlCod = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                //trata o resultado
                if ($curlCod != 200) {
                    $this->salvar_log($curlCod . ' - Erro de consulta curl Correios!');

                    return ['erro' => true, 'resultado' => $resultado, 'url' => $url];
                } elseif ($curlCod == 200) {
                    libxml_use_internal_errors(true);
                    $objeto_xml = simplexml_load_string($resultado);
                    if ($objeto_xml === false) {
                        return ['erro' => true, 'resultado' => $resultado, 'url' => $url];
                    } else {
                        return ['erro' => false, 'objeto' => json_decode(json_encode($objeto_xml), true), 'resultado' => $resultado, 'url' => $url];
                    }
                }
            }
        } else {
            //token nao ok
            $token_api = $this->token_de_acesso_correios();
            if (!isset($token_api['dados']['token'])) {
                return ['erro' => true, 'resultado' => $token_api, 'url' => $token_api['url']];
            } else {
                //dados
                $meio = str_pad($servico, 5, "0", STR_PAD_LEFT);
                $para = preg_replace('/\D/', '', $para);
                $lados = round(sqrt($peso), 2);
                $alt = ($lados >= 2) ? $lados : 2;
                $larg = ($lados >= 11) ? $lados : 11;
                $comp = ($lados >= 16) ? $lados : 16;
                //token rest
                $token = trim($token_api['dados']['token']);
                $codServico = $meio;
                //dados a calcular
                $dados = [];
                $dados['psObjeto'] = $peso * 1000;
                $id_contrato_correios = $this->config->get('correiospro5_contrato');
                $id_dr_correios = $this->config->get('correiospro5_dr');
                if (!empty(trim($id_contrato_correios))) {
                    $dados['nuContrato'] = trim($id_contrato_correios);
                    $dados['nuDR'] = trim($id_dr_correios);
                }
                $dados['cepOrigem'] = preg_replace('/\D/', '', $this->config->get('correiospro5_cep'));
                $dados['cepDestino'] = $para;
                $dados['VlDeclarado'] = 0.00;
                $dados['tpObjeto'] = 2;
                $dados['comprimento'] = $comp;
                $dados['largura'] = $larg;
                $dados['altura'] = $alt;
                $resultado = $this->get_frete_correios($codServico, $dados, $token);
                $resultado_prazo = $this->get_prazo_correios($codServico, $dados, $token);
                if (isset($resultado['dados']['pcFinal'])) {
                    return ['erro' => false, 'objeto' => [
                        'cServico' => [
                            'Erro' => 0,
                            'PrazoEntrega' => (isset($resultado_prazo['dados']['prazoEntrega']) ? $resultado_prazo['dados']['prazoEntrega'] : 10),
                            'MsgErro' => '',
                            'Valor' => $resultado['dados']['pcFinal'],
                        ],
                    ]];
                } else {
                    return ['erro' => true, 'resultado' => array_merge(['original' => $dados], ['preco' => $resultado], ['prazo' => $resultado_prazo]), 'url' => ''];
                }
            }
        }
    }

    public function verificar_se_atualizado($servico, $peso, $para, $custom)
    {
        $sql = $this->db->query("SELECT *,DATEDIFF(atualizado,NOW()) AS dias_atualizado FROM `" . DB_PREFIX . "correiospro5_cotacoes` WHERE id_servico = '" . $this->db->escape($servico) . "' AND peso = " . number_format($peso, 2, '.', '') . " AND cep_base = '" . $para . "' AND custom = '" . (int)$custom . "'");
        $row = $sql->row;
        if (!$row || $sql->num_rows == 0) {
            return ['acao' => 'criar'];
        } elseif ($row['erro'] <> 0 || abs($row['dias_atualizado']) > 30) {
            return ['id' => $row['id'], 'dias' => abs($row['dias_atualizado']), 'acao' => 'atualizar'];
        } else {
            return ['dias' => abs($row['dias_atualizado']), 'acao' => 'atualizado'];
        }
    }

    public function api()
    {
        $json = [];
        $resultado = [];
        $erros = [];
        if (isset($_POST['servico'])) {
            $peso_inicio = 1;
            $peso_fim = 30;
            $meio = str_pad($_POST['servico'], 5, "0", STR_PAD_LEFT);
            $custom = (int)$_POST['custom'];
            $para = str_pad($_POST['cep'], 8, "0", STR_PAD_LEFT);
            $inicio = (int)$_POST['de'];
            $fim = (int)$_POST['para'];
            $array_pesos = [0.3, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30];
            foreach ($array_pesos as $key => $i) {
                $cod_erro = 99;
                $frete = $prazo = 0;
                $acao = $this->verificar_se_atualizado($meio, $i, $para, $custom);
                $dias = isset($acao['dias']) ? $acao['dias'] : 0;
                if ($acao['acao'] == 'criar') {
                    $resultado = $this->calcular_frete($meio, $i, $para);
                    if (isset($resultado['objeto']['cServico'])) {
                        $cod_erro = $resultado['objeto']['cServico']['Erro'];
                        $frete = str_replace(',', '.', str_replace('.', '', $resultado['objeto']['cServico']['Valor']));
                        $prazo = $resultado['objeto']['cServico']['PrazoEntrega'];
                        //cria a cotacao
                        $sql = "INSERT INTO `" . DB_PREFIX . "correiospro5_cotacoes` (`id_servico`, `erro`, `valor`, `peso`, `prazo`, `cep_base`, `cep_inicio`, `cep_fim`, `atualizado`, `custom`) VALUES ('" . $meio . "', '" . (($frete > 0) ? '0' : $cod_erro) . "', '" . $frete . "', '" . $i . "', '" . $prazo . "', '" . $para . "', '" . $inicio . "', '" . $fim . "', NOW(), '" . $custom . "');";
                        $this->db->query($sql);
                        //atualiza
                        $sql = "UPDATE `" . DB_PREFIX . "correiospro5_servicos` SET atualizado = NOW() WHERE id_servico = '" . $meio . "'";
                        $this->db->query($sql);
                    } else {
                        if (!empty($resultado)) {
                            $erros[] = $resultado;
                        }
                    }
                } elseif ($acao['acao'] == 'atualizar') {
                    $resultado = $this->calcular_frete($meio, $i, $para);
                    if (isset($resultado['objeto']['cServico'])) {
                        $cod_erro = $resultado['objeto']['cServico']['Erro'];
                        $frete = str_replace(',', '.', str_replace('.', '', $resultado['objeto']['cServico']['Valor']));
                        $prazo = $resultado['objeto']['cServico']['PrazoEntrega'];
                        //atualiza a cotacao
                        $sql = "UPDATE `" . DB_PREFIX . "correiospro5_cotacoes` SET `erro` = '" . (($frete > 0) ? '0' : $cod_erro) . "', `valor` = '" . $frete . "', `prazo` = '" . $prazo . "', `cep_base` = '" . $para . "', `cep_inicio` = '" . $inicio . "', `cep_fim` = '" . $fim . "', `atualizado` = NOW(), `custom` = '" . $custom . "' WHERE id = '" . $acao['id'] . "'";
                        $this->db->query($sql);
                        //atualiza
                        $sql = "UPDATE `" . DB_PREFIX . "correiospro5_servicos` SET atualizado = NOW() WHERE id_servico = '" . $meio . "'";
                        $this->db->query($sql);
                    }
                } else {
                    $cod_erro = 0;
                    $frete = $prazo = 'atualizado';
                }
                $json[$para][$i] = ['dias' => $dias, 'erro' => $cod_erro, 'frete' => $frete, 'prazo' => $prazo, 'resultado' => $resultado];
            }
            //salva erros de debug
            if (count($erros) > 0) {
                $this->salvar_log($erros);
            }
        }
        die(json_encode($json));
    }

    private function get_cep_pedido($id)
    {
        $jaexiste = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$id . "'");
        if ($jaexiste->num_rows) {
            return preg_replace('/\D/', '', $jaexiste->row['shipping_postcode']);
        } else {
            return '';
        }
    }

    private function get_metodo_pedido($id)
    {
        $jaexiste = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE code = 'shipping' AND order_id = '" . (int)$id . "'");
        if ($jaexiste->num_rows) {
            return ['meio' => $jaexiste->row['title'], 'total' => number_format($jaexiste->row['value'], 2, '.', '')];
        } else {
            return ['meio' => 'Envio da loja', 'total' => 0.00];
        }
    }

    public function imprimir()
    {
        $this->load->model('setting/setting');
        $this->load->model('sale/order');
        $data['config'] = $this->model_setting_setting->getSetting('correiospro5');
        $i = 1;
        $pedidos = explode(',', $_GET['pedidos']);
        $lista_pedidos = [];
        foreach ($pedidos as $k => $v) {
            $pedido = $this->model_sale_order->getOrder((int)$v);
            $servico_frete = explode('.', $pedido['shipping_code']);
            if (isset($servico_frete[1]) && file_exists(DIR_SYSTEM . '../image/correios/chancelas/' . $servico_frete[1] . '.png')) {
                $chancela = HTTPS_CATALOG . 'image/correios/chancelas/' . $servico_frete[1] . '.png';
            } else {
                $chancela = HTTPS_CATALOG . 'image/' . $this->config->get('config_logo');
            }
            $lista_pedidos[(int)$v] = array_merge($pedido, ['chancela' => $chancela]);
        }
        $data['pedidos'] = $lista_pedidos;
        $tema = 'extension/shipping/correiospro5_imprimir';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function etiquetas()
    {
        $this->load->language('extension/shipping/correiospro5');
        $this->load->model('sale/order');

        $status = $this->config->get('correiospro5_status');
        if ($status == 0) {
            $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->document->setTitle('Etiquetas de Postagem');

        $data['breadcrumbs'] = [];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        ];
        $data['breadcrumbs'][] = [
            'text' => 'Etiquetas de Postagem',
            'href' => $this->url->link('extension/shipping/correiospro5/etiquetas', 'token=' . $this->session->data['token'], 'SSL'),
        ];

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->get['filter_order_id'])) {
            $filter_order_id = $this->request->get['filter_order_id'];
        } else {
            $filter_order_id = null;
        }

        if (isset($this->request->get['filter_customer'])) {
            $filter_customer = $this->request->get['filter_customer'];
        } else {
            $filter_customer = null;
        }

        if (isset($this->request->get['filter_order_status'])) {
            $filter_order_status = $this->request->get['filter_order_status'];
        } else {
            $filter_order_status = null;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'o.order_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_order_id'])) {
            $url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
        }

        if (isset($this->request->get['filter_customer'])) {
            $url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_order_status'])) {
            $url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
        }

        $data['filtro_url'] = $url;

        $registro_por_pagina = $this->config->get('config_limit_admin');

        $data['orders'] = [];

        $filter_data = [
            'filter_order_id' => $filter_order_id,
            'filter_customer' => $filter_customer,
            'filter_order_status' => $filter_order_status,
            'filter_total' => null,
            'filter_date_added' => null,
            'filter_date_modified' => null,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $registro_por_pagina,
            'limit' => $registro_por_pagina,
        ];

        $order_total = $this->model_sale_order->getTotalOrders($filter_data);
        $results = $this->model_sale_order->getOrders($filter_data);
        $data['total_registro'] = $order_total;

        $data['total_paginas'] = ceil($order_total / $registro_por_pagina);
        $data['qual_pagina'] = $page;

        //$url = '';
        foreach ($results as $result) {
            $data['orders'][] = [
                'order_id' => $result['order_id'],
                'pedido_geral' => $this->model_sale_order->getOrder($result['order_id']),
                'customer' => $result['customer'],
                'status' => (isset($result['order_status']) ? $result['order_status'] : $result['status']),
                'cep' => $this->get_cep_pedido($result['order_id']),
                'meio' => $this->get_metodo_pedido($result['order_id']),
                'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
                'shipping_code' => $result['shipping_code'],
                'view' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'edit' => $this->url->link('sale/order/edit', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
                'delete' => $this->url->link('sale/order/delete', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
            ];
        }

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/shipping/correiospro5/etiquetas', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

        $data['filter_order_id'] = $filter_order_id;
        $data['filter_customer'] = $filter_customer;
        $data['filter_order_status'] = $filter_order_status;

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_etiquetas';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function index()
    {
        $this->load->language('extension/shipping/correiospro5');
        $this->document->setTitle("Correios Online e Offline PRO [Loja5]");
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('correiospro5', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/shipping/correiospro5', 'salvo=true&token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_none'] = $this->language->get('text_none');

        $data['entry_rate'] = $this->language->get('entry_rate');
        $data['entry_tax_class'] = $this->language->get('entry_tax_class');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_shipping'),
            'href' => $this->url->link('marketplace/extension', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Correios Online e Offline PRO [Loja5]',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['action'] = $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('marketplace/extension', 'token=' . $this->session->data['token'], 'SSL');
        $data['add_servico'] = $this->url->link('extension/shipping/correiospro5/add_servicos', 'token=' . $this->session->data['token'], 'SSL');
        $data['remover_servico'] = $this->url->link('extension/shipping/correiospro5/remover_servicos', 'token=' . $this->session->data['token'], 'SSL');
        $data['editar_servico'] = $this->url->link('extension/shipping/correiospro5/editar_servicos', 'token=' . $this->session->data['token'], 'SSL');

        $data['tab'] = isset($_GET['tab']) ? $_GET['tab'] : 'configuracoes';
        $data['salvos'] = isset($_GET['salvo']) ? true : false;
        $data['campos'] = $this->getCustomFields();

        $data['lista_servicos'] = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "correiospro5_servicos_cadastrados` AS a
		JOIN `" . DB_PREFIX . "correiospro5_servicos` AS b ON (a.id_servico=b.id_servico)
		ORDER BY a.titulo ASC");

        if (isset($this->request->post['correiospro5_geo_zone_id'])) {
            $data['correiospro5_geo_zone_id'] = $this->request->post['correiospro5_geo_zone_id'];
        } else {
            $data['correiospro5_geo_zone_id'] = $this->config->get('correiospro5_geo_zone_id');
        }

        if (isset($this->request->post['correiospro5_api'])) {
            $data['correiospro5_api'] = $this->request->post['correiospro5_api'];
        } else {
            $data['correiospro5_api'] = $this->config->get('correiospro5_api');
        }

        if (isset($this->request->post['correiospro5_cartao'])) {
            $data['correiospro5_cartao'] = $this->request->post['correiospro5_cartao'];
        } else {
            $data['correiospro5_cartao'] = $this->config->get('correiospro5_cartao');
        }

        if (isset($this->request->post['correiospro5_debug'])) {
            $data['correiospro5_debug'] = $this->request->post['correiospro5_debug'];
        } else {
            $data['correiospro5_debug'] = $this->config->get('correiospro5_debug');
        }

        if (isset($this->request->post['correiospro5_status'])) {
            $data['correiospro5_status'] = $this->request->post['correiospro5_status'];
        } else {
            $data['correiospro5_status'] = $this->config->get('correiospro5_status');
        }

        if (isset($this->request->post['correiospro5_status_mp'])) {
            $data['correiospro5_status_mp'] = $this->request->post['correiospro5_status_mp'];
        } else {
            $data['correiospro5_status_mp'] = $this->config->get('correiospro5_status_mp');
        }

        if (isset($this->request->post['correiospro5_status_vd'])) {
            $data['correiospro5_status_vd'] = $this->request->post['correiospro5_status_vd'];
        } else {
            $data['correiospro5_status_vd'] = $this->config->get('correiospro5_status_vd');
        }

        if (isset($this->request->post['correiospro5_status_ar'])) {
            $data['correiospro5_status_ar'] = $this->request->post['correiospro5_status_ar'];
        } else {
            $data['correiospro5_status_ar'] = $this->config->get('correiospro5_status_ar');
        }

        if (isset($this->request->post['correiospro5_cod'])) {
            $data['correiospro5_cod'] = $this->request->post['correiospro5_cod'];
        } else {
            $data['correiospro5_cod'] = $this->config->get('correiospro5_cod');
        }

        if (isset($this->request->post['correiospro5_senha'])) {
            $data['correiospro5_senha'] = $this->request->post['correiospro5_senha'];
        } else {
            $data['correiospro5_senha'] = $this->config->get('correiospro5_senha');
        }

        if (isset($this->request->post['correiospro5_contrato'])) {
            $data['correiospro5_contrato'] = $this->request->post['correiospro5_contrato'];
        } else {
            $data['correiospro5_contrato'] = $this->config->get('correiospro5_contrato');
        }

        if (isset($this->request->post['correiospro5_dr'])) {
            $data['correiospro5_dr'] = $this->request->post['correiospro5_dr'];
        } else {
            $data['correiospro5_dr'] = $this->config->get('correiospro5_dr');
        }

        if (isset($this->request->post['correiospro5_sort_order'])) {
            $data['correiospro5_sort_order'] = $this->request->post['correiospro5_sort_order'];
        } else {
            $data['correiospro5_sort_order'] = $this->config->get('correiospro5_sort_order');
        }

        if (isset($this->request->post['correiospro5_nome'])) {
            $data['correiospro5_nome'] = $this->request->post['correiospro5_nome'];
        } else {
            $data['correiospro5_nome'] = $this->config->get('correiospro5_nome');
        }

        if (isset($this->request->post['correiospro5_tax_class_id'])) {
            $data['correiospro5_tax_class_id'] = $this->request->post['correiospro5_tax_class_id'];
        } else {
            $data['correiospro5_tax_class_id'] = $this->config->get('correiospro5_tax_class_id');
        }

        if (isset($this->request->post['correiospro5_cep'])) {
            $data['correiospro5_cep'] = $this->request->post['correiospro5_cep'];
        } else {
            $data['correiospro5_cep'] = $this->config->get('correiospro5_cep');
        }

        if (isset($this->request->post['correiospro5_tempo_cache'])) {
            $data['correiospro5_tempo_cache'] = $this->request->post['correiospro5_tempo_cache'];
        } else {
            $data['correiospro5_tempo_cache'] = $this->config->get('correiospro5_tempo_cache');
        }

        if (isset($this->request->post['correiospro5_custom_numero'])) {
            $data['correiospro5_custom_numero'] = $this->request->post['correiospro5_custom_numero'];
        } else {
            $data['correiospro5_custom_numero'] = $this->config->get('correiospro5_custom_numero');
        }

        if (isset($this->request->post['correiospro5_custom_complemento'])) {
            $data['correiospro5_custom_complemento'] = $this->request->post['correiospro5_custom_complemento'];
        } else {
            $data['correiospro5_custom_complemento'] = $this->config->get('correiospro5_custom_complemento');
        }

        if (isset($this->request->post['correiospro5_remetente'])) {
            $data['correiospro5_remetente'] = $this->request->post['correiospro5_remetente'];
        } else {
            $data['correiospro5_remetente'] = $this->config->get('correiospro5_remetente');
        }

        if (isset($this->request->post['correiospro5_endereco_remetente'])) {
            $data['correiospro5_endereco_remetente'] = $this->request->post['correiospro5_endereco_remetente'];
        } else {
            $data['correiospro5_endereco_remetente'] = $this->config->get('correiospro5_endereco_remetente');
        }

        $tema = 'extension/shipping/correiospro5';

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function add_servicos()
    {
        $this->load->language('extension/shipping/correiospro5');

        $this->document->setTitle("Adicionar Servi&ccedil;o");

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Frete Correios',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Adicionar Servi&ccedil;o',
            'href' => $this->url->link('extension/shipping/correiospro5/add_servicos', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['voltar'] = $this->url->link('extension/shipping/correiospro5', 'tab=servicos&token=' . $this->session->data['token'], 'SSL');
        $data['salvar_add_servico'] = $this->url->link('extension/shipping/correiospro5/salvar_servico', 'tab=servicos&token=' . $this->session->data['token'], 'SSL');

        $data['lista_servicos'] = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "correiospro5_servicos` WHERE id_servico NOT IN ('" . $this->servicosCadastrados() . "') ORDER BY nome ASC");

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_add_servico';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function atualizar()
    {
        $this->load->language('extension/shipping/correiospro5');

        $this->document->setTitle("Atualizar Tabelas");

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Frete Correios',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Tabelas Offline',
            'href' => $this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['id_servico'] = str_pad($_GET['servico'], 5, "0", STR_PAD_LEFT);

        $data['voltar'] = $this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL');

        $data['tabelas'] = html_entity_decode($this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL'));
        $data['api'] = html_entity_decode($this->url->link('extension/shipping/correiospro5/api', 'token=' . $this->session->data['token'], 'SSL'));

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "correiospro5_base`");
        $data['faixas'] = $query->rows;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_atualizar';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function tabelas()
    {
        $this->load->language('extension/shipping/correiospro5');

        $this->document->setTitle("Tabelas");

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Frete Correios',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Tabelas Offline',
            'href' => $this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['voltar'] = $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL');
        $data['atualizar_tabela'] = $this->url->link('extension/shipping/correiospro5/atualizar', 'token=' . $this->session->data['token'], 'SSL');
        $data['limpar_tabela'] = $this->url->link('extension/shipping/correiospro5/limpar', 'token=' . $this->session->data['token'], 'SSL');
        $data['valores_tabela'] = $this->url->link('extension/shipping/correiospro5/valores', 'token=' . $this->session->data['token'], 'SSL');
        $data['limpar_cache'] = $this->url->link('extension/shipping/correiospro5/limpar_cache', 'token=' . $this->session->data['token'], 'SSL');

        $data['total_faixas'] = $this->db->query("SELECT * FROM `" . DB_PREFIX . "correiospro5_base`");

        $data['lista_servicos'] = $this->db->query("SELECT (SELECT COUNT(*) FROM `" . DB_PREFIX . "correiospro5_cotacoes` AS a WHERE a.id_servico=b.id_servico AND a.erro = 0 AND a.cliente=0) AS cotacoes_cadastradas,b.* FROM  `" . DB_PREFIX . "correiospro5_servicos` AS b WHERE b.suporte_offline = 1 ORDER BY b.nome ASC");

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_tabelas';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function editar_servicos()
    {
        $this->load->language('extension/shipping/correiospro5');

        $this->document->setTitle("Editar Servi&ccedil;o");

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Frete Correios',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Faixas',
            'href' => $this->url->link('extension/shipping/correiospro5', 'tab=servicos&token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $sql = "SELECT * FROM `" . DB_PREFIX . "correiospro5_servicos_cadastrados` WHERE id_servico = '" . $_GET['servico'] . "'";
        $servico = $this->db->query($sql);
        $data['servico'] = $servico->row;
        $data['lista_servicos'] = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "correiospro5_servicos` WHERE id_servico = '" . $_GET['servico'] . "' ORDER BY nome ASC");

        $data['voltar'] = $this->url->link('extension/shipping/correiospro5', 'tab=servicos&token=' . $this->session->data['token'], 'SSL');
        $data['salvar_editar_servico'] = $this->url->link('extension/shipping/correiospro5/salvar_editar_servico', 'token=' . $this->session->data['token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_editar_servicos';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function editar_faixa()
    {
        $this->load->language('extension/shipping/correiospro5');

        $this->document->setTitle("Editar Faixa");

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Frete Correios',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Faixas',
            'href' => $this->url->link('extension/shipping/correiospro5/faixas', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $sql = "SELECT * FROM `" . DB_PREFIX . "correiospro5_base` WHERE id = '" . (int)$_GET['faixa'] . "'";
        $faixa = $this->db->query($sql);
        $data['faixa'] = $faixa->row;

        $data['voltar'] = $this->url->link('extension/shipping/correiospro5/faixas', 'token=' . $this->session->data['token'], 'SSL');

        $data['salvar_faixa'] = $this->url->link('extension/shipping/correiospro5/salvar_editar_faixa', 'token=' . $this->session->data['token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_editar_faixa';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function add_faixa()
    {
        $this->load->language('extension/shipping/correiospro5');

        $this->document->setTitle("Adicionar Faixa");

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Frete Correios',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Adicionar Faixa',
            'href' => $this->url->link('extension/shipping/correiospro5/add_faixa', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['voltar'] = $this->url->link('extension/shipping/correiospro5/faixas', 'token=' . $this->session->data['token'], 'SSL');

        $data['salvar_faixa'] = $this->url->link('extension/shipping/correiospro5/salvar_faixa', 'token=' . $this->session->data['token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_add_faixa';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function faixas()
    {
        $this->load->language('extension/shipping/correiospro5');

        $this->document->setTitle("Faixas de CEPs");

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Frete Correios',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Faixas de CEP',
            'href' => $this->url->link('extension/shipping/correiospro5/faixas', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['voltar'] = $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL');
        $data['editar_faixa'] = $this->url->link('extension/shipping/correiospro5/editar_faixa', 'token=' . $this->session->data['token'], 'SSL');
        $data['valores_faixa'] = $this->url->link('extension/shipping/correiospro5/valores_faixa', 'token=' . $this->session->data['token'], 'SSL');
        $data['add_faixa'] = $this->url->link('extension/shipping/correiospro5/add_faixa', 'token=' . $this->session->data['token'], 'SSL');
        $data['remover_faixa'] = $this->url->link('extension/shipping/correiospro5/remover_faixa', 'token=' . $this->session->data['token'], 'SSL');

        //paginacao
        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $registros = 50;
        $inicio = ($registros * ($page - 1));

        $sql = "SELECT * FROM `" . DB_PREFIX . "correiospro5_base` ORDER BY id ASC";
        $total_registros = $this->db->query($sql);

        $data['total_registro'] = $order_total = $total_registros->num_rows;
        $data['total_paginas'] = ceil($total_registros->num_rows / $registros);
        $data['qual_pagina'] = $page;

        $sql = "SELECT * FROM `" . DB_PREFIX . "correiospro5_base`
		ORDER BY inicio ASC LIMIT $inicio,$registros";
        $data['faixas'] = $this->db->query($sql);

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = 50;
        $pagination->url = $this->url->link('extension/shipping/correiospro5/faixas', 'token=' . $this->session->data['token'] . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 50) + 1 : 0, ((($page - 1) * 50) > ($order_total - 50)) ? $order_total : ((($page - 1) * 50) + 50), $order_total, ceil($order_total / 50));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_faixas';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function valores_faixa()
    {
        $this->load->language('extension/shipping/correiospro5');

        $this->document->setTitle("Valores de Tabelas por Faixa");

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Frete Correios',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Tabelas Offline',
            'href' => $this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['voltar'] = $this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL');

        //paginacao
        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $registros = 50;
        $inicio = ($registros * ($page - 1));

        $sql = "SELECT * FROM `" . DB_PREFIX . "correiospro5_cotacoes` WHERE cep_base = '" . $this->db->escape(str_pad($_GET['cep_base'], 8, "0", STR_PAD_LEFT)) . "' ORDER BY peso ASC";
        $total_registros = $this->db->query($sql);

        $data['total_registro'] = $order_total = $total_registros->num_rows;
        $data['total_paginas'] = ceil($total_registros->num_rows / $registros);
        $data['qual_pagina'] = $page;

        $sql = "SELECT * FROM `" . DB_PREFIX . "correiospro5_cotacoes` AS a 
		LEFT JOIN `" . DB_PREFIX . "correiospro5_servicos` AS b ON(b.id_servico=a.id_servico)
		WHERE a.cep_base = '" . $this->db->escape(str_pad($_GET['cep_base'], 8, "0", STR_PAD_LEFT)) . "' ORDER BY a.id_servico ASC, a.peso ASC LIMIT $inicio,$registros";
        $data['valores'] = $this->db->query($sql);

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = 50;
        $pagination->url = $this->url->link('extension/shipping/correiospro5/valores_faixa', 'cep_base=' . $_GET['cep_base'] . '&token=' . $this->session->data['token'] . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 50) + 1 : 0, ((($page - 1) * 50) > ($order_total - 50)) ? $order_total : ((($page - 1) * 50) + 50), $order_total, ceil($order_total / 50));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_valores';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function valores()
    {
        $this->load->language('extension/shipping/correiospro5');

        $this->document->setTitle("Valores de Tabelas");

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false,
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Frete Correios',
            'href' => $this->url->link('extension/shipping/correiospro5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Tabelas Offline',
            'href' => $this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: ',
        ];

        $data['voltar'] = $this->url->link('extension/shipping/correiospro5/tabelas', 'token=' . $this->session->data['token'], 'SSL');

        //paginacao
        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $registros = 50;
        $inicio = ($registros * ($page - 1));

        $sql = "SELECT * FROM `" . DB_PREFIX . "correiospro5_cotacoes` WHERE id_servico = '" . $this->db->escape($_GET['servico']) . "' ORDER BY peso ASC";
        $total_registros = $this->db->query($sql);

        $data['total_registro'] = $order_total = $total_registros->num_rows;
        $data['total_paginas'] = ceil($total_registros->num_rows / $registros);
        $data['qual_pagina'] = $page;

        $sql = "SELECT * FROM `" . DB_PREFIX . "correiospro5_cotacoes` AS a 
		LEFT JOIN `" . DB_PREFIX . "correiospro5_servicos` AS b ON(b.id_servico=a.id_servico)
		WHERE a.id_servico = '" . $this->db->escape($_GET['servico']) . "' ORDER BY a.cep_base ASC, a.peso ASC LIMIT $inicio,$registros";
        $data['valores'] = $this->db->query($sql);

        $pagination = new Pagination();
        $pagination->total = $order_total;
        $pagination->page = $page;
        $pagination->limit = 50;
        $pagination->url = $this->url->link('extension/shipping/correiospro5/valores', 'servico=' . $_GET['servico'] . '&token=' . $this->session->data['token'] . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 50) + 1 : 0, ((($page - 1) * 50) > ($order_total - 50)) ? $order_total : ((($page - 1) * 50) + 50), $order_total, ceil($order_total / 50));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $tema = 'extension/shipping/correiospro5_valores';
        $this->response->setOutput($this->load->view($tema, $data));
    }

    public function getCustomFields($data = [])
    {
        if (empty($data['filter_customer_group_id'])) {
            $sql = "SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN " . DB_PREFIX . "custom_field_description cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cfd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        } else {
            $sql = "SELECT * FROM " . DB_PREFIX . "custom_field_customer_group cfcg LEFT JOIN `" . DB_PREFIX . "custom_field` cf ON (cfcg.custom_field_id = cf.custom_field_id) LEFT JOIN " . DB_PREFIX . "custom_field_description cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cfd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        }

        if (!empty($data['filter_name'])) {
            $sql .= " AND cfd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $sql .= " AND cfcg.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
        }

        $sort_data = [
            'cfd.name',
            'cf.type',
            'cf.location',
            'cf.status',
            'cf.sort_order',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY cfd.name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function obj2array($obj)
    {
        return json_decode(json_encode($obj), true);
    }

    private function validate()
    {
        return true;
    }
}
