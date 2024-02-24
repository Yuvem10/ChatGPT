<?php

namespace APIFox\Models;

use Exception;
use RFCore\Models\RF_Model;

/**
 * Class M_APIGouv
 * @package APIFox\Models
 *
 * Model used to interact with the api.gouv.fr API
 */
class M_APIGouv extends RF_Model
{
	/**
	 * Make a call to the public holidays endpoint
	 * @param string $area Area to retrieve the public holidays for (alsace-moselle, guadeloupe, guyane, la-reunion, martinique, mayotte, metropole, nouvelle-caledonie, polynesie-francaise, saint-barthelemy, saint-martin, saint-pierre-et-miquelon, wallis-et-futuna)
	 * @param $year string|int|null Year to retrieve the public holidays for (if null, public holidays of the last 20 years and the next 5 years will be retrieved)
	 * @return array Array containing the status, the reason and the data (which is an array with the following structure: ['YYYY-MM-DD' => 'Public holiday name'])
	 * @see https://api.gouv.fr/documentation/jours-feries
	 */
	public function getPublicHolidays($year = null, string $area = 'metropole'): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => []];

		try {
			$curl = curl_init();

			$URL = "https://calendrier.api.gouv.fr/jours-feries/$area";

			// If a year is specified, we add it to the URL
			if (!empty($year))
			{
				$URL .= '/'.$year;
			}

			$URL .= '.json';

			// Setting the cURL options
			curl_setopt_array($curl, [
				CURLOPT_URL => $URL,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => [
					'cache-control: no-cache'
				],
			]);

			// Executing the cURL request
			$response 	= curl_exec($curl);

			// Getting the cURL error
			$err 		= curl_error($curl);

			curl_close($curl);

			if ($err) {
				$ret['reason'] = $err;
			} else {
				$response 		= json_decode($response, true);
				$ret['status'] 	= SC_SUCCESS;
				$ret['data'] 	= $response;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}
}
