<?php

// =====================================================================================================================
// =====================================================================================================================
// GLOBAL CONSTANTS
// =====================================================================================================================
// =====================================================================================================================

// Project name
const PROJECT_ID = 'ChatGOT';

// Logos
const PROJECT_LOGO = 'public/img/logo.png';
const PROJECT_LOGO_MAINTENANCE = 'public/img/logo-red.png';

// Default language
const LANGUAGE = 'french';

// Default avatar that will be used if the user has not set one
const DEFAULT_AVATAR = 'public/img/avatar.svg';

// =====================================================================================================================
// =====================================================================================================================
// PUBLIC FILES REFERENCES
// =====================================================================================================================
// =====================================================================================================================

const PUBLIC_FOLDER_PATH = ROOTPATH . 'public' . DIRECTORY_SEPARATOR;

// =====================================================================================================================
// =====================================================================================================================
// VIEW RELATED FILE REFERENCES
// =====================================================================================================================
// =====================================================================================================================

const HOME_PAGE = INTEGRATION_BASE_MODULE . '\Views\V_Home';
const LOGIN_PAGE = INTEGRATION_BASE_MODULE . '\Views\V_Login';
const REGISTER_PAGE = 'RFCore\Views\Users\V_UserRegisterForm';

// General menu view
const VIEW_MENU = INTEGRATION_BASE_MODULE . '\Views\V_Menu';

// Password reset related views
const FORM_FORGOTTEN_PWD_STEP_1 = INTEGRATION_BASE_MODULE . '\Views\Users\V_UserForgottenPwdStep1';
const FORM_FORGOTTEN_PWD_STEP_2 = INTEGRATION_BASE_MODULE . '\Views\Users\V_UserForgottenPwdStep2';
const FORM_FORGOTTEN_PWD_STEP_3 = INTEGRATION_BASE_MODULE . '\Views\Users\V_UserForgottenPwdStep3';
const FORM_FORGOTTEN_PWD_STEP_4 = INTEGRATION_BASE_MODULE . '\Views\Users\V_UserForgottenPwdStep4';

const FORM_NEW_PWD = INTEGRATION_BASE_MODULE . '\Views\Users\V_UserNewPwdForm';

// List of URLs accessible without being logged in (without an existing user session)
const AUTHORIZED_URLS_NO_SESSION = [
	LOGIN_PAGE,
	HOME_PAGE,
	REGISTER_PAGE,
	FORM_FORGOTTEN_PWD_STEP_3,
	FORM_FORGOTTEN_PWD_STEP_4,
	FORM_NEW_PWD,
	INTEGRATION_BASE_MODULE. '\Views\ChatGPT\V_ChatGPT',
];

// =====================================================================================================================
// =====================================================================================================================
// TOAST
// =====================================================================================================================
// =====================================================================================================================

// Toast type values
$index = 1;
define('TOAST_OK', $index++);
define('TOAST_ERROR', $index++);
define('TOAST_DEFAULT', $index++);

// Default delay in ms before a toast is hidden
const TOAST_DEFAULT_DELAY = 5000;
const TOAST_REFRESHING_DELAY = 5;

// =====================================================================================================================
// =====================================================================================================================
// USER ROLES
// =====================================================================================================================
// =====================================================================================================================

// Roles binary value
const ROLE_USER 							= 1;
const ROLE_ADMIN 							= ROLE_USER << 1;

const ROLE_FRANCHISE_MANAGER 				= ROLE_ADMIN << 1;
const ROLE_FRANCHISE_STOCK_MANAGER 			= ROLE_FRANCHISE_MANAGER << 1;
const ROLE_FRANCHISE_SALESMAN 				= ROLE_FRANCHISE_STOCK_MANAGER << 1;
const ROLE_FRANCHISE_WORKSITE_TECHNICIAN 	= ROLE_FRANCHISE_SALESMAN << 1;

const ROLE_FRANCHISOR_ADMIN 				= ROLE_FRANCHISE_WORKSITE_TECHNICIAN << 1;
const ROLE_FRANCHISOR_STOCK_MANAGER 		= ROLE_FRANCHISOR_ADMIN << 1;
const ROLE_FRANCHISOR_MARKETING 			= ROLE_FRANCHISOR_STOCK_MANAGER << 1;
const ROLE_FRANCHISOR_NETWORK_ANIMATOR 		= ROLE_FRANCHISOR_MARKETING << 1;

// Roles associated labels
const ROLES_ARRAY_STR = [
	ROLE_ADMIN 							=> 'Super administrateur',        	// 2
	ROLE_USER 							=> 'Utilisateur', 					// 1
];

