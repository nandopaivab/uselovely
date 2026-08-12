<?php
// api/calculate_shipping.php - API de Cálculo de Frete Correios (PAC e SEDEX) por CEP
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/env.php';

// Obter CEP de Destino
$destCep = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $destCep = $input['cep'] ?? ($input['destination_cep'] ?? '');
    $itemCount = isset($input['item_count']) ? (int)$input['item_count'] : 1;
} else {
    $destCep = $_GET['cep'] ?? ($_GET['destination_cep'] ?? '');
    $itemCount = isset($_GET['item_count']) ? (int)$_GET['item_count'] : 1;
}

$destCep = preg_replace('/\D/', '', $destCep);

if (strlen($destCep) !== 8) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'CEP de destino inválido. Informe um CEP com 8 dígitos.']);
    exit;
}

$senderCep = preg_replace('/\D/', '', env('SENDER_CEP', '01001000'));
if (empty($senderCep) || strlen($senderCep) !== 8) {
    $senderCep = '01001000';
}

$weight = max(0.3, $itemCount * 0.28); // Peso em kg
$length = 20; // cm
$width = 15;  // cm
$height = 10; // cm

// Tentar consultar API Oficial do WebService dos Correios
function queryCorreiosWS($senderCep, $destCep, $serviceCode, $weight, $length, $width, $height) {
    $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?" . http_build_query([
        'nCdEmpresa' => '',
        'sDsSenha' => '',
        'nCdServico' => $serviceCode,
        'sCepOrigem' => $senderCep,
        'sCepDestino' => $destCep,
        'nVlPeso' => number_format($weight, 2, ',', ''),
        'nCdFormato' => 1, // Caixa/Pacote
        'nVlComprimento' => $length,
        'nVlAltura' => $height,
        'nVlLargura' => $width,
        'nVlDiametro' => 0,
        'sCdMaoPropria' => 'N',
        'nVlValorDeclarado' => 0,
        'sCdAvisoRecebimento' => 'N',
        'StrRetorno' => 'xml'
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Timeout curto de 3 segundos
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && !empty($response)) {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response);
        if ($xml && isset($xml->cServico)) {
            $valStr = (string)$xml->cServico->Valor;
            $daysStr = (string)$xml->cServico->PrazoEntrega;
            $errStr = (string)$xml->cServico->MsgErro;

            $price = (float)str_replace(',', '.', str_replace('.', '', $valStr));
            $days = (int)$daysStr;

            if ($price > 0 && $days > 0) {
                return ['price' => $price, 'days' => $days];
            }
        }
    }
    return null;
}

// 1. Tentar PAC (04510 / 03298) e SEDEX (04016 / 03220)
$pacRes = queryCorreiosWS($senderCep, $destCep, '04510', $weight, $length, $width, $height);
if (!$pacRes) $pacRes = queryCorreiosWS($senderCep, $destCep, '03298', $weight, $length, $width, $height);

$sedexRes = queryCorreiosWS($senderCep, $destCep, '04016', $weight, $length, $width, $height);
if (!$sedexRes) $sedexRes = queryCorreiosWS($senderCep, $destCep, '03220', $weight, $length, $width, $height);

// Fallback por Faixa de CEP Regional se Correios estiver indisponível
$ufGroup = substr($destCep, 0, 1);
$pacPrice = 18.90; $pacDays = 6;
$sedexPrice = 32.90; $sedexDays = 2;

if ($ufGroup === '0' || $ufGroup === '1') { // SP Capital e Interior
    $pacPrice = 14.90; $pacDays = 3;
    $sedexPrice = 22.90; $sedexDays = 1;
} elseif ($ufGroup === '2' || $ufGroup === '3') { // RJ, ES, MG
    $pacPrice = 18.90; $pacDays = 5;
    $sedexPrice = 29.90; $sedexDays = 2;
} elseif ($ufGroup === '8' || $ufGroup === '9') { // Sul (PR, SC, RS)
    $pacPrice = 21.90; $pacDays = 5;
    $sedexPrice = 34.90; $sedexDays = 2;
} elseif ($ufGroup === '7') { // Centro-Oeste / DF
    $pacPrice = 24.90; $pacDays = 6;
    $sedexPrice = 39.90; $sedexDays = 3;
} else { // Nordeste e Norte (4, 5, 6)
    $pacPrice = 29.90; $pacDays = 8;
    $sedexPrice = 49.90; $sedexDays = 3;
}

// Adicionar taxa por quantidade adicional de itens se houver mais de 3 itens
if ($itemCount > 3) {
    $extraFee = ($itemCount - 3) * 2.50;
    $pacPrice += $extraFee;
    $sedexPrice += $extraFee;
}

$finalPacPrice = $pacRes ? $pacRes['price'] : $pacPrice;
$finalPacDays = $pacRes ? $pacRes['days'] : $pacDays;

$finalSedexPrice = $sedexRes ? $sedexRes['price'] : $sedexPrice;
$finalSedexDays = $sedexRes ? $sedexRes['days'] : $sedexDays;

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
