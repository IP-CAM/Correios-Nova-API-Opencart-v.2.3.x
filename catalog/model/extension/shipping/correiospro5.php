<?php

class ModelExtensionShippingCorreiosPro5 extends Model
{

    private $valor_declarado_max = 10000;
    private $cubagem_max = 296207.4163;
    private $peso_max = 30;
    private $fator = 6000;
    private $lados_max = 105;

    public $correios_vd = [
        '03085' => '064',
        '03050' => '019',
        '03298' => '064',
        '03220' => '019',
        '03140' => '019',
        '03158' => '019',
        '03204' => '019',
        '04227' => '065',
        '04367' => '064',
        '04154' => '019',
        '41106' => '064',
        '40010' => '019',
        '04510' => '064',
        '04014' => '019',
        '04669' => '064',
        '04553' => '019',
        '04596' => '064',
        '04162' => '019',
        '40045' => '019',
        '40126' => '019',
        '40215' => '019',
        '40169' => '019',
        '40290' => '019',
        '04235' => '065',
        '04391' => '065',
    ];

    public function formatar($valor)
    {
        if (version_compare(VERSION, '2.2.0.0', '>=')) {
            return $this->currency->format($valor, $this->session->data['currency']);
        } else {
            return $this->currency->format($valor);
        }
    }

    private function getDimensaoEmCm($unidade_id, $dimensao)
    {
        if (is_numeric($dimensao)) {
            $length_class_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class mc LEFT JOIN " . DB_PREFIX . "length_class_description mcd ON (mc.length_class_id = mcd.length_class_id) WHERE mcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND mc.length_class_id =  '" . (int)$unidade_id . "'");
            if (isset($length_class_product_query->row['unit'])) {
                if ($length_class_product_query->row['unit'] == 'mm') {
                    return $dimensao / 10;
                }
            }
        }

        return $dimensao;
    }

    private function getPesoEmKg($unidade_id, $peso)
    {
        if (is_numeric($peso)) {
            $weight_class_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND wc.weight_class_id =  '" . (int)$unidade_id . "'");
            if (isset($weight_class_product_query->row['unit'])) {
                if ($weight_class_product_query->row['unit'] == 'g') {
                    return ($peso / 1000);
                }
            }
        }

        return $peso;
    }

    private function dados_produtos()
    {
        $i = 0;
        $alt = [];
        $com = [];
        $lar = [];
        $peso = [];
        foreach ($this->cart->getProducts() as $k => $v) {
            //dados
            $largura = $this->getDimensaoEmCm($v['length_class_id'], $v['width']);
            $altura = $this->getDimensaoEmCm($v['length_class_id'], $v['height']);
            $profundidade = $this->getDimensaoEmCm($v['length_class_id'], $v['length']);
            $p = $this->getPesoEmKg($v['weight_class_id'], $v['weight']);
            $qtd = $v['quantity'];
            //minimos se = 0
            $largura = ($largura > 0) ? $largura : 10.00;
            $altura = ($altura > 0) ? $altura : 1.00;
            $profundidade = ($profundidade > 0) ? $profundidade : 15.00;
            $p = ($p > 0.01) ? $p : 0.01;
            //ordena as medidas
            $numbers = [$largura, $altura, $profundidade];
            sort($numbers);
            $altura = isset($numbers[0]) ? $numbers[0] : $altura;
            $largura = isset($numbers[1]) ? $numbers[1] : $largura;
            $profundidade = isset($numbers[2]) ? $numbers[2] : $profundidade;
            //produtos que tenha frete
            if ($qtd > 0 && $v['shipping']) {
                //se mais de um produto
                if ($qtd > 1) {
                    $n = $i;
                    for ($j = 0; $j < $qtd; $j++) {
                        $alt[$n] = $altura;
                        $com[$n] = $profundidade;
                        $lar[$n] = $largura;
                        $peso[$n] = $p / $qtd;
                        $n++;
                    }
                    $i = $n;
                } else {
                    //se um produto
                    $alt[$i] = $altura;
                    $com[$i] = $profundidade;
                    $lar[$i] = $largura;
                    $peso[$i] = $p;
                }
                $i++;
            }
        }

        return ['alt' => array_values($alt), 'com' => array_values($com), 'lar' => array_values($lar), 'peso' => array_sum($peso)];
    }

    private function raiz_cubica($alt, $com, $lar, $maximos)
    {
        $cubagem_total = 0;
        $total_items = count($alt);
        for ($i = 0; $i < $total_items; $i++) {
            $cubagem_total += $alt[$i] * $com[$i] * $lar[$i];
        }
        $raiz = 0;
        $maior = max($maximos);
        if (0 !== $cubagem_total && 0 < $maior) {
            $fator = $cubagem_total / $maior;
            $raiz = round(sqrt($fator), 1);
        }

        return $raiz;
    }

