<?php
function ldapGleaner($uniqname)
{
  ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
  $ds=ldap_connect("ldap.umich.edu");  // must be a valid LDAP server!
    if ($ds) { // this is an "anonymous" bind, typically read-only access
      // echo "Binding ...";
      $r=ldap_bind($ds);
        // Search surname entry
        $sr=ldap_search($ds, "ou=People,dc=umich,dc=edu", "uid=$uniqname");
        $info = ldap_get_entries($ds, $sr);
        if (count($info) > 1 ){
            if (array_key_exists('cn', $info[0])) {
                if (strlen($info[0]["cn"][0]) > 0) {
                    $str = explode(" ", $info[0]["cn"][0]);
                    $firstName = $str[0];
                    $lastName = $str[count($str) - 1];
                }
            } else {
                $firstName = "----";
                $lastName = "----";
            }
            if (array_key_exists('umichpostaladdress', $info[0])) {
                if (strlen($info[0]["umichpostaladdress"][0]) > 0) {
                    $str = explode(" $ ", $info[0]["umichpostaladdress"][0]);
                    $department = $str[0];
                }
            } else {
                $department = "----";
            }
            ldap_close($ds);
            return array($firstName, $lastName, $department);
        }
    } else {
      echo "<h4>Unable to connect to LDAP server</h4>";
    }
}

function print_r2($val)
{
    echo '<pre>';
        print_r($val);
    echo  '</pre>';
}

function getUTCTime($tz = 'UTC')
{
    date_default_timezone_set($tz);
    return time();
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// *** PAGE REDIRECT FUNCTIONS ***

/* Redirects to specified address if passed to function
*   URL = is set in the applications config file
*   --See also safeRedirect and forceRedirect below
*/
function redirect_to($url = URL)
{
    header('Location: ' . $url);
    exit;
}

// The following are from https://github.com/heinkasner/PHP-Library/blob/master/extra.php
/**
 * Performs a safe redirect to another url
 * URL is set in the application config file
 * @param <string> $url Url to redirect to
 * @param <boolean> $exit (optional) Exit the operation or not
 * @category Extra
 * <code>
 *  $result = Extra::safeRedirect('http://www.google.co.za');
 * </code>
 */
function safeRedirect($url = URL, $exit = TRUE) {
    try {
        // Only use the header redirection if headers are not already sent
        if (!headers_sent()) {
            //header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);
            // Optional workaround for an IE bug (thanks Olav)
            header("Connection: close");
        }
        // HTML/JS Fallback:
        // If the header redirection did not work, try to use various methods other methods
        print '<html>';
        print '<head><title>Redirecting you...</title>';
        print '<meta http-equiv="Refresh" content="0;url=' . $url . '" />';
        print '</head>';
        print '<body onload="location.replace(\'' . $url . '\')">';
        // If the javascript and meta redirect did not work,
        // the user can still click this link
        print 'You should be redirected to this URL:<br />';
        print "<a href='$url'>$url</a><br /><br />";
        print 'If you are not, please click on the link above.<br />';
        print '</body>';
        print '</html>';
        // Stop the script here (optional)
        if ($exit) {
            exit;
        } else {
            echo "didn't exit";
        }
    } catch (Exception $err) {
        //echo $err->getMessage();
        non_db_error($err->getMessage());
        return $err->getMessage();
    }
}
/**
 * Function forces a redirect to the url specified within a given number of seconds
 * @param <string> $url Url to redirect to
 * @param <integer> $delay (optional) Delay in seconds that the url should redirect
 * @category Extra
 * <code>
 *  $result = Extra::forceRedirect('http://www.go.com', 1);
 * </code>
 */
function forceRedirect($url = URL, $delay = 0) {
    try {
        if (!headers_sent() && $delay == 0) {
            ob_end_clean();
            header("Location: " . $url);
        }
        // Performs a redirect once headers have been sent
        echo "<meta http-equiv=\"Refresh\" content=\"" . $delay . "; URL=" . $url . "\">";
        exit();
    } catch (Exception $err) {
        non_db_error($err->getMessage());
        return $err->getMessage();
    }
}
