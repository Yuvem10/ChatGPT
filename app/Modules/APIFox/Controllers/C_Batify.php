<?php

namespace APIFox\Controllers;

use APIFox\Models\M_Batify;
use CodeIgniter\HTTP\DownloadResponse;
use CodeIgniter\HTTP\RedirectResponse;
use Exception;
use RFCore\Controllers\RF_Controller;

class C_Batify extends RF_Controller
{
	/**
	 * Attempt to download a specific document through the Batify API
	 * @param string $worksiteID The ID of the worksite associated with the document
	 * @param string $documentID The ID of the document to download
	 * @return RedirectResponse|DownloadResponse|null Either a download response or a redirect response to the 404 page
	 */
	public function downloadDocument(string $worksiteID, string $documentID): ?RedirectResponse
	{
		$ret = redirect()->to(base_url('404'));

		try {
			$M_Batify 		= new M_Batify();
			$batifyResponse = $M_Batify->downloadDocument($worksiteID, $documentID);

			if ($batifyResponse['status'] == SC_SUCCESS)
			{
				$ret = $this->response->download($batifyResponse['data']['filename'], $batifyResponse['data']['content']);
			}
		}
		catch (Exception $e) {
			log_message('error', __CLASS__ . '::' . __FUNCTION__ . ' : ' . $e);
		}

		return $ret;
	}
}
