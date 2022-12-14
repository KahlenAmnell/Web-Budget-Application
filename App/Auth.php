<?php

namespace App;

use \App\Models\User;

/**
 * Authentication
 */
class Auth
{
    /**
     * Add session id when user log in
     * 
     * @param User $user The user model
     * 
     * @return void
     */
    public static function login($user)
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
    }

    /**
     * Log out the user
     */
    public static function logout()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }
}