    private function cubagem($alt, $com, $lar)
    {
        if (count($alt) > 1) {
            $cubagem = [];
            $maximos = ['alt' => max($alt), 'com' => max($com), 'lar' => max($lar)];
            $raiz = $this->raiz_cubica($alt, $com, $lar, $maximos);
            $maior = array_search(max($maximos), $maximos, true);
            switch ($maior) {
                case 'alt' :
                    $cubagem = [
                        'alt' => max($alt),
                        'com' => $raiz,
                        'lar' => $raiz,
                        'itens' => count($alt),
                    ];
                    break;
                case 'com' :
                    $cubagem = [
                        'alt' => $raiz,
                        'com' => max($com),
                        'lar' => $raiz,
                        'itens' => count($alt),
                    ];
                    break;
                case 'lar' :
                    $cubagem = [
                        'alt' => $raiz,
                        'com' => $raiz,
                        'lar' => max($lar),
                        'itens' => count($alt),
                    ];
                    break;
                default :
                    $cubagem = [
                        'alt' => 0,
                        'com' => 0,
                        'lar' => 0,
                        'itens' => count($alt),
                    ];
                    break;
            }
        } else {
            $cubagem = ['alt' => max($alt), 'com' => max($com), 'lar' => max($lar), 'itens' => 1];
        }

        return $cubagem;
    }

    private function peso_cubado($cubagem)
    {
        return round(($cubagem['alt'] * $cubagem['com'] * $cubagem['lar']) / $this->fator, 2);
    }

    private function salvar_log($dados_log)
    {
        if (!empty($dados_log)) {
            $log = new Log('correiospro5-' . md5($this->config->get('correiospro5_serial')) . '-' . date('dmY') . '.log');
            $dados_log = is_array($dados_log) ? print_r($dados_log, true) : $dados_log;
            $log->write($dados_log);
        }

        return true;
    }

    private function validar_pacote($pacote, $peso)
    {
        $cubagem = (float)$pacote['alt'] * (float)$pacote['com'] * (float)$pacote['lar'];
        if (!$peso || $peso > $this->peso_max || !$cubagem || $cubagem > $this->cubagem_max) {
            $this->salvar_log('Ops, o peso ou cubagem fora dos limites!');
            $this->salvar_log('Peso: ' . $peso . ' / ' . $this->peso_max);
            $this->salvar_log('Peso: ' . $cubagem . ' / ' . $this->cubagem_max);

            return false;
        } else if (($pacote['alt'] > $this->lados_max) || ($pacote['com'] > $this->lados_max) || ($pacote['lar'] > $this->lados_max)) {
            $this->salvar_log('Ops, medidas de lados fora dos limites (+' . $this->lados_max . 'cm)!');
            $this->salvar_log($pacote['alt'] . 'x' . $pacote['com'] . 'x' . $pacote['lar']);

            return false;
        } else if (($pacote['alt'] + $pacote['com'] + $pacote['lar']) > 200) {
            $this->salvar_log('Ops, medidas de lados fora dos limites (+200cm)!');
            $this->salvar_log(($pacote['alt'] + $pacote['com'] + $pacote['lar']));

            return false;
        }

        return true;
    }

    public function dados_servico($cod)
    {
        $query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "correiospro5_servicos_cadastrados` WHERE id_servico = '" . str_pad($cod, 5, "0", STR_PAD_LEFT) . "'");

