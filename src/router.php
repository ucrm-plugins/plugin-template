<?php
declare(strict_types=1);

// Get the currently requested URI.
use UCRM\Common\Plugin;

$uri = $_SERVER["REQUEST_URI"];

$pluginName = basename(realpath(__DIR__."/../"));


if($uri && $uri === "/public")
{
    header("Location: /public/");
    exit();
}


if($uri && strpos($uri, "/public/") === 0)
    return false;



if($uri && ($uri === "/public.php" || $uri === "/public.php?" ))
{
    header("Location: /public.php?/");
    exit();
}

// IF the URI is for the root, the "/public" folder or the "index.html" file, THEN redirect to our SPA...
if($uri === "/" || $uri === "/public" || $uri === "/public/" || strpos($uri, "/public/index.html") === 0)
{
    // NOTE: In this case, I believe it's best to drop any query string provided, as we are moving into our SPA!
    header("Location: /public.php?/");
    exit();
}


// =====================================================================================================================
// REMOTE ASSETS
// =====================================================================================================================
// Here we proxy requests for '/assets/' and '/dist.' to a valid UCRM server, so that we can use UCRM's built-in assets
// during development.  This includes fonts, icons, images, stylesheets, scripts and more.
//
// NOTE: Feel free to continue using my own UCRM development server for this purpose, or change the following URL to
// your desired location.

/** @const The UCRM Server from which to query remote assets. */
const UCRM_SERVER_URL = "https://ucrm.dev.mvqn.net";

// IF the URI is valid and begins with "/assets/" or "/dist/", THEN we need to proxy the request...
if ($uri && (strpos($uri, "/assets/") === 0) || (strpos($uri, "/dist/") === 0))
{
    // Construct the remote URI, given the specified UCRM Server.
    $url = UCRM_SERVER_URL . $uri;

    // Get the headers from the remote site and locate the status code (here as a substring).
    $headers = @get_headers($url);

    // IF retrieving the headers was successful, THEN we can get the remote assets...
    if($headers)
    {
        $status = substr($headers[0], 9, 3);

        // Add all headers from the result to the current response.
        foreach($headers as $header)
            header($header);

        // IF the status code is HTTP 200 "OK", THEN return the remote contents!
        if($status === "200")
            echo file_get_contents(UCRM_SERVER_URL.$uri);
    }

    exit();
}

// =====================================================================================================================
// SOURCE ASSETS
// =====================================================================================================================
// Here we serve requests for '/public/' directly, so that we can use the source files directly without the need for
// additional servers running and to prevent us from having to use multiple relative URLs in the source files.

/**
 * Gets the current list of MIME types from the Apache Foundation and caches the results.
 *
 * @param string $uri The URI of the resource for which to determine the MIME type.
 * @param bool $update If TRUE, forces an update of the list, even when a cached version exists.
 *
 * @return string|null Returns the MIME type as a string representation, NULL if unknown.
 */
