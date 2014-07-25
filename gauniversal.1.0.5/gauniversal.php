<?php
/**
 *
 * @version		1.0.5
 * @package		GAUniversal
 * @subpackage  System.GaUniversal
 * @copyright	2014 Tools for Joomla, www.toolsforjoomla.com
 * @license		GNU GPL
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');


class plgSystemGaUniversal extends JPlugin {

	function onAfterRender() {
		$mainframe = &JFactory::getApplication();

		// don't run if we are in the admin or index.php or not an HTML view
		if($mainframe->isAdmin() || strpos($_SERVER["PHP_SELF"], "index.php") === false || JRequest::getVar('format','html') != 'html'){
			return;
			}
		
		// Get the Body of the HTML - have to do this twice to get the HTML
		$buffer = JResponse::getBody();
		$buffer = JResponse::getBody();
		// Get our Tracking ID and Domain Parameters
		$track_id = $this->params->get('track_id','');
		$domain = $this->params->get('domain','');
		
		

		
		// String containing the Universal Analytics JavaScript code including the tracking id and domain
		$ua_code = "<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', '".$track_id."', '".$domain."');";
		 
/*		// Track Registered Users?
		$track_users = $this->params->get('track_users',false);
		$user = JFactory::getUser();
		if (!$user->guest) {
			$ua_code .= "ga('set', 'dimension1', 'Registered User');";
		}
*/		
		// Track Demographics?
		$track_demographics = $this->params->get('demographics','off');
		if ($track_demographics == 'on') {
			$ua_code .= "ga('require', 'displayfeatures');";
		}

		$ua_code .= "ga('send', 'pageview');

		</script>
		";


		$buffer = preg_replace ("/<\/head>/", $ua_code."</head>", $buffer);
		
/*		// Add the PDF download code

		// Create a DOMdocument object and load the buffer in the HTML using loadHTML method
		$dom = new DOMDocument();
		@$dom->loadHTML($buffer);
		
		// Next we will get all the 'a' elements using getElementsByTagName and store it in an array
		$items = $dom->getElementsByTagName('a');
		
		// Loop through each 'a' element and look for pdf downloads
		foreach ($items as $item) {
			$doc_name = $item->getAttribute('href');
			if (strpos ( $doc_name , '.pdf')) {
				// $doc_name = 'pdf/' . $doc_name;
				$item->setAttribute('onclick', "ga('send','pageview','".$doc_name."');");
				}
		}
		$buffer = $dom->saveHTML();
*/
		
		JResponse::setBody($buffer);
		
		return true;
		}
	}