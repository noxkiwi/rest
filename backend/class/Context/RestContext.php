<?php declare(strict_types = 1);
namespace noxkiwi\rest\Context;

use Exception;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Context;
use noxkiwi\core\Request;
use noxkiwi\core\Response;
use noxkiwi\rest\Exception\ForbiddenHttpMethodException;
use noxkiwi\rest\Response\RestResponse;
use function filter_input;
use function header;
use function in_array;
use function strtolower;
use function ucfirst;
use const E_USER_NOTICE;
use const FILTER_SANITIZE_SPECIAL_CHARS;
use const INPUT_SERVER;

/**
 * I am the main REST context.
 *
 * @package      noxkiwi\rest\Context
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class RestContext extends Context
{
    private const RESPONSE_TYPE_SUCCESS = 'success';
    private const RESPONSE_TYPE_WARNING = 'warning';
    private const RESPONSE_TYPE_ERROR   = 'error';
    public const  RESPONSE_TYPES        = [
        self::RESPONSE_TYPE_SUCCESS,
        self::RESPONSE_TYPE_WARNING,
        self::RESPONSE_TYPE_ERROR
    ];
    /** @var string I am the HTTP Method that was used. * */
    protected string $method;

    /**
     * RestContext constructor.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function __construct()
    {
        $this->response = RestResponse::getInstance();
        parent::__construct();
    }

    /**
     * @inheritDoc
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function dispatch(Request $request): void
    {
        try {
            $request = $this->methodController($request);
            $this->backendController($request);
        } catch (Exception $exception) {
            # ErrorHandler::handleException($exception);
            $this->setResponseType(self::RESPONSE_TYPE_ERROR);
            $this->addResponseMessage($exception->getMessage());
            $this->setResponseSuccess(false);
        }
        header('Content-Type: application/json');
        echo Response::getInstance()->getOutput();
        exit(200);
    }

    /**
     * Based on the given $request, I will call the correct
     * method for the given REQUEST_METHOD.
     *
     * @param \noxkiwi\core\Request $request
     *
     * @return \noxkiwi\core\Request
     */
    final protected function methodController(Request $request): Request
    {
        $this->request->set(Mvc::TEMPLATE, 'json');
        $this->response->set(Mvc::TEMPLATE, 'json');
        $this->method = strtolower(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS));
        $methodMethod = 'method' . ucfirst($this->method);
        $this->$methodMethod();

        return $request;
    }

    /**
     * I will be called when the request header is GET.
     * @throws \noxkiwi\rest\Exception\ForbiddenHttpMethodException
     */
    protected function methodGet(): void
    {
        throw new ForbiddenHttpMethodException('Method GET is not allowed', E_USER_NOTICE);
    }

    /**
     * I will be called when the request header is HEAD.
     * @throws \noxkiwi\rest\Exception\ForbiddenHttpMethodException
     */
    protected function methodHead(): void
    {
        throw new ForbiddenHttpMethodException('Method HEAD is not allowed', E_USER_NOTICE);
    }

    /**
     * I will be called when the request header is POST.
     * @throws \noxkiwi\rest\Exception\ForbiddenHttpMethodException
     */
    protected function methodPost(): void
    {
        throw new ForbiddenHttpMethodException('Method POST is not allowed', E_USER_NOTICE);
    }

    /**
     * I will be called when the request header is PUT.
     * @throws \noxkiwi\rest\Exception\ForbiddenHttpMethodException
     */
    protected function methodPut(): void
    {
        throw new ForbiddenHttpMethodException('Method PUT is not allowed', E_USER_NOTICE);
    }

    /**
     * I will be called when the request header is DELETE.
     * @throws \noxkiwi\rest\Exception\ForbiddenHttpMethodException
     */
    protected function methodDelete(): void
    {
        throw new ForbiddenHttpMethodException('Method DELETE is not allowed', E_USER_NOTICE);
    }

    /**
     * I will be called when the request header is CONNECT.
     * @throws \noxkiwi\rest\Exception\ForbiddenHttpMethodException
     */
    protected function methodConnect(): void
    {
        throw new ForbiddenHttpMethodException('Method CONNECT is not allowed', E_USER_NOTICE);
    }

    /**
     * I will be called when the request header is OPTIONS.
     * @throws \noxkiwi\rest\Exception\ForbiddenHttpMethodException
     */
    protected function methodOptions(): void
    {
        throw new ForbiddenHttpMethodException('Method OPTIONS is not allowed', E_USER_NOTICE);
    }

    /**
     * I will be called when the request header is TRACE.
     * @throws \noxkiwi\rest\Exception\ForbiddenHttpMethodException
     */
    protected function methodTrace(): void
    {
        throw new ForbiddenHttpMethodException('Method TRACE is not allowed', E_USER_NOTICE);
    }

    /**
     * I will be called when the request header is PATCH.
     * @throws \noxkiwi\rest\Exception\ForbiddenHttpMethodException
     */
    protected function methodPatch(): void
    {
        throw new ForbiddenHttpMethodException('Method PATCH is not allowed', E_USER_NOTICE);
    }

    /**
     * I will set the response type to the given one.
     *
     * @param string $responseType
     */
    final protected function setResponseType(string $responseType): void
    {
        if (! in_array($responseType, self::RESPONSE_TYPES, true)) {
            return;
        }
        $this->response->type = $responseType;
    }

    /**
     * I will set the response type to success.
     *
     * @param bool $success
     */
    final protected function setResponseSuccess(bool $success = true): void
    {
        $this->response->success = $success;
    }

    /**
     * I will add an arbitrary response message.
     *
     * @param string $message
     */
    final protected function addResponseMessage(string $message): void
    {
        $this->response->messages [] = $message;
    }
}
