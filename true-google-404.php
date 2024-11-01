<?php
/*
Plugin Name: true Google 404
Plugin URI: http://wordpress.org/extend/plugins/trueGoogle404
Description: Don't let 404 errors be an exit sign for your blog readers, help them out. This plugin uses trueGoogleSearch API by TheTechnofreak.com to suggest a list of possible URLs they might want to visit since the one they requested can't be found. Configure it at <a href="options-general.php?page=trueGoogle404.php">Settings &rarr; true Google 404</a>.
Version: 1.0.1
Author: Yash Gupta <technofreak777@gmail.com>
Author URI: http://thetechnofreak.com
*/
/*
Original plugin Site: http://thetechnofreak.com/technofreak/wordpress-plugin-true-google-404

						Copyright (C) 2012 - yash gupta
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require("trueGS.php");
/**
 * Register the admin pages and actions
 */
if ( is_admin() ) {
    // admin actions
    add_action( 'admin_menu', 'trueGoogle404_setup_admin' );
    add_action( 'admin_menu', 'trueGoogle404_admin_add_page' );
}
/**
 * include the standard template.
 *
 * If the user has opted to use the included template then include it for use.
 */
function trueGoogle404_use_included_template_hook() 
{
    include dirname( __FILE__ ). '/default-404.php';
    exit;
}
function trueGoogle404_showlink()
{
	echo 'I use <a href="http://thetechnofreak.com">true Google 404</a>';
}
/**
 * Register the 404 hook.
 * Only register the 404 hook if the user wants to use our included template.
 */
if(trueGoogle404_get_option( 'template' )){
    add_action( '404_template','trueGoogle404_use_included_template_hook' );
}
if(trueGoogle404_get_option('thank')=="yes")
{
	add_action('wp_footer','trueGoogle404_showlink');
}
/**
 * perform the actual search
 * 
 * This is the heart of the plugin. This function is called from the 404.php
 * template. No parameters are necessary. It returns the properly formatted
 * HTML to either display a list of potential links.
 *
 * @return string html to output.
 */
function trueGoogle404() 
{
	$query=trueGoogle404_get_option('prefix');
	if ( $localsite = trueGoogle404_get_option( 'site' ))
        $query.=" site:".$localsite." ";      
	$requested=str_replace( '/', ' ', html_entity_decode( urldecode( $_SERVER['REQUEST_URI'] ) ) );
	$query.=$requested;
	$results=trueGS($query);
	$n=trueGoogle404_get_option('count');
	$n=$results["num"]<$n ?$results["num"]:$n;
	if($n<=0)
	{
	$output= "<p>Sorry, but we could not relate '<b>$requested</b>' to anything on our site. Have a look at the categories to find what you might be looking for</p>";
	}
    $output = "<p>Showing links related to: '<b>$requested</b>'".'<ol class="trueGoogle404">';
	for($i=0;$i<$n;$i++)
		$output .= sprintf( '<li><a href="%s">%s</a> - %s</li>', $results['urls'][$i],strip_tags($results['title'][$i]),strip_tags($results['desc'][$i],"<b><em><i>"));
    $output .= '</ol></p>';
    return $output;
}
/**
 * register the options page witht he admin menu
 */
