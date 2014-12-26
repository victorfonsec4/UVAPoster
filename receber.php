<?hh
error_reporting(E_ERROR | E_PARSE);

$currentDir = dirname(__FILE__);
require_once $currentDir."/includes/simple_html_dom.php";

$ch = curl_init("http://uhunt.felix-halim.net/api/subs-user/144377");
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

$content = json_decode(curl_exec($ch), true);
curl_close($ch);

function cmp($a, $b)
{
	return $a[4] - $b[4];
}

$probs = usort($content["subs"], "cmp");
$lastSub = end($content["subs"]);
$dt = new DateTime('@'.$lastSub[4]);
$dt->setTimeZone(new DateTimeZone('America/Sao_Paulo'));
$time = $dt->format('d/m/Y H:m');
$uname = $content["uname"]."\n";

$subCodes = array(
"10" => "Submission error",
"15" => "Cant be judged",
"20" => "In queue",
"30" => "Compile error",
"35" => "Restricted function",
"40" => "Runtime error",
"45" => "Output limit",
"50" => "Time limit",
"60" => "Memory limit",
"70" => "Wrong answer",
"80" => "PresentationE",
"90" => "Accepted");
$status = $subCodes[$lastSub[2]];

$url = "http://uhunt.felix-halim.net/api/p/id/".$lastSub[1];

$ch = curl_init($url);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

$content = json_decode(curl_exec($ch), true);
curl_close($ch);

echo $uname;
echo $content['title']."\n";
echo $time."\n";
echo $status."\n";
