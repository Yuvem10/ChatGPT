<?php
/** @noinspection DuplicatedCode */
/** @noinspection PhpUnused */

namespace RFCore\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use DateTime;
use Exception;
use RFCore\Models\RF_Model;

class M_Mantis extends RF_Model
{
	// API URL
	private $baseURL;

	// Token management
	private $token;

	// cURL handle
	private $ch;

	// Header options array
	private $headers;

	public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
	{
		parent::__construct($db, $validation);

		// Retrieving a valid base URL that will be used by the client
		$this->baseURL = defined('INT_MANTIS_BASE_URL') ? constant('INT_MANTIS_BASE_URL') : MANTIS_BASE_URL;
		$this->token = defined('INT_MANTIS_TOKEN') ? constant('INT_MANTIS_TOKEN') : MANTIS_TOKEN;
	}

	// =================================================================================================================
	// CONTRACTS
	// =================================================================================================================

    /**
     * Retrieve the list of issues in a project
     * @param $projectId
     * @return array
     */
	public function getProjectIssues($projectId): array
	{
        $response = $this->_curlRequest('issues/?project_id='.$projectId);

		return $this->_handleResponse($response);
	}

    /**
     * Create a new issue
     * @param $data
     * @return array
     */
    public function postIssue($data): array
    {
        $ret = [
			'status' 	=> SC_INTERNAL_SERVER_ERROR,
			'reason' 	=> 'Une erreur interne est survenue. Merci de réessayer ultérieurement',
		];

		try {
			$response = $this->_curlRequest('issues', 'POST', json_encode($data));
			$ret = $this->_handleResponse($response);
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . ' : ' . $e);
		}

        return $ret;
    }

    /**
     * Retrieves the details of a project
     * @param $projectId
     * @return array
     */
    public function getProject($projectId): array
    {
        $response = $this->_curlRequest('projects/'.$projectId);

        return $this->_handleResponse($response);
    }

    /**
     * @param string $endpoint no leading slash
     * @param string $method GET/POST
     * @param string $postData data to post as JSON string
     * @return bool|string
     */
    private function _curlRequest(string $endpoint, string $method = 'GET', string $postData = ''){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseURL.'/'.$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.$this->token,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => $postData,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    /**
     * Handles the errors and JSON operations on curl return
     * @param $response mixed the response from a curl request
     * @param $returnAsJson bool set to true if we want to keep the return as a json string
     * @return array
     */
    private function _handleResponse($response, bool $returnAsJson = false): array
    {
        $ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'data' => null];

        if ($response !== false){
            $ret = ['status' => SC_SUCCESS, 'data' => ($returnAsJson)?$response:json_decode($response)];
        }

        return $ret;
    }

	/**
	 * Return the data needed for generating the Mantis summary file
	 * @return array[]
	 */
    public function generateSummary(): array
	{
		$ret = [];

		try {
			$projects = [];
			$headers = ['Projet'];

			$filterIndex = 0;

			foreach (MANTIS_FILTERS as $FILTER => $LABEL)
			{
				$headers[] = $LABEL;

				// Retrieving issues according to the filter
				$res = $this->_curlRequest('issues?filter_id='.$FILTER);
				$res = $this->_handleResponse($res, true);
				$res = json_decode($res['data'], true)['issues'];

				// Adding the issues to the projects array
				foreach ($res as $issue)
				{
					// Retrieving the project name
					$projectName = $issue['project']['name'];

					// If the project is not in the array, we add it with the counters
					if (!key_exists($projectName,$projects))
					{
						$counters = [];

						// Adding the counters for each filter
						foreach (MANTIS_FILTERS as $ignored)
						{
							$counters[] = 0;
						}

						$projects[$projectName] = $counters;
					}

					$projects[$projectName][$filterIndex]++;
				}

				$filterIndex++;
			}

			// Adding the total column
			$headers[] = 'Total';

			// Counting the total number of issues for each project
			foreach ($projects as $project => $values)
			{
				$total = 0;

				foreach ($values as $value)
				{
					$total += $value;
				}

				$projects[$project][] = $total;
			}

			// Formatting the data for the CSV file
			foreach ($projects as $project => $values)
			{
				$line = [$project];

				foreach ($values as $value)
				{
					$line[] = $value;
				}

				$ret[] = $line;
			}

			// Sorting the projects by the total number of issues
			usort($ret, function($a, $b) {
				return $b[count($b)-1] <=> $a[count($a)-1];
			});

			// Prepending the CSV header
			array_unshift($ret, $headers);

			// Generating the CSV file name
			$CSVName = 'RAPPORT_SUPPORT_'.date('Y-m-d').'.csv';

			// Creating the Mantis folder if it doesn't exist
			if (!is_dir(MANTIS_FOLDER))
			{
				mkdir(MANTIS_FOLDER);
			}

			// Removing the file if it already exists
			if (file_exists(MANTIS_FOLDER.$CSVName))
			{
				unlink(MANTIS_FOLDER.$CSVName);
			}

			// Opening the CSV file for writing
			$CSVFile = fopen(MANTIS_FOLDER.$CSVName, 'w');

			// Fixing the UTF-8 BOM issue
			fprintf($CSVFile, chr(0xEF).chr(0xBB).chr(0xBF));

			// Appending the CSV data
			foreach ($ret as $line)
			{
				fputcsv($CSVFile, $line, ';');
			}

			// Closing the CSV file
			fclose($CSVFile);
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . ' : ' . $e);
		}

		return $ret;
    }
}
