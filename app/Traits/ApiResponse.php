<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Message.
     *
     * @var string
     */
    protected $message = "";

    /**
     * Meta Data.
     *
     * @var array
     */
    protected $meta = [];
    protected $headers = [];

    public function customResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                'code' => $this->getCode(),
                'errors' => $this->getErrors(),
                'data' => $this->getData(),
                'message' => $this->getMessage(),
                'meta' => $this->getMeta()
            ],
            $this->getCode()
        );
    }

    public function getMessage(): string
    {
        return $this->message;
    }
    public function setCode(int $code)
    {
        $this->code = (int) $code;
        return $this;
    }
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setMeta(array $meta): object
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Info if reply is isValid.
     *
     * @var bool
     */
    private $isValid = true;

    /**
     * Errors.
     *
     * @var array
     */
    private $errors = [];

    /**
     * Data.
     *
     * @var null
     */
    private $data = null;

    /**
     * Error code.
     *
     * @var int
     */
    private $code = 200;

    /**
     * Returns data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Adds data to return.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns information if that is valid response.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * setup response as inValidRequest
     *
     * @return $this
     */
    public function inValidRequest()
    {
        $this->isValid = false;
        return $this;
    }

    /**
     * Returns error messages.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Returns latest error.
     *
     * @return string|false false when array is empty
     */
    public function getLastError()
    {
        return end($this->errors);
    }

    /**
     * Setup error messages.
     *
     * @param array $errors Error messages
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        $this->inValidRequest();
        return $this;
    }

    /**
     * Add error.
     *
     * @param string $message
     * @return $this
     */
    public function addError(string $message)
    {
        $this->errors[] = trans($message);
        $this->inValidRequest();
        return $this;
    }

    /**
     * Add error
     *
     * @param string $key
     * @param string $message
     * @return $this
     */
    public function addErrorWithKey(string $key, string $message)
    {
        $this->errors[$key] = trans($message);
        $this->inValidRequest();
        return $this;
    }

    /**
     * Returns code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return (int) $this->code;
    }

    /**
     * Setup response code.
     *
     * @param int $code Error code
     *
     * @return $this
     */


    /**
     * Set code for: Not Found
     *
     * @return ApiResponse
     */
    public function setCodeAsNotFound()
    {
        $this->inValidRequest();
        return $this->setCode(Response::HTTP_NOT_FOUND);
    }

    /**
     * Set code for: Validation Fail
     *
     * @return ApiResponse
     */
    public function setCodeAsValidationFail()
    {
        $this->inValidRequest();
        return $this->setCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Set code for: UnauthorizedAccess
     *
     * @return ApiResponse
     */
    public function setCodeAsUnauthorizedAccess()
    {
        $this->inValidRequest();
        return $this->setCode(Response::HTTP_FORBIDDEN);
    }

    /**
     * Set code for: UnauthorizedAccess
     *
     * @return ApiResponse
     */
    public function setCodeAsUnauthenticatedAccess()
    {
        $this->inValidRequest();
        return $this->setCode(Response::HTTP_UNAUTHORIZED);
    }


    /**
     * Set code for: Unprocessable entity
     *
     * @return ApiResponse
     */
    public function setCodeAsUnprocessableEntity()
    {
        $this->inValidRequest();
        return $this->setCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Set code for: Unprocessable entity
     *
     * @return ApiResponse
     */
    public function setCodeAsInternalServerError()
    {
        $this->inValidRequest();
        return $this->setCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Helper method to find a section or throw 404.
     */
    public function notFound()
    {
        return $this->errorResponse(
            trans('response_messages.not_found'),
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Success response wrapper.
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse(array $data, string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        if (!empty($data['status']))
            session()->flash('status', $data['status']);
        return $this->setData($data)
            ->setCode($code)
            ->setMessage($message)
            ->customResponse();
    }

    /**
     * Error response wrapper.
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse(string $message, int $code): JsonResponse
    {
        return $this->setData([])
            ->setCode($code)
            ->setMessage($message)
            ->customResponse();
    }

    /**
     * Success response wrapper.
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponseWithoutDataKey($data, string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->setData($data)
            ->setCode($code)
            ->setMessage($message)
            ->customResponse();
    }
    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }
}
