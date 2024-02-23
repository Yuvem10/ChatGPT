<?php /** @noinspection SqlResolve */
/** @noinspection PhpUnused */

/** @noinspection DuplicatedCode */

namespace APIFox\Models;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;
use DateTime;
use Exception;
use RFCore\Entities\E_API;
use RFCore\Models\M_API;
use RFCore\Models\RF_Model;

/**
 * Class M_Jorani
 * Model responsible for handling requests to the Jorani API
 * @package APIFox\Models
 */
class M_Jorani extends RF_Model
{
	private $baseURL;
	private $bearerToken;
	private $clientID;
	private $clientSecret;

	/** @var CURLRequest $curlClient */
	private $curlClient;

	// Indicate whether the client has been successfully initialized or not
	private $initDone = false;

	// Properties for the API and their constant equivalents
	const JORANI_API_REFERENCES = [
		'baseURL' 		=> 'API_JORANI_BASE_URL',
		'clientID' 		=> 'API_JORANI_CLIENT_ID',
		'clientSecret' 	=> 'API_JORANI_CLIENT_SECRET',
	];

	// Default return values
	private $ret = [
		'status' 	=> SC_INTERNAL_SERVER_ERROR,
		'reason' 	=> 'Une erreur interne est survenue lors de la communication avec l\'API de Jorani',
		'data' 		=> null
	];

	// Default options for the cURL client requests
	private $requestOptions = [
		'http_errors' => false, // Tells the cURL client to not throw exceptions on HTTP errors
	];

	/** @noinspection PhpUndefinedMethodInspection */
	public function __construct()
	{
		parent::__construct();

		try {
			$M_API = new M_API();

			// Retrieving the associated constants and their values from the database
			foreach (self::JORANI_API_REFERENCES as $property => $key) {
				/** @var E_API $config */
				$config = $M_API->findOneBy('key',$key);

				// Making sure that the key associated value is defined in the database
				if (!empty($config)) {
					$this->$property = $config->getValue();
				}
				else {
					throw new Exception("Missing API config: $key");
				}
			}

			// Appending the API endpoint to the base URL
			$this->baseURL .= 'api/';

			$this->curlClient = Services::curlrequest(['shareOptions' => false]);

			// Retrieving the bearer token
			$response = $this->curlClient->post($this->baseURL . 'token', [
				'form_params' => [
					'grant_type' 	=> 'client_credentials',
					'client_id' 	=> $this->clientID,
					'client_secret' => $this->clientSecret,
				],
			]);

			// Checking the response status code
			if ($response->getStatusCode() !== SC_SUCCESS) {
				throw new Exception('Error while retrieving the Jorani API bearer token: ' . $response->getReasonPhrase());
			}
			else{
				// Retrieving the bearer token
				$body 	= $response->getBody();
				$body 	= json_decode($body, true);
				$this->bearerToken = $body['access_token'] ?? null;

				// Making sure that the bearer token has been retrieved
				if(!empty($this->bearerToken))
				{
					// Adding the bearer token to the request header
					$this->curlClient->setHeader('Authorization', 'Bearer ' . $this->bearerToken);

					// Indicating that the client has been successfully initialized
					$this->initDone = true;
				}
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . ' : ' . $e);
		}
	}

	/**
	 * @throws Exception if the client has not been initialized
	 * @noinspection PhpUndefinedMethodInspection
	 */
	private function _checkInitialization()
	{
		$this->curlClient = Services::curlrequest(['shareOptions' => false]);

		// Adding the bearer token to the request header
		$this->curlClient->setHeader('Authorization', 'Bearer ' . $this->bearerToken);

		// Resetting the request options to their default values
		$this->requestOptions = [
			'http_errors' => false, // Tells the cURL client to not throw exceptions on HTTP errors
		];

		$this->_resetReturnValues();

		if (!$this->initDone) {
			$this->ret['status'] = SC_PRECONDITION_FAILED;
			$this->ret['reason'] = 'Erreur interne rencontrée durant l\'initialisation du client d\'accès à l\'API de Batify';
			throw new Exception('Batify API not initialized');
		}
	}

	/**
	 * Resets the return values to their default state
	 * @return void
	 */
	private function _resetReturnValues()
	{
		$this->ret['status'] 	= SC_INTERNAL_SERVER_ERROR;
		$this->ret['reason'] 	= 'Une erreur interne est survenue lors de la communication avec l\'API de Batify';
		$this->ret['data'] 		= null;
	}

