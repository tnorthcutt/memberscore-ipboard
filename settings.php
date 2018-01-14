//<?php

$form->add( new \IPS\Helpers\Form\Text( 'memberscore_team_id', \IPS\Settings::i()->memberscore_team_id ) );
$form->add( new \IPS\Helpers\Form\Text( 'memberscore_api_key', \IPS\Settings::i()->memberscore_api_key ) );

if ( $values = $form->values() )
{
	$form->saveAsSettings();
	return TRUE;
}

return $form;