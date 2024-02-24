<?php

namespace APIFox\Models;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model\BodyType;
use Microsoft\Graph\Model\Calendar;
use Microsoft\Graph\Model\DateTimeTimeZone;
use Microsoft\Graph\Model\DirectoryObject;
use Microsoft\Graph\Model\Event;
use Microsoft\Graph\Model\ItemBody;
use Microsoft\Graph\Model\Location;
use Microsoft\Graph\Model\PhysicalAddress;
use Microsoft\Graph\Model\User;
use RFCore\Entities\E_API;
use RFCore\Models\M_API;
use RFCore\Models\RF_Model;

class M_MSGraphAPI extends RF_Model
{
	private $client;
	private $clientID;
	private $clientSecret;
	private $tenantID;
	private $initDone = false;

	const MS_GRAPH_CONSTANTS = [
		'clientID' 		=> 'API_MS_GRAPH_CLIENT_ID',
		'tenantID' 		=> 'API_MS_GRAPH_TENANT_ID',
		'clientSecret' 	=> 'API_MS_GRAPH_CLIENT_SECRET',
	];

	public function __construct()
	{
		parent::__construct();

		try {
			$M_API = new M_API();

			// Retrieving the associated constants and their values from the database
			foreach (self::MS_GRAPH_CONSTANTS as $property => $constant) {
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

			$this->client = new Graph();

			// Fetching the access token from Microsoft
			$guzzle = new Client();
			$url = 'https://login.microsoftonline.com/' . $this->tenantID . '/oauth2/v2.0/token';
			$token = json_decode($guzzle->post($url, [
				'form_params' => [
					'client_id' 	=> $this->clientID,
					'client_secret' => $this->clientSecret,
					'scope' 		=> 'https://graph.microsoft.com/.default',
					'grant_type' 	=> 'client_credentials',
				],
			])->getBody()->getContents());

			// If no exception was thrown, we can set the access token
			$this->client->setAccessToken($token->access_token);

			// Setting the initDone flag to true
			$this->initDone = true;
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}
	}

	/**
	 * Log the error returned by the Graph API
	 * @param $function string Function name
	 * @param $message string Message
	 * @return void
	 */
	public function logErrorResponse(string $function, string $message)
	{
		$response = substr($message, strpos($message, 'response:') + 9);
		$message = str_replace($response, '', $message);
		$response = json_decode($response, true);
		log_message('error', __FUNCTION__ . '::' . $function . '() : ' .PHP_EOL. $message.PHP_EOL.json_encode($response, JSON_PRETTY_PRINT));
	}

	// =================================================================================================================
	// =================================================================================================================
	// TENANTS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Function responsible for retrieving the list of tenants
	 * @return array
	 */
	public function listTenants(): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => null];

