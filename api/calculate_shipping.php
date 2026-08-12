<?php
// api/calculate_shipping.php - API de Cálculo de Frete Correios (PAC e SEDEX) por CEP
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/env.php';

// Alias de segurança para env() caso seja chamado por compatibilidade
if (!function_exists('env')) {
    function env($key, $default = '') {
        return get_env($key, $default);
    }
}

// Suporte a requisições HTTP GET/POST e CLI
$destCep = $_GET['cep'] ?? ($_GET['destination_cep'] ?? '');

if (empty($destCep) && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = @json_decode(file_get_contents('php://input'), true);
    $destCep = $input['cep'] ?? ($input['destination_cep'] ?? '');
    $itemCount = isset($input['item_count']) ? (int)$input['item_count'] : 1;
} else {
    $itemCount = isset($_GET['item_count']) ? (int)$_GET['item_count'] : 1;
}

$destCep = preg_replace('/\D/', '', (string)$destCep);

if (strlen($destCep) !== 8) {
    echo json_encode([
        'status' => 'error',
        'message' => 'CEP de destino inválido. Informe um CEP com 8 dígitos.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$senderCep = preg_replace('/\D/', '', get_env('SENDER_CEP', '29026741'));
if (empty($senderCep) || strlen($senderCep) !== 8) {
    $senderCep = '29026741';
}

$weight = max(0.3, $itemCount * 0.28); // Peso em kg
$length = 20; // cm
$width = 15;  // cm
$height = 10; // cm

// Tabela de Cálculo de Frete Regional baseada na Origem 29026-741 (Vitória - ES)
$ufGroup = substr($destCep, 0, 2); // Primeiros 2 dígitos
$firstDigit = substr($destCep, 0, 1);

// Padrão Sudeste
$pacPrice = 17.90; $pacDays = 5;
$sedexPrice = 26.90; $sedexDays = 2;

if ($ufGroup === '29') { 
    // Espírito Santo (Local / Estadual)
    $pacPrice = 13.90; $pacDays = 3;
    $sedexPrice = 18.90; $sedexDays = 1;
} elseif ($firstDigit === '0' || $firstDigit === '1') { 
    // São Paulo (SP)
    $pacPrice = 16.90; $pacDays = 4;
    $sedexPrice = 24.90; $sedexDays = 2;
} elseif ($firstDigit === '2' || $firstDigit === '3') { 
    // Rio de Janeiro (RJ) & Minas Gerais (MG)
    $pacPrice = 17.90; $pacDays = 4;
    $sedexPrice = 26.90; $sedexDays = 2;
} elseif ($firstDigit === '8' || $firstDigit === '9') { 
    // Região Sul (PR, SC, RS)
    $pacPrice = 21.90; $pacDays = 5;
    $sedexPrice = 34.90; $sedexDays = 2;
} elseif ($firstDigit === '7') { 
    // Centro-Oeste / DF
    $pacPrice = 23.90; $pacDays = 6;
    $sedexPrice = 38.90; $sedexDays = 3;
} else { 
    // Nordeste e Norte (4, 5, 6)
    $pacPrice = 27.90; $pacDays = 7;
    $sedexPrice = 47.90; $sedexDays = 3;
}

// Taxa para quantidade adicional de itens se houver mais de 3 itens
if ($itemCount > 3) {
    $extraFee = ($itemCount - 3) * 2.50;
    $pacPrice += $extraFee;
    $sedexPrice += $extraFee;
}

// Tentar consulta rápida no WebService dos Correios com timeout seguro de 1.5s
function tryCorreiosWS($senderCep, $destCep, $serviceCode, $weight, $length, $width, $height) {
    $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?" . http_build_query([
        'nCdEmpresa' => '',
        'sDsSenha' => '',
        'nCdServico' => $serviceCode,
        'sCepOrigem' => $senderCep,
        'sCepDestino' => $destCep,
        'nVlPeso' => number_format($weight, 2, ',', ''),
        'nCdFormato' => 1,
        'nVlComprimento' => $length,
        'nVlAltura' => $height,
        'nVlLargura' => $width,
        'nVlDiametro' => 0,
        'sCdMaoPropria' => 'N',
        'nVlValorDeclarado' => 0,
        'sCdAvisoRecebimento' => 'N',
        'StrRetorno' => 'xml'
    ]);

    if (function_exists('curl_init')) {
        $ch = @curl_init();
        if ($ch) {
            @curl_setopt($ch, CURLOPT_URL, $url);
            @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            @curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = @curl_exec($ch);
            $httpCode = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
            @curl_close($ch);

            if ($httpCode === 200 && !empty($response)) {
                libxml_use_internal_errors(true);
                $xml = @simplexml_load_string($response);
                if ($xml && isset($xml->cServico)) {
                    $valStr = (string)$xml->cServico->Valor;
                    $daysStr = (string)$xml->cServico->PrazoEntrega;
                    $price = (float)str_replace(',', '.', str_replace('.', '', $valStr));
                    $days = (int)$daysStr;
                    if ($price > 0 && $days > 0) {
                        return ['price' => $price, 'days' => $days];
                    }
                }
            }
        }
    }
    return null;
}

$pacWS = tryCorreiosWS($senderCep, $destCep, '04510', $weight, $length, $width, $height);
$sedexWS = tryCorreiosWS($senderCep, $destCep, '04016', $weight, $length, $width, $height);

$finalPacPrice = $pacWS ? $pacWS['price'] : $pacPrice;
$finalPacDays = $pacWS ? $pacWS['days'] : $pacDays;

$finalSedexPrice = $sedexWS ? $sedexWS['price'] : $sedexPrice;
$finalSedexDays = $sedexWS ? $sedexWS['days'] : $sedexDays;

echo json_encode([
    'status' => 'success',
    'destinationCep' => $destCep,
    'senderCep' => $senderCep,
    'options' => [
        [
            'id' => 'pac',
            'name' => 'Correios PAC (Econômico)',
            'price' => round($finalPacPrice, 2),
            'days' => $finalPacDays,
            'label' => 'R$ ' . number_format($finalPacPrice, 2, ',', '.') . ' (' . $finalPacDays . ' dias úteis)'
        ],
        [
            'id' => 'sedex',
            'name' => 'Correios SEDEX (Expresso)',
            'price' => round($finalSedexPrice, 2),
            'days' => $finalSedexDays,
            'label' => 'R$ ' . number_format($finalSedexPrice, 2, ',', '.') . ' (' . $finalSedexDays . ' dias úteis)'
        ]
    ]
], JSON_UNESCAPED_UNICODE);
