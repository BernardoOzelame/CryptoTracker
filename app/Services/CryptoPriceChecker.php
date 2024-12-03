<?php

namespace App\Services;

use App\Jobs\AlertCryptoJob;

class CryptoPriceChecker
{
    /**
     * Verifica os preços das moedas e dispara os jobs se necessário.
     *
     * @param array $userAlerts Lista de alertas configurados pelos usuários.
     */
    public static function checkPrices($userAlerts)
    {
        // Itera sobre todos os alertas do usuário
        foreach ($userAlerts as $alert) {
            $currentPrice = self::getCurrentPrice($alert->coin_name); // Pega o preço atual da moeda
            if ($currentPrice >= $alert->alert_value) {
                // Dispara o job para enviar o e-mail de alerta
                AlertCryptoJob::dispatch($alert->user, $alert->coin_name, $alert->alert_value);
            }
        }
    }

    /**
     * Obtém o preço atual de uma moeda.
     * Substituir por uma chamada real a uma API de mercado de criptomoedas.
     *
     * @param string $coinName Nome da moeda.
     * @return float Preço simulado da moeda.
     */
    public static function getCurrentPrice($coinName)
    {
        // Simulação de preço; substituir por integração com uma API real (como CoinGecko ou CoinMarketCap)
        return rand(1, 100);
    }
}
