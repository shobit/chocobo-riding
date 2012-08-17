<?php

class Auth_ORM extends Kohana_Auth_ORM {

    protected function _login($user, $password, $remember)
    {
        if ( ! is_object($user))
        {
            $username = $user;

            // Load the user
            $user = ORM::factory('user');
            $user->where($user->unique_key($username), '=', $username)->find();
        }

        if (is_string($password))
        {
            // Create a hashed password
            $password = sha1($password);
        }

        // If the passwords match, perform a login
        if ($user->password === $password)
        {
            if ($remember === TRUE)
            {
                // Token data
                $data = array(
                    'user_id'    => $user->id,
                    'expires'    => time() + $this->_config['lifetime'],
                    'user_agent' => sha1(Request::$user_agent),
                );

                // Create a new autologin token
                $token = ORM::factory('user_token')
                            ->values($data)
                            ->create();

                // Set the autologin cookie
                Cookie::set('authautologin', $token->token, $this->_config['lifetime']);
            }

            // Finish the login
            $this->complete_login($user);

            return TRUE;
        }

        // Login failed
        return FALSE;
    }
 
    public function password($user)
    {
        if ( ! is_object($user))
        {
            $username = $user;

            // Load the user
            $user = ORM::factory('user');
            $user->where($user->unique_key($username), '=', $username)->find();
        }

        return $user->password;
    }
 
    public function check_password($password)
    {
        $user = $this->get_user();

        if ( ! $user)
            return FALSE;

        return (sha1($password) === $user->password);
    }
}