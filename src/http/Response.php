<?php


namespace App\src\http;

/**
 * Class Response
 * @package App\src\http
 */
class Response
{
    /**
     * HTTP success dode
     */
    const STATUS_OK = 200;

    /**
     * HTTP access forbidden code
     */
    const STATUS_FORBIDDEN = 403;

    /**
     * HTTP page not found code
     */
    const STATUS_NOT_FOUND = 404;

    /**
     * HTTP-headers
     *
     * @var array
     */
    private array $headers;

    /**
     * HTTP status code
     *
     * @var int
     */
    private int $statusCode;

    /**
     * HTML-page content
     *
     * @var string
     */
    private string $content;

    /**
     * Cookie instance
     *
     * @var Cookie
     */
    private Cookie $cookie;

    /**
     * Session instance
     *
     * @var Session
     */
    private Session $session;

    public function __construct(int $statusCode = self::STATUS_OK)
    {
        $this->headers = [];
        $this->statusCode = $statusCode;
        $this->cookie = new Cookie();
        $this->session = new Session();
    }

    /**
     * Adds HTTP-header to response
     *
     * @param string $header
     * @return $this
     */
    public function addHeader(string $header)
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * Sets HTTP status code
     *
     * @param int $code
     * @return $this
     */
    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Sets contens of response
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Sets flash-message
     *
     * @param string $category
     * @param string $message
     */
    public function setFlash(string $category, string $message) : void
    {
        $this->session->set('flash-type', $category);
        $this->session->set('flash-message', $message);
    }

    /**
     * Returns flash-message
     *
     * @return array|null
     */
    public function getFlash() : ?array
    {
        $flashType = $this->session->get('flash-type');
        if($flashType){
            $message = $this->session->get('flash-message');
            return [
                'type' => $flashType,
                'text' => $message
            ];
        }
        return null;
    }

    /**
     * Clear flash message
     */
    public function clearFlash() : void
    {
        $this->session->unset('flash-type');
        $this->session->unset('flash-message');
    }

    /**
     * Sends response to client
     *
     * @param bool $isJson
     */
    public function send(bool $isJson = false) : void
    {
        header('HTTP/1.1 ' . $this->statusCode);
        if($isJson){
            $this->addHeader('Content-Type: application/json');
        }
        foreach ( $this->headers as $header ) {
            header($header);
        }
        echo $this->content;
        exit();
    }

    /**
     * Redirects client
     *
     * @param string $url
     */
    public function redirect(string $url) : void
    {
        header('Location: ' . $url);
        exit();
    }
}