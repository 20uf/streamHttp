<?php

/*
 * This file is part of the stream project.
 *
 * (c) OsLab <https://github.com/OsLab>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\StreamHttp;

use Psr\Http\Message\UriInterface;
use OsLab\StreamHttp\Exception\InvalidArgumentException;
use OsLab\StreamHttp\Exception\UnexpectedTypeException;

/**
 * Value object representing a URI.
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class Uri implements UriInterface
{
    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $port;

    /**
     * @var string
     */
    private $path = '/';

    /**
     * @var array
     */
    private $query;

    /**
     * @var string
     */
    private $queryString;

    /**
     * @var string
     */
    private $fragment;

    /**
     * @var string
     */
    private $uriString;

    /**
     * @var array
     */
    protected $allowedSchemes = [
        'http'  => 80,
        'https' => 443,
    ];

    /**
     * constructor Uri
     *
     * @param string|null $uri
     */
    public function __construct($uri = null)
    {
        if (is_string($uri) === false) {
            throw new UnexpectedTypeException($uri, 'string');
        }

        $this->parseUri($uri);
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority()
    {
        if ($this->host === null) {
            return '';
        }

        $authority = $this->host;

        if ($this->user !== null) {
            $authority = sprintf('%s@%s', $this->host, $this->user);
        }

        if ($this->port !== null) {
            $authority .= sprintf(':%s', $this->port);
        }

        return $authority;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     */
    public function withScheme($scheme)
    {
        if (is_string($scheme) === false) {
            throw new UnexpectedTypeException($scheme, 'string');
        }

        $scheme = $this->checkScheme($scheme);

        if ($scheme === $this->scheme) {
            return clone $this;
        }

        $clone = clone $this;
        $clone->scheme = $this->scheme;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withUserInfo($user, $password = null)
    {
        if (is_string($user) === false) {
            throw new UnexpectedTypeException($user, 'string');
        }

        if (($password !== null) && (is_string($password) === false)) {
            throw new UnexpectedTypeException($password, 'string');
        }

        if ($password !== null) {
            $user = sprintf('%s:%s', $user, $password);
        }

        if ($user === $this->user) {
            return clone $this;
        }

        $clone = clone $this;
        $clone->user = $user;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withHost($host)
    {
        if (is_string($host) === false) {
            throw new UnexpectedTypeException($host, 'string');
        }

        if ($host === $this->host) {
            return clone $this;
        }

        $clone = clone $this;
        $clone->host = $host;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withPort($port)
    {
        if ((is_numeric($port) === false) && ($port !== null)) {
            throw new UnexpectedTypeException($port, 'null or integer');
        }

        if ($port === $this->port) {
            return clone $this;
        }

        if ($port !== null && $port < 1 || $port > 65535) {
            throw new InvalidArgumentException(sprintf('Invalid TCP/UDP port "%d"', $port));
        }

        $clone = clone $this;
        $clone->port = $clone;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath($path)
    {
        if (is_string($path) === false) {
            throw new UnexpectedTypeException($path, 'string');
        }

        if (strpos($path, '?') !== false) {
            throw new InvalidArgumentException('Invalid path provided, must not contain a query string');
        }

        if (strpos($path, '#') !== false) {
            throw new InvalidArgumentException('Invalid path provided, must not contain a URI fragment');
        }

        if ($path === $this->path) {
            return clone $this;
        }

        $clone = clone $this;
        $clone->path = $path;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery($query)
    {
        if (is_string($query) === false) {
            throw new InvalidArgumentException('Query string must be a string');
        }

        if (strpos($query, '#') !== false) {
            throw new InvalidArgumentException('Query string must not include a URI fragment');
        }

        $query = $this->queryString;

        if ($query === $this->query) {
            return clone $this;
        }

        $clone = clone $this;
        $clone->query = $query;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withFragment($fragment)
    {
        if (is_string($fragment) === false) {
            throw new UnexpectedTypeException($fragment, 'string');
        }

        $fragment = $this->fragment;

        if ($fragment === $this->fragment) {
            return clone $this;
        }

        $clone = clone $this;
        $clone->fragment = $fragment;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        if ($this->uriString !== null) {
            return $this->uriString;
        }

        $this->uriString = static::create(
            $this->scheme,
            $this->getAuthority(),
            $this->getPath(),
            $this->queryString,
            $this->fragment
        );

        return $this->uriString;
    }

    /**
     * Object Cloning
     */
    public function __clone()
    {
        $this->uriString = null;
    }

    /**
     * {@inheritdoc}
     */
    public static function create($scheme, $authority, $path, $query, $fragment)
    {
        $uri = '';

        if (empty($scheme) === false) {
            $uri .= sprintf('%s://', $scheme);
        }

        if (empty($authority) === false) {
            $uri .= $authority;
        }

        if ($path !== null) {
            if (empty($path) === true || '/' !== substr($path, 0, 1)) {
                $path = '/' . $path;
            }

            $uri .= $path;
        }

        if ($query !== null) {
            $uri .= sprintf('?%s', $query);
        }

        if ($fragment !== null) {
            $uri .= sprintf('#%s', $fragment);
        }

        return $uri;
    }

    /**
     * Parse a URI into its parts, and set the properties
     *
     * @param string $uri
     */
    private function parseUri($uri)
    {
        $components = parse_url($uri);

        if ($components === false) {
            throw new \InvalidArgumentException('The source URI string appears to be malformed');
        }

        if (isset($components['host'])) {
            $this->host = $components['host'];
        }

        if (isset($components['scheme'])) {
            $this->scheme = $this->checkScheme($components['scheme']);
        }

        if (isset($components['user'])) {
            $this->user = $components['user'];
        }

        if (isset($components['pass'])) {
            $this->user .= ':' . $components['pass'];
        }

        if (isset($components['port'])) {
            $this->port = $components['port'];
        }

        if (isset($components['fragment'])) {
            $this->fragment = $components['fragment'];
        }

        if (isset($components['path'])) {
            $this->path = $components['path'];
        }

        if (isset($components['query'])) {
            $this->queryString = $components['query'];

            parse_str(html_entity_decode($components['query']), $qs);

            $this->query = $qs;
        }
    }

    /**
     * Check that the scheme is valid
     *
     * @param string $scheme Scheme name.
     *
     * @return string
     */
    private function checkScheme($scheme)
    {
        $scheme = preg_replace('#:(//)?$#', '', strtolower($scheme));

        if (empty($scheme) === true) {
            return '';
        }

        if (array_key_exists($scheme, $this->allowedSchemes) === false) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported scheme "%s", must be in the following list (%s)',
                $scheme,
                implode(', ', array_keys($this->allowedSchemes))
            ));
        }

        return $scheme;
    }
}
