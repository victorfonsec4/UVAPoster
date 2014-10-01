<?hh

$currentDir = dirname(__FILE__); 
require_once $currentDir.'/includes/simple_html_dom.php';

$user = "Sharkest";
$dir = dirName(__FILE__);
$myfile = fopen("{$dir}/password", "r");
$password = fread($myfile,filesize("{$dir}/password"));
$postData = "username=".urlencode($user)."&password=".urlencode($password);
$cookie = "cookie";

#abre uma sessão com o site e carrega variaveis escondidas para logar
$ch = curl_init("http://uva.onlinejudge.org/");
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie); 
curl_setopt ($ch, CURLOPT_REFERER, "http://uva.onlinejudge.org/"); 


$content = curl_exec($ch);
curl_close($ch);

$html = str_get_html($content);
$html = $html->find('table[class]', 0)->find('input[type=hidden]');

$loginFields = array('username' => $user, 'passwd' => $password);

foreach ($html as $element)
	$loginFields[$element->name] = $element->value;



$url = "http://uva.onlinejudge.org/index.php?option=com_comprofiler&task=login";
#faz o post com login, senha e as variaveis escondidas
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie); 
curl_setopt ($ch, CURLOPT_POST, 1); 
curl_setopt ($ch, CURLOPT_POSTFIELDS, $loginFields); 
$result = curl_exec ($ch); 

curl_close($ch);

#pega pagina de quick submit
$ch = curl_init("http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=25");
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie); 
#curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie); 
curl_setopt ($ch, CURLOPT_REFERER, $url); 


$content = curl_exec($ch);
curl_close($ch);

#echo $content;
$html = str_get_html($content);
$html = $html->find('form[method=post]', 0)->find('input[type=hidden]');

$dir = dirName(__FILE__);
$myfile = fopen($argv[2], "r");
$code = fread($myfile,filesize($argv[2]));

$submitFields = array('localid' => $argv[1], 'code' => $code, 'language' => 5);

foreach ($html as $element)
	$submitFields[$element->name] = urlencode($element->value);

// Submiting file
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=25&page=save_submission");
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie); 
curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $submitFields);
$result=curl_exec ($ch);
curl_close ($ch);
