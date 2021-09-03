<?php

namespace Tahir\Session;

class Session
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;

        $this->startFlashMessages();
    }

    public function __destruct()
    {
        $this->deleteFlashMessages();
    }

    public function start()
    {
        ini_set('session.use_only_cookies', 1);

        if(!session_id())
        {
            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return array_get($_SESSION, $key, $default);
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function pull($key)
    {
        $value = $this->get($key);

        $this->remove($key);

        return $value;
    }

    public function all()
    {
        return $_SESSION;
    }

    public function destroy($key)
    {
        session_destroy();

        unset($_SESSION);
    }

    public function startFlashMessages()
    {
        $flashMessages = $_SESSION['flash_messages'] ?? [];

        foreach($flashMessages as $key => &$flashMessage)
        {
            $flashMessage['deleted'] = true;
        }

        $this->set('flash_messages', $flashMessages);
    }

    public function deleteFlashMessages()
    {
        $flashMessages = $_SESSION['flash_messages'] ?? [];

        foreach($flashMessages as $key => $flashMessage)
        {
            if($flashMessage['deleted'])
            {
                unset($flashMessages[$key]);
            }
        }

        $this->set('flash_messages', $flashMessages);
    }

    public function setFlash($key, $message)
    {
        $_SESSION['flash_messages'][$key] = [
            'deleted' => false,
            'message' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION['flash_messages'][$key]['message'] ?? false;
    }

    public function flashHas($key)
    {
        return isset($_SESSION['flash_messages'][$key]);
    }

}