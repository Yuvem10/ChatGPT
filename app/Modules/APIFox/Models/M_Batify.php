<?php /** @noinspection DuplicatedCode */

namespace APIFox\Models;

use CodeIgniter\Files\File;
use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Services;
use CURLFile;
use Exception;
use RFCore\Entities\E_API;
use RFCore\Models\M_API;
use RFCore\Models\RF_Model;

/**
 * Class M_Batify
 * Model responsible for handling requests to the Batify Open API
 * @package APIFox\Models
 */
class M_Batify extends RF_Model
{
	private $baseURL;
	private $bearerToken;

	private $APIEnabled;

	/** @var CURLRequest $curlClient */
	private $curlClient;

	// Indicate whether the client has been successfully initialized or not
	private $initDone = false;

	// Properties for the API and their constant equivalents
	const BATIFY_CONSTANTS = [
		'APIEnabled' 	=> 'API_BATIFY_ENABLED',
		'baseURL' 		=> 'API_BATIFY_BASE_URL',
		'bearerToken' 	=> 'API_BATIFY_BEARER_TOKEN',
	];

	// Default return values
	private $ret = [
		'status' 	=> SC_INTERNAL_SERVER_ERROR,
		'reason' 	=> 'Une erreur interne est survenue lors de la communication avec l\'API de Batify',
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
			foreach (self::BATIFY_CONSTANTS as $property => $constant) {
				// Making sure that the constant is defined
				if (!defined($constant)) {
					throw new Exception("Missing constant: $constant");
				}

				/** @var E_API $config */
				$config = $M_API->findOneBy('key',constant($constant));

				// Making sure that the constant associated value is defined in the database
				if (!empty($config)) {
					$this->$property = $config->getValue();
				}
				else {
					throw new Exception("Missing config: $constant");
				}
			}

			// Making sure that the API is enabled
			if ($this->APIEnabled)
			{
				$this->curlClient = Services::curlrequest(['shareOptions' => false]);

				// Adding the bearer token to the request header
				$this->curlClient->setHeader('Authorization', 'Bearer ' . $this->bearerToken);

				$this->initDone = true;
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
	// SYSTEM
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Test the connection to the Batify API with the current bearer token
	 * @return array
	 */
	public function testConnectivity(): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'testConnectivity', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// COMPANIES (EMPLOYEES)
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve all the employees of a given company
	 * @return array
	 */
	public function getEmployees(): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'companies/current/users', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve the details of a given employee
	 * @param string $employeeID
	 * @return array
	 */
	public function getEmployee(string $employeeID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'companies/current/users/' . $employeeID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update the roles of a given employee
	 * @param string $employeeID The ID of the employee to update
	 * @param int $roles The new roles of the employee
	 * @return array
	 */
	public function updateEmployeeRoles(string $employeeID, int $roles): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode(['roles' => $roles])];

			$response = $this->curlClient->post($this->baseURL . 'companies/current/users/' . $employeeID.'/roles', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update the account activation status of a given employee
	 * @param string $employeeID The ID of the employee to update
	 * @param bool $active The new activation status of the employee
	 * @return array
	 */
	public function updateEmployeeActivation(string $employeeID, bool $active): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode(['status' => ($active ? 1 : 0)])];

			$response = $this->curlClient->post($this->baseURL . 'companies/current/users/' . $employeeID.'/activation', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Add a new employee to the company
	 * @param array $data The data of the new employee
	 * @return array
	 */
	public function addEmployee(array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'companies/current/users', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Remove an employee from the company
	 * @param string $employeeID The ID of the employee to update
	 * @return array
	 */
	public function removeEmployee(string $employeeID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->delete($this->baseURL . 'companies/current/users/' . $employeeID, $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$this->ret['reason'] = $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// WORKSITES
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve all the worksites associated with the current bearer token
	 * @return array
	 * @see https://app.swaggerhub.com/apis/Ingefox-projet/Batify_Open_API/1.0.0#/worksites/Get%20current%20company's%20worksites
	 */
	public function getWorksites(): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve a specific worksite associated with the current bearer token
	 * @param string $id
	 * @return array
	 * https://app.swaggerhub.com/apis/Ingefox-projet/Batify_Open_API/1.0.0#/worksites/Get%20current%20company's%20worksite
	 */
	public function getWorksite(string $id): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/' . $id, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Create a new worksite
	 * @param array $data The data to be sent to the API
	 * @return array The response from the API ('data' returns the ID of the newly created worksite)
	 * @see https://app.swaggerhub.com/apis/Ingefox-projet/Batify_Open_API/1.0.0#/worksites/Add%20worksite
	 */
	public function addWorksite(array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update an existing worksite
	 * @param string $id The ID of the worksite to update
	 * @param array $data The data to be sent to the API
	 * @return array
	 * @see https://app.swaggerhub.com/apis/Ingefox-projet/Batify_Open_API/1.0.0#/worksites/Update%20worksite
	 */
	public function updateWorksite(string $id, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/' . $id, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Delete an existing worksite
	 * @param string $id The ID of the worksite to delete
	 * @return array
	 * @see https://app.swaggerhub.com/apis/Ingefox-projet/Batify_Open_API/1.0.0#/worksites/Delete%20worksite
	 */
	public function deleteWorksite(string $id): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->delete($this->baseURL . 'worksites/' . $id, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// SUBSCRIBERS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve the list of subscribers associated with a given worksite
	 * @param string $worksiteID The ID of the worksite
	 * @return array
	 */
	public function getWorksiteSubscribers(string $worksiteID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/' . $worksiteID . '/subscribers', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve a specific subscriber from a given worksite
	 * @param string $worksiteID The ID of the worksite
	 * @param string $subscriberID The ID of the subscriber
	 * @return array
	 */
	public function getWorksiteSubscriber(string $worksiteID, string $subscriberID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/' . $worksiteID . '/subscribers/' . $subscriberID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Add a new subscriber to a given worksite
	 * @param string $worksiteID The ID of the worksite
	 * @param array $data The data to be sent to the API
	 * @return array
	 */
	public function addWorksiteSubscriber(string $worksiteID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/' . $worksiteID . '/subscribers', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update an existing subscriber from a given worksite
	 * @param string $worksiteID The ID of the worksite
	 * @param string $subscriberID The ID of the subscriber
	 * @param array $data The data to be sent to the API
	 * @return array
	 */
	public function updateWorksiteSubscriber(string $worksiteID, string $subscriberID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/' . $worksiteID . '/subscribers/' . $subscriberID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Remove an existing subscriber from a given worksite
	 * @param string $worksiteID The ID of the worksite
	 * @param string $subscriberID The ID of the subscriber
	 * @return array
	 */
	public function removeWorksiteSubscriber(string $worksiteID, string $subscriberID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->delete($this->baseURL . 'worksites/' . $worksiteID . '/subscribers/' . $subscriberID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// FOLDERS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve data about a given folder
	 * @param string $worksiteID The ID of the worksite associated with the folder
	 * @param string $folderID The ID of the folder to retrieve
	 * @return array
	 */
	public function getFolder(string $worksiteID, string $folderID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/'.$worksiteID.'/folders/' . $folderID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve the contents of a given folder
	 * @param string $worksiteID The ID of the worksite associated with the folder
	 * @param string $folderID The ID of the folder to retrieve the content from (leave empty to retrieve the root folder)
	 * @return array
	 */
	public function getFolderContent(string $worksiteID, string $folderID = 'root'): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/'.$worksiteID.'/folders/' . $folderID . '/documents', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Create a new folder
	 * @param string $worksiteID The ID of the worksite associated with the folder
	 * @param array $data The data to be sent to the API
	 * @return array
	 */
	public function createFolder(string $worksiteID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/'.$worksiteID.'/folders', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update a given folder
	 * @param string $worksiteID The ID of the worksite associated with the document
	 * @param string $folderID The ID of the folder to update
	 * @param array $data The data to be sent to the API
	 * @return array
	 */
	public function updateFolder(string $worksiteID, string $folderID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/'.$worksiteID.'/folders/' . $folderID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Delete a given folder
	 * @param string $worksiteID The ID of the worksite associated with the document
	 * @param string $folderID The ID of the folder to delete
	 * @return array
	 */
	public function deleteFolder(string $worksiteID, string $folderID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->delete($this->baseURL . 'worksites/'.$worksiteID.'/folders/' . $folderID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// DOCUMENTS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve data about a given document
	 * @param string $worksiteID The ID of the worksite associated with the document
	 * @param string $documentID The ID of the document to retrieve
	 * @return array
	 */
	public function getDocument(string $worksiteID, string $documentID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/'.$worksiteID.'/documents/' . $documentID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve the content of a given document
	 * @param string $worksiteID The ID of the worksite associated with the document
	 * @param string $documentID The ID of the document to retrieve
	 * @return array If the document is found, the content is returned in the 'data' key : ['filename' => '...', 'content' => '...']
	 */
	public function downloadDocument(string $worksiteID, string $documentID): array
	{
		try {
			$this->_checkInitialization();

			$this->ret = $this->getDocument($worksiteID, $documentID);

			// If the request indicates a temporary redirect, we need to follow it to get the actual file
			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = [
					'filename' 	=> $this->ret['data']['name'],
					'content' 	=> file_get_contents(str_replace(' ', '%20', $this->ret['data']['file']))
				];
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Create a new document in a given folder ('parentFolder' key, if not provided, will default to the root folder)
	 * @param string $worksiteID The ID of the worksite associated with the document
	 * @param array $data The data to be sent to the API
	 * @param File|UploadedFile $file The file to be uploaded
	 * @return array
	 */
	public function addDocument(string $worksiteID, array $data, $file): array
	{
		try {
			$this->_checkInitialization();

			// Instantiating a new CURLFile object based on the provided file
			$CURLFile = new CURLFile(
				$file->getRealPath(),
				$file->getMimeType(),
				($file instanceof UploadedFile) ? $file->getClientName():$file->getFilename()
			);

			// Adding the file and the form data to the request options
			$this->requestOptions['multipart'] = [
				'data' => json_encode($data),
				'file' => $CURLFile
			];

			$response = $this->curlClient->post($this->baseURL . 'worksites/'.$worksiteID.'/documents', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update a given document
	 * @param string $worksiteID The ID of the worksite associated with the document
	 * @param string $documentID The ID of the document to update
	 * @param array $data The data to be sent to the API
	 * @return array
	 */
	public function updateDocument(string $worksiteID, string $documentID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/'.$worksiteID.'/documents/' . $documentID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Delete a given document
	 * @param string $worksiteID The ID of the worksite associated with the document
	 * @param string $documentID The ID of the document to delete
	 * @return array
	 */
	public function deleteDocument(string $worksiteID, string $documentID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->delete($this->baseURL . 'worksites/'.$worksiteID.'/documents/' . $documentID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// TASKS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve the list of tasks lists for a given worksite
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @return array
	 */
	public function getTasksLists(string $worksiteID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve a given tasks list
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @param string $tasksListID The ID of the tasks list to retrieve
	 * @return array
	 */
	public function getTasksList(string $worksiteID, string $tasksListID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists/' . $tasksListID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Create a new tasks list
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @param array $data The data to be sent to the API
	 * @return array
	 */
	public function createTasksList(string $worksiteID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update a given tasks list
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @param string $tasksListID The ID of the tasks list to update
	 * @param array $data The data to be sent to the API
	 * @return array
	 */
	public function updateTasksList(string $worksiteID, string $tasksListID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists/' . $tasksListID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Delete a given tasks list
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @param string $tasksListID The ID of the tasks list to delete
	 * @return array
	 */
	public function deleteTasksList(string $worksiteID, string $tasksListID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->delete($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists/' . $tasksListID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve the tasks of a given tasks list
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @param string $tasksListID The ID of the tasks list to retrieve the tasks from
	 * @return array
	 */
	public function getTasks(string $worksiteID, string $tasksListID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists/' . $tasksListID . '/tasks', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieve a specific task of a given tasks list
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @param string $tasksListID The ID of the tasks list to create the task in
	 * @param string $taskID The ID of the task to retrieve
	 * @return array
	 */
	public function getTask(string $worksiteID, string $tasksListID, string $taskID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists/' . $tasksListID . '/tasks/' . $taskID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Create a new task
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @param string $tasksListID The ID of the tasks list to create the task in
	 * @param array $data The data to be sent to the API
	 * @return array
	 */
	public function createTask(string $worksiteID, string $tasksListID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists/' . $tasksListID . '/tasks', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Update a given task
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @param string $tasksListID The ID of the tasks list to update the task in
	 * @param string $taskID The ID of the task to update
	 * @param array $data The data to be sent to the API
	 * @return array
	 */
	public function updateTask(string $worksiteID, string $tasksListID, string $taskID, array $data): array
	{
		try {
			$this->_checkInitialization();

			// Adding the form data to the request options
			$this->requestOptions['multipart'] = ['data' => json_encode($data)];

			$response = $this->curlClient->post($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists/' . $tasksListID . '/tasks/' . $taskID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Delete a given task
	 * @param string $worksiteID The ID of the worksite associated with the tasks
	 * @param string $tasksListID The ID of the tasks list to delete the task from
	 * @param string $taskID The ID of the task to delete
	 * @return array
	 */
	public function deleteTask(string $worksiteID, string $tasksListID, string $taskID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->delete($this->baseURL . 'worksites/'.$worksiteID.'/tasksLists/' . $tasksListID . '/tasks/' . $taskID, $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = json_decode($response->getBody(), true)['data'] ?? null;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}


	// =================================================================================================================
	// =================================================================================================================
	// POSTS
	// =================================================================================================================
	// =================================================================================================================
	/**
	 * Retrieve the list of posts associated with a given worksite
	 * @param string $worksiteID The ID of the worksite
	 * @return array
	 */
	public function getPosts(string $worksiteID): array
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . 'worksites/'.$worksiteID.'/posts', $this->requestOptions);

			$this->ret['status'] 	= $response->getStatusCode();
			$this->ret['reason'] 	= $response->getReasonPhrase();

			if ($this->ret['status'] === SC_SUCCESS) {
				$posts = json_decode($response->getBody(), true)['data'] ?? null;
				$this->ret['data'] = $posts;
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}
		return $this->ret;
	}


}
