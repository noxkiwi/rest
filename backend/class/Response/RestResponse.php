<?php declare(strict_types = 1);
namespace noxkiwi\rest\Response;

use noxkiwi\core\Helper\JsonHelper;
use noxkiwi\core\Response;

/**
 * I am the REST Response object.
 *
 * @package      noxkiwi\rest\Response
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class RestResponse extends Response
{
    public string $type;
    /** @var string[] * */
    public array $messages;
    /** @var \Error[] * */
    public array $errors;
    /** @var bool $success Stats whether the request was processed successfully. * */
    public bool $success;

    /**
     * I will solely formulate the standard Response design.
     * @return string
     */
    final public function getOutput(): string
    {
        parent::getOutput();
        $response = [];
        if (! empty($this->messages)) {
            $response['messages'] = $this->messages;
        }
        if (! empty($this->errors)) {
            $response['errors'] = $this->errors;
        }
        $response['success'] = $this->success ?? false;
        $response['data']    = $this->get();

        return JsonHelper::encode($response);
    }
}