function getMimeType(string $uri, bool $update = false): ?string
{
    // IF the cache file does not exist OR an update is requested, THEN get the latest information from the website...
    if(!file_exists(__DIR__."/.cache/mime-types.json") || $update)
    {
        // IF the cache folder does not exist, THEN create it!
        if(!file_exists(__DIR__."/.cache"))
            mkdir(__DIR__."/.cache");

        // Initialize the full list of MIME types.
        $types = [];

        // Get the MIME type list from the Apache Foundation's website.
        $contents = file_get_contents("http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types");

        // Loop through each line of the
        foreach(explode("\n", $contents) as $x)
        {
            // IF the current line is not empty, not a comment, THEN use RegEx to get the line values...
            if (isset($x[0]) && $x[0] !== "#" &&
                preg_match_all('#([^\s]+)#', $x, $out) &&
                isset($out[1]) && ($c = count($out[1])) > 1)
                // Loop through each file extension listed and pair it with it's MIME type in the array.
                for($i = 1; $i < $c; $i++)
                    $types[$out[1][$i]] = $out[1][0];
        }

        // Sort the list by extension.
        ksort($types);

        // Convert the array into JSON.
        $json = json_encode($types, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Save the JSON to the cache file for later lookup.
        file_put_contents(__DIR__."/.cache/mime-types.json", $json);
    }

    // Load the MIME types from the cache and convert them to an associative array.
    $mimes = json_decode(file_get_contents(__DIR__."/.cache/mime-types.json"), true);

    // Get the current resource's file extension.
    $extension = pathinfo($uri)["extension"];

    switch($extension)
    {
        // Source Maps
        case "map":
            $mime = "application/json";
            break;

        //
        // NOTE: Add any extension types that are not included in the Apache MIME types list!
        //

        default:
            $mime = array_key_exists($extension, $mimes) ? $mimes[$extension] : null;
    }

    return $mime;
}



// IF the URI is valid and is either the root or begins with "/public/", THEN we need to handle the request ourselves...
if ($uri && ($uri === "/" || $uri === "/public" || strpos($uri, "/public/") === 0) ||
    $uri === "/public.php" || $uri === "/public.php?" || $uri === "/public.php?/")
{
    /*
    // IF the URI is for the root, the "/public" folder or the "index.html" file, THEN redirect to our SPA...
    if($uri === "/" || $uri === "/public" || $uri === "/public/" || strpos($uri, "/public/index.html") === 0)
    {
        // NOTE: In this case, I believe it's best to drop any query string provided, as we are moving into our SPA!
        header("Location: /public.php?/");
        exit();
    }
    */


    if($uri === "/public.php" || $uri === "/public.php?" || $uri === "/public.php?/")
        $uri = "/public/index.html";


    $uriPath = __DIR__ . $uri;

    if(!file_exists($uriPath))
    {
        header("HTTP/1.1 404 Not Found");
        echo "The URL '$uri' does not exist!";
        exit();
    }

    // Get the requested URL's MIME type.
    $mime = getMimeType($uriPath);

    // Set the content type and echo the URL's file contents directly!
    header("Content-Type: $mime");

    // NOTE: This is ALWAYS the case, as this file is only present in "dev" mode!
    //echo Plugin::environment() === "dev" ?
    //echo str_replace("/_plugins/${pluginName}/public/", "/public/", file_get_contents($uriPath));
    //: file_get_contents($uriPath);
    echo file_get_contents($uriPath);

    exit();
}

//
// IF the request is NOT to be provided via one of the proxies above, THEN we continue...
//

chdir(__DIR__);

// Get the filename part of the requested URL.
$file = explode("?", $uri)[0];

// Check to see if the request path exists, and resolve as needed.
$filePath = realpath(ltrim($file, "/"));

// IF the request path exists AND is a directory, THEN attempt to find an index file...
if($filePath && is_dir($filePath))
{
    // Loop through each of the supported index files...
    foreach ([ "index.php", "index.html" ] as $indexFile)
        // IF the file exists, THEN set it as the new file path and break!
        if($filePath = realpath($filePath.DIRECTORY_SEPARATOR.$indexFile))
            break;
}


// IF the request path exists AND is a file, THEN attempt to serve it...
if ($filePath && is_file($filePath))
{
    // First, check the following restrictions:
    // 1. That the file is not outside of this directory for security purposes.
    // 2. That the file is "router.php" to prevent a circular reference.
    // 3. That the file is not a dot file (i.e. ".", ".." or any ".filename" type file.
    if (strpos($filePath, __DIR__ . DIRECTORY_SEPARATOR) === 0 &&
        $filePath != __DIR__ . DIRECTORY_SEPARATOR . "router.php" &&
        substr(basename($filePath), 0, 1) != ".")
    {
        // IF the file contains a PHP file extension...
        if (strtolower(substr($filePath, -4)) === '.php')
        {
            // ...IF the requested resource the /public.php script, THEN "include" the file...
            if($filePath === realpath(__DIR__ . "/public.php"))
            {
                /** @noinspection PhpIncludeInspection */
                include $filePath;
                exit();
            }
            else
            {
                // ...OTHERWISE, another PHP script has been requested.

                // NOTE: This is only here to simulate direct access from within the UCRM system.
                header("HTTP/1.1 403 Forbidden");
                echo "UCRM only allows /public.php and files from the /public folder to be served directly!";
            }
        }
        else
        {
            // ...OTHERWISE, serve the requested resource, AS IS!
            //return false;

            // NOTE: This is only here to simulate direct access from within the UCRM system.  Files in the /public
            // folder are already handled above!
            header("HTTP/1.1 403 Forbidden");
            echo "UCRM only allows /public.php and files from the /public folder to be served directly!";
        }
    }
    else
    {
        // ...OTHERWISE, this is a forbidden file!

        header("HTTP/1.1 403 Forbidden");
        echo "The file '$filePath' is not a supported file type!";
    }
}
else
{
    // ...OTHERWISE, we need to simply serve the "public.php" file for the plugin.
    //include __DIR__ . DIRECTORY_SEPARATOR . "public.php";

    header("HTTP/1.1 404 Not Found");
    echo "The URL '$file' was not found!";
}
