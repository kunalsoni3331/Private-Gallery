<div class="">
	<form action="" class="category-login wc-ppc-password-form post-password-form" method="post">
		<div class="category-login__text"></div>
		<p class="category-login__fields wc-ppc-password-form-inputs">
			<label class="category-login__label wc-ppc-password-label">
				<?php _e( 'Please enter password', KPG_txt_domain ); ?> 
				<input name="post_password" id="post_password" class="category-login__password wc-ppc-password" type="password" size="25" style="border:1px solid #000; margin: 20px auto; color:#000;"/>
				<span class="error-msg error_password_msg"> <?php _e( 'Please enter password.', KPG_txt_domain ); ?> </span>
				<span class="error-msg error_password_wrong"> <?php _e( 'Please enter corrent password.', KPG_txt_domain ); ?> </span>
			</label>
			<br/>
			<input id="private_gallery" class="category-login__submit" type="submit" name="Submit" value="Submit" />
		</p>
	</form>
</div>