// =====================================================================================================================
// =====================================================================================================================
// COMFOX + EMAILS RELATED CONSTANTS
// =====================================================================================================================
// =====================================================================================================================

// Indicates to methods using the ComFox module if it is available
const COMFOX_AVAILABLE = TRUE;

// Route used for sending account verification emails
const ROUTE_VERIF_EMAIL_ACCOUNT = 'userVerifAccount';

// Registration confirmation email content
const INTEGRATION_REGISTER_EMAIL_TITLE = PROJECT_ID . ': Validation de votre compte';
const INTEGRATION_REGISTER_EMAIL_MESSAGE = '<html lang="fr"><body>Bonjour<br/>Merci de cliquer sur le lien suivant pour valider votre compte :<br/>';

// Default signature used for emails
const INTEGRATION_EMAIL_SIGNATURE = 'L\'équipe ' . PROJECT_ID;

// Support contact email address
const EMAIL_SUPPORT = 'projet@ingefox.com'; // TODO - Update before production deployment

// Password reset related constants
const INTEGRATION_RENEW_PWD_USER_REQUEST = 1;
const INTEGRATION_RENEW_PWD_MANAGER_REQUEST = INTEGRATION_RENEW_PWD_USER_REQUEST + 1;

// Password reset + update routes
const ROUTE_UPDATE_PWD_ACCOUNT = 'Users/forgottenPassword';
const ROUTE_NEW_PWD_ACCOUNT = 'Users/newPassword';

// Forgotten password emails content
const INTEGRATION_UPDATE_PWD_EMAIL_TITLE = PROJECT_ID . ' : Mot de passe oublié';
const INTEGRATION_UPDATE_PWD_EMAIL_MESSAGE = INTEGRATION_BASE_MODULE . '\Views\Users\V_UserForgottenPwdEmail';

// User account registration / activation emails content
const INTEGRATION_NEW_PWD_EMAIL_TITLE = PROJECT_ID . ' : Inscription';
const INTEGRATION_NEW_PWD_EMAIL_MESSAGE = INTEGRATION_BASE_MODULE . '\Views\Users\V_UserActivationEmail';

// User account creation confirmation emails content (new account)
const INTEGRATION_UPDATE_PWD_EMAIL_TITLE_NEW_USER = PROJECT_ID . ' : Nouveau Compte';
const INTEGRATION_UPDATE_PWD_EMAIL_MESSAGE_NEW_USER = '<html lang="fr"><body>Bonjour<br/>Merci de cliquer sur le lien suivant pour valider votre nouveau compte ' . PROJECT_ID . ' :<br/>';

// Authentication action used in verification emails
const AUTH_ACTION_EMAIL_VERIFY = 'verifyEmail';
const AUTH_ACTION_RESET_PASSWORD = 'resetPassword';
const AUTH_ACTION_ = '';

// =====================================================================================================================
// =====================================================================================================================
// USER SESSIONS RELATED CONSTANTS
// =====================================================================================================================
// =====================================================================================================================

const SESSION_REMEMBER_ME 		= 'SESSION_REMEMBER_ME';
const SESSION_FRANCHISE_ID 		= 'SESSION_FRANCHISE_ID';
const SESSION_FRANCHISE_ENABLED = 'SESSION_FRANCHISE_ENABLED';
const SESSION_SITE_ID 			= 'SESSION_SITE_ID';
const SESSION_IS_ACTIVE			= 'isActive';

// =====================================================================================================================
// =====================================================================================================================
// STATUS CODE
// =====================================================================================================================
// =====================================================================================================================

const SC_INTEGRATION_START = SC_REDFOX_MAX_VALUE + 1;
const SC_INTEGRATION_ERROR = SC_INTEGRATION_START + 1;

// User related errors status codes
const SC_INTEGRATION_USER_ALREADY_EXIST = SC_INTEGRATION_ERROR + 1;
const SC_INTEGRATION_USER_UNKNOWN = SC_INTEGRATION_USER_ALREADY_EXIST + 1;
const SC_INTEGRATION_USER_UPDATE_ERROR = SC_INTEGRATION_USER_UNKNOWN + 1;
const SC_INTEGRATION_USER_DISABLE = SC_INTEGRATION_USER_UPDATE_ERROR + 1;

// Email related errors status codes
const SC_INTEGRATION_EMAIL_SEND_ERROR = SC_INTEGRATION_USER_DISABLE + 1;
const SC_INTEGRATION_CHECK_BAD_TOKEN = SC_INTEGRATION_EMAIL_SEND_ERROR + 1;
const SC_INTEGRATION_DB_UPDATE_PROBLEM = SC_INTEGRATION_CHECK_BAD_TOKEN + 1;

const DATATABLE_DISPLAY_PHONE_NUMBER_AS_LINK = false;
