<?php

/*
 * This file is part of the stream project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StreamHttp;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use StreamHttp\Exception\InvalidArgumentException;
use StreamHttp\Exception\UnexpectedTypeException;

/**
 * Client-side request
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 */
class Request implements RequestInterface
{
    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var string
     */
    protected $requestTarget;

    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * @var string
     */
    protected $protocol = '1.1';

    /**
     * @var array
     */
    private $httpMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

    /**
     * constructor Client
     *
     * @param string|null $uri
     * @param string      $method
     * @param string      $body
     * @param array       $headers
     */
    public function __construct($uri = null, $method = 'POST', $body = 'php://temp', array $headers = [])
    {
        $this->checkMethod($method);

        $this->uri = $this->createUri($uri);
        $this->method = strtoupper($method);
        $this->body = $body;
        $this->headers = $headers;
    }

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion($version)
    {
        if (empty($version)) {
            throw new InvalidArgumentException('HTTP protocol version can not be empty');
        }

        if (is_string($version) === false) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported HTTP protocol version; must be a string, %s given',
                (is_object($version) ? get_class($version) : gettype($version))
            ));
        }

        if (preg_match('#^(1\.[01]|2)$#', $version) === false) {
            throw new InvalidArgumentException(sprintf('Unsupported HTTP protocol version "%s" provided', $version));
        }

        $clone = clone $this;
        $clone->protocol = $version;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader($name)
    {
        return array_key_exists(strtolower($name), $this->headers);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($name)
    {
        if (array_key_exists(strtolower($name), $this->headers) === true) {
            return $this->headers[$name];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine($name)
    {
        $value = $this->getHeader($name);

        if (empty($value) === true) {
            return '';
        }

        return implode(',', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader($name, $value)
    {
        if (is_string($value) === true) {
            $value = [$value];
        }

        if ((is_array($value) === false) || ($this->arrayContainsOnlyStrings($value) === false)) {
            throw new InvalidArgumentException('Invalid header value, must be a string or array of strings');
        }

        $normalized = strtolower($name);

        $clone = clone $this;

        if ($clone->hasHeader($name)) {
            unset($clone->headers[$clone->headers[$normalized]]);
        }

        $clone->headers[$normalized]         = $value;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader($name, $value)
    {
        if (is_string($value) === true) {
            $value = [ $value ];
        }

        if ((is_array($value) === false) || ($this->arrayContainsOnlyStrings($value) === false)) {
            throw new InvalidArgumentException('Invalid header value, must be a string or array of strings');
        }

        if ($this->hasHeader($name) === false) {
            return $this->withHeader($name, $value);
        }

        $normalized = strtolower($name);

        $header     = $this->headers[$normalized];

        $clone = clone $this;
        $clone->headers[$header] = array_merge($this->headers[$header], $value);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutHeader($name)
    {
        if ($this->hasHeader($name) === false) {
            return clone $this;
        }

        $normalized = strtolower($name);
        $original = $this->headers[$normalized];

        $clone = clone $this;
        unset($clone->headers[$original], $normalized);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->stream;
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->stream = $body;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTarget()
    {
        if ($this->requestTarget !== null) {
            return $this->requestTarget;
        }

        if (($this->uri instanceof UriInterface) === false) {
            return '/';
        }

        $target = $this->uri->getPath();

        if ($this->uri->getQuery()) {
            $target .= sprintf('?%s', $this->uri->getQuery());
        }

        if (empty($target) === true) {
            $target = '/';
        }

        return $target;
    }

    /**
     * {@inheritdoc}
     */
    public function withRequestTarget($requestTarget)
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new InvalidArgumentException('Invalid request target provided, cannot contain whitespace');
        }

        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function withMethod($method)
    {
        $this->checkMethod($method);

        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $clone = clone $this;
        $clone->uri = $uri;

        if ($preserveHost === true && $this->hasHeader('Host')) {
            return $clone;
        }

        if ($uri->getHost() === false) {
            return $clone;
        }

        $host = $uri->getHost();

        if ($uri->getPort() === true) {
            $host .= sprintf(':%s', $uri->getPort());
        }

        if (array_key_exists('Host', $this->headers) === true) {
            unset($clone->headers['Host']);
        }

        $clone->headers['Host'] = [$host];

        return $clone;
    }

    /**
     * Create and return a URI instance.
     *
     * @param string|UriInterface $uri
     *
     * @return UriInterface
     */
    private function createUri($uri = null)
    {
        if ($uri instanceof UriInterface) {
            return $uri;
        }

        if (is_string($uri) === true) {
            return new Uri($uri);
        }

        if ($uri === null) {
            return new Uri();
        }

        throw new InvalidArgumentException(sprintf('Invalid argument, must be null, string, or a %s instance', UriInterface::class));
    }

    /**
     * Validate the HTTP method
     *
     * @param string $method
     *
     * @return bool
     */
    private function checkMethod($method)
    {
        if (is_string($method) === false) {
            throw new UnexpectedTypeException($method, 'string');
        }

        if (in_array(strtoupper($method), $this->httpMethods) === true) {
            return true;
        }

        throw new InvalidArgumentException(sprintf('Unsupported HTTP method "%s"', $method));
    }

    /**
     * Test that an array contains only strings
     *
     * @param array $array
     *
     * @return bool
     */
    private function arrayContainsOnlyStrings(array $array)
    {
        return array_reduce($array, [__CLASS__, 'filterStringValue'], true);
    }

    /**
     * Test if a value is a string
     *
     * @param bool  $carry
     * @param mixed $item
     *
     * @return bool
     */
    private static function filterStringValue($carry, $item)
    {
        if (is_string($item) === false) {
            return false;
        }

        return $carry;
    }
}
