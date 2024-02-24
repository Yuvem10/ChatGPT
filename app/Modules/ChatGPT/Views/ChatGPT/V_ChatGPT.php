<?php
/**
 * @var $newSessionID string
 */
?>
<head>
	<style>

		.chat-messages {
			display: flex;
			flex-direction: column;
			overflow-y: scroll;
			scrollbar-color: #212121 #212121;
			scrollbar-width: initial;
			height: calc(100vh - 100px);
			max-height: calc(100vh - 100px);
		}

		.chat-message-left,
		.chat-message-right {
			display: flex;
			flex-shrink: 0;
		}

		.chat-message-left {
			margin-right: auto;
		}

		.chat-message-right {
			flex-direction: row-reverse;
			margin-left: auto
		}
		.py-3 {
			padding-top: 1rem!important;
			padding-bottom: 1rem!important;
		}
		.px-4 {
			padding-right: 1.5rem!important;
			padding-left: 1.5rem!important;
		}
		.flex-grow-0 {
			flex-grow: 0!important;
		}

		.material-symbols-outlined{
			border-radius: 100%;
		}
		.bg-primary {
			background-color: #147efb!important;
			color: white;
			border-radius: 2%!important;
			margin-right: 10px;
			place-self: center;
			padding: 12.5px;
		}
		.bg-secondary {
			background-color: #333336!important;
			color: white;
			border-radius: 2%!important;
			margin-left: 10px;
			place-self: center;
			padding: 12.5px;
		}

		::placeholder {
			color: white !important;
		}


		#inputsend{
			margin-left: 20px;
			z-index: 1;
			border-color: #9b9b9b!important;
			font-weight: normal!important;
			color: white;
			height: 50px;
			border-right: none;
			border-radius: 0%!important;
			background-color: #212121!important;
		}

		#send{
			height: 50px;
			border-left: none;
			background-color: #212121;
			border-color: #9b9b9b!important;
			margin-right: 20px;
		}
		#send:hover{
			color: white!important;

		}
		#spansend{
			font-size: 35px;
		}
		.form-control:focus{
			box-shadow: none;
		}

		.chat-app .people-list {
			width: 280px;
			position: absolute;
			left: 0;
			top: 0;
			padding: 20px;
			z-index: 7
		}

		.people-list {
			-moz-transition: .5s;
			-o-transition: .5s;
			-webkit-transition: .5s;
			transition: .5s
		}
		.people-list .chat-list li {
			padding: 10px 15px;
			list-style: none;
			border-radius: 3px;
			color: white;
		}
		.people-list .chat-list li:hover {
			background: #424242;
			cursor: pointer;
		}
		.people-list .chat-list li .name {
			font-size: 15px
		}
		.people-list .chat-list img {
			width: 45px;
			border-radius: 50%;
		}

		.people-list img {
			float: left;
			border-radius: 50%
		}

		.people-list .about {
			float: left;
			padding-left: 8px;
		}

		.people-list .status {
			color: #676767;
			font-size: 13px
		}
		.chat-app .people-list.open {
			left: 0
		}
		#containerDiv{
			display: flex;
			background-color: #212121;
			position: absolute;
			top: 0px;
			left: 0px;
			right: 0px;
			bottom: 0px;
		}
		.card{
			border: none;
		}
		.people-list, #chat{
			flex: 1;
		}
		.people-list{
			max-width: 300px;
			background-color: #171717;
			display: flex;
			flex-direction: column;
			height: 100%;

		}
		.card{
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 9999;
		}
		.content{
			zoom: 90%;
			overflow-y: hidden; /* Hide vertical scrollbar */
			overflow-x: hidden;

		}
		::-webkit-scrollbar {
			display: none;
			overflow: hidden;
		}
		.new{
			display: flex;
			padding-left: 20px;
			padding-top: 20px;
			padding-bottom: 20px;
			padding-right: 20px;
		}
		#add{
			background-color: #171717;
			color: white;
		 }
		.newconv{
			color: white;
			margin-left: 10px;
			align-self: center;
		}
		.new:hover{
			background-color: white!important;
			#add{
				background-color: white;
				color: black;
			}
			.newconv{
				color: black;
			}
		}
		.chat-list{
			height: 100%;
			overflow-x: scroll;
		}

		.bin {
			margin-left: 110px;
			align-self: center;
		}
		#btnBin{
			color: white;
			border: none;
			border-radius: 5%;
			opacity: 0%;
		}
		#spanBin{
			font-size: 20px;
			align-self: center;
			margin-top: 5px;
		}


		#user{
			padding: 3px;
			background-color: white;
			color: black;
			font-size: 35px;
			font-weight: 100;
		}
		#ia{
			padding: 3px;
			background-color: white;
			color: black;
			font-size: 35px;
			font-weight: 100;
		}
		.containerDelete{
			display: flex;
			justify-content: center;
			margin-top: auto;
		}
		#search{
			width: 225px;
			height: 35px;
			border-color: #9b9b9b!important;
			font-weight: normal!important;
			color: white;
		}
		.navbar{
			background-color: #171717!important;
			padding-left: 15px;
			padding-right: 15px;
			padding-top: 15px;
			padding-bottom: 15px;
			border-bottom: #333336 1px solid;
		}
		#form{
			display: flex;
			align-items: center;
			height: 25px;
		}
		#spanSearch{
			color: white;
			background-color: #171717;
			font-size: 30px;
			border-radius: 0%!important;
			height: 35px;
			margin-right: 5px ;
		}
		.clearfix{

		}
	</style>
