<?php

namespace werx\Messages;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use werx\Messages\Enums\MessageType;

class Messages
{
	/**
	 * @var \Symfony\Component\HttpFoundation\Session\SessionInterface $session
	 */
	public $session;

	/**
	 * @var \werx\Messages\Decorators\DecoratorInterface $decorator
	 */
	public $decorator;

	/**
	 * @var \werx\Messages\Messages $instance
	 */
	protected static $instance;

	/**
	 * @param SessionInterface $session
	 */
	protected function __construct(SessionInterface $session = null)
	{
		$this->session = $session;
		$this->decorator = new Decorators\SimpleList;
	}

	/**
	 * @param SessionInterface $session
	 * @return Messages
	 */
	public static function getInstance(SessionInterface $session = null)
	{
		if (static::$instance == null) {

			if (empty($session)) {
				$session = new Session(new NativeSessionStorage(['cookie_lifetime' => 604800]));
			}

			static::$instance = new static($session);
		}

		return static::$instance;
	}

	/**
	 * @param Decorators\DecoratorInterface $interface
	 */
	public static function setDecorator(Decorators\DecoratorInterface $interface = null)
	{
		if (empty($interface)) {
			static::$instance->decorator = new Decorators\SimpleList;
		} else {
			static::$instance->decorator = $interface;
		}
	}

	/**
	 * Destroys the instance of the Messages object.
	 */
	public static function destroy()
	{
		static::$instance = null;
	}

	/**
	 * Remove any messages from the stack
	 */
	public static function delete()
	{
		static::$instance->session->remove('messages');
	}

	/**
	 * Add an error message.
	 * @param $message
	 * @param mixed $data
	 */
	public static function error($message, $data = [])
	{
		static::add($message, MessageType::ERROR, $data);
	}

	/**
	 * Add a success message.
	 * @param $message
	 * @param mixed $data
	 */
	public static function success($message, $data = [])
	{
		static::add($message, MessageType::SUCCESS, $data);
	}

	/**
	 * Add an informational message.
	 * @param $message
	 * @param mixed $data
	 */
	public static function info($message, $data = [])
	{
		static::add($message, MessageType::INFO, $data);
	}

	/**
	 * @param $message
	 * @param string $type
	 * @param mixed $data
	 */
	public static function add($message, $type = MessageType::ERROR, $data = [])
	{
		$message = static::format($message, $data);

		$messages = static::$instance->session->get('messages');

		if (empty($messages)) {
			$messages = [MessageType::ERROR => [], MessageType::INFO => [], MessageType::SUCCESS => []];
		}

		$messages[$type][] = $message;
		static::$instance->session->set('messages', $messages);
	}

	/**
	 * @param bool $delete
	 * @return array
	 * @throws \Exception
	 */
	public static function all($delete = true)
	{
		if (!empty(static::$instance)) {
			$messages = static::$instance->session->get('messages');

			if ($delete === true) {
				static::delete();
			}

			if (empty($messages)) {
				$messages = [];
			}

			return $messages;
		} else {
			throw new \Exception("Messages not initialized");
		}
	}

	/**
	 * @param $message
	 * @param mixed $data
	 * @return string
	 */
	public static function format($message, $data = null)
	{
		if (empty($data)) {
			return $message;
		} elseif (is_array($data)) {
			return vsprintf($message, $data);
		} elseif (is_object($data)) {
			return vsprintf($message, (array) $data);
		} else {
			return sprintf($message, $data);
		}
	}

	/**
	 * Display all messages using whatever decorator has been set.
	 * Default decorator: Decorators\SimpleList
	 *
	 * @param bool $delete
	 * @return null|string
	 */
	public static function display($delete = true)
	{
		$messages = static::all($delete);

		if (empty($messages)) {
			return null;
		}

		$decorator = static::$instance->decorator;

		$error = array_key_exists(MessageType::ERROR, $messages) ?
			$decorator::decorate($messages[MessageType::ERROR], MessageType::ERROR) : '';

		$info = array_key_exists(MessageType::INFO, $messages) ?
			$decorator::decorate($messages[MessageType::INFO], MessageType::INFO) : '';

		$success = array_key_exists(MessageType::SUCCESS, $messages) ?
			$decorator::decorate($messages[MessageType::SUCCESS], MessageType::SUCCESS) : '';

		return $error . "\n" . $info . "\n" . $success;
	}

	/**
	 * @param $method
	 * @param $parameters
	 * @return mixed
	 * @codeCoverageIgnored
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array(array(static::$instance, $method), $parameters);
	}
}
