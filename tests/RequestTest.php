<?php

/*
 * This file is part of the stream project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use Psr\Http\Message\UriInterface;
use StreamHttp\Request;
use StreamHttp\Uri;

/**
 * Class RequestTest
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    private $request;

    public function setUp()
    {
        $this->request = new Request('https://example.com');
    }

    public function testConstructorRaisesExceptionInvalidArgumentExceptionMethod()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Request(null, null);
    }

    public function testGetUriReturnsInstanceUriInterface()
    {
        $request = new Request('https://example.com');

        $this->assertTrue($request->getUri() instanceof UriInterface);
    }

    public function testWithUriReturnsNewInstanceUriInterface()
    {
        $request = $this->request->withUri(new Uri('https://example.com:443/foo/bar?key=value'));

        $this->assertNotSame($request, $this->request);
    }
}

