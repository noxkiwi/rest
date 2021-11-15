<?php declare(strict_types = 1);
namespace noxkiwi\rest;

use JetBrains\PhpStorm\NoReturn;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Request;

/**
 * I am the basic REST app
 *
 * @package      noxkiwi\rest
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class App extends \noxkiwi\core\App
{
    /**
     * @inheritDoc
     */
    #[NoReturn] public function run(): void
    {
        $req      = explode('?', $_SERVER['REQUEST_URI']);
        $url      = $req[0];
        $segments = explode('/', $url);
        if (! empty($segments[1])) {
            $context = $segments[1];
            $view    = empty($segments[2]) ? null : $segments[2];
            $action  = empty($segments[3]) ? null : $segments[3];
            $queries = [];
            parse_str($req[1] ?? '', $queries);
            $_GET               = $queries;
            $_GET[Mvc::CONTEXT] = $context;
            $_GET[Mvc::VIEW]    = $view;
            $_GET[Mvc::ACTION]  = $action;
            $request            = Request::getInstance();
            $request->add($_GET);
            $request->add($_POST);
            $request->add((array)json_decode(file_get_contents('php://input'), true));
        }
        parent::run();
    }
}
