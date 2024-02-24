<?php

namespace app\Modules\APIFox;

use APIFox\Models\M_ChatGPT;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;
use CURLFile;
use DateTime;
use PHPUnit\Framework\TestCase;

class M_ChatGPTTest extends TestCase
{

	const CONTEXT_MODEL_DEVELOPER = [
		[
			'role' 		=> 'system',
			'content' 	=> 'Tu es un assistant'
		],
		[
			'role' 		=> 'user',
			'content' 	=> 'Je suis un candidat'
		],
	];

	public function __construct()
	{
		parent::__construct();

		helper('text');
	}

	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();

		helper('text');

		ini_set('memory_limit', '-1');

		define('API_CHATGPT_ENABLED','API_CHATGPT_ENABLED');
		define('API_CHATGPT_BEARER_TOKEN','API_CHATGPT_BEARER_TOKEN');
		define('API_CHATGPT_VERSION','API_CHATGPT_VERSION');
		define('API_CHATGPT_MODEL','API_CHATGPT_MODEL');
	}

	public static function tearDownAfterClass(): void
	{
		parent::tearDownAfterClass();

		ini_set('memory_limit', '2048M');
	}

	public function testExample()
	{
		$M_ChatGPT = new M_ChatGPT();

		$data = [
			[
				'role' => 'user',
				'content' => 'Parle moi de la tour eiffel.'
			]
		];


		$dataRole = [
			[
				'role' => 'test',
				'content' => ''
			]
		];


		$dataContent = [
			[
				'role' => 'user',
				'content' => ''
			]
		];


		$dataEmpty = [];

		$dataEmptyItem = [[[]]];

		$test = $M_ChatGPT->createChatCompletion($data);
		$emptyTest = $M_ChatGPT->createChatCompletion($dataEmpty, true);
		$roleTest = $M_ChatGPT->createChatCompletion($dataRole);
		$contentTest = $M_ChatGPT->createChatCompletion($dataContent);
		$dataEmptyItemTest = $M_ChatGPT->createChatCompletion($dataEmptyItem);


		$this->assertEquals(200, $test['status'], $test['reason']);
		$this->assertEquals(400, $emptyTest['status'], $emptyTest['reason']);
		$this->assertEquals(400, $roleTest['status'], $roleTest['reason']);
		$this->assertEquals(400, $contentTest['status'], $contentTest['reason']);
		$this->assertEquals(400, $dataEmptyItemTest['status'], $dataEmptyItemTest['reason']);
	}

	public function testContextModel()
	{

	}

	public function testUploadFile(){
		$M_ChatGPT = new M_ChatGPT();
		$filePath = 'C:/wamp64/www/ChatGPT/tests/app/Modules/APIFox/trainingFile.jsonl';
		$res = $M_ChatGPT->uploadFile($filePath);
		print_r($res['data']['id']);
		$this->assertEquals("Le fichier a été correctement téléversé", $res['status'], $res['reason']);
	}

	public function testFiles()
	{
		$M_ChatGPT = new M_ChatGPT();
		$filePath = 'C:/wamp64/www/ChatGPT/tests/app/Modules/APIFox/trainingFile.jsonl';

		// Test file upload
		$res = $M_ChatGPT->uploadFile($filePath);
		$this->assertEquals(200, $res['status'], $res['reason']);
		$uploadedFileID = $res['data']['id'];

		// Test files list
		$list = $M_ChatGPT->listFiles();
		$this->assertEquals(200, $list['status'], $list['reason']);

		$similarFile = false;

		foreach ($list['data']['data'] as $file){
			if ($file['id'] == $uploadedFileID){
				$similarFile = true;
				break;
			}
		}
		$this->assertTrue($similarFile, "Le fichier a bien été uploadé");

		// Test retrieve file
		$fileInfo = $M_ChatGPT->retrieveFile($uploadedFileID);
		$this->assertEquals(200, $fileInfo['status'], $fileInfo['reason']);
		$this->assertEquals($uploadedFileID, $fileInfo['data']['id'], "Les informations ne correspondent pas");

		// Test retrieve file content
		$fileContent = $M_ChatGPT->retrieveFileContent($uploadedFileID);
		$this->assertContains($fileContent['status'], [200, 400], $fileContent['reason'] ?? "Le contenu du fichier n'a pas pu être récupéré");

		// Test delete file
		$deleteFile = $M_ChatGPT->deleteFile($uploadedFileID);
		$this->assertEquals(200, $deleteFile['status'], $deleteFile['reason']);

		// Testing that the file has been deleted
		$listAfterDelete = $M_ChatGPT->listFiles();
		$found = false;
		foreach ($listAfterDelete['data']['data'] as $file){
			if ($file['id'] == $uploadedFileID){
				$found = true;
				break;
			}
		}
		$this->assertFalse($found, "Le fichier n'a pas été supprimé");
	}
}
