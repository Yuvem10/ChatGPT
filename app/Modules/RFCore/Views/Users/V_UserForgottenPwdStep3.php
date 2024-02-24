<?php
/**
 * @var string $action
 * @var string $token
 */
?>

<div class="page-content" >
	<div class="row">
		<div class="col-4" style="min-width: 350px; margin: auto">

			<h3>Réinitialisation de mot de passe</h3>
			<hr style="margin-bottom: 20px">
			<form id="formPwdReset" action="<?php echo site_url($action); ?>" method="post">

				<input type="hidden" name="pwdReset[token]" value="<?php echo $token;?>">
				<input type="hidden" name="step" value="4">

				<div class="form-group">
					<label>Nouveau mot de passe *</label>
					<div class="input-group">
						<div class="input-group-prepend">
                            <span class="input-group-text bg-primary"> <i
										class="fa fa-lock text-white"></i> </span>
						</div>
						<input required name="pwdReset[password]" class="<?php if (isset($errors['pwdReset.password'])) echo "is-invalid";?> form-control" placeholder="******" type="password">
						<?php if (isset($errors['pwdReset.password'])) {
							echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['pwdReset.password']."</div>";
						} ?>
					</div>
				</div>

				<div class="form-group">
					<label>Confirmation *</label>
					<div class="input-group">
						<div class="input-group-prepend">
                            <span class="input-group-text bg-primary"> <i
										class="fa fa-lock text-white"></i> </span>
						</div>
						<input required name="pwdReset[passwordConf]" class="<?php if (isset($errors['pwdReset.passwordConf'])) echo "is-invalid";?> form-control" placeholder="******" type="password">
						<?php if (isset($errors['pwdReset.passwordConf'])) {
							echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['pwdReset.passwordConf']."</div>";
						} ?>
					</div>
				</div>
				<hr style="margin-bottom: 30px;margin-top: 30px">

				<div class="form-group">
					<button type="submit" class="btn btn-accent btn-block d-block mb-3">Modifier</button>
					<a type="button" class="btn btn-outline-accent-darker btn-block d-block " href="<?php echo base_url();?>">Retour à la page de connexion</a>

				</div>

			</form>

			<?php
			if (isset($alert) && isset($type)) {
				$alertDisp = "<div class=\"alert alert-" . $type . "\" role=\"alert\">";
				$alertDisp .= $alert;
				$alertDisp .= "</div>";
				echo $alertDisp;
			}
			?>

		</div>
	</div>
</div>

<script>
	$('#formPwdReset').submit(
		function(){
			var newPassword = $("[name='pwdReset\\[password\\]']");

			if(newPassword.val().length)
			{
				let pass = newPassword.val();
				newPassword.val(sha256(pass));
			}
			var confPassword = $("[name='pwdReset\\[passwordConf\\]']");

			if(confPassword.val().length)
			{
				let pass = confPassword.val();
				confPassword.val(sha256(pass));
			}
			return true;
		});
</script>
