<?php
/*
Script Name: true Google Search (trueGS)
Description: This script returns google search results as they would actually show up when the search is executed manually. The returned results include title, url and description as shown in the search results.
Version: 1.0.1
Author: technofreak777@gmail.com
Author URI: http://thetechnofreak.com
NOTES:
* This code is free to use as long as this comment is untouched.
* The author (technofreak777@gmail.com or http://thetechnofreak.com or the actual human author) will not be responsible for any consequences of using this script.
* By using this script, you agree to not increase the search load on google's servers.
*/
require("functions.php");
function trueGS($query="sample query",$npages=1,$local="com",$start=0,$proxy="",$cookie_file_path="cookies.txt" )//caution: use relative path for cookies
{
$results=array();
if($npages>=98)	//Thats what google will give you.
	$npages=98;	
$cookie_file_path=realpath($cookie_file_path);
$gg_url = 'http://www.google.'.$local.'/search?hl=en&q=' . urlencode($query) . "&start=";
$results['search_url']=$gg_url;
$options = array(		
        CURLOPT_RETURNTRANSFER	=> true,     // return web page
        CURLOPT_HEADER			=> false,    // don't return headers
        CURLOPT_FOLLOWLOCATION	=> true,     // follow redirects
        CURLOPT_ENCODING		=> "",       // handle all encodings
        CURLOPT_AUTOREFERER		=> true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT	=> 120,      // timeout on connect
        CURLOPT_TIMEOUT			=> 120,      // timeout on response
        CURLOPT_MAXREDIRS		=>	10,       // stop after 10 redirects
		CURLOPT_COOKIEFILE		=>	$cookie_file_path,
		CURLOPT_COOKIEJAR		=>	$cookie_file_path,
		CURLOPT_USERAGENT		=>	"Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3",
		CURLOPT_REFERER			=>	"http://www.google.com/",
    );
if(testPROXY($proxy))
{
	$options[CURLOPT_PROXY]=$proxy;
	$results['proxy_used']=true;
}else
	$results['proxy_used']=false;
$errors=array();	//Will contain all the pages where an error occured.
$size=0;$num=0;
$done=false;
for ($page = $start; ($page < $npages) && !$done; $page++) 
{	 
    $ch = curl_init($gg_url.$page.'0');
    curl_setopt_array($ch,$options);	
	$scraped=curl_exec($ch);	
	$curl_error = curl_error($ch);	
    curl_close( $ch );
	
	if(!empty($curl_error))
	{
		$errors[]=$page;
		$results["errors"][]=$errors;
		continue;		
	}
	if(preg_match("/sorry.google.com/", $scraped))
	{
		$results["blocked"]=true;
		return;
	}else
		$results["blocked"]=false;
	
	$result = array();
	$nres=preg_match_all('/a href="([^"]+)" class=l.+?>(.+?)<\/a>.*?<span class="st.+?>(.*?)<\/span><\//',$scraped,$result);
	
	/*
	Current google search format:
	<li class="g"><div [some shit]><h3 class="r"><a href="[url]" class=l [shit]>[title]</a></h3><div class="s"><div class="f kv"><cite>[domain name]</cite><span class="vshid"><a href="[cache url]" [some shit]>Cached</a>[similar results link. Useless]</span>[shit]</div>[more shit]<span class="st">[description]</span></div></div><!--n--></li><!--m-->
	*/
	if($nres==0)
	{
		$done=true; break;
	}
	for ($i=0;$i<$nres;$i++) 
	{
		$results['urls'][]=$result[1][$i];
		$results['title'][]=$result[2][$i];
		$results['desc'][]=$result[3][$i];
	}
	$num+=$nres;
	$size+=strlen($scraped);
	if($nres<10)	//Google doesn't have more results!
	{
		$done=true; break;
	}
}
$results['num']=$num;
$results['size']=$size/1024;
return $results;
}
?>