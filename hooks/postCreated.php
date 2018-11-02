//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

use IPS\Http\Request\Curl;

class hook39 extends _HOOK_CLASS_
{

	/**
	 * Create comment
	 *
	 * @param	\IPS\Content\Item		$item				The content item just created
	 * @param	string					$comment			The comment
	 * @param	bool					$first				Is the first comment?
	 * @param	string					$guestName			If author is a guest, the name to use
	 * @param	bool|NULL				$incrementPostCount	Increment post count? If NULL, will use static::incrementPostCount()
	 * @param	\IPS\Member|NULL		$member				The author of this comment. If NULL, uses currently logged in member.
	 * @param	\IPS\DateTime|NULL		$time				The time
	 * @param	string|NULL				$ipAddress			The IP address or NULL to detect automatically
	 * @param	int|NULL				$hiddenStatus		NULL to set automatically or override: 0 = unhidden; 1 = hidden, pending moderator approval; -1 = hidden (as if hidden by a moderator)
	 * @return	static
	 */
	static public function create( $item, $comment, $first=false, $guestName=NULL, $incrementPostCount=NULL, $member=NULL, \IPS\DateTime $time=NULL, $ipAddress=NULL, $hiddenStatus=NULL )
	{
		try
		{
		    $member = $member ? $member : \IPS\Member::loggedIn();

	        $team_id = \IPS\Settings::i()->memberscore_team_id;
	        $api_key = \IPS\Settings::i()->memberscore_api_key;

	        $url = \IPS\Http\Url::external('https://app.memberscore.io/integration/ipboard/' . $team_id . '?api_token=' . $api_key);
	        try {
	            $response = $url->request(15 )->post(array(
	                'data' => array(
	                    'api_key' => $api_key,
	                    'email' => $member->_data['email'],
	                    'first' => $first,
	                )
	            ));
	            if ($response->httpResponseCode !== '200') {
	                throw new \Exception('MemberScore returned an error.', $response->httpResponseCode);
	            }
	        }
	        catch(\Exception $exception) {
	            \IPS\Log::log( $exception->getMessage() . ' ' . $exception->getCode(), 'MemberScore'  );
	        }

			return call_user_func_array( 'parent::create', func_get_args() );
		}
		catch ( \RuntimeException $e )
		{
			if ( method_exists( get_parent_class(), __FUNCTION__ ) )
			{
				return call_user_func_array( 'parent::' . __FUNCTION__, func_get_args() );
			}
			else
			{
				throw $e;
			}
		}
	}

}
