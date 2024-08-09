<?php
global $amp_conf;
$html = '';
$version	 = get_framework_version();
$version = $version ? $version : getversion();
$version_tag = '?load_version=' . urlencode($version);
if ($amp_conf['FORCE_JS_CSS_IMG_DOWNLOAD']) {
  $this_time_append	= '.' . time();
  $version_tag 		.= $this_time_append;
} else {
	$this_time_append = '';
}

$baseUrl = isset($baseUrl) ? $baseUrl : "";

// Brandable logos in footer
//fpbx logo
/*$html .= '<div class="col-md-4">
	<a target="_blank" href="https://haloocom.com" >'
                . '<img id="footer_logo1" src="'.$baseUrl.'/admin/'.$amp_conf['BRAND_IMAGE_FREEPBX_FOOT']
                . '" alt="'.$amp_conf['BRAND_FREEPBX_ALT_FOOT'] .'"/>
	</a>
	</div>';*/

//text
$html .= '<div class="col-md-12" id="footer_text">';
$html .= sprintf(_('All Rights Reserved by Haloocom. Designed and Developed by '))
. br() . '<a href="https://haloocom.com" target="_blank"> Haloocom.</a>';
//$html .= sprintf(_('%s %s is licensed under the %s'),'FreePBX',$version,'<a href="http://www.gnu.org/copyleft/gpl.html" target="_blank"> GPL</a>') . br();
$html .= 'Copyright&copy;'.date('Y',time());

//module license
if (!empty($active_modules[$module_name]['license'])) {
  $html .= br() . sprintf(_('Current module licensed under %s'),
  trim($active_modules[$module_name]['license']));
}

//benchmarking
if (isset($amp_conf['DEVEL']) && $amp_conf['DEVEL']) {
	$benchmark_time = number_format(microtime_float() - $benchmark_starttime, 4);
	$html .= '<br><span id="benchmark_time">Page loaded in ' . $benchmark_time . 's</span>';
}
$html .= '</div>';

/*$html .= '<div class="col-md-4">
	<a target="_blank" href="' . $amp_conf['BRAND_IMAGE_SPONSOR_LINK_FOOT']
		. '" >'
		. '<img id="footer_logo" src="'.$baseUrl.'/admin/' . $amp_conf['BRAND_IMAGE_SPONSOR_FOOT'] . '" '
		. 'alt="' . $amp_conf['BRAND_SPONSOR_ALT_FOOT'] . '"/>
	</a>
	</div>';*/
echo $html;
?>
