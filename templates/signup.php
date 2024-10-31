<?php if ($errors): ?>
	<div class="alert alert-error">
		<?php foreach ($errors as $error): ?>
			<?php echo $error ?><br />
		<?php endforeach ?>
	</div>
<?php endif ?>

<form action="" method="post">

	<input type="hidden" name="request-access-form" value="1" />

	<label for="first_name"><?php _e('First Name', RequestAccess::$text_domain) ?></label>
	<input id="first_name" type="text" name="first_name" value="<?php echo @$_POST['first_name'] ?>" />

	<label for="last_name"><?php _e('Last Name', RequestAccess::$text_domain) ?></label>
	<input id="last_name" type="text" name="last_name" value="<?php echo @$_POST['last_name'] ?>" />
	
	<label for="company"><?php _e('Company', RequestAccess::$text_domain) ?></label>
	<input id="company" type="text" name="company" value="<?php echo @$_POST['company'] ?>" />

	<label for="title"><?php _e('Title', RequestAccess::$text_domain) ?></label>
	<input id="title" type="text" name="title" value="<?php echo @$_POST['title'] ?>" />

	<label for="poc"><?php _e('Point of Contact', RequestAccess::$text_domain) ?></label>
	<input id="poc" type="text" name="poc" value="<?php echo @$_POST['poc'] ?>" />

	<hr />

	<label for="email"><?php _e('Your Email Address', RequestAccess::$text_domain) ?></label>
	<input id="email" type="text" name="email" value="<?php echo @$_POST['email'] ?>" />

	<label for="password1"><?php _e('Password', RequestAccess::$text_domain) ?></label>
	<input id="password1" type="password" name="password1" value="<?php echo @$_POST['password1'] ?>" />

	<label for="password2"><?php _e('Confirm Password', RequestAccess::$text_domain) ?></label>
	<input id="password2" type="password" name="password2" value="<?php echo @$_POST['password2'] ?>" />

	<br />
	<input type="submit" name="submit" value="<?php _e('Submit', RequestAccess::$text_domain) ?>" />
</form>