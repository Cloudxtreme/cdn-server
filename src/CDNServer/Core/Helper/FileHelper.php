<?php

namespace CDNServer\Core\Helper;

/**
 * Class FileHelper
 * @package CDNServer\Core\Helper
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class FileHelper
{
    /**
     * @param $filename
     * @return string
     */
    public static function  getName($filename)
    {
        $name = parse_url($filename, PHP_URL_PATH);
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        return substr($name, 0, strlen($name) - strlen($extension));
    }

    /**
     * @param $filename
     * @return string
     */
    public static function  getExtensionFromFilename($filename)
    {
        return pathinfo(parse_url($filename, PHP_URL_PATH), PATHINFO_EXTENSION);
    }

    /**
     * @param $stream
     * @return null|string
     */
    public static function  getExtensionFromStream($stream)
    {
        $finfo = new \finfo(FILEINFO_MIME);
        $mime = $finfo->buffer($stream);
        return FileHelper::getExtensionFromMimetype($mime);
    }

    /**
     * @param $mimetype
     * @return null|string
     */
    public static function  getExtensionFromMimetype($mimetype)
    {
        switch ($mimetype)
        {
            case 'text/css': return 'css';
            case 'text/csv': return 'csv';
            case 'text/html': return 'html';
            case 'text/javascript': return 'js';
            case 'text/x-markdown': return 'md';
            case 'text/xml': return 'xml';
            case 'text/rtf': return 'rtf';
            case 'image/jpeg': return 'jpeg';
            case 'image/png': return 'png';
            case 'image/gif': return 'gif';
            case 'image/svg+xml': return 'svg';
            case 'image/tiff': return 'tiff';
            case 'audio/mp4': return 'mp4';
            case 'audio/mpeg': return 'mp3';
            case 'audio/ogg': return 'ogg';
            case 'audio/vnd.wave': return 'wav';
            case 'audio/webm': return 'webm';
            case 'audio/x-aac': return 'aac';
            case 'video/avi': return 'avi';
            case 'video/mpeg': return 'mpeg';
            case 'video/webm': return 'webm';
            case 'video/mp4': return 'mp4';
            case 'application/pdf': return 'pdf';
            case 'application/javascript': return 'js';
            case 'application/x-javascript': return 'js';
            case 'application/font-woff': return 'woff';
            case 'application/x-font-woff': return 'woff';
            case 'application/x-font-otf': return 'otf';
            case 'application/zip': return 'zip';
            case 'application/gzip': return 'gzip';
            case 'application/x-tar': return 'tar';
            case 'application/x-rar-compressed': return 'rar';
            case 'application/x-7z-compressed': return '7z';
            case 'application/x-latex': return 'tex';
            case 'application/x-shockwave-flash': return 'swf';
            default: return null;
        }
    }
} 