	// =================================================================================================================
	// =================================================================================================================
	// CONTRACTS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve all the contracts
	 * @return array
	 */
	public function getContracts(): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'contracts', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] 	= 'Contrats récupérés avec succès';
				$this->ret['data'] 		= json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve a contract by its ID
	 * @param int $id The contract ID
	 * @return array
	 */
	public function getContract(int $id): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'contracts/' . $id, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] 	= 'Contrat récupéré avec succès';
				$this->ret['data'] 		= json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Generate the days off for a given contract
	 * @param $contractID string|int The contract ID
	 * @return array
	 */
	public function generateDaysOff($contractID): array
	{
		try {
			$this->_checkInitialization();

			$M_APIGouv 		= new M_APIGouv();
			$publicHolidays = $M_APIGouv->getPublicHolidays(date('Y'))['data'];

			// Iterating over the public holidays
			foreach ($publicHolidays as $date => $title)
			{
				$dt = DateTime::createFromFormat('Y-m-d', $date);

				$params = [
					'timestamp' 	=> $dt->getTimestamp(),
					'type' 			=> 1,
					'title'			=> $title,
				];

				$response = $this->curlClient->post($this->baseURL . 'contracts/' . $contractID .'/calendar/edit', [
					'form_params' => $params,
				]);
			}

			////////////////////////////////////////////////////////////////////////////////////////////////////////////

			// Adding the Sundays
			$params = [
				'start' 		=> date('Y') . '-01-01',
				'end' 			=> date('Y') . '-12-31',
				'title'			=> '',
				'type'			=> 1,
				'day' 			=> 'sunday'
			];

			$response = $this->curlClient->post($this->baseURL . 'contracts/' . $contractID .'/calendar/series', [
				'form_params' => $params,
			]);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] 	= 'Jours fériés et week-ends ajoutés avec succès';
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// ENTITLED DAYS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve all the entitled days for a given contract
	 * @param int $contractID The ID of the contract
	 * @return array
	 */
	public function getEntitledDaysByContract(int $contractID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/entitleddayscontract/' . $contractID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Crédits d\'absence récupérés avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Add or remove entitlement on a contract
	 * @param int $contractID The ID of the contract
	 * @param array $data The data to send to the API
	 * @return array
	 */
	public function addEntitledDaysByContract(int $contractID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['form_params'] = $data;

			$response = $this->curlClient->post($this->baseURL . '/addentitleddayscontract/' . $contractID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Crédits d\'absence modifiés avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve all the entitled days for a given employee
	 * @param int $employeeID The ID of the employee
	 * @return array
	 */
	public function getEntitledDaysByEmployee(int $employeeID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/entitleddaysemployee/' . $employeeID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Crédits d\'absence récupérés avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Give entitlement to an employee
	 * @param int $employeeID The ID of the employee
	 * @param array $data The data to send to the API
	 * @return array
	 */
	public function addEntitledDaysByEmployee(int $employeeID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['form_params'] = $data;

			$response = $this->curlClient->post($this->baseURL . '/addentitleddaysemployee/' . $employeeID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Crédits d\'absence ajoutés avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update the entitled days of an employee
	 * @param int $entitlementID The ID of the entitlement
	 * @param array $data The data to send to the API
	 * @return array
	 */
	public function updateEntitledDays(int $entitlementID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['form_params'] = $data;

			$response = $this->curlClient->post($this->baseURL . '/editentitleddaysemployee/' . $entitlementID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Crédits d\'absence mis à jour avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// LEAVES
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve the leaves summary for a given employee
	 * @param int $employeeID The ID of the employee
	 * @param string|null $refTmp Reference date (YYYY-MM-DD or Unix timestamp)
	 * @return array
	 */
	public function getLeavesSummaryByEmployee(int $employeeID, string $refTmp = null): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/leavessummary/' . $employeeID . (!empty($refTmp) ? ('/'.$refTmp):''), $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Résumé des congés récupéré avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Get all the leave requests stored into the database for the given period
	 * @param string $startDate Start date (YYYY-MM-DD or Unix timestamp)
	 * @param string $endDate End date (YYYY-MM-DD or Unix timestamp)
	 * @return array
	 */
	public function getLeavesByPeriod(string $startDate, string $endDate): array
	{
		try {
			$this->_checkInitialization();

			// The reverse order is required because the API seems to compute the period in the wrong way
			$response = $this->curlClient->get($this->baseURL . '/leaves/' . $endDate . '/' . $startDate, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Demandes récupérées avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Get all the leave requests stored into the database for the given employee
	 * @param int $employeeID The ID of the employee
	 * @return array
	 */
	public function getLeavesByEmployee(int $employeeID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/userleaves/' . $employeeID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Demandes récupérées avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Get all the overtime requests of an employee
	 * @param int $employeeID The ID of the employee
	 * @return array
	 */
	public function getOvertimeByEmployee(int $employeeID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/userextras/' . $employeeID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Demandes récupérées avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Get the monthly presence report for a given employee
	 * @param int $employeeID The ID of the employee
	 * @param string $month The month (MM)
	 * @param string $year The year (YYYY)
	 * @return array
	 */
	public function getPresenceReportByEmployee(int $employeeID, string $month, string $year): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/monthlypresence/' . $employeeID . '/' . $month . '/' . $year, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Présences récupérées avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Get all the leave types stored into the database
	 * @return array
	 */
	public function getLeaveTypes(): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/leavetypes', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Types de congés récupérés avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Accept a given leave request
	 * @param int $leaveID The ID of the leave request
	 * @return array
	 */
	public function acceptLeave(int $leaveID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/acceptleave/'.$leaveID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Demande acceptée avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Reject a given leave request
	 * @param int $leaveID The ID of the leave request
	 * @return array
	 */
	public function rejectLeave(int $leaveID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/rejectleave/'.$leaveID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Demande rejetée avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Cancel a given leave request
	 * @param int $leaveID The ID of the leave request
	 * @return array
	 */
	public function cancelLeave(int $leaveID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/cancelleave/'.$leaveID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Demande annulée avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Create a new leave request. Return the ID of the new leave request.
	 * @param array $data The data of the leave request
	 * @return array
	 */
	public function createLeave(array $data): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->post($this->baseURL . '/leaves', $this->requestOptions + ['form_params' => $data]);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Demande de congé créée avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Delete a given leave request
	 * @param int $leaveID The ID of the leave request
	 * @return array
	 */
	public function deleteLeave(int $leaveID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->delete($this->baseURL . '/deleteLeaveRequest/'.$leaveID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Demande de congé supprimée avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Validate a leave period
	 * Result varies according to input :
	 *  - difference between the entitled and the taken days
	 *  - try to calculate the duration of the leave
	 *  - try to detect overlapping leave requests
	 * If the user is linked to a contract, returns end date of the yearly leave period or NULL
	 * @param array $data The data of the leave request period
	 * Possible keys :
	 * - employee : the ID of the employee
	 * - type : the ID of the leave type
	 * - startdate : the start date of the leave period (format : YYYY-MM-DD)
	 * - enddate : the end date of the leave period (format : YYYY-MM-DD)
	 * - startdatetype : the type of the start date (Morning, Afternoon)
	 * - enddatetype : the type of the end date (Morning, Afternoon)
	 * - leave_id : the ID of the leave request (optional)
	 * @return array
	 */
	public function validateLeavePeriod(array $data): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->post($this->baseURL . '/leaves/validate', $this->requestOptions + ['form_params' => $data]);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Période de congé validée avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// USERS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Get all the positions stored into the database
	 * @return array
	 */
	public function getPositions(): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/positions', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Postes récupérés avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Get the department details of a given employee
	 * @param int $employeeID The ID of the employee
	 * @return array
	 */
	public function getEmployeeDepartment(int $employeeID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/userdepartment/' . $employeeID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Département récupéré avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Get the list of users
	 * @return array
	 */
	public function getUsers(): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/users', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Utilisateurs récupérés avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Get a specific user
	 * @param int $userID The ID of the user
	 * @return array
	 */
	public function getUser(int $userID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/users/' . $userID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Utilisateur récupéré avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Delete a user
	 * @param int $userID The ID of the user
	 * @return array
	 */
	public function deleteUser(int $userID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->delete($this->baseURL . '/users/' . $userID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Utilisateur supprimé avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update a user
	 * @param int $userID The ID of the user
	 * @param array $data The data to update
	 * @return array
	 */
	public function updateUser(int $userID, array $data): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->patch($this->baseURL . '/users/' . $userID, $this->requestOptions + ['form_params' => $data]);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Utilisateur mis à jour avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Create a new user
	 * @param array $data The data to create the user
	 * @param bool $sendEmail If true, email the user on creation
	 * @return array
	 */
	public function createUser(array $data, bool $sendEmail = false): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->post($this->baseURL . '/users/'.($sendEmail ? 'TRUE':'FALSE'), $this->requestOptions + ['form_params' => $data]);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Utilisateur créé avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Get the list of employees in a given entity
	 * @param int $entityID The ID of the entity
	 * @param bool $includeChildren If true, include the children entities
	 * @return array
	 */
	public function getListOfEmployeesInEntity(int $entityID, bool $includeChildren = true): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/getListOfEmployeesInEntity/' . $entityID . '/' . $includeChildren, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Employés récupérés avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// ORGANIZATION
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Get the tree of organizations
	 * @return array
	 */
	public function getOrganizationTree(): array
	{
		try {
			$this->_checkInitialization();

			// SQL query to execute
			$SQL = "
				SELECT *
				FROM organization
			";

			$response = $this->curlClient->post($this->baseURL . '/executeQuery', $this->requestOptions+['form_params' => ['query' => $SQL]]);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Arborescence de l\'organisation récupérée avec succès';
				$this->ret['data'] 	 = json_decode($response->getBody(), true);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}
}
