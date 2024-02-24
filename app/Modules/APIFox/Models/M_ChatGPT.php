<?php

namespace APIFox\Models;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;
use CURLFile;
use Exception;
use RFCore\Entities\E_API;
use RFCore\Models\M_API;
use RFCore\Models\RF_Model;

class M_ChatGPT extends RF_Model
{
	private $baseURL = 'https://api.openai.com';
	private $bearerToken;

	private $version;

	private $APIEnabled;

	private $model;

	/** @var CURLRequest $curlClient */
	private $curlClient;

	// Indicate whether the client has been successfully initialized or not
	private $initDone = false;

	const CHAT_GPT_SESSIONS_FOLDER = ROOTPATH.DIRECTORY_SEPARATOR.'writable'.DIRECTORY_SEPARATOR.'session'.DIRECTORY_SEPARATOR.'ChatGPT'.DIRECTORY_SEPARATOR;

	// Properties for the API and their constant equivalents
	const CHATGPT_CONSTANTS = [
		'APIEnabled' 	=> 'API_CHATGPT_ENABLED',
		'bearerToken' 	=> 'API_CHATGPT_BEARER_TOKEN',
		'version' 		=> 'API_CHATGPT_VERSION',
		'model' 		=> 'API_CHATGPT_MODEL',
	];

	const JSON_FORMAT = [
		[
			'role' 		=> 'system',
			'content' 	=> 'Tu réponds uniquement au format JSON et en francais'
		]
	];

	const CHAT_GPT_BASE = [
		[
			'role' 		=> 'system',
			'content' 	=> 'Tu réponds uniquement en français'
		],
		[
			'role' 		=> 'assistant',
			'content' 	=> 'Bonjour ! Comment puis-je vous aider ?'
		]
	];

