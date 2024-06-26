<?php
/**
 * @var string $action
 */
?>
<form id="formEmail" accept-charset="utf-8" style="min-width: 350px">
	<input type="hidden" name="step" value="2">

	<div class="form-group">
		<label>Adresse Email</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<div class="input-group-text bg-primary"> <i class="fa fa-user text-white"></i> </div>
			</div>
			<input name="forgottenPwd[email]" class="<?php if (isset($errors['forgottenPwd.email'])) echo "is-invalid";?> form-control" placeholder="Email" type="text" value="<?php echo set_value('forgottenPwd[email]'); ?>">
			<?php if (isset($errors['forgottenPwd.email']))echo "<div class=\"invalid-feedback\" role=\"alert\">".$errors['forgottenPwd.email']."</div>"; ?>
		</div>
	</div>
	<br>
	<div class="form-group text-center">
		<button style="margin-left: auto; margin-right: auto; display: block" id="forgottenPwdButton" type="submit" class="btn btn-accent btn-block text-center">Envoyer</button>
	</div>
	<?php if (isset($alert) && isset($type)) echo "<div class=\"alert alert-" . $type . "\" role=\"alert\">".$alert."</div>";?>
</form>


<script>
	var form = $('#formEmail');
	var forgottenPwdButton = $('#forgottenPwdButton');

	$(function () {
		form.on('submit',function (e) {
			forgottenPwdButton.html('Envoi en cours...');
			forgottenPwdButton.attr('disabled',true);
			loadHTML('<?php echo base_url($action) ?>', form.serialize(), form.parent());
			e.preventDefault();
		});
	});
</script>
