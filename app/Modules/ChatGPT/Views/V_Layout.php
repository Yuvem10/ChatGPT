<?php
/**
 * @var $content
 */

$hideHeader = $hideHeader ?? false;
?>

<!doctype html>
<html lang="fr">

	<!-- =========================================================================================================== -->
	<!-- =========================================================================================================== -->
	<!-- HEAD -->
	<!-- =========================================================================================================== -->
	<!-- =========================================================================================================== -->

	<head>
		<base href="<?= base_url() ?>/">

		<link rel="icon" href="public/img/tab.png">

		<!-- FONT -->
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

		<!-- CSS -->
		<link href="public/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.1/rr-1.4.1/sb-1.6.0/sp-2.2.0/sl-1.7.0/sr-1.3.0/datatables.min.css" rel="stylesheet">
        <link href="public/css/tail.select/tail.select-default.min.css" rel="stylesheet">
        <link href="public/css/intlTelInput.css" rel="stylesheet">
        <link href="public/css/integration.css?version=3" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

		<!-- JS -->
		<script src="public/js/jquery-3.7.1.min.js"></script>
		<script src="public/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js" integrity="sha256-cVdRFpfbdE04SloqhkavI/PJBWCr+TuyQP3WkLKaiYo=" crossorigin="anonymous"></script>
		<script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.1/rr-1.4.1/sb-1.6.0/sp-2.2.0/sl-1.7.0/sr-1.3.0/datatables.min.js"></script>
		<script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/tail.select.js@0.5.22/js/tail.select.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/tail.select.js@0.5.22/langs/tail.select-all.min.js "></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/fr.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/localization/messages_fr.js"></script>
		<script type="text/javascript" src="public/js/jquery.autocomplete.js"></script>

		<script src="public/js/intlTelInput-jquery.min.js"></script>

		<script src="public/js/funct.js"></script>

		<?= $script ?? '' ?>

		<title><?= $title ?? PROJECT_ID ?></title>
	</head>

	<!-- =========================================================================================================== -->
	<!-- =========================================================================================================== -->
	<!-- BODY -->
	<!-- =========================================================================================================== -->
	<!-- =========================================================================================================== -->

	<body>

		<!-- ======================================================================================================= -->
		<!-- TOAST CONTAINER -->
		<!-- ======================================================================================================= -->

		<div id="toast-container" class="toast-container end-0 p-3"></div>

		<!-- ======================================================================================================= -->
		<!-- GLOBAL MODAL -->
		<!-- ======================================================================================================= -->

		<div class="modal fade" tabindex="-1" id="RF_MODAL">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="RF_MODAL_TITLE"></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body" id="RF_MODAL_BODY">

					</div>
				</div>
			</div>
		</div>

		<!-- ======================================================================================================= -->
		<!-- ======================================================================================================= -->

		<div id="masterContainer">
			<?php if (!$hideHeader): ?>
				<div class="main-content">
					<!-- =================================================================================================== -->
					<!-- HEADER -->
					<!-- =================================================================================================== -->

					<div class="menu-container">
						<div class="menu-logo">
							<a href="<?= base_url() ?>"><img src="<?= PROJECT_LOGO ?>" alt="Logo projet"></a>
						</div>
						<div class="menu-items">
							<?= view(INTEGRATION_BASE_MODULE.'\Views\V_Menu') ?>
						</div>
					</div>

					<div class="right-panel">
						<div class="header-container">
							<button class="menu-toggle-button" onclick="toggleMenu()">
								<span class="material-icons-outlined">menu</span>
							</button>

							<div class="header-user dropdown">
								<div class="header-user-text" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							<span class="header-user-name">
								<span class="material-icons-outlined">expand_more</span>
								<?= strtoupper(session()->get('lastname')).' '.ucfirst(session()->get('firstname')) ?>
							</span>
									<span class="header-user-role">
								<?php foreach(ROLES_ARRAY_STR as $role => $label): ?>
									<?php if (session()->get('roles') & $role): ?>
										<?= $label ?>
										<?php break; ?>
									<?php endif; ?>
								<?php endforeach; ?>
							</span>
								</div>
								<div class="header-user-profile-picture" type="button" data-bs-toggle="dropdown" aria-expanded="false">
									<img src="<?= DEFAULT_AVATAR ?>" alt="">
								</div>
								<ul class="dropdown-menu" style="position: absolute; inset: 0 0 auto auto; margin: 0; transform: translate(-10px, 60px);">
									<li><a class="dropdown-item clickable" onclick="openModal('<?= base_url('Users/'.session()->get('id').'/form') ?>','Modifier mon profil')"><span class="material-icons-outlined">badge</span>Mon profil</a></li>
									<li><a class="dropdown-item" href="<?= base_url('Users/logout') ?>"><span class="material-icons-outlined">logout</span>Se déconnecter</a></li>
								</ul>
							</div>
						</div>

						<!-- =================================================================================================== -->
						<!-- CONTENT -->
						<!-- =================================================================================================== -->

						<div class="container <?= $hideHeader ? 'no-header':'' ?>">
							<?= $content ?>
						</div>
					</div>
				</div>
			<?php else: ?>
				<!-- =================================================================================================== -->
				<!-- CONTENT -->
				<!-- =================================================================================================== -->

				<div class="container <?= $hideHeader ? 'no-header':'' ?>">
					<?= $content ?>
				</div>
			<?php endif; ?>

			<!-- =================================================================================================== -->
			<!-- FOOTER -->
			<!-- =================================================================================================== -->

			<div class="footer-container">
				<!-- PROJECT ID -->
				<a href="<?= base_url() ?>" class="footer-project-id"><?= PROJECT_ID ?></a>
				<!-- ENVIRONMENT -->
				<span class="footer-environment"><?= (strpos(strtolower(base_url()),'localhost') || (strpos(strtolower(base_url()),'preprod'))) ? 'PRE-PRODUCTION':'' ?></span>
				<?php if (!$hideHeader): ?>
				<!-- SUPPORT -->
				<span onclick="openSupportRequestForm()" class="footer-support material-symbols-outlined" title="<?= lang('app.footer.support') ?>">support</span>
				<?php else: ?>
				<span>&nbsp;</span>
				<?php endif; ?>
			</div>
		</div>

		<!-- ======================================================================================================= -->
		<!-- LOADING ANIMATION MODAL -->
		<!-- ======================================================================================================= -->

		<div class="loading-modal" style="z-index: 10000"></div>

	</body>
