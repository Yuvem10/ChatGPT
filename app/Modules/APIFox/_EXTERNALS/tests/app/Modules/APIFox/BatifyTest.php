<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace app\Modules\APIFox;

use APIFox\Models\M_Batify;
use CodeIgniter\Files\File;
use CodeIgniter\Test\CIUnitTestCase;

class BatifyTest extends CIUnitTestCase
{
	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();

		helper('text');
	}

	public static function tearDownAfterClass(): void
	{
		parent::tearDownAfterClass();

		// Delete all temp files
		foreach (glob(ROOTPATH. DIRECTORY_SEPARATOR. 'writable'. DIRECTORY_SEPARATOR . 'tests/*') as $file)
		{
			@unlink($file);
		}
	}

	// =================================================================================================================
	// =================================================================================================================
	// SYSTEM
	// =================================================================================================================
	// =================================================================================================================

	public function testConnectivity()
	{
		$M_Batify = new M_Batify();
		$batifyResponse = $M_Batify->testConnectivity();
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);
	}

	// =================================================================================================================
	// =================================================================================================================
	// WORKSITES
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Test the worksites related endpoints
	 * @return void
	 */
	public function testWorksiteEndpoints()
	{
		$M_Batify = new M_Batify();

		// Retrieving the initial count of worksites
		$batifyResponse = $M_Batify->getWorksites();

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		$initialCount = count($batifyResponse['data']);

		// Generate a random set of data
		$data = [
			'name' 			=> random_string('alnum', 10),
			'address1' 		=> random_string('alnum', 10),
			'address2' 		=> random_string('alnum', 10),
			'postalCode' 	=> random_string('alnum', 10),
			'city' 			=> random_string('alnum', 10),
		];

		// Add the worksite
		$batifyResponse = $M_Batify->addWorksite($data);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the new worksite ID
		$newWorksiteID = $batifyResponse['data'];

		// Retrieving the new count of worksites
		$batifyResponse = $M_Batify->getWorksites();

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		$newCount = count($batifyResponse['data']);

		// Making sure the count has increased by 1
		$this->assertEquals(
			$initialCount + 1,
			$newCount,
			__FUNCTION__.'('.__LINE__.') test failed : The worksites count has not increased by 1'
		);

		// Retrieving the worksite
		$batifyResponse = $M_Batify->getWorksite($newWorksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure the data is correct
		foreach ($data as $key => $value) {
			$this->assertEquals(
				$value,
				$batifyResponse['data'][$key],
				__FUNCTION__.'('.__LINE__.') test failed : {ADD} The data for '.$key.' is different from the one sent'
			);
		}

		// Updating the worksite
		$data = [
			'name' 			=> random_string('alnum', 10),
			'address1' 		=> random_string('alnum', 10),
			'address2' 		=> random_string('alnum', 10),
			'postalCode' 	=> random_string('alnum', 10),
			'city' 			=> random_string('alnum', 10),
		];

		$batifyResponse = $M_Batify->updateWorksite($newWorksiteID, $data);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the worksite
		$batifyResponse = $M_Batify->getWorksite($newWorksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure the data is correct
		foreach ($data as $key => $value) {
			$this->assertEquals(
				$value,
				$batifyResponse['data'][$key],
				__FUNCTION__.'('.__LINE__.') test failed : {UPDATE} The data for '.$key.' is different from the one sent'
			);
		}

		// Deleting the worksite
		$batifyResponse = $M_Batify->deleteWorksite($newWorksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the new count of worksites
		$batifyResponse = $M_Batify->getWorksites();

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		$newCount = count($batifyResponse['data']);

		// Making sure the count has decreased by 1
		$this->assertEquals(
			$initialCount,
			$newCount,
			__FUNCTION__.'('.__LINE__.') test failed : The worksites count has not decreased by 1'
		);
	}

	// =================================================================================================================
	// =================================================================================================================
	// FOLDERS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Test the folders related endpoints
	 */
	public function testFolders()
	{
		$M_Batify = new M_Batify();

		// Retrieving the list of worksites
		$batifyResponse = $M_Batify->getWorksites();

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that at least one worksite exists
		$this->assertGreaterThan(
			0,
			count($batifyResponse['data']),
			__FUNCTION__.'('.__LINE__.') test failed : No worksite found'
		);

		// Retrieving the first worksite ID
		$worksiteID = $batifyResponse['data'][0]['id'];

		// Retrieving the initial count of folders
		$batifyResponse = $M_Batify->getFolderContent($worksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__ . '('.__LINE__.') test failed : ' . $batifyResponse['reason'] ?? 'Unknown'
		);

		$initialCount = count($batifyResponse['data']);

		// Generate a random set of data
		$data = [
			'name' => random_string('alnum', 10),
		];

		// Add the folder
		$batifyResponse = $M_Batify->createFolder($worksiteID,$data);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__ . '('.__LINE__.') test failed : ' . $batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the new folder ID
		$newFolderID = $batifyResponse['data'];

		// Retrieving the new count of folders
		$batifyResponse = $M_Batify->getFolderContent($worksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__ . '('.__LINE__.') test failed : ' . $batifyResponse['reason'] ?? 'Unknown'
		);

		$newCount = count($batifyResponse['data']);

		// Making sure the count has increased by 1
		$this->assertEquals(
			$initialCount + 1,
			$newCount,
			__FUNCTION__ . '('.__LINE__.') test failed : The folders count has not increased by 1'
		);

		// Retrieving the folder
		$batifyResponse = $M_Batify->getFolder($worksiteID,$newFolderID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__ . '('.__LINE__.') test failed : ' . $batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure the data is correct
		foreach ($data as $key => $value) {
			$this->assertEquals(
				$value,
				$batifyResponse['data'][$key],
				__FUNCTION__ . '('.__LINE__.') test failed : {ADD} The data for ' . $key . ' is different from the one sent'
			);
		}

		// Updating the folder
		$data = [
			'name' => random_string('alnum', 10),
		];

		$batifyResponse = $M_Batify->updateFolder($worksiteID, $newFolderID, $data);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__ . '('.__LINE__.') test failed : ' . $batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the folder
		$batifyResponse = $M_Batify->getFolder($worksiteID, $newFolderID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__ . '('.__LINE__.') test failed : ' . $batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure the data is correct
		foreach ($data as $key => $value) {
			$this->assertEquals(
				$value,
				$batifyResponse['data'][$key],
				__FUNCTION__ . '('.__LINE__.') test failed : {UPDATE} The data for ' . $key . ' is different from the one sent'
			);
		}

		// Deleting the folder
		$batifyResponse = $M_Batify->deleteFolder($worksiteID, $newFolderID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__ . '('.__LINE__.') test failed : ' . $batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the new count of folders
		$batifyResponse = $M_Batify->getFolderContent($worksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__ . '('.__LINE__.') test failed : ' . $batifyResponse['reason'] ?? 'Unknown'
		);

		$newCount = count($batifyResponse['data']);

		// Making sure the count has decreased by 1
		$this->assertEquals(
			$initialCount,
			$newCount,
			__FUNCTION__ . '('.__LINE__.') test failed : The folders count has not decreased by 1'
		);

	}

	// =================================================================================================================
	// =================================================================================================================
	// DOCUMENTS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Test the document related endpoints
	 */
	public function testDocuments() {
		$M_Batify = new M_Batify();

		// Retrieving the list of worksites
		$batifyResponse = $M_Batify->getWorksites();

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that at least one worksite exists
		$this->assertGreaterThan(
			0,
			count($batifyResponse['data']),
			__FUNCTION__.'('.__LINE__.') test failed : No worksite found'
		);

		// Retrieving the first worksite ID
		$worksiteID = $batifyResponse['data'][0]['id'];

		// Ensuring that the PHPUnit tests folder exists in the writable directory
		$testsFolder = ROOTPATH. DIRECTORY_SEPARATOR. 'writable'. DIRECTORY_SEPARATOR . 'tests';
		if (!is_dir($testsFolder)) {
			mkdir($testsFolder);
		}

		// Creating a new text file in the writable folder
		$fileName = random_string('alnum', 16) . '.txt';
		$filePath = $testsFolder . DIRECTORY_SEPARATOR . $fileName;

		// Writing some random text in the file
		$randomText = random_string('alnum', 32);
		file_put_contents($filePath, $randomText);

		// Generating a random set of data
		$data = [
			'name' => random_string('alnum', 16) . '.txt',
		];

		$file = new File($filePath);

		// Uploading the file
		$batifyResponse = $M_Batify->addDocument($worksiteID, $data, $file);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the new document ID
		$newDocumentID = $batifyResponse['data'];

		// Retrieving the list of documents
		$batifyResponse = $M_Batify->getFolderContent($worksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		$documentFound = false;

		// Making sure the document is in the list
		foreach ($batifyResponse['data'] as $document) {
			if ($document['id'] == $newDocumentID) {
				$documentFound = true;
				break;
			}
		}

		$this->assertTrue(
			$documentFound,
			__FUNCTION__.'('.__LINE__.') test failed : The document was not found in the list'
		);

		// Retrieving the document
		$batifyResponse = $M_Batify->getDocument($worksiteID, $newDocumentID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure the data is correct
		foreach ($data as $key => $value) {
			$this->assertEquals(
				$value,
				$batifyResponse['data'][$key],
				__FUNCTION__ . '(' . __LINE__ . ') test failed : {UPDATE} The data for ' . $key . ' is different from the one sent'
			);
		}

		// Updating the document
		$data = [
			'name' => random_string('alnum', 16) . '.txt',
		];

		$batifyResponse = $M_Batify->updateDocument($worksiteID, $newDocumentID, $data);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the document
		$batifyResponse = $M_Batify->getDocument($worksiteID, $newDocumentID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure the data is correct
		foreach ($data as $key => $value) {
			$this->assertEquals(
				$value,
				$batifyResponse['data'][$key],
				__FUNCTION__ . '(' . __LINE__ . ') test failed : {UPDATE} The data for ' . $key . ' is different from the one sent'
			);
		}

		// Deleting the document
		$batifyResponse = $M_Batify->deleteDocument($worksiteID, $newDocumentID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the list of documents
		$batifyResponse = $M_Batify->getFolderContent($worksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		$documentFound = false;

		// Making sure the document is not in the list
		foreach ($batifyResponse['data'] as $document) {
			if ($document['id'] == $newDocumentID) {
				$documentFound = true;
				break;
			}
		}

		$this->assertFalse(
			$documentFound,
			__FUNCTION__.'('.__LINE__.') test failed : The document was found in the list'
		);
	}

	// =================================================================================================================
	// =================================================================================================================
	// SUBSCRIBERS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Test the subscribers related endpoints
	 */
	public function testSubscribers()
	{
		$M_Batify = new M_Batify();

		// Retrieving the list of worksites
		$batifyResponse = $M_Batify->getWorksites();

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that at least one worksite exists
		$this->assertGreaterThan(
			0,
			count($batifyResponse['data']),
			__FUNCTION__.'('.__LINE__.') test failed : No worksite found'
		);

		// Retrieving the first worksite ID
		$worksiteID = $batifyResponse['data'][0]['id'];

		// Retrieving the list of subscribers
		$batifyResponse = $M_Batify->getWorksiteSubscribers($worksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that at least one subscriber exists
		$this->assertGreaterThan(
			0,
			count($batifyResponse['data']),
			__FUNCTION__.'('.__LINE__.') test failed : No subscriber found'
		);

		// Retrieving the first subscriber ID
		$subscriberID = $batifyResponse['data'][0]['uid'];

		// Retrieving the subscriber
		$batifyResponse = $M_Batify->getWorksiteSubscriber($worksiteID, $subscriberID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);
	}

	// =================================================================================================================
	// =================================================================================================================
	// COMPANIES (EMPLOYEES)
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Test the companies related endpoints
	 */
	public function testCompanies()
	{
		$M_Batify = new M_Batify();

		// Retrieving the list of employees
		$batifyResponse = $M_Batify->getEmployees();

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that at least one employee exists
		$this->assertGreaterThan(
			0,
			count($batifyResponse['data']),
			__FUNCTION__.'('.__LINE__.') test failed : No employee found'
		);

		// Retrieving the first employee ID
		$employeeID = $batifyResponse['data'][0]['uid'];

		// Retrieving the employee
		$batifyResponse = $M_Batify->getEmployee($employeeID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);
	}

	// =================================================================================================================
	// =================================================================================================================
	// TASKS
	// =================================================================================================================
	// =================================================================================================================

	/**
	 * Test the tasks related endpoints
	 */
	public function testTasks()
	{
		$M_Batify = new M_Batify();

		// Retrieving the list of worksites
		$batifyResponse = $M_Batify->getWorksites();

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that at least one worksite exists
		$this->assertGreaterThan(
			0,
			count($batifyResponse['data']),
			__FUNCTION__.'('.__LINE__.') test failed : No worksite found'
		);

		// Retrieving the first worksite ID
		$worksiteID = $batifyResponse['data'][0]['id'];

		// Retrieving the list of tasks lists
		$batifyResponse = $M_Batify->getTasksLists($worksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Generating a random set of data for the new tasks list
		$newTasksListData = [
			'name' => random_string('alnum', 10),
		];

		// Creating a new tasks list
		$batifyResponse = $M_Batify->createTasksList($worksiteID, $newTasksListData);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the new tasks list ID
		$newTasksListID = $batifyResponse['data'];

		// Retrieving the tasks list
		$batifyResponse = $M_Batify->getTasksList($worksiteID, $newTasksListID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Checking that the tasks list data are correct
		foreach ($newTasksListData as $key => $value) {
			$this->assertEquals(
				$value,
				$batifyResponse['data'][$key],
				__FUNCTION__.'('.__LINE__.') test failed : '.$key.' is not correct'
			);
		}

		// Retrieving the list of users subscribed to the worksite
		$batifyResponse = $M_Batify->getWorksiteSubscribers($worksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that at least one subscriber exists
		$this->assertGreaterThan(
			0,
			count($batifyResponse['data']),
			__FUNCTION__.'('.__LINE__.') test failed : No subscriber found'
		);

		// Retrieving the first subscriber ID
		$subscriberID = $batifyResponse['data'][0]['uid'];

		// Generating a random set of data for the new task
		$newTaskData = [
			'name' 			=> random_string('alnum', 10),
			'description' 	=> random_string('alnum', 10),
			'taggedUsers' 	=> [$subscriberID],
			'startDate' 	=> date('Y-m-d'),
			'endDate' 		=> date('Y-m-d', strtotime('+1 day')),
		];

		// Creating a new task
		$batifyResponse = $M_Batify->createTask($worksiteID, $newTasksListID, $newTaskData);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the new task ID
		$newTaskID = $batifyResponse['data'];

		// Retrieving the task
		$batifyResponse = $M_Batify->getTask($worksiteID, $newTasksListID, $newTaskID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// TODO : Check that all the task data are correct
		unset($newTaskData['taggedUsers']);
		unset($newTaskData['startDate']);
		unset($newTaskData['endDate']);

		// Checking that the task data are correct
		foreach ($newTaskData as $key => $value) {
			$this->assertEquals(
				$value,
				$batifyResponse['data'][$key],
				__FUNCTION__.'('.__LINE__.') test failed : '.$key.' is not correct'
			);
		}

		// Generating a random set of data for the new task
		$newTaskData = [
			'name' 			=> random_string('alnum', 10),
			'description' 	=> random_string('alnum', 10),
			'taggedUsers' 	=> [$subscriberID],
			'startDate' 	=> date('Y-m-d'),
			'endDate' 		=> date('Y-m-d', strtotime('+1 day')),
		];

		// Updating the task
		$batifyResponse = $M_Batify->updateTask($worksiteID, $newTasksListID, $newTaskID, $newTaskData);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the task
		$batifyResponse = $M_Batify->getTask($worksiteID, $newTasksListID, $newTaskID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// TODO : Check that all the task data are correct
		unset($newTaskData['taggedUsers']);
		unset($newTaskData['startDate']);
		unset($newTaskData['endDate']);

		// Checking that the task data are correct
		foreach ($newTaskData as $key => $value) {
			$this->assertEquals(
				$value,
				$batifyResponse['data'][$key],
				__FUNCTION__.'('.__LINE__.') test failed : '.$key.' is not correct'
			);
		}

		// Retrieving the tasks in the list
		$batifyResponse = $M_Batify->getTasks($worksiteID, $newTasksListID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that the task is in the list
		$this->assertContains(
			$newTaskID,
			array_column($batifyResponse['data'], 'id'),
			__FUNCTION__.'('.__LINE__.') test failed : The task is not in the list'
		);

		// Deleting the task
		$batifyResponse = $M_Batify->deleteTask($worksiteID, $newTasksListID, $newTaskID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the tasks in the list
		$batifyResponse = $M_Batify->getTasks($worksiteID, $newTasksListID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that the task is not in the list
		$this->assertNotContains(
			$newTaskID,
			array_column($batifyResponse['data'], 'id'),
			__FUNCTION__.'('.__LINE__.') test failed : The task is in the list'
		);

		// Deleting the tasks list
		$batifyResponse = $M_Batify->deleteTasksList($worksiteID, $newTasksListID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Retrieving the tasks lists
		$batifyResponse = $M_Batify->getTasksLists($worksiteID);

		// Making sure the response is successful
		$this->assertEquals(
			SC_SUCCESS,
			$batifyResponse['status'],
			__FUNCTION__.'('.__LINE__.') test failed : '.$batifyResponse['reason'] ?? 'Unknown'
		);

		// Making sure that the tasks list is not in the list
		$this->assertNotContains(
			$newTasksListID,
			array_column($batifyResponse['data'], 'id'),
			__FUNCTION__.'('.__LINE__.') test failed : The tasks list is in the list'
		);
	}
}