function trueGoogle404_admin_add_page() {
    $settingsPage = add_options_page( 'true Google 404 Options Page', 'true Google 404', 'manage_options', 'trueGoogle404', 'trueGoogle404_options' );
}
$trueGoogle404_optDetails=array();
$trueGoogle404_optDetails['prefix']=array("name"=>"<b>Search Query Prefix</b>","def"=>" ","field"=>"tinytext");
$trueGoogle404_optDetails['site']=array("name"=>"<b>Search Specific Site</b><br />This is the domain that the search will be limited to. (We highly recommended that you use your domain)","def"=>get_bloginfo('url'),"field"=>"tinytext");
$trueGoogle404_optDetails['count']=array("name"=>"Number of results to display (<=10)","def"=>"5","field"=>"tinytext");
$trueGoogle404_optDetails['thank']=array("name"=>'Thank <a href="http://TheTechnofreak.com">The Technofreak</a> for this free plugin by displaying a link to his website.
',"def"=>"no","field"=>"yesno");
$trueGoogle404_optDetails['template']=array("name"=>'<b>Use the 404 template included with the plugin.</b><br />(If you uncheck this, you need to make sure that your template has a 404 template and that you have properly modified it. Add the following code where you want to display the search results:<br /><pre>&lt;?php if(function_exists("trueGoogle404"))echo trueGoogle404();?&gt;</pre>',"def"=>"yes","field"=>"yesno");
function trueGoogle404_load_default(&$options,$what)
{
	global $trueGoogle404_optDetails;
	if($what!="all")
	{
		$options[$what]=$trueGoogle404_optDetails[$what]["def"];
	}
	else
	{
		$options=array();		
		if(is_array($trueGoogle404_optDetails))foreach($trueGoogle404_optDetails as $key=>$value)
		{
			$options[$key]=$value["def"];
		}
	}
}
function trueGoogle404_get_option($optname)
{
	$options = get_option('trueGoogle404_options'); // get the options from the database
	if(!$options){//if the options don't exist yet. Plugin just installed.
        	trueGoogle404_load_default($options,'all'); 
        	add_option("trueGoogle404_options", $options); // add our options to the database
	}
	if(!isset($options[$optname]))
		trueGoogle404_load_default($options,$optname);
	return isset($options[$optname])?$options[$optname]:"";
}
/**
 * register the variables to be edited on the options page
 */
function trueGoogle404_setup_admin() {
	$options=array();
	trueGoogle404_load_default($options,"all");
	add_option('trueGoogle404_options',$options);
	return;
}
/**
 * Outputs the HTML for the plugin options page.
 */
function trueGoogle404_options() 
{
	global $trueGoogle404_optDetails;
	$options = get_option('trueGoogle404_options'); // get the options from the database
	if(!$options) // if the options don't exist yet.
	{
    	trueGoogle404_load_default($options,"all");
		add_option('trueGoogle404_options',$options); // add our options to the database
    }
	$message=""; 
    if(isset($_POST['submit']))
	{ 
		trueGoogle404_load_default($options,'all');
		foreach($_POST as $key=>$value)
		{
			if(isset($options[$key])&&$_POST[$key]!="")
			{
				$options[$key] = stripslashes($_POST[$key]);
			}
		}
		foreach($options as $key=>$value)
		{
			if($options[$key]=="")
			{
				trueGoogle404_load_default($options,$key);
			}
		}
        update_option('trueGoogle404_options',$options); // update the database 
        $url = get_bloginfo('url'); // get the blog url from the database
        $message = "<div id='message' class='updated below-h2'>Settings saved. <a href='$url'>Visit your site</a> to see how it looks.</div>"; // some HTML to show that the options were updated
    }
	?>
<style type="text/css">
#trueGoogle404 p{ border:dashed #396d85 1px; background:#E8F7FF; display:inline-block; padding:10px;}
#trueGoogle404 tr{ border:dashed #396d85 1px; }
#trueGoogle404 td{ vertical-align:top; border-bottom:inset #396d85 1px; margin:0; padding: 5px; }
#trueGoogle404 form{ }
</style>
<div id="trueGoogle404"><h2>true Google 404 | options</h2>
<p>These are the main settings for the true Google 404 Error page enhancer plugin</p>
<?php echo $message; ?>
<form method="post">
<table cellpadding="0" cellspacing="0" width="600px">
<tr><td width="350px"></td><td width="250px"> </td></tr>
<?php foreach($options as $key=>$value){?>
<tr><td valign='top' scope='row'><label for='<?php echo $key ?>'><?php echo $trueGoogle404_optDetails[$key]["name"] ?></label></td><td>
<?php 
if($trueGoogle404_optDetails[$key]["field"]=="tinytext"):
	?><input id='<?php echo $key ?>' name='<?php echo $key ?>' value="<?php echo $value ?>" type="text" size="40" /><?php
elseif($trueGoogle404_optDetails[$key]["field"]=="yesno"):
	
	?><input name='<?php echo $key ?>' value="yes" type="radio" <?php echo $value=="yes"?"checked":""; ?> />Yes<br /><input name='<?php echo $key ?>' value="no" type="radio" <?php echo $value=="no"?"checked":""; ?> />No<?php
endif;
?>
</td></tr>
<?php } ?>
<tr><td colspan="2"><input name="submit" type="submit" value="Save Changes"/></td></tr>
</table>
*leaving a field empty will set the default value for that option
</form></div>
<?php
    return;
}
?>