</head>


<main class="content">

<div class="container">

		<div class="card">

			<div class="row g-0" id="containerDiv">

				<div id="plist" class="people-list">

					<div class="new">
						<span id="add" class="material-symbols-outlined">edit_square</span>
						<div class="newconv" onclick="newConversation()">Nouvelle conversation</div>
					</div>
					<nav class="navbar navbar-light bg-light">
						<form class="form-inline" id="form">
							<span id="spanSearch" class="material-symbols-outlined">search</span>
							<input  id="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
						</form>
					</nav>

					<ul class="list-unstyled chat-list">

					</ul>
				</div>

				<div class="col-12" id="chat">

					<div class="py-2 px-4 d-none d-lg-block" >
						<div class="d-flex align-items-center py-1">
							<div class="flex-grow-1 pl-3" style="">ChatGPT
							</div>
						</div>
					</div>

					<div class="position-relative">
						<div class="chat-messages p-4">
						</div>
					</div>

					<div class="flex-grow-0 py-3 px-4" id="chatbar">
						<div class="input-group mb-3">
							<input type="text" id="inputsend" class="form-control" placeholder="Message" aria-label="Message" aria-describedby="basic-addon2" autofocus>
							<div class="input-group-append">
							<button class="btn btn-outline-light" id="send" onclick="createChatCompletion()"><span id="spansend" class="material-symbols-outlined">send</span></button>
							</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<script>

	// variables
	let sessionList;
	let inputsend = $("#inputsend");
	let sessionID = "<?= $newSessionID ?>";
	let newSession = true;


	$(function () {
		/**
		 * Send the message when the user presses the Enter key
		 */
		inputsend.on('keyup', function (e) {
			if (e.key === 'Enter' || e.keyCode === 13) {
				createChatCompletion();
			}
		});


		getChatCompletionSessions();
		createFirstMessage("Bonjour ! Comment puis-je vous aider ?");

		// for each click on clearfix class
		$(".people-list").on('click', 'li.clearfix', function () {
			sessionID = $(this).attr('id');
			loadChatCompletionSession(sessionID);
			highlightCurrentSession(sessionID);
		});
	})


	/**
	 * Create a new conversation
	 */
	function newConversation()
	{
		newSession = true;

		// disabled the loading animation
		disableLoadingAnimation = false;

		// ajax request to retrieve the empty file, route = /ChatGPT/sessions
		$.ajax({
			url: '<?= base_url('sessions') ?>',
			type: 'POST',
			dataType: 'json',
			success: function (response) {
				if (response['status'] === <?= SC_SUCCESS ?>)
				{
					// Retrieve the session ID
					sessionID = response['data'];

					// Clear the chat
					$(".chat-messages").empty();

					// Display the first message
					createFirstMessage("Bonjour ! Comment puis-je vous aider ?");

					// autofocus on the input
					document.getElementById("inputsend").focus();

					// remove the highlight from the other sessions
					$(".chat-list li").css("background-color", "transparent");

				}
			},
			error: function (response) {
				let json 	= response['responseJSON'];
				let reason 	= json['reason'] ?? 'Une erreur est survenue';
				console.log(reason);
			}
		});
		disableLoadingAnimation = true;
	}

	/**
	 * Delete the empty files
	 */
	function deleteEmptyFiles(sessionList)
	{
		// deletes the item in sessionList if the role === "system"
		for (var item in sessionList)
		{
			let sessionData = sessionList[item];
			if (sessionData['role'] === "system")
			{
				deleteSession(sessionData['id'], true);
				delete sessionList[item];
			}
		}
	}

	/**
	 * delete all the sessions
	 */
	function deleteAllSessions()
	{
		// ask to the user if he wants to delete all the sessions
		if (confirm("Voulez-vous vraiment supprimer toutes les conversations ?")) {
			// delete all the sessions
			for (var item in sessionList)
			{
				let sessionData = sessionList[item];
				deleteSession(sessionData['id'], true);
			}
			location.reload();
		}
	}

	/**
	 * Ask Chat GPT a question and display the answer
	 */
	function createChatCompletion()
	{
		// Retrieve the question from the input
		let message = document.getElementById("inputsend").value;

		// Display the question
		createQuestion(message);

		// Display the placeholder
		displayPlaceholder();

		// Clear the input
		document.getElementById("inputsend").value = "";

		disableLoadingAnimation = true;

		// Send the Ajax request to the server (POST : /ChatGPT/createCompletion)
		$.ajax({
			url: '<?= base_url('createCompletion') ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				question: message,
				sessionID: sessionID
			},
			success: function (response) {
				if (response['status'] === <?= SC_SUCCESS ?>) {
					createAnswer(response['data']['choices'][0]['message']['content'], false);
					newSession = false;
					getChatCompletionSessions();
				}else{
					console.log("IL Y A UNE ERREUR ICI");
				}
			},
			error: function (response) {
				let json 	= response['responseJSON'];
				let reason 	= json['reason'] ?? 'Une erreur est survenue';
				createAnswer(reason, true);
			}
		});

		disableLoadingAnimation = false;
	}

	/**
	 * Display the question
	 * @param message
	 */
	function createQuestion(message) {
		// Retrieving the current time
		let time = moment().format('HH:mm');
		var newQuestion = "<div class=\"chat-message-right mb-4\"><div><span id=\"user\" class=\"material-symbols-outlined\">person</span><div class=\" small text-nowrap mt-2\" style=\"color:white;\">"+time+"</div> </div> <div class=\"flex-shrink-1 bg-primary rounded px-3 align-self-baseline\"><div style=''>"+message+"</div></div> </div>";
		$(".chat-messages > div:last").after(newQuestion);
		showNewMessage();
	}

	/**
	 * Display the answer from Chat GPT
	 * @param message
	 */
	function createAnswer(message, error) {

		if (error){
			$("#placeholder").remove();
			let time = moment().format('HH:mm');
			var newAnswer = "<div class=\"chat-message-left pb-4\"> <div> <span class=\"material-symbols-outlined\" id=\"ia\">smart_toy</span> <div class=\" small text-nowrap mt-2\" style=\"color: white\">"+time+"</div> </div> <div class=\"flex-shrink-1 bg-secondary rounded px-3 align-self-baseline\"><div style=''>"+message+"</div></div> </div>";
			$(".chat-messages > div:last").after(newAnswer);
			showNewMessage();
		}
		else {
			// remove the placeholder id if exists
			$("#placeholder").remove();
			let time = moment().format('HH:mm');
			var newAnswer = "<div class=\"chat-message-left pb-4\"> <div> <span class=\"material-symbols-outlined\" id=\"ia\">smart_toy</span> <div class=\" small text-nowrap mt-2\" style=\"color:white;\">"+time+"</div> </div> <div class=\"flex-shrink-1 bg-secondary rounded px-3 align-self-baseline\"><div style=''>"+message+"</div></div> </div>";
			$(".chat-messages > div:last").after(newAnswer);
			showNewMessage();
		}
	}

	/**
	 * Display the first message
	 * @param message
	 */
	function createFirstMessage(message)
	{

		let time = moment().format('HH:mm');
		var newAnswer = "<div class=\"chat-message-left pb-4\"> <div> <span class=\"material-symbols-outlined\" id=\"ia\">smart_toy</span> <div class=\" small text-nowrap mt-2\"style=\"color:white;\">"+time+"</div> </div> <div class=\"flex-shrink-1 bg-secondary rounded px-3 align-self-baseline \"><div style=''>"+message+"</div></div> </div>";
		$(".chat-messages").append(newAnswer);
		showNewMessage();
	}

	/**
	 * Display a placeholder
	 */
	function displayPlaceholder(){
		let time = moment().format('HH:mm');
		var newAnswer = "<div id=\"placeholder\" class=\"chat-message-left pb-4\"> <div> <span class=\"material-symbols-outlined\" id=\"ia\">smart_toy</span> <div class=\" small text-nowrap mt-2\">"+time+"</div> </div> <div class=\"flex-shrink-1 bg-secondary rounded py-2 px-3 align-self-baseline\"><div><img src=\"<?= base_url('public/img/32.gif')?>\" style=\"\" alt=\"loading\" alt=\"loading\"></div></div> </div>";
		$(".chat-messages > div:last").after(newAnswer);
		showNewMessage();
	}

	/**
	 * Scroll to the bottom of the chat with animation
	 */
	function showNewMessage()
	{
		$('.chat-messages').animate({scrollTop: $('.chat-messages').prop("scrollHeight")}, 1000);
	}

	/**
	 * Retrieve all sessions from the server
	 * @returns {Promise<void>}
	 */
	function getChatCompletionSessions()
	{
		disableLoadingAnimation = true;

		$.ajax({
			url: '<?= base_url('sessions') ?>',
			type: 'GET',
			dataType: 'json',
			success: function (response) {
				if (response['status'] === <?= SC_SUCCESS ?>) {
					sessionList = response['data'];

					// Converting the object to an array
					sessionList = Object.values(sessionList);

					// Sorting the sessions by date
					sessionList.sort(function(a, b) {
						var dateA = convertDate(a.date);
						var dateB = convertDate(b.date);
						return dateB - dateA;
					});

					// Clear the chat list
					$(".chat-list").empty();


					// clear the empty sessions
					deleteEmptyFiles(sessionList);

					// remove the button if it exists
					$("#deleteAll").remove();

					// Displaying the sessions
					for (var item in sessionList)
					{
						let sessionData = sessionList[item];
						let onglet;

						if(!empty(sessionData)){
							// Display the session
							let shortMessage = sessionData['last_message_user'];
							if (shortMessage.length > 10){
								shortMessage = shortMessage.substring(0, 10);
								shortMessage = shortMessage + "...";
							}
							onglet = "<li class=\"clearfix\" id=\"" + sessionData['id'] + "\"> <div class=\"about\">   <div class =\"containerGlobal\"  style='display: flex'><div class=\"containerName\"> <div class=\"name\">" + "Vous : "+ shortMessage + "</div>      <div class=\"status\"> <i class=\"fa fa-circle offline\"></i>" + sessionData['date'] + "</div> </div>      <div class=\"bin\"><button id=\"btnBin\" onclick=\"deleteSession('"+sessionData['id']+"',false)\" class=\"btn btn-danger\"><span id=\"spanBin\" class=\"material-symbols-outlined\">delete</span></button></div></div> </li>";
							$(".chat-list").append(onglet);
						}
						// highlight the current session
						if (sessionData['id'] === sessionID){
							highlightCurrentSession(sessionID);
						}
					}
					let i = 0;
					for (var item in sessionList)
					{
						i++;
					}
					if(i>1 && $("#deleteAll").length === 0){
						$(".people-list").append("<div class=\"containerDelete\"><button id=\"deleteAll\" onclick=\"deleteAllSessions()\" type=\"button\" class=\"btn btn-outline-danger\">Tout Supprimer</button></div>");
					}
				}
			},
			error: function (response) {
				let json 	= response['responseJSON'];
				let reason 	= json['reason'] ?? 'Une erreur est survenue';
				console.log(reason);
				sessionList = "erreur";
				console.log(sessionList);
			}
		});
	}

	/**
	 * Convert a date string to a Date object
	 * @param dateString
	 * @returns {Date}
	 */
	function convertDate(dateString) {
		var dateParts = dateString.split(' ')[0].split('/');
		var timeParts = dateString.split(' ')[1].split(':');
		var year = parseInt(dateParts[2], 10);
		var month = parseInt(dateParts[1], 10) - 1;
		var day = parseInt(dateParts[0], 10);
		var hours = parseInt(timeParts[0], 10);
		var minutes = parseInt(timeParts[1], 10);
		var seconds = parseInt(timeParts[2], 10);
		return new Date(year, month, day, hours, minutes, seconds);
	}

	/**
	 * highlight the current session
	 */
	function highlightCurrentSession(sessionID){
		// highlight the current session
		$("#"+sessionID+"").css("background-color", "#424242");

		// remove the highlight from the other sessions
		$(".chat-list li").not("#"+sessionID+"").css("background-color", "transparent");

		// set the opacity to 100% for the delete button of the current session
		$("#"+sessionID+" .btn-danger").css("opacity", "100%");

		// set the opacity to 0% for the delete button of the other sessions
		$(".chat-list li").not("#"+sessionID+"").find(".btn-danger").css("opacity", "0%");
	}

	/**
	 * Load a chat completion session
	 * @param sessionID
	 */
	function loadChatCompletionSession(sessionID)
	{
		newSession = false;

		// without reloading the page retrieve the session
		$.ajax({
			url: '<?= base_url('sessions') ?>/'+sessionID,
			type: 'GET',
			dataType: 'json',
			async: false,
			success: function (response) {
				if (response['status'] === <?= SC_SUCCESS ?>) {

					// Clear the chat
					$(".chat-messages").empty();

					// autofocus on the input
					document.getElementById("inputsend").focus();

					let index = 0;


					console.log(response['data']);

					// Display the messages
					for (var item in response['data']) {
						let message = response['data'][item];
						if(index === 0){
							index++;
						}
						else if (index === 1) {
							createFirstMessage(message['content']);
							index++;
						}
						else if (message['role'] === 'user') {
							createQuestion(message['content']);
						}
						else if (message['role'] === 'assistant') {
							createAnswer(message['content'], false);
						}

					}

				}
			},
			error: function (response) {
				let json 	= response['responseJSON'];
				let reason 	= json['reason'] ?? 'Une erreur est survenue';
				console.log(reason);
			}
		});
	}

	/**
	 * delete the session
	 */
	function deleteSession(sessionID, multiples)
	{
		if (!multiples){
			// ask to the user if he wants to delete the session
			if (confirm("Voulez-vous vraiment supprimer cette conversation ?")) {

				$.ajax({
					url: '<?= base_url('sessions') ?>/'+sessionID,
					type: 'DELETE',
					dataType: 'json',
					success: function (response) {
						if (response['status'] === <?= SC_SUCCESS ?>) {

					
							// remove the session from the list
							$("#"+sessionID+"").remove();

							// remove the session from the sessionList
							sessionList = sessionList.filter(function( obj ) {
								return obj.id !== sessionID;
							});

							// load the first session if there is one
						    if (sessionList.length !== 0){
							sessionID = sessionList[0]['id'];
							loadChatCompletionSession(sessionID);
							highlightCurrentSession(sessionID);
							console.log(sessionList);
							}else
							{
								// enable the loading animation
								disableLoadingAnimation = false;
								location.reload();
							}
							
							// remove the button if there is only one session
							if (sessionList.length === 1){
								$("#deleteAll").remove();
							}
						
						}
					},
					error: function (response) {
						let json 	= response['responseJSON'];
						let reason 	= json['reason'] ?? 'Une erreur est survenue';
						console.log(reason);
					}
				});
			}
		}
		else {
			$.ajax({
				url: '<?= base_url('sessions') ?>/'+sessionID,
				type: 'DELETE',
				dataType: 'json',
				success: function (response) {
					if (response['status'] === <?= SC_SUCCESS ?>) {
						// remove the session from the list
						$("#"+sessionID+"").remove();
					}
				},
				error: function (response) {
					let json 	= response['responseJSON'];
					let reason 	= json['reason'] ?? 'Une erreur est survenue';
					console.log(reason);
				}
			});
		}

	}

</script>