        return $query->row;
    }

    public function servicosAtivados()
    {
        $api = trim($this->config->get('correiospro5_api'));
        if ($api == 0) {
            $query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "correiospro5_servicos_cadastrados` WHERE status > 0");
            $servicos = [];
            foreach ($query->rows as $k => $v) {
                $servicos[$v['status']][] = $v['id_servico'];
            }

            return $servicos;
        } else {
            $query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "correiospro5_servicos_cadastrados` WHERE status > 0");
            $servicos = [];
            foreach ($query->rows as $k => $v) {
                $servicos[$k][] = $v['id_servico'];
            }

            return $servicos;
        }
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

    public function calcular_frete_online($vars_correios)
    {
        //dados contrato
        $cod = trim($this->config->get('correiospro5_cod'));
        $senha = trim($this->config->get('correiospro5_senha'));
        $api = trim($this->config->get('correiospro5_api'));

        //remove o cache se = 0
        if ((int)$this->config->get('correiospro5_tempo_cache') == 0) {
            $this->db->query("DELETE FROM  `" . DB_PREFIX . "correiospro5_cache`");
        }

        //servicos
        $servicos_correios_lista = explode(',', $vars_correios['nCdServico']);

        //monta a url de calculo
        $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?';
        $url .= http_build_query($vars_correios);

        //cria o hash da consulta
        $checksum = md5(json_encode($vars_correios));

        //consulta o cache 1
        $query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "correiospro5_cache` WHERE hash = '" . $checksum . "' AND DATEDIFF(atualizado,NOW()) <= " . (int)$this->config->get('correiospro5_tempo_cache') . " ORDER BY id DESC");
        $resultado_cache = $query->row;
        if (isset($resultado_cache['json']) && !empty($resultado_cache['json'])) {
            if ($this->config->get('correiospro5_debug')) {
                //$this->salvar_log('Frete Retornando do Cache:');
                //$this->salvar_log($resultado_cache);
            }

            return ['erro' => false, 'resultado' => json_decode($resultado_cache['json'], true), 'url' => $url, 'cache' => true];
        }

        //possui pac mini
        $possui_pac_mini = false;
        if (isset($servicos_correios_lista['04227']) || isset($servicos_correios_lista['4227'])) {
            $possui_pac_mini = true;
        }

        //se for cliente com contrato (api antiga)
        if ($api == 0) {
            if (($possui_pac_mini == false && (!empty($cod) && !empty($senha))) || ($possui_pac_mini == false && count($servicos_correios_lista) == 1)) {
                //curl
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                if (curl_errno($ch)) {
                    return ['erro' => true, 'erro_curl' => curl_error($ch), 'resultado' => [], 'url' => $url, 'api' => 'api antiga webservice'];
                } else {
                    $resultado = curl_exec($ch);
                    $curlCod = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    //trata o resultado
                    if ($curlCod != 200) {
                        return ['erro' => true, 'erro_curl' => $curlCod, 'resultado' => $resultado, 'url' => $url, 'api' => 'api antiga webservice'];
                    } elseif ($curlCod == 200) {
                        libxml_use_internal_errors(true);
                        $objeto_xml = simplexml_load_string($resultado);
                        if ($objeto_xml === false) {
                            return ['erro' => true, 'resultado' => [], 'url' => $url, 'api' => 'api antiga webservice'];
                        } else {
                            //resultado
                            $resultado_frete = $this->tratar_resultado($objeto_xml);
                            //verifica se retornou todos com erro
                            if (isset($resultado_frete['erros']) && count($servicos) == $resultado_frete['erros']) {
                                //retorn o resultado como erro
                                return ['erro' => true, 'resultado' => $resultado_frete, 'url' => $url, 'cache' => false, 'api' => 'api antiga webservice'];
                            } else {
                                //cria o cache
                                if (count($resultado_frete) > 0) {
                                    $sql = "INSERT INTO `" . DB_PREFIX . "correiospro5_cache` SET hash = '" . $checksum . "', peso = '" . str_replace(',', '.', $vars_correios['nVlPeso']) . "', total = '" . str_replace(',', '.', $vars_correios['nVlValorDeclarado']) . "', cep_destino = '" . $vars_correios['sCepDestino'] . "', json = '" . $this->db->escape(json_encode($resultado_frete, JSON_UNESCAPED_UNICODE)) . "', atualizado = NOW()";
                                    $this->db->query($sql);
                                }

                                //retorna o resultado
                                return ['erro' => false, 'resultado' => $resultado_frete, 'url' => $url, 'cache' => false, 'api' => 'api antiga webservice'];
                            }
                        }
                    }
                }

            } else {
                //clientes em o contrato correios
                $erros = [];
                $servicosXml = ['<Servicos>'];
                foreach ($servicos_correios_lista as $k => $v) {
                    //faz o merge do servico unico
                    $vars_correios_sem = array_merge($vars_correios, ['nCdServico' => $v]);
                    //merge valor declarado se ativado e for pac mini
                    if ($v == '04227') {
                        $vars_correios_sem = array_merge($vars_correios_sem, ['nVlValorDeclarado' => '100,00', 'sCdMaoPropria' => 'N', 'sCdAvisoRecebimento' => 'N']);
                    }
                    //monda a url de calculo
                    $url_sem = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?';
                    $url_sem .= http_build_query($vars_correios_sem);
                    //curl
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_URL, $url_sem);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                    if (curl_errno($ch)) {
                        //erro curl
                        $this->salvar_log('Erro no Calculo de Frete Correios: ' . curl_error($ch) . '!');
                    } else {
                        $resultado = curl_exec($ch);
                        $curlCod = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);
                        //trata o resultado
                        if ($curlCod != 200) {
                            //erro resultado
                            $this->salvar_log('Erro no Calculo de Frete Correios: Erro HTTP ' . $curlCod . '!');
                        } elseif ($curlCod == 200) {
                            libxml_use_internal_errors(true);
                            $objeto_xml = simplexml_load_string($resultado);
                            if ($objeto_xml === false) {
                                //erro xml
                                $this->salvar_log('Erro no Calculo de Frete Correios: Objeto XML Invalido!');
                            } else {
                                //resultado
                                if (isset($objeto_xml->cServico) && !empty($objeto_xml->cServico) && $objeto_xml->cServico instanceof SimpleXMLElement) {
                                    $servicosXml[] = $objeto_xml->cServico->asXML();
                                }
                            }
                        }
                    }
                }
                $servicosXml[] = '</Servicos>';
                $xml = new SimpleXMLElement(implode('', $servicosXml));

                //resultado
                $resultado_frete = $this->tratar_resultado($xml);
                $resultados = count($resultado_frete);
                $qtd_erro = 0;
                foreach ($resultado_frete as $c => $v) {
                    if (isset($v['erros'])) {
                        $qtd_erro = $v['erros'];
                    }
                }
                //verifica se retornou todos com erro
                if ($resultados == 0 || $resultados == $qtd_erro) {
                    //retorn o resultado como erro
                    return ['erro' => true, 'resultado' => $resultado_frete, 'url' => $url, 'cache' => false, 'api' => 'api antiga webservice'];
                } else {
                    //cria o cache
                    if (count($resultado_frete) > 0) {
                        $sql = "INSERT INTO `" . DB_PREFIX . "correiospro5_cache` SET hash = '" . $checksum . "', peso = '" . str_replace(',', '.', $vars_correios['nVlPeso']) . "', total = '" . str_replace(',', '.', $vars_correios['nVlValorDeclarado']) . "', cep_destino = '" . $vars_correios['sCepDestino'] . "', json = '" . $this->db->escape(json_encode($resultado_frete, JSON_UNESCAPED_UNICODE)) . "', atualizado = NOW()";
                        $this->db->query($sql);
                    }

                    //retorna o resultado
                    return ['erro' => false, 'resultado' => $resultado_frete, 'url' => $url, 'cache' => false, 'api' => 'api antiga webservice'];
                }
            }
        } else {
            //nova api rest correios
            $token_api = $this->token_de_acesso_correios();
            //token nao ok
            if (!isset($token_api['dados']['token'])) {
                $this->salvar_log('Erro Authorization Nova API Correios:');
                $this->salvar_log($token_api);

                return ['erro' => true, 'erro_curl' => $token_api['status'], 'resultado' => $token_api['dados'], 'url' => $token_api['url'], 'api' => 'nova api rest'];
            } else {
                //token rest
                $token = trim($token_api['dados']['token']);
                $codServico = $vars_correios['nCdServico'];
                //dados a calcular
                $dados = [];
                $dados['psObjeto'] = (int)($this->valoresAntigoToNovo($vars_correios['nVlPeso']) * 1000);
                $id_contrato_correios = $this->config->get('correiospro5_contrato');
                $id_dr_correios = $this->config->get('correiospro5_dr');
                if (!empty(trim($id_contrato_correios))) {
                    $dados['nuContrato'] = trim($id_contrato_correios);
                    $dados['nuDR'] = trim($id_dr_correios);
                }
                $dados['cepOrigem'] = $vars_correios['sCepOrigem'];
                $dados['cepDestino'] = $vars_correios['sCepDestino'];
                $dados['VlDeclarado'] = $this->valoresAntigoToNovo($vars_correios['nVlValorDeclarado']);
                $dados['tpObjeto'] = 2;
                $dados['comprimento'] = $this->valoresAntigoToNovo($vars_correios['nVlComprimento']);
                $dados['largura'] = $this->valoresAntigoToNovo($vars_correios['nVlLargura']);
                $dados['altura'] = $this->valoresAntigoToNovo($vars_correios['nVlAltura']);
                if ($this->config->get('correiospro5_status_vd')) {
                    $dados['servicosAdicionais'][] = (isset($this->correios_vd[$codServico]) ? $this->correios_vd[$codServico] : '019');
                }
                if ($this->config->get('correiospro5_status_ar')) {
                    $dados['servicosAdicionais'][] = '001';
                }
                if ($this->config->get('correiospro5_status_mp')) {
                    $dados['servicosAdicionais'][] = '002';
                }
                $resultado = $this->get_frete_correios($codServico, $dados, $token);
                $resultado_prazo = $this->get_prazo_correios($codServico, $dados, $token);
                //se erro
                if (!isset($resultado['dados']['pcFinal'])) {
                    $this->salvar_log('Erro Frete Nova API Correios:');
                    $this->salvar_log($resultado);
                    $erro_correios_rest_api = isset($resultado['dados']['msgs'][0]) ? $resultado['dados']['msgs'][0] : 'Erro ao calcular frete via Rest API Correios (ver logs)!';

                    return ['erro' => true, 'erro_curl' => $erro_correios_rest_api, 'resultado' => $resultado, 'url' => $resultado['uri'], 'api' => 'nova api rest'];
                } else {
                    //resultado
                    $resultado_frete = $this->tratar_resultado($resultado['dados'], $resultado_prazo['dados'], $codServico);
                    //cria o cache
                    if (count($resultado_frete) > 0) {
                        $sql = "INSERT INTO `" . DB_PREFIX . "correiospro5_cache` SET hash = '" . $checksum . "', peso = '" . $this->valoresAntigoToNovo($vars_correios['nVlPeso']) . "', total = '" . $this->valoresAntigoToNovo($vars_correios['nVlValorDeclarado']) . "', cep_destino = '" . $vars_correios['sCepDestino'] . "', json = '" . $this->db->escape(json_encode($resultado_frete, JSON_UNESCAPED_UNICODE)) . "', atualizado = NOW()";
                        $this->db->query($sql);
                    }

                    //retorna o resultado
                    return ['erro' => false, 'resultado' => $resultado_frete, 'url' => $resultado['uri'], 'cache' => false, 'api' => 'nova api rest'];
                }
            }
        }
    }

    public function valoresAntigoToNovo($valor)
    {
        return (float)str_replace(',', '.', str_replace('.', '', $valor));
    }

    public function calcular_frete_offline($vars_correios)
    {
        //remove o cache se = 0
        if ((int)$this->config->get('correiospro5_tempo_cache') == 0) {
            $this->db->query("DELETE FROM  `" . DB_PREFIX . "correiospro5_cache`");
        }

        //custom
        $servicos_correios = explode(',', $vars_correios['nCdServico']);
        $peso = (float)str_replace(',', '.', $vars_correios['nVlPeso']);
        if ($peso <= 0.30) {
            $peso = 0.30;
        } elseif ($peso <= 1.00) {
            $peso = 1.00;
        } else {
            $peso = number_format(round($peso), 2, '.', '');
        }
        $para = $vars_correios['sCepDestino'];
        $para = substr(str_pad(preg_replace('/\D/', '', $para), 8, "0", STR_PAD_LEFT), 0, 5);
        //inicio
        $fretes = [];
        $servicos_total = count($servicos_correios);
        $erro = 0;
        foreach ($servicos_correios as $k => $v) {
            $sql = "SELECT * FROM `" . DB_PREFIX . "correiospro5_cotacoes` WHERE id_servico = '" . $this->db->escape($v) . "' AND peso = " . number_format($peso, 2, '.', '') . " AND erro = 0 AND valor > 0 AND (" . (int)$para . " BETWEEN `cep_inicio` AND `cep_fim`) ORDER BY custom DESC";
            $sql = $this->db->query($sql);
            if ($sql->num_rows == 0) {
                $erro++;
                $fretes[$v] = ['offline' => true, 'valor' => 0.00, 'prazo' => 0, 'servico' => $v, 'erro' => '-999', 'alerta' => 'Servico ' . $v . ' sem frete offline cadastrado ao cep ' . $para . ' e peso ' . number_format($peso, 2, '.', '') . '!', 'servico' => $this->dados_servico($v)];
            } else {
                $linha = $sql->row;
                $fretes[$v] = ['offline' => true, 'valor' => $linha['valor'], 'prazo' => $linha['prazo'], 'servico' => $v, 'erro' => '0', 'alerta' => '', 'servico' => $this->dados_servico($v), 'regra' => $linha];
            }
        }
        if ($erro == $servicos_total) {
            return ['erro' => true, 'resultado' => $fretes];
        } else {
            return ['erro' => false, 'resultado' => $fretes];
        }
    }

    public function tratar_resultado($objeto_xml, $resultado_prazo = [], $servico_rest = '')
    {
        $api = trim($this->config->get('correiospro5_api'));
        $fretes = [];
        if ($api == 0) {
            $obj = json_decode(json_encode($objeto_xml, JSON_UNESCAPED_UNICODE), true);
            if (isset($obj['cServico'][0]['Codigo']) && !empty($obj['cServico'][0]['Codigo']) && strlen($obj['cServico'][0]['Codigo']) == 5) {
                $erros = 0;
                foreach ($obj['cServico'] as $k => $v) {
                    $valor_frete = (float)str_replace(',', '.', str_replace('.', '', $v['Valor']));
                    if ($valor_frete == 0) {
                        $erros++;
                    }
                    $prazo = $v['PrazoEntrega'];
                    $servico = $v['Codigo'];
                    $erro = isset($v['Erro']) ? $v['Erro'] : '';
                    $alerta = isset($v['obsFim']) ? $v['obsFim'] : '';
                    $fretes[$servico] = ['erros' => $erros, 'offline' => false, 'valor' => $valor_frete, 'prazo' => $prazo, 'servico' => $servico, 'erro' => $erro, 'alerta' => $alerta, 'servico' => $this->dados_servico($servico)];
                }
            } elseif (isset($obj['cServico']['Codigo']) && !empty($obj['cServico']['Codigo']) && strlen($obj['cServico']['Codigo']) == 5) {
                $erros = 0;
                $valor_frete = (float)str_replace(',', '.', str_replace('.', '', $obj['cServico']['Valor']));
                if ($valor_frete == 0) {
                    $erros++;
                }
                $prazo = $obj['cServico']['PrazoEntrega'];
                $servico = $obj['cServico']['Codigo'];
                $erro = isset($obj['cServico']['Erro']) ? $obj['cServico']['Erro'] : '';
                $alerta = isset($obj['cServico']['obsFim']) ? $obj['cServico']['obsFim'] : '';
                $fretes[$servico] = ['erros' => $erros, 'offline' => false, 'valor' => $valor_frete, 'prazo' => $prazo, 'servico' => $servico, 'erro' => $erro, 'alerta' => $alerta, 'servico' => $this->dados_servico($servico)];
            } else {
                if (isset($obj['cServico']['MsgErro']) && !empty($obj['cServico']['MsgErro'])) {
                    $erro = $obj['cServico']['MsgErro'];
                } else {
                    $erro = 'problema ao calcular frete webservice, verificar se o servidor esta online!';
                }
                $fretes[] = ['erros' => 1, 'offline' => false, 'valor' => 0, 'prazo' => 0, 'erro' => $erro, 'alerta' => '', 'resultado' => $obj];
            }
        } else {
            $prazo = isset($resultado_prazo['prazoEntrega']) ? $resultado_prazo['prazoEntrega'] : 10;
            $erros = 0;
            $erro = '';
            $alerta = '';
            $valor_frete = (float)str_replace(',', '.', str_replace('.', '', $objeto_xml['pcFinal']));
            $fretes[$servico_rest] = ['erros' => $erros, 'offline' => false, 'valor' => $valor_frete, 'prazo' => $prazo, 'servico' => $this->dados_servico($servico_rest), 'erro' => $erro, 'alerta' => $alerta];
        }

        return $fretes;
    }

    public function consulta($fonte, $dados)
    {
        if ($fonte == 2) {
            //offline
            $offline = $this->calcular_frete_offline($dados);
            if ($this->config->get('correiospro5_debug')) {
                //$this->salvar_log($offline);
            }
            if ($offline['erro']) {
                $online = $this->calcular_frete_online($dados);
                $this->salvar_log('Ops, calculo offline Correios falhou ou indisponível no momento, tentativa de frete online realizada!');
                if ($this->config->get('correiospro5_debug')) {
                    //$this->salvar_log($online);
                }

                return $online;
            } else {
                return $offline;
            }
        } elseif ($fonte == 3) {
            //offline se webservice falhar
            $online = $this->calcular_frete_online($dados);
            if ($this->config->get('correiospro5_debug')) {
                //$this->salvar_log($online);
            }
            if ($online['erro']) {
                $offline = $this->calcular_frete_offline($dados);
                $this->salvar_log('Ops, calculo online Correios falhou ou indisponível no momento, tentativa de frete offline realizada!');
                if ($this->config->get('correiospro5_debug')) {
                    //$this->salvar_log($offline);
                }

                return $offline;
            } else {
                return $online;
            }
        } else {
            //online
            $online = $this->calcular_frete_online($dados);
            if ($this->config->get('correiospro5_debug')) {
                //$this->salvar_log($online);
            }

            return $online;
        }
    }

    public function getQuote($address)
    {
        //inicio
        $this->load->language('extension/shipping/cod');
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('correiospro5_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
        //regra de zona
        if (!$this->config->get('correiospro5_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }
        //se ativado
        $servicos_ativados = $this->servicosAtivados();
        if (!$this->config->get('correiospro5_status') || count($servicos_ativados) == 0 || $status == false) {
            return [];
        }
        //valida o cep
        $cep_destino = preg_replace('/\D/', '', $address['postcode']);
        if (strlen($cep_destino) != 8) {
            return [];
        }
        //faz a cubagem
        $quote_data = [];
        $total_pedido = $total_pedido_real = $this->cart->getSubTotal();
        $produtos_cubados = $this->dados_produtos();
        if (count($produtos_cubados) > 0) {
            //calcula os limites e cubagem
            $cubagem = $this->cubagem($produtos_cubados['alt'], $produtos_cubados['com'], $produtos_cubados['lar']);
            $peso_cubado = $this->peso_cubado($cubagem);
            //valida o pacote
            $valido = $this->validar_pacote($cubagem, $peso_cubado);
            if ($valido) {
                //peso base
                $peso_real = $produtos_cubados['peso'];
                if ($peso_real > $peso_cubado) {
                    $peso_base = $peso_real;
                } elseif ($peso_cubado > $peso_real && $peso_real > 5.00) {
                    $peso_base = $peso_cubado;
                } else {
                    $peso_base = $peso_real;
                }
                $peso_base = ($peso_base > 0) ? $peso_base : 0.10;
                //medidas
                $alt = ($cubagem['alt'] >= 1) ? $cubagem['alt'] : 1;
                $com = ($cubagem['com'] >= 15) ? $cubagem['com'] : 15;
                $lar = ($cubagem['lar'] >= 10) ? $cubagem['lar'] : 10;
                //regra valor minimo
                if ($total_pedido > 0 && $total_pedido < 22.50) {
                    $total_pedido = 22.50;
                } elseif ($total_pedido > 10000) {
                    $total_pedido = 10000;
                } else {
                    $total_pedido = $total_pedido_real;
                }
                $total = number_format($total_pedido, 2, ',', '');
                //monta os dados correios
                $cotacoes = $erros = [];
                foreach ($servicos_ativados as $k => $v) {
                    $vars_correios = [
                        'nCdServico' => implode(',', $v),
                        'nCdEmpresa' => trim($this->config->get('correiospro5_cod')),
                        'sDsSenha' => trim($this->config->get('correiospro5_senha')),
                        'sCepDestino' => $cep_destino,
                        'sCepOrigem' => preg_replace('/\D/', '', $this->config->get('correiospro5_cep')),
                        'nVlAltura' => number_format($alt, 2, ',', ''),
                        'nVlLargura' => number_format($lar, 2, ',', ''),
                        'nVlDiametro' => 0,
                        'nVlComprimento' => number_format($com, 2, ',', ''),
                        'nVlPeso' => number_format($peso_base, 2, ',', ''),
                        'nCdFormato' => 1,
                        'sCdMaoPropria' => (($this->config->get('correiospro5_status_mp') == '1') ? 'S' : 'N'),
                        'nVlValorDeclarado' => (($this->config->get('correiospro5_status_vd') == '1') ? $total : '0,00'),
                        'sCdAvisoRecebimento' => (($this->config->get('correiospro5_status_ar') == '1') ? 'S' : 'N'),
                        'StrRetorno' => 'xml',
                    ];
                    //se possuir servicos a cobrar forca o valor declarado
                    if (in_array('40045', $v) || in_array('40126', $v)) {
                        $vars_correios = array_merge($vars_correios, ['nVlValorDeclarado' => $total]);
                    }
                    //fonte de consulta
                    $resultado = $this->consulta($k, $vars_correios);
                    if (!empty($resultado['resultado']) && is_array($resultado['resultado']) && count($resultado['resultado']) > 0) {
                        foreach ($resultado['resultado'] as $cod => $cota) {
                            if (isset($cota['valor']) && $cota['valor'] > 0) {
                                //custom
                                $extra = 0;
                                $prazo_extra = isset($cota['servico']['prazo_extra']) ? $cota['servico']['prazo_extra'] : 0;
                                $valor_extra = isset($cota['servico']['valor_extra']) ? $cota['servico']['valor_extra'] : 0;
                                $tipo__extra = isset($cota['servico']['real_porcentagem']) ? $cota['servico']['real_porcentagem'] : 0;
                                $total_minim = isset($cota['servico']['total_minimo']) ? $cota['servico']['total_minimo'] : 0;
                                $total_minif = isset($cota['servico']['total_minimo_frete']) ? $cota['servico']['total_minimo_frete'] : 0;
                                $total_maxim = isset($cota['servico']['total_maximo']) ? $cota['servico']['total_maximo'] : 10000;
                                $ceps__frete = trim($cota['servico']['ceps']);
                                $ceps__exclu = trim($cota['servico']['ceps_excluir']);
                                $titulo = $cota['servico']['titulo'];
                                $peso_maximo = $cota['servico']['peso_maximo'];
                                $total_frete = $cota['valor'];
                                //regras
                                if ($total_pedido_real <= $total_maxim && $peso_base <= $peso_maximo) {
                                    //calcula o valor extra
                                    $valor_extra = abs($valor_extra);
                                    if ($valor_extra > 0 && $tipo__extra == 0) {
                                        $extra = $valor_extra;
                                        $total_frete = $total_frete + $extra;
                                    } elseif ($valor_extra > 0 && $tipo__extra == 1) {
                                        $extra = ($total_frete / 100) * $valor_extra;
                                        $total_frete = $total_frete + $extra;
                                    } elseif ($valor_extra > 0 && $tipo__extra == 2 && $total_minim > 0 && $total_pedido_real >= $total_minim) {
                                        $extra = ($total_frete / 100) * $valor_extra;
                                        $total_frete = $total_frete - $extra;
                                    } elseif ($valor_extra > 0 && $tipo__extra == 3 && $total_minim > 0 && $total_pedido_real >= $total_minim) {
                                        $total_frete = $total_frete - $valor_extra;
                                    }
                                    $total_frete = abs($total_frete);
                                    //valores somado
                                    $frete_texto = $this->formatar($this->tax->calculate(($total_frete), $this->config->get('correiospro5_tax_class_id'), $this->config->get('config_tax')));
                                    //prazos
                                    if (($cota['prazo'] + $prazo_extra) > 1) {
                                        $prazo_texto = $titulo . ' em at&eacute; ' . ($cota['prazo'] + $prazo_extra) . ' dias &uacute;teis';
                                    } else {
                                        $prazo_texto = $titulo . ' em at&eacute; ' . ($cota['prazo'] + $prazo_extra) . ' dia &uacute;til';
                                    }
                                    //se possui alerta
                                    if (isset($cota['alerta']) && !empty($cota['alerta'])) {
                                        $prazo_texto .= ' (<i>' . $cota['alerta'] . '</i>)';
                                    }
                                    //regra frete a excluir o calculo
                                    $pular_metodo = false;
                                    if (!empty($ceps__exclu)) {
                                        $gratuito = $this->cep_gratuito($cep_destino, $ceps__exclu);
                                        if ($gratuito) {
                                            $pular_metodo = true;
                                        }
                                    }
                                    //regra frete gratuito
                                    if ($total_minif > 0 && $total_pedido_real >= $total_minif && !empty($ceps__frete)) {
                                        $gratuito = $this->cep_gratuito($cep_destino, $ceps__frete);
                                        if ($gratuito) {
                                            $total_frete = 0;
                                        }
                                    }
                                    //cotacao
                                    if ($pular_metodo == false) {
                                        $quote_data[$cod] = [
                                            'offline' => $cota['offline'],
//                                            'resultado' => $resultado,
                                            'code' => 'correiospro5.' . $cod,
                                            'title' => $prazo_texto,
                                            'cost' => $total_frete,
                                            'tax_class_id' => $this->config->get('correiospro5_tax_class_id'),
                                            'text' => (($total_frete == 0) ? "<span style='color:#468847'><b>Gr&aacute;tis</b></span>" : $frete_texto),
                                        ];
                                    }
                                }
                            } else {
                                $erros[] = $cota;
                            }
                        }
                    }
                }
                //salva erros
                if (count($erros) > 0 || $this->config->get('correiospro5_debug')) {
                    $this->salvar_log('Logs Calculo de Frete Correios ao CEP ' . $cep_destino . ':');
                    $this->salvar_log(print_r($resultado, true));
                    $this->salvar_log('Pesos e Medidas:');
                    $this->salvar_log(print_r($vars_correios, true));
                    $this->salvar_log('Cubagem:');
                    $this->salvar_log(print_r($produtos_cubados, true));
                    $this->salvar_log(print_r($cubagem, true));
                    $this->salvar_log('-----------------------------------------');
                }
                //resultado
                if (count($quote_data) > 0) {
                    return [
                        'code' => 'correiospro5',
                        'title' => html_entity_decode($this->config->get('correiospro5_nome')),
                        'quote' => $quote_data,
                        'sort_order' => $this->config->get('correiospro5_sort_order'),
                        'error' => false,
                    ];
                } else {
                    return [];
                }
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    private function cep_gratuito($cep, $faixas)
    {
        $faixas = trim($faixas);
        $cep_ignorado = false;
        if (!empty($faixas)) {
            $faixas = explode(PHP_EOL, $faixas);
            foreach ($faixas as $v) {
                $quebra = explode(',', $v);
                if (isset($quebra[0]) && $cep >= preg_replace('/\D/', '', $quebra[0]) && isset($quebra[1]) && $cep <= preg_replace('/\D/', '', $quebra[1])) {
                    $cep_ignorado = true;
                    break;
                }
            }
        }

        return $cep_ignorado;
    }
}