	// Default return values
	private $ret = [
		'status' 	=> SC_INTERNAL_SERVER_ERROR,
		'reason' 	=> 'Une erreur interne est survenue lors de la communication avec l\'API de ChatGPT',
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
			foreach (self::CHATGPT_CONSTANTS as $property => $constant) {
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

		$this->curlClient->setHeader('Content-Type', 'application/json');

		// Resetting the request options to their default values
		$this->requestOptions = [
			'http_errors' => false, // Tells the cURL client to not throw exceptions on HTTP errors
		];

		$this->_resetReturnValues();

		if (!$this->initDone) {
			$this->ret['status'] = SC_PRECONDITION_FAILED;
			$this->ret['reason'] = 'Erreur interne rencontrée durant l\'initialisation du client d\'accès à l\'API de ChatGPT';
			throw new Exception('ChatGPT API not initialized');
		}
	}

	/**
	 * Resets the return values to their default state
	 * @return void
	 */
	private function _resetReturnValues()
	{
		$this->ret['status'] 	= SC_INTERNAL_SERVER_ERROR;
		$this->ret['reason'] 	= 'Une erreur interne est survenue lors de la communication avec l\'API de ChatGPT';
		$this->ret['data'] 		= null;
	}

	// =================================================================================================================
	// =================================================================================================================
	// MODELS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Lists the currently available models, and provides basic information about each one such as the owner and availability
	 * @see https://platform.openai.com/docs/api-reference/models/list For request format
	 * @return array
	 */
	public function listModels()
	{
		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/v'.$this->version.'/models', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$body = json_decode($response->getBody(), true);

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'La liste des modèles a été correctement récupérée';
				$this->ret['data'] = $body ?? null;
			}
			else{
				$this->ret['reason'] = $body['error']['message'] ?? 'Une erreur interne est survenue lors de la communication avec l\'API de ChatGPT';
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}


	// =================================================================================================================
	// =================================================================================================================
	// TEXT GENERATION
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Generates a chat completion for the provided prompt
	 * @see https://platform.openai.com/docs/api-reference/chat/create For request format
	 * @see https://platform.openai.com/docs/api-reference/chat/streaming For response format
	 * @param array $data The data to be used for the chat completion
	 * @param bool $jsonFormat Whether to tell the assistant to respond in JSON format (only available for models newer than gpt-3.5-turbo-1106)
	 * @param string $sessionID The ID of the session file to be used for the chat completion
	 * @return array
	 */
	public function createChatCompletion(array $data = [], bool $jsonFormat = false, string $sessionID = null): array
	{
		try {
			$this->_checkInitialization();

			$roles = ['user', 'assistant', 'system'];

			if (!empty($data)){
				foreach ($data as $item)
				{
					if (empty($item)){
						$this->ret['status'] = SC_BAD_REQUEST;
						$this->ret['reason'] = 'Les données ne peuvent pas être vides';
						throw new Exception('Empty data');
					}

					$role = $item['role'] ?? '';

					if(!(in_array($role, $roles))){
						$this->ret['status'] = SC_BAD_REQUEST;
						$this->ret['reason'] = 'Le role fourni n\'est pas reconnu : ' . $role . ' Les rôles reconnus sont : ' . implode(', ', $roles);
						throw new Exception('Unknown role : ' . $role);
					}

					if (empty($item['content'] ?? '')){
						$this->ret['status'] = SC_BAD_REQUEST;
						$this->ret['reason'] = 'Le contenu des messages ne peut pas être vide';
						throw new Exception('Empty message content');
					}
				}
			}else{
				$this->ret['status'] = SC_BAD_REQUEST;
				$this->ret['reason'] = 'Les données ne peuvent pas être vides';
				return $this->ret;
			}

			// If a sessionID is provided, we attempt to retrieve the history from the session file
			if (!empty($sessionID))
			{
				// Check if the folder exists, if not, create it
				if (!is_dir(self::CHAT_GPT_SESSIONS_FOLDER))
				{
					mkdir(self::CHAT_GPT_SESSIONS_FOLDER, 0777, true);
				}

				$sessionFilePath = self::CHAT_GPT_SESSIONS_FOLDER . $sessionID . '.json';

				if (is_file($sessionFilePath))
				{
					$fileContent 	= file_get_contents($sessionFilePath);
					$history 		= json_decode($fileContent, true);
					$data 			= array_merge($history,$data);
				}
			}

			if (!$jsonFormat && !isset($history))
			{
				$data = array_merge(self::CHAT_GPT_BASE, $data);
			}

			// preparing the payload
			if ($jsonFormat){
				$data[] = self::JSON_FORMAT[0];
				$payload = [
					'model' 	=> "gpt-3.5-turbo-1106",
					'messages' 	=> $data,
					'response_format' =>  [ "type" => "json_object" ]
				];

			}else{
				$payload = [
					'model' 	=> $this->model,
					'messages' 	=> $data,
				];
			}

			// Updating the request body with the payload
			$this->curlClient->setBody(json_encode($payload));

			$response = $this->curlClient->post($this->baseURL . '/v'.$this->version.'/chat/completions', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$body = json_decode($response->getBody(), true);

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['data'] = $body ?? null;

				if($jsonFormat){
					$choices = $this->ret['data']['choices'] ?? [];

					foreach ($choices as &$choice)
					{
						$content = $choice['message']['content'] ?? '';
						$content = json_decode($content, true);
						$choice['message']['content'] = $content;
					}

					$this->ret['data']['choices'] = $choices;
				}

				// Saving the history to the session file
				if (!empty($sessionID))
				{
					$data[] = [
						'role' 		=> 'assistant',
						'content' 	=> $this->ret['data']['choices'][0]['message']['content']
					];

					file_put_contents($sessionFilePath, json_encode($data, JSON_PRETTY_PRINT));
				}
			}
			else{
				$this->ret['reason'] = 'Une erreur interne est survenue lors de la communication avec l\'API de ChatGPT';
				throw new Exception('ChatGPT API error : '.$body['error']['message'] ?? 'Unknown');
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}
		return $this->ret;
	}

	/**
	 * Create a new empty chat completion session and return its ID
	 * @return array
	 */
	public function createEmptyChatCompletionSession() : array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue', 'data' => null];

		try {
			helper('text');
			$sessionID = random_string('alnum', 32);

			// Check if the folder exists, if not, create it
			if (!is_dir(self::CHAT_GPT_SESSIONS_FOLDER))
			{
				mkdir(self::CHAT_GPT_SESSIONS_FOLDER, 0777, true);
			}

			$sessionFilePath = self::CHAT_GPT_SESSIONS_FOLDER . $sessionID . '.json';

			$data = self::CHAT_GPT_BASE;

			file_put_contents($sessionFilePath, json_encode($data, JSON_PRETTY_PRINT));

			$ret['status'] 	= SC_SUCCESS;
			$ret['reason'] 	= 'La session de chat à été créée';
			$ret['data'] 	= $sessionID;
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * Deletes a specific chat completion session
	 * @param $sessionID string The ID of the session to be deleted
	 */
	public function deleteChatCompletionSession($sessionID)
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue', 'data' => null];

		try {
			$sessionFilePath = self::CHAT_GPT_SESSIONS_FOLDER . $sessionID . '.json';

			if (is_file($sessionFilePath))
			{
				unlink($sessionFilePath);
				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= 'La session de chat à été supprimée';
			}
			else
			{
				$ret['status'] 	= SC_NOT_FOUND;
				$ret['reason'] 	= 'La session de chat n\'a pas été trouvée';
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}


	// =================================================================================================================
	// =================================================================================================================
	// FILES
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Uploads a file to the API (i.e: for fine-tuning a model)
	 * @param $filePath string The path to the file to be uploaded
	 * @return array
	 */
	public function uploadFile(string $filePath): array
	{
		try {
			$this->_checkInitialization();
			$this->curlClient->setHeader('Content-Type', 'multipart/form-data');

			$filePath = str_replace('/', DIRECTORY_SEPARATOR, $filePath);

			$CURLFile = new CURLFILE($filePath);

			// Setting the multipart form data
			$payload = [
				'file' => $CURLFile,
				'purpose' => 'fine-tune'
			];

			$this->requestOptions['multipart'] = $payload;

			$response = $this->curlClient->post($this->baseURL . '/v'.$this->version.'/files', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$body = json_decode($response->getBody(), true);

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Le fichier a été correctement téléversé';
				$this->ret['data'] = $body ?? null;
			}
			else{
				$this->ret['reason'] = $body['error']['message'] ?? 'Une erreur interne est survenue lors de la communication avec l\'API de ChatGPT';
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Lists the files that have been uploaded to the API
	 * @return array
	 */
	public function listFiles(): array{

		try {
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/v'.$this->version.'/files', $this->requestOptions);

			$this->ret['status'] = $response->getStatusCode();
			$body = json_decode($response->getBody(), true);


			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'La liste des fichiers a été correctement récupérée';
				$this->ret['data'] = $body ?? null;
			}
			else{
				$this->ret['reason'] = $body['error']['message'] ?? 'Une erreur interne est survenue lors de la communication avec l\'API de ChatGPT';
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}
		return $this->ret;
	}

	/**
	 * Retrieves the information about a specific file
	 * @param $fileId string The ID of the file to be retrieved
	 * @return array
	 */
	public function retrieveFile($fileId){

		try{
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/v'.$this->version.'/files/'.$fileId, $this->requestOptions);
			$this->ret['status'] = $response->getStatusCode();
			$body = json_decode($response->getBody(), true);

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Les informations sur le fichier ont été correctement récupérées';
				$this->ret['data'] = $body ?? null;
			}
			else{
				$this->ret['reason'] = $body['error']['message'] ?? 'Une erreur interne est survenue lors de la communication avec l\'API de ChatGPT';
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Retrieves the content of a specific file
	 * @param $fileId string The ID of the file to be retrieved
	 * @return array
	 */
	public function retrieveFileContent($fileId){

		try{
			$this->_checkInitialization();

			$response = $this->curlClient->get($this->baseURL . '/v'.$this->version.'/files/'.$fileId.'/content', $this->requestOptions);
			$this->ret['status'] = $response->getStatusCode();
			$body = json_decode($response->getBody(), true);

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Les informations sur le fichier ont été correctement récupérées';
				$this->ret['data'] = $body ?? null;
			}
			else{
				$this->ret['reason'] = $body['error']['message'] ?? 'Une erreur interne est survenue lors de la communication avec l\'API de ChatGPT';
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}

	/**
	 * Deletes a specific file
	 * @param $fileId string The ID of the file to be deleted
	 * @return array
	 */
	public function deleteFile($fileId){

		try{
			$this->_checkInitialization();


			// Body for deletion
			$response = $this->curlClient->delete($this->baseURL . '/v'.$this->version.'/files/'.$fileId, $this->requestOptions);
			$this->ret['status'] = $response->getStatusCode();
			$body = json_decode($response->getBody(), true);

			if ($this->ret['status'] === SC_SUCCESS) {
				$this->ret['reason'] = 'Les informations sur le fichier ont été correctement récupérées';
				$this->ret['data'] = $body ?? null;
			}
			else{
				$this->ret['reason'] = $body['error']['message'] ?? 'Une erreur interne est survenue lors de la communication avec l\'API de ChatGPT';
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $this->ret;
	}
}