		try {
			if ($this->initDone)
			{
				$tenants = $this->client
					->setBaseUrl('https://management.azure.com')
					->createRequest('GET', '/tenants?api-version=2020-01-01')
					->setReturnType(DirectoryObject::class)
					->execute()
				;

				$ret['status'] = SC_SUCCESS;
				$ret['data'] = $tenants;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échoué';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * Retrieve data about a specific tenant
	 * @param string|null $tenantID The tenant ID
	 * @return array
	 */
	public function getTenant(string $tenantID = null): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => null];

		try {
			if ($this->initDone)
			{
				if (is_null($tenantID))
				{
					$tenantID = $this->tenantID;
				}

				$tenant = $this->client
					->createRequest('GET', '/tenants/' . $tenantID)
					->setReturnType(DirectoryObject::class)
					->execute()
				;

				$ret['status'] = SC_SUCCESS;
				$ret['data'] = $tenant;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échoué';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// USERS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve the list of all the users in the tenant
	 * @return array
	 */
	public function listUsers(): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => []];

		try {
			if ($this->initDone)
			{
				$users = $this->client->createRequest('GET', '/users')
					->setReturnType(User::class)
					->execute();

				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= '';
				$ret['data'] 	= $users;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échouée';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * Retrieve the data of a specific user from the tenant
	 * @param $ID string The ID of the user to retrieve
	 * @return array
	 */
	public function getUser(string $ID): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => null];

		try {
			if ($this->initDone)
			{
				$user = $this->client->createRequest('GET', '/users/' . $ID)
					->setReturnType(User::class)
					->execute();

				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= '';
				$ret['data'] 	= $user;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échouée';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	// =================================================================================================================
	// =================================================================================================================
	// CALENDARS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Retrieve the list of all the calendars of a given user
	 * @param $ID string The ID of the user to retrieve the calendars from
	 * @return array
	 */
	public function listCalendars(string $ID): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => []];

		try {
			if ($this->initDone)
			{
				$calendars = $this->client
					->createCollectionRequest('GET', '/users/' . $ID . '/calendars')
					->setReturnType(Calendar::class)
					->execute();

				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= '';
				$ret['data'] 	= $calendars;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échouée';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * Retrieve the data of a specific calendar from a given user
	 * @param $userID string The ID of the user to retrieve the calendar from
	 * @param null|string $calendarID Optional calendar ID (if not provided, the default calendar will be used)
	 * @return array
	 */
	public function getCalendar(string $userID, string $calendarID = null): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => []];

		try {
			if ($this->initDone)
			{
				if (empty($calendarID)){
					$endpoint = '/users/' . $userID . '/calendar';
				}
				else{
					$endpoint = '/users/' . $userID . '/calendars/' . $calendarID;
				}

				$calendars = $this->client
					->createRequest('GET', $endpoint)
					->setReturnType(Calendar::class)
					->execute();

				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= '';
				$ret['data'] 	= $calendars;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échouée';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * List all the events of the default calendar of a given user
	 * @param string $ID The ID of the user to retrieve the events from
	 * @param string|null $calendarID Optional calendar ID (if not provided, the default calendar will be used)
	 * @return array
	 */
	public function listEvents(string $ID, string $calendarID = null): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => null];

		try {
			if ($this->initDone)
			{
				if (empty($calendarID))
				{
					$endpoint = '/users/' . $ID . '/calendar/events';
				}
				else{
					$endpoint = '/users/' . $ID . '/calendars/' . $calendarID . '/events';
				}

				$events = $this->client->createCollectionRequest('GET', $endpoint)
					->setReturnType(Event::class)
					->execute();

				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= '';
				$ret['data'] 	= $events;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échouée';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * Instantiate a new event object based on the provided data
	 * @param array $eventData The data of the event to create
	 * @param Event|null $originalEvent Optional original event to update
	 * @return Event|null The event object
	 */
	public function createEventObject(array $eventData, ?Event $originalEvent = null): ?Event
	{
		$ret = null;

		try {
			if (empty($originalEvent)) {
				$event = new Event();
			} else {
				$event = $originalEvent;
			}

			foreach ($eventData as $key => $value)
			{
				switch ($key){
					case 'id':
						$event->setId($value);
						break;
					case 'subject':
						$event->setSubject($value);
						break;
					case 'content':
						// Add the content to the body of the event
						$body = new ItemBody();
						$body->setContentType(new BodyType($eventData['bodyType'] ?? 'html'));
						$body->setContent($value);
						$event->setBody($body);
						break;
					case 'start':
						$dateTime = new DateTimeTimeZone();

						// Converting the date to a proper format
						if ($value instanceof DateTime){
							$dateTime->setDateTime($value->format('Y-m-d\TH:i:s'));
						}
						else{
							$dateTime->setDateTime($value);
						}

						$dateTime->setDateTime($value);

						// Setting the timezone (default is Europe/Paris)
						$dateTime->setTimeZone($eventData['timezone'] ?? 'Europe/Paris');

						$event->setStart($dateTime);
						break;
					case 'end':
						$dateTime = new DateTimeTimeZone();

						// Converting the date to a proper format
						if ($value instanceof DateTime){
							$dateTime->setDateTime($value->format('Y-m-d\TH:i:s'));
						}
						else{
							$dateTime->setDateTime($value);
						}

						$dateTime->setDateTime($value);

						// Setting the timezone (default is Europe/Paris)
						$dateTime->setTimeZone($eventData['timezone'] ?? 'Europe/Paris');

						$event->setEnd($dateTime);
						break;
					case 'location':
						$location = new Location();

						if (is_array($value))
						{
							// Setting the location display name
							$location->setDisplayName($value['name']);

							// Instantiating the physical address
							$physicalAddress = new PhysicalAddress();
							$physicalAddress->setCity($value['city'] ?? null);
							$physicalAddress->setCountryOrRegion($value['country'] ?? null);
							$physicalAddress->setPostalCode($value['postalCode'] ?? null);
							$physicalAddress->setState($value['state'] ?? null);
							$physicalAddress->setStreet($value['street'] ?? null);
							$location->setAddress($physicalAddress);
						}
						else{
							// Setting the location display name
							$location->setDisplayName($value);
						}

						$event->setLocation($location);

						break;
				}
			}

			// This parameter define if the final user can propose a new date/time for the event
			$event->setAllowNewTimeProposals($eventData['allowNewTimeProposals'] ?? false);

			$ret = $event;
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . ' : ' . $e);
		}

		return $ret;
	}

	/**
	 * Add event to the default calendar of a given user
	 * @param string $ID The ID of the user to add the event to
	 * @param Event $event The event to add
	 * @param null|string $calendarID Optional calendar ID (if not provided, the default calendar will be used)
	 */
	public function addEvent(string $ID, Event $event, string $calendarID = null): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => null];

		try {
			if ($this->initDone)
			{
				if (empty($calendarID))
				{
					$endpoint = '/users/' . $ID . '/calendar/events';
				}
				else{
					$endpoint = '/users/' . $ID . '/calendars/' . $calendarID . '/events';
				}

				$event = $this->client->createRequest('POST', $endpoint)
					->attachBody($event)
					->setReturnType(Event::class)
					->execute();

				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= '';
				$ret['data'] 	= $event;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échouée';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * Update an event of the default calendar of a given user
	 * @param string $ID The ID of the user to update the event to
	 * @param Event $event The event to update
	 * @param null|string $calendarID Optional calendar ID (if not provided, the default calendar will be used)
	 */
	public function updateEvent(string $ID, Event $event, string $calendarID = null): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => null];

		try {
			if ($this->initDone)
			{
				if (empty($calendarID))
				{
					$endpoint = '/users/' . $ID . '/calendar/events/' . $event->getId();
				}
				else{
					$endpoint = '/users/' . $ID . '/calendars/' . $calendarID . '/events/' . $event->getId();
				}

				$event = $this->client->createRequest('PATCH', $endpoint)
					->attachBody($event)
					->setReturnType(Event::class)
					->execute();

				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= '';
				$ret['data'] 	= $event;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échouée';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * Delete an event of the default calendar of a given user
	 * @param string $ID The ID of the user to delete the event to
	 * @param string $eventID The ID of the event to delete
	 * @param null|string $calendarID Optional calendar ID (if not provided, the default calendar will be used)
	 */
	public function deleteEvent(string $ID, string $eventID, string $calendarID = null): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => null];

		try {
			if ($this->initDone)
			{
				if (empty($calendarID))
				{
					$endpoint = '/users/' . $ID . '/calendar/events/' . $eventID;
				}
				else{
					$endpoint = '/users/' . $ID . '/calendars/' . $calendarID . '/events/' . $eventID;
				}

				$this->client->createRequest('DELETE', $endpoint)
					->execute();

				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= '';
				$ret['data'] 	= null;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échouée';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * Retrieve an event from the default calendar of a given user
	 * @param string $ID The ID of the user to delete the event to
	 * @param string $eventID The ID of the event to retrieve
	 * @param null|string $calendarID Optional calendar ID (if not provided, the default calendar will be used)
	 */
	public function getEvent(string $ID, string $eventID, string $calendarID = null): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue lors du traitement de votre demande', 'data' => null];

		try {
			if ($this->initDone)
			{
				if (empty($calendarID))
				{
					$endpoint = '/users/' . $ID . '/calendar/events/' . $eventID;
				}
				else{
					$endpoint = '/users/' . $ID . '/calendars/' . $calendarID . '/events/' . $eventID;
				}

				$event = $this->client->createRequest('GET', $endpoint)
					->setReturnType(Event::class)
					->execute();

				$ret['status'] 	= SC_SUCCESS;
				$ret['reason'] 	= '';
				$ret['data'] 	= $event;
			}
			else{
				$ret['reason'] = 'L\'initialisation de l\'API Microsoft Graph a échouée';
				$ret['status'] = SC_INTERNAL_SERVER_ERROR;
			}
		}
		catch (GuzzleException $e)
		{
			$this->logErrorResponse(__FUNCTION__,$e->getMessage());
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}
}