</html>

<script>
	let 	menu 					= $('.menu-container');
	const 	menuToggle 				= $('.menu-toggle-button');
	const 	baseURL 				= '<?= base_url() ?>';

	// Phone number related inputs' options that will be used with the 'intlTelInput' library
	const 	phoneInputOptions = {
		autoInsertDialCode:true,
		autoPlaceholder:'polite',
		nationalMode:false,
		initialCountry: 'fr',
		preferredCountries: ['fr', 'be', 'de', 'gb',],
		separateDialCode: true,
		formatOnDisplay:true,
		localizedCountries: {
			'fr': 'France',
			'be': 'Belgique',
			'de': 'Allemagne',
			'gb': 'Royaume-Uni',
		},
		utilsScript: '<?= base_url('public/js/utils.js') ?>'
	};

	/**
	 * Format a given phone number
	 * @TODO Implement other countries
	 * @param {string} phoneNumber The phone number to format
	 * @returns {string} The formatted phone number
	 */
	function formatPhoneNumber(phoneNumber)
	{
		let formattedPhoneNumber = phoneNumber;

		if (phoneNumber)
		{
			// @FIXME : This will only work for national prefixes on 2 digits
			let nationalPrefix 	= phoneNumber.substring(0, 3);
			phoneNumber 		= phoneNumber.substring(3);

			switch (nationalPrefix)
			{
				case '+31':
					// Adding the national prefix
					formattedPhoneNumber = nationalPrefix + ' ';

					// Separating the digits by groups of 3
					formattedPhoneNumber += phoneNumber.replace(/.(\d{3})/g, '$1 ');
					break;
				case '+32':
				case '+33':
				case '+34':
					// Adding the national prefix
					formattedPhoneNumber = nationalPrefix + ' ';

					// Adding the first digit
					formattedPhoneNumber += phoneNumber.substring(0,1) + ' ';

					// Separating the remaining digits by groups of 2
					formattedPhoneNumber += phoneNumber.substring(1).replace(/.(\d{2})/g, '$1 ');
					break;
			}
		}

		return formattedPhoneNumber;
	}

	/**
	 * Handle the rendering of the phone number column in the datatables
	 * @param data Cell data
	 * @param type Render type
	 * @param row Row data
	 * @param meta Row metadata
	 * @returns {*|jQuery}
	 */
	function dtPhoneRender(data, type, row, meta)
	{
		let ret = data;

		if (type === 'display') {
			if (<?= DATATABLE_DISPLAY_PHONE_NUMBER_AS_LINK ? 'true':'false' ?>) {
				// If the data is being displayed, we format it with a "tel:" link
				let telLink = $('<a></a>');
				telLink.attr('href', 'tel:' + data);
				telLink.text(formatPhoneNumber(data));
				ret = telLink.prop('outerHTML');
			} else {
				// If the data is being displayed, we format it with the "formatPhoneNumber" function
				ret = formatPhoneNumber(data);
			}
		}

		return ret;
	}

	/**
	 * Function responsible for triggering the formatting of the phone number inputs associated with the given selector
	 */
	function triggerPhoneNumberFormatting(selector = 'input[type="tel"]')
	{
		$(selector).each(function(){
			let input 		= $(this);
			let form 		= input.closest('form');

			form.on('submit', function(){
				let intlNumber 	= input.intlTelInput('getNumber');

				// Setting the formatted value of the input
				input.val(intlNumber);
			});
		});
	}

	/**
	 * Function responsible for initializing the phone number inputs associated with the given selector
	 * @param selector
	 */
	function initPhoneNumberInputs(selector = 'input[type="tel"]')
	{
		$(selector).each(function(){
			let input = $(this);

			// Destroying the intlTelInput instance if it already exists
			input.intlTelInput('destroy');

			// Initializing the phone number input
			input.intlTelInput(phoneInputOptions);
		});

		triggerPhoneNumberFormatting(selector);
	}

	// Set to "true" to prevent the loading animation displayed when sending ajax requests
	let disableLoadingAnimation = false;

	// Toast index used to determine at which position a new toast should be displayed
	let toastIndex = 0;

	$(document).ready(function () {
		<?php if(session()->has('toast')): ?>
			displayToast("<?= session()->get('toast')['title'] ?>","<?= session()->get('toast')['message'] ?>",<?= session()->get('toast')['type'] ?>);
			<?php session()->remove('toast'); ?>
		<?php endif; ?>

		<?php if (session()->has('toasts') && !empty(session()->get('toasts'))): ?>
			<?php foreach (session()->get('toasts') as $toast): ?>
				displayToast("<?= $toast['title'] ?>","<?= $toast['message'] ?>",<?= $toast['type'] ?>);
			<?php endforeach; ?>
			<?php session()->remove('toasts'); ?>
		<?php endif; ?>

		<?php if (session()->get(SESSION_KEY_LOGGED_IN)): ?>
		// Set the initial state of the menu
		if (
			(localStorage.getItem('menuExpanded') === 'true')
			||
			// If the menu state is not set in the local storage, we set it to "true" by default
			(
				(localStorage.getItem('menuExpanded') !== 'false')
				&&
				(localStorage.getItem('menuExpanded') !== 'true')
			)
		) {
			// Disabling the transition animation for the first toggle
			menu.addClass('menu-no-animation');
			toggleMenu();
		}
		<?php endif; ?>
	})

	/**
	 * Toggle the menu's expansion
	 */
	function toggleMenu() {
		menu.toggleClass('expanded');

		if (menu.hasClass('expanded'))
		{
			menuToggle.attr('title', 'Réduire le menu');
			menuToggle.html('<span class="material-icons-outlined">menu_open</span>');
		}
		else {
			menuToggle.attr('title', 'Développer le menu');
			menuToggle.html('<span class="material-icons-outlined">menu</span>');
		}

		// Save the state of the menu in the local storage for the next page loading
		localStorage.setItem('menuExpanded', menu.hasClass('expanded'));

		// The 'menu-no-animation' class is used to prevent the menu from animating when the page is loaded
		// It is removed after the first toggle
		if (menu.hasClass('menu-no-animation')) {
			// A timeout is needed in order to prevent a subtle "jump" when the menu is expanded for the first time without animation
			setTimeout(function () {
				menu.removeClass('menu-no-animation');
			}, 500);
		}
	}

	$(document).on({
		fetchStart: function () {
			if (!disableLoadingAnimation) {
				$('body').addClass("loading");
			}
		},
		ajaxStart: function () {
			if (!disableLoadingAnimation) {
				$('body').addClass("loading");
			}
		},
		ajaxStop: function () {
			$('body').removeClass("loading");
		}
	});

	/**
	 * Function responsible for displaying the support request form modal
	 */
	function openSupportRequestForm()
	{
		openModal('<?= base_url('Mantis/displayIssueForm') ?>','Signaler un problème');
	}
</script>
