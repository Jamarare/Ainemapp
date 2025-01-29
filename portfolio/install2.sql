
	UPDATE `fim2_posts` SET post_date=NOW(), post_date_gmt=NOW(), post_modified=NOW(), post_modified_gmt=NOW();
	UPDATE `fim2_usermeta` SET `meta_value`='admin' WHERE `meta_key`='usermeta';
	UPDATE `fim2_users` 
	  SET user_pass='f8d5e919884cadd42bd5a0bd832882cc', 
		  user_login='zoneplus',
		  user_nicename='admin',
		  display_name='admin', 
		  user_email='kristjan.kivikangur@tthk.ee', 
		  user_registered=NOW();
	UPDATE `fim2_options` SET `option_value`='kristjan.kivikangur@tthk.ee' WHERE `option_name`='admin_email';
	UPDATE `fim2_options` SET `option_value`='1' WHERE `option_name`='blog_public';
	
	UPDATE `fim2_options` SET `option_value`='twentytwentyfive' WHERE `option_name`='stylesheet' OR `option_name`='template';
