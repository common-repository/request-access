<?php if ($errors): ?>
	<div class="alert alert-error">
		<?php foreach ($errors as $error): ?>
			<?php echo $error ?><br />
		<?php endforeach ?>
	</div>
<?php endif ?>
<div id="request-access-login-form-wrap">
	<form action="" method="post" id="request-access-login-form">

		<input type="hidden" name="request-access-login" value="1" />

		<label for="username"><?php _e('Username', RequestAccess::$text_domain) ?></label>
		<input id="username" type="text" name="log" value="<?php echo @$_POST['log'] ?>" />

		<label for="password"><?php _e('Password', RequestAccess::$text_domain) ?></label>
		<input id="password" type="password" name="pwd" value="<?php echo @$_POST['pwd'] ?>" />

		<br />
		<label><input type="checkbox" name="rememberme" value="forever"<?php echo @$_POST['rememberme'] == 'forever' ? ' checked="checked"' : '' ?> /> <?php _e('Remember Me', RequestAccess::$text_domain) ?></label>
		<input type="submit" name="submit" value="<?php _e('Submit', RequestAccess::$text_domain) ?>" />
		<br /><br />
		<a href="<?php echo get_permalink(RequestAccess::get_setting('signup_page')) ?>"><?php _e('Need an account? Request one here.', RequestAccess::$text_domain) ?></a>

	</form>
</div>