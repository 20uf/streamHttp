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

/**
 * Client.
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 */
class Client
{
    /**
     * @param string $url
     * @param string $method
     * @param array  $headers
     * @param string $content
     */
    public function request($url, $method = 'GET', array $headers = [], $content = null)
    {
        $options = [
            'http' => [
                'method' => $method,
                'header' => $headers,
                'content' => $content,
            ]
        ];

        $context = stream_context_create($options);

        file_get_contents($url, false, $context);
    }
}
