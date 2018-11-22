<?php
namespace Timezone\Middleware;

/*
 * GeoPlugin class to use www.geoplugin.com API.
 *
 * It allows to get client's time from Ip adress.
 * 
 */
class GeoPlugin
{	
	/**
	 * Get client timezone.
	 * 
	 * @return string|null
	 */
	public function getClientTimezone()
	{
		$response = $this->_fetch();

		$clientTimezone = null;
		if (isset($response['geoplugin_timezone'])) {
			$clientTimezone = $response['geoplugin_timezone'];
		}

		return $clientTimezone;
	}
	
	/**
	 * _fetch method.
	 * 
	 * It calls geoPlugin API and return a set of data using client Ip adress.
	 * 
	 * @return array|null
	 * [
	   		'geoplugin_request' => '86.204.205.84',
	   		'geoplugin_status' => (int) 206,
	   		'geoplugin_delay' => '2ms',
	        'geoplugin_credit' => 'Some of t ...',
	   		'geoplugin_city' => '',
	   		'geoplugin_region' => '',
	   		'geoplugin_regionCode' => '',
	   		'geoplugin_regionName' => '',
	   		'geoplugin_areaCode' => '',
	   		'geoplugin_dmaCode' => '',
	   		'geoplugin_countryCode' => 'FR',
	   		'geoplugin_countryName' => 'France',
	   		'geoplugin_inEU' => (int) 1,
	   		'geoplugin_euVATrate' => (int) 20,
	   		'geoplugin_continentCode' => 'EU',
	   		'geoplugin_continentName' => 'Europe',
	   		'geoplugin_latitude' => '48.8582',
	   		'geoplugin_longitude' => '2.3387',
	   		'geoplugin_locationAccuracyRadius' => '500',
	   		'geoplugin_timezone' => 'Europe/Paris',
	   		'geoplugin_currencyCode' => 'EUR',
	   		'geoplugin_currencySymbol' => '&#8364;',
	   		'geoplugin_currencySymbol_UTF8' => '€',
	   		'geoplugin_currencyConverter' => '0.882'
	 * ]
	 */
	private function _fetch()
	{
		$response = null;

		$ip = file_get_contents('http://www.geoplugin.com/ip.php');
		$host = 'http://www.geoplugin.net/php.gp?ip=' . $ip;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $host);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.1');
		$response = curl_exec($ch);
		curl_close($ch);

		if ($response) {
			$response = unserialize($response);			
		}

		return $response;
	}
}