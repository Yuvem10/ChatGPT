<?php

namespace ChatGPT\Controllers;

use APIFox\Models\M_ChatGPT;
use Exception;
use RFCore\Controllers\RF_Controller;

class C_ChatGPT extends RF_Controller
{
	const VIEW_CHATGPT 			= INTEGRATION_BASE_MODULE.'\Views\ChatGPT\V_ChatGPT';
	const CHAT_GPT_SESSIONS_FOLDER = ROOTPATH.DIRECTORY_SEPARATOR.'writable'.DIRECTORY_SEPARATOR.'session'.DIRECTORY_SEPARATOR.'ChatGPT'.DIRECTORY_SEPARATOR;

	public function __construct()
	{
		define('API_CHATGPT_ENABLED','API_CHATGPT_ENABLED');
		define('API_CHATGPT_BEARER_TOKEN','API_CHATGPT_BEARER_TOKEN');
		define('API_CHATGPT_VERSION','API_CHATGPT_VERSION');
		define('API_CHATGPT_MODEL','API_CHATGPT_MODEL');
		parent::__construct();
	}

	/**
	 * Display the ChatGPT page
	 * @return \CodeIgniter\HTTP\RedirectResponse|string
	 */
	public function displayChatGPT()
	{
		$ret = redirect()->to(base_url());

		try {
			helper('text');

			$ret = render(self::VIEW_CHATGPT, [
				'hideHeader' 		=> true,
				'newSessionID' 	=> random_string('alnum', 32),
			]);
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		return $ret;
	}

	/**
	 * Create a chat completion
	 * @return false|string
	 */
	public function createChatCompletion()
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue', 'data' => []];

		try {
			/** @var string $question */
			$question 	= $this->request->getPostGet('question');
			$sessionID 	= $this->request->getPostGet('sessionID');
			$sessionID 	= !empty($sessionID) ? $sessionID : null;

			if (!empty($question)) {

				$M_ChatGPT = new M_ChatGPT();

				$data = [
					[
						'role' 		=> 'user',
						'content' 	=> $question
					]
				];

				$response = $M_ChatGPT->createChatCompletion($data, false, $sessionID);

				$ret = ['status' => $response['status'], 'reason' => $response['reason'], 'data' => $response['data']];
			}
			else{
				$ret['status'] = SC_BAD_REQUEST;
				$ret['reason'] = 'La question est vide';
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		$this->response->setStatusCode($ret['status'], $ret['reason']);
		$this->response->setContentType('application/json');

		return json_encode($ret);

	}

	/**
	 * List the available chat completion sessions
	 * @return false|string
	 */
	public function getChatCompletionSessions()
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue', 'data' => []];

		try {

			$M_ChatGPT = new M_ChatGPT();


			if (!is_dir(self::CHAT_GPT_SESSIONS_FOLDER))
			{
				mkdir(self::CHAT_GPT_SESSIONS_FOLDER, 0777, true);
			}

			$sessionFiles = scandir(self::CHAT_GPT_SESSIONS_FOLDER);


			foreach ($sessionFiles as $sessionFile) {

				if ($sessionFile !== '.' && $sessionFile !== '..') {

					$sessionID = str_replace('.json', '', $sessionFile);

					$sessionMessages = json_decode(file_get_contents(self::CHAT_GPT_SESSIONS_FOLDER.$sessionFile), true);

					// retrieve the last message of the user not the system
					$count = count($sessionMessages);
					$lastMessageUser = $sessionMessages[$count-2]['content'] ?? [];

					$ret['data'][$sessionID]['id'] = $sessionID;
					$ret['data'][$sessionID]['date'] = date('d/m/Y H:i:s', filemtime(self::CHAT_GPT_SESSIONS_FOLDER.$sessionFile));
					$ret['data'][$sessionID]['last_message_user'] = $lastMessageUser ?? '';
					$ret['data'][$sessionID]['role'] = $sessionMessages[$count-2]['role'] ?? '';

				}

			}

			$ret['status'] = SC_SUCCESS;
			$ret['reason'] = 'Les sessions de chat ont été récupérées';
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		$this->response->setStatusCode($ret['status'], $ret['reason']);
		$this->response->setContentType('application/json');

		return json_encode($ret);
	}

	/**
	 * Retrieve the messages of a chat completion session
	 * @return false|string
	 */
	public function loadChatCompletionSession($sessionID)
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue', 'data' => []];

		try {
			$M_ChatGPT = new M_ChatGPT();

			if (!is_dir(self::CHAT_GPT_SESSIONS_FOLDER))
			{
				mkdir(self::CHAT_GPT_SESSIONS_FOLDER, 0777, true);
			}

			$sessionFiles = scandir(self::CHAT_GPT_SESSIONS_FOLDER);

			foreach ($sessionFiles as $sessionFile) {

				if ($sessionFile !== '.' && $sessionFile !== '..') {

					$currentSessionId = str_replace('.json', '', $sessionFile);

					if ($currentSessionId === $sessionID) {
						$sessionMessages = json_decode(file_get_contents(self::CHAT_GPT_SESSIONS_FOLDER.$sessionFile), true);
						$ret['data'] = $sessionMessages;
					}

				}

			}

			$ret['status'] = SC_SUCCESS;
			$ret['reason'] = 'La session de chat à été récupérée';
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		$this->response->setStatusCode($ret['status'], $ret['reason']);
		$this->response->setContentType('application/json');

		return json_encode($ret);
	}

	/**
	 * Create a chat completion session
	 * @return false|string
	 */
	public function createChatCompletionSession()
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue', 'data' => []];

		try {
			$M_ChatGPT = new M_ChatGPT();
			$ret = $M_ChatGPT->createEmptyChatCompletionSession();
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		$this->response->setStatusCode($ret['status'], $ret['reason']);
		$this->response->setContentType('application/json');

		return json_encode($ret);
	}

	/**
	 * Delete a chat completion session
	 * @return false|string
	 */
	public function deleteChatCompletionSession($sessionID)
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => 'Une erreur interne est survenue', 'data' => []];

		try {
			$M_ChatGPT = new M_ChatGPT();
			$ret = $M_ChatGPT->deleteChatCompletionSession($sessionID);
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . '() : ' . $e);
		}

		$this->response->setStatusCode($ret['status'], $ret['reason']);
		$this->response->setContentType('application/json');

		return json_encode($ret);
	}


}
