<?php
/**
 * User: Denis Popov
 * Date: 31.10.2017
 * Time: 09:34
 */

namespace App\Model;

/**
 * Class Downloader
 *
 * @package App\Model
 */
class Downloader
{
    /**
     * Force Download
     *
     * Generates headers that force a download to happen
     *
     * @param    mixed    filename (or an array of local file path => destination filename)
     * @param    mixed    the data to be downloaded
     * @param    bool     whether to try and send the actual file MIME type
     *
     * @return    void
     */
    function force_download($filename = '', $data = '', $set_mime = false)
    {
        if ($filename === '' OR $data === '') {
            return;
        } elseif ($data === null) {
            // Is $filename an array as ['local source path' => 'destination filename']?
            if (is_array($filename)) {
                if (count($filename) !== 1) {
                    return;
                }
                reset($filename);
                $filepath = key($filename);
                $filename = current($filename);
                if (is_int($filepath)) {
                    return;
                }
            } else {
                $filepath = $filename;
                $filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
                $filename = end($filename);
            }
            if (!@is_file($filepath) OR ($filesize = @filesize($filepath)) === false) {
                return;
            }
        } else {
            $filesize = strlen($data);
        }
        // Set the default MIME type to send
        $mime = 'application/octet-stream';
        $x = explode('.', $filename);
        $extension = end($x);
        if ($set_mime === true) {
            if (count($x) === 1 OR $extension === '') {
                /* If we're going to detect the MIME type,
                 * we'll need a file extension.
                 */
                return;
            }
            // Load the mime types
            $mimeType = config('mimes.' . $extension);
            // Only change the default MIME if we can find one
            if (isset($mimeType)) {
                $mime = is_array($mimeType) ? $mimeType[0] : $mimeType;
            }
        }
        /* It was reported that browsers on Android 2.1 (and possibly older as well)
         * need to have the filename extension upper-cased in order to be able to
         * download it.
         *
         * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
         */
        if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT'])
            && preg_match(
                '/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT']
            )) {
            $x[count($x) - 1] = strtoupper($extension);
            $filename = implode('.', $x);
        }
        // Clean output buffer
        if (ob_get_level() !== 0 && @ob_end_clean() === false) {
            @ob_clean();
        }
        // Generate the server headers
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $filesize);
        header('Cache-Control: private, no-transform, no-store, must-revalidate');
        // If we have raw data - just dump it
        if ($data !== null) {
            exit($data);
        }
        // Flush the file
        if (@readfile($filepath) === false) {
            return;
        }
        exit;
    }
}
