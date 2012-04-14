<?php

class Session extends Session_Core 
{
    
    /**
	 * Freshen one, multiple or all flash variables.
	 *
	 * @param   string  variable key(s)
	 * @return  void
	 */
	public function expire($key)
	{
		if (isset(Session::$flash[$key]))
		{
			Session::$flash[$key] = 'old';
		}
	}
	    
}