<?php

if (! function_exists('extract_host_from_url')) {
    /**
     * Extracts host name from a URL.
     *
     * @param  string  $url
     * @return string|null
     */
    function extract_host_from_url($url)
    {
        $parsedUrl = parse_url($url);

        // Check if the host name exists in the parsed URL
        if (isset($parsedUrl['host'])) {
            $host = $parsedUrl['host'];

            // Remove www. prefix if exists
            $host = Illuminate\Support\Str::startsWith($host, 'www.') ? substr($host, 4) : $host;

            // Remove port number if exists
            $host = strtok($host, ':');

            return $host;
        }

        // Return null if the host name couldn't be extracted
        return null;
    }
}
