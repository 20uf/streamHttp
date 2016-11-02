<?php

/*
 * This file is part of the stream project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StreamHttp;

use Psr\Http\Message\UriInterface;
use InvalidArgumentException;

/**
 * Value object representing a URI.
 *
 * @author Michael COULLERET <michael@coulleret.pro>
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
    private $path;

    /**
     * @var string
     */
    private $query;

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
            throw new InvalidArgumentException(sprintf('URI passed to constructor must be a string, %s given', gettype($uri)));
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
     * Retrieve the authority component of the URI.
     *
     * If no authority information is present, this method MUST return an empty
     * string.
     *
     * The authority syntax of the URI is:
     *
     * <pre>
     * [user-info@]host[:port]
     * </pre>
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     *
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority()
    {
        // TODO: Implement getAuthority() method.
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
     * Return an instance with the specified scheme.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified scheme.
     *
     * Implementations MUST support the schemes "http" and "https" case
     * insensitively, and MAY accommodate other schemes if required.
     *
     * An empty scheme is equivalent to removing the scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     * @return static A new instance with the specified scheme.
     * @throws \InvalidArgumentException for invalid or unsupported schemes.
     */
    public function withScheme($scheme)
    {
        // TODO: Implement withScheme() method.
    }

    /**
     * Return an instance with the specified user information.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified user information.
     *
     * Password is optional, but the user information MUST include the
     * user; an empty string for the user is equivalent to removing user
     * information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return static A new instance with the specified user information.
     */
    public function withUserInfo($user, $password = null)
    {
        // TODO: Implement withUserInfo() method.
    }

    /**
     * Return an instance with the specified host.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified host.
     *
     * An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return static A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host)
    {
        // TODO: Implement withHost() method.
    }

    /**
     * Return an instance with the specified port.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified port.
     *
     * Implementations MUST raise an exception for ports outside the
     * established TCP and UDP port ranges.
     *
     * A null value provided for the port is equivalent to removing the port
     * information.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *     removes the port information.
     * @return static A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort($port)
    {
        // TODO: Implement withPort() method.
    }

    /**
     * Return an instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If the path is intended to be domain-relative rather than path relative then
     * it must begin with a slash ("/"). Paths not starting with a slash ("/")
     * are assumed to be relative to some base path known to the application or
     * consumer.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     * @return static A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath($path)
    {
        // TODO: Implement withPath() method.
    }

    /**
     * Return an instance with the specified query string.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified query string.
     *
     * Users can provide both encoded and decoded query characters.
     * Implementations ensure the correct encoding as outlined in getQuery().
     *
     * An empty query string value is equivalent to removing the query string.
     *
     * @param string $query The query string to use with the new instance.
     * @return static A new instance with the specified query string.
     * @throws \InvalidArgumentException for invalid query strings.
     */
    public function withQuery($query)
    {
        // TODO: Implement withQuery() method.
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified URI fragment.
     *
     * Users can provide both encoded and decoded fragment characters.
     * Implementations ensure the correct encoding as outlined in getFragment().
     *
     * An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return static A new instance with the specified fragment.
     */
    public function withFragment($fragment)
    {
        // TODO: Implement withFragment() method.
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        if ($this->uriString !== null) {
            return $this->uriString;
        }
    }

    /**
     * Object Cloning
     */
    public function __clone()
    {
        $this->uriString = null;
    }

    /**
     * Parse a URI into its parts, and set the properties
     *
     * @param string $uri
     */
    private function parseUri($uri)
    {
        $parsedUrl = parse_url($uri);

        if ($parsedUrl === false) {
            throw new \InvalidArgumentException('The source URI string appears to be malformed');
        }

        $this->scheme = isset($parsedUrl['scheme']) ? $this->isValidScheme($parsedUrl['scheme']) : '';
        $this->user = isset($parsedUrl['user']) ? $parsedUrl['user']     : '';
        $this->host = isset($parsedUrl['host']) ? $parsedUrl['host']     : '';
        $this->port = isset($parsedUrl['port']) ? $parsedUrl['port']     : null;
        $this->path = isset($parsedUrl['path']) ? $this->path : '';
        $this->query = isset($parsedUrl['query']) ? $this->query : '';
        $this->fragment = isset($parsedUrl['fragment']) ? $this->fragment : '';

        if (isset($parts['pass'])) {
            $this->user .= ':' . $parsedUrl['pass'];
        }
    }

    /**
     * Verifies that the scheme is valid
     *
     * @param string $scheme Scheme name.
     *
     * @return string
     */
    private function isValidScheme($scheme)
    {
        $scheme = strtolower($scheme);
        $scheme = preg_replace('#:(//)?$#', '', $scheme);

        if (empty($scheme)) {
            return '';
        }

        if (array_key_exists($scheme, $this->allowedSchemes) === false) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported scheme "%s"; must be in the following list (%s)',
                $scheme,
                implode(', ', array_keys($this->allowedSchemes))
            ));
        }

        return $scheme;
    }
}
