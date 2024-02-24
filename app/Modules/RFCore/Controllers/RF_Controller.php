<?php
namespace RFCore\Controllers;

use CodeIgniter\Controller;
use Exception;

class RF_Controller extends Controller
{
    public function __construct()
    {
        helper("security");
    }

	/**
	 * Function responsible for getting the list of countries
	 * @see https://data.enseignementsup-recherche.gouv.fr/explore/dataset/curiexplore-pays/export/?flg=fr-fr&disjunctive.iso3&sort=iso3
	 * @return array
	 */
	public function getCountryList():array
	{
		$ret = [];

		try {
			if (file_exists(COUNTRY_LIST_FILE)){
				$JSON = file_get_contents(COUNTRY_LIST_FILE);
				$JSON = json_decode($JSON, true);

				foreach ($JSON as $country)
				{
					$ret[$country['name_fr']] = $country['name_fr'];
				}

				asort($ret);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . ' : ' . $e);
		}

		return $ret;
	}

    public function __RTM($className, $functionName){

        $bench= \Config\Services::timer();
        // Get the current URL
        $currentURL = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Get the elapsed time
        $duration = $bench->getElapsedTime('controller');
        // echo "Elapsed Time: " . $duration . " seconds";

        log_message('info', 'RTM ++ '. $className . ' ++ ' . $functionName . ' ++ ' . $duration .' ++ '. $currentURL);
    }

	/**
	 * Function responsible for displaying a toast message to the user and saving a redirection url in the session
	 * @return void
	 */
	public function handleNoSessionEvent()
	{
		// Display an error toast if the user is not logged in
		$toasts = session()->get('toasts') ?? [];
		$toasts[] = [
			'type' 		=> TOAST_ERROR,
			'title' 	=> 'Erreur',
			'message' 	=> 'Vous devez être connecté pour accéder à cette page',
		];
		session()->set('toasts', $toasts);

		// Save the current url in the session to redirect the user to it after login
		session()->set('redirect', current_url());
	}

	/**
	 * Return a formatted array usable with the JQuery/JS method displayErrors()
	 * @param $errors array A set of field names with the failed rules (ex: [ 'user[email]' => [ 'required', 'email' ] ] )
	 * @return array
	 */
	public function formatFormErrors(array $errors): array
	{
		$ret = [];

		// Iterating through each field, identified by their "name" attribute value
		foreach ($errors as $name => $rules)
		{
			$fieldErrors = [];

			// Formatting each failed rule
			foreach ($rules as $rule)
			{
				$fieldErrors[] = [
					'result' 	=> false,
					'rule'		=> $rule
				];
			}

			// Adding the formatted errors to the final array
			$ret[] = [
				'name' 				=> $name,
				'validationArray' 	=> $fieldErrors
			];
		}

		return $ret;
	}

    /**
     * Return all the defined roles in an array object
     * @return array Roles defined as constants
     */
    public function getRolesArray(){
        $array = get_defined_constants(true)['user'];
        $only_roles = array();
        foreach ($array as $key => $value) {
            if (strpos($key, 'ROLE_') === 0) {
                $only_roles[$key] = $value;
            }
        }
        return $only_roles;
    }

    /**
     * Return a list of checkboxes corresponding to the available roles
     * @param int $roles If passed, all checkboxes matching these roles will be checked
     * @return array
     */
    public function getRolesArrayCB($roles = 0){
        $rolesCB = [];
        foreach (ROLES_ARRAY_STR as $key=>$value){
            $role = array();
            $role['value'] = $key;
            $role['label'] = $value;
            $role['checked'] = ($roles & $key);
//            $role['disabled'] = !(session()->get('roles') & $key);
            $rolesCB[] = $role;
        }
        return $rolesCB;
    }


    /**
     * Convert the mimeType to a file extension
     * @param string $mime
     * @return string the file extension associated to the mimeType
     */
    public function mimeTypeToFileExtension($mime)
    {
        return isset(MIME_MAP[$mime]) ? MIME_MAP[$mime] : '';
    }
}
