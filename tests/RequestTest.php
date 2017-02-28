<?php

/*
 * This file is part of the stream project.
 *
 * (c) OsLab <https://github.com/OsLab>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OsLab\Tests;

use Psr\Http\Message\UriInterface;
use OsLab\StreamHttp\Request;
use OsLab\StreamHttp\Uri;

/**
 * @author Michael COULLERET <michael@coulleret.pro>
 * @author Florent DESPIERRES <orions07@gmail.com>
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    private $request;

    public function setUp()
    {
        $this->request = new Request('https://example.com');
    }

    public function testConstructorRaisesExceptionInvalidArgumentExceptionMethod()
    {
        $this->expectException('InvalidArgumentException');

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
