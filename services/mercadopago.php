<?php
/**
 * Serviço de Integração com Mercado Pago REST API (cURL nativo PHP)
 * Suporta Checkout Pro (Criação de Preferências) e Consulta Oficial de Pagamentos para Webhook
 */

require_once __DIR__ . '/../config/env.php';

class MercadoPagoService {
    private string $accessToken;
    private string $baseUrl = 'https://api.mercadopago.com';

    public function __construct(?string $accessToken = null) {
        $this->accessToken = $accessToken ?? get_env('MERCADO_PAGO_ACCESS_TOKEN', '');
    }

    /**
     * Cria uma Preferência no Mercado Pago Checkout Pro
     * @param array $preferencePayload
     * @return array
     * @throws Exception
     */
    public function createPreference(array $preferencePayload): array {
        if (empty($this->accessToken)) {
            throw new Exception('Mercado Pago Access Token não configurado no backend (.env).');
        }

        $url = $this->baseUrl . '/checkout/preferences';
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferencePayload, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Erro cURL ao conectar ao Mercado Pago: " . $error);
        }

        $data = json_decode($response, true);
        if ($httpCode >= 400) {
            $msg = $data['message'] ?? 'Erro ao criar preferência no Mercado Pago.';
            throw new Exception("Mercado Pago API Error ({$httpCode}): " . $msg);
        }

        return $data;
    }

    /**
     * Consulta as informações oficiais de um pagamento via Payment ID (Segurança do Webhook)
     * @param string $paymentId
     * @return array
     * @throws Exception
     */
    public function getPaymentInfo(string $paymentId): array {
        if (empty($this->accessToken)) {
            throw new Exception('Mercado Pago Access Token não configurado no backend (.env).');
        }

        $url = $this->baseUrl . '/v1/payments/' . urlencode($paymentId);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Erro cURL ao consultar pagamento no Mercado Pago: " . $error);
        }

        $data = json_decode($response, true);
        if ($httpCode >= 400) {
            $msg = $data['message'] ?? 'Pagamento não encontrado no Mercado Pago.';
            throw new Exception("Mercado Pago API Error ({$httpCode}): " . $msg);
        }

        return $data;
    }
}
