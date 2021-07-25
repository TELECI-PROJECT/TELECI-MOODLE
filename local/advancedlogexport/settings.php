<?php
// Ensure the configurations for this site are set
if ( $hassiteconfig ){
 
	// Create the new settings page
	// - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
	// $settings will be NULL
	//$settings = new admin_settingpage( 'local_yourplugin', 'Your Settings Page Title' );
 
	// Create 
	//$ADMIN->add( 'localplugins', $settings );
    $ADMIN->add('reports', new admin_externalpage('reportlog', 'TELECI Advanced logs export', "$CFG->wwwroot/local/advancedlogexport/index.php?id=".SITEID, 'report/log:view'));
 
	// Add a setting field to the settings for this page
//	$settings->add( new admin_setting_configtext(
// 
//		// This is the reference you will use to your configuration
//		'local_yourplugin/apikey',
// 
//		// This is the friendly title for the config, which will be displayed
//		'External API: Key',
// 
//		// This is helper text for this config field
//		'This is the key used to access the External API',
// 
//		// This is the default value
//		'No Key Defined',
// 
//		// This is the type of Parameter this config is
//		PARAM_TEXT
// 
//	) );
 
}