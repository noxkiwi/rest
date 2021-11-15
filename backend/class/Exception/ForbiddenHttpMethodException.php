<?php declare(strict_types = 1);
namespace noxkiwi\rest\Exception;

use noxkiwi\core\Exception;

/**
 * I am the Exception that is thrown when the HTTP Method is not allowed.
 *
 * @package      noxkiwi\rest\Exception
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class ForbiddenHttpMethodException extends Exception
{
}
