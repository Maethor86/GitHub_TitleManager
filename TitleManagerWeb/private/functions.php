<?php
function redirect_to($new_location) {
  header("Location: " . $new_location);
  exit;
}


function __autoload($class_name) {
  $output  = "The class \"{$class_name}\" could not be found. Trying to locate it ...";
  $output .= "<br />";
  $class_name = strtolower($class_name);
  $file = CLASS_PATH.DS."{$class_name}.php";
  if (file_exists($file)) {
    $output .= "Success! Located at {$file}.";
    $output .= "<br />";
    $output .= "Continuing ...";
    $output .= "<br />";
    require_once($file);
    $_SESSION["message"] = $output;
  }
  else {
    throw new CannotFindClassException("Could not find the class {$class_name} at {$file}. ");
  }

}


function load_layout($page_type) {
  switch ($page_type) {
    case "login":
        $layout_files_to_load["header"]        = LAYOUT_PATH.DS."standard".DS."header_standard.php";
        $layout_files_to_load["sidebar_left"]  = LAYOUT_PATH.DS."login".DS."sidebar_left_login.php";
        $layout_files_to_load["sidebar_right"] = LAYOUT_PATH.DS."login".DS."sidebar_right_login.php";
        $layout_files_to_load["footer"]        = LAYOUT_PATH.DS."login".DS."footer_login.php";
      break;

    case "standard":
        $layout_files_to_load["header"]        = LAYOUT_PATH.DS."standard".DS."header_standard.php";
        $layout_files_to_load["sidebar_left"]  = LAYOUT_PATH.DS."standard".DS."sidebar_left_standard.php";
        $layout_files_to_load["sidebar_right"] = LAYOUT_PATH.DS."standard".DS."sidebar_right_standard.php";
        $layout_files_to_load["footer"]        = LAYOUT_PATH.DS."standard".DS."footer_standard.php";
        $layout_files_to_load["sidebar_left_back"]  = LAYOUT_PATH.DS."sidebar_left_back.php";
        $layout_files_to_load["sidebar_left_back_main"]  = LAYOUT_PATH.DS."sidebar_left_back_main.php";
        $layout_files_to_load["sidebar_left_back_search"]  = LAYOUT_PATH.DS."sidebar_left_back_search.php";
        $layout_files_to_load["sidebar_left_back_browse"]  = LAYOUT_PATH.DS."sidebar_left_back_browse.php";
      break;

    default:
        throw new \Exception("Wrong page type input.");
      break;
  }
  return $layout_files_to_load;
}

function load_contents($page_type) {
  switch ($page_type) {

    case "standard":
      $content_files_to_load["title"]            = PUBLIC_PATH.DS."content_title.php";
      $content_files_to_load["subtitles"]        = PUBLIC_PATH.DS."content_subtitles.php";
    break;

    default:
        throw new \Exception("Wrong page type input.");
      break;
  }
  return $content_files_to_load;
}

function generate_datetime_for_sql() {
  $fractional_second_precision = SQL_DATETIME_FRAC_SEC_PRECISION;
  $time = microtime(TRUE);
  $milli_secs =  $time - floor($time);
  $milli_secs =  substr($milli_secs, 1, $fractional_second_precision + 1);
  $datetime_format = "Y-m-d H:i:s";
  $datetime =  date($datetime_format, $time) . $milli_secs;

  return $datetime;
}


// small functions
function make_page_title($page_title="Default") {
  $output = "<h2 class=\"page_header\">{$page_title}</h2>";
  return $output;
}


// error handling functions
function error_to_exception($error_code, $message, $file, $line) {
    global $logger;

    $e = new ErrorException($message, ExceptionCode_Error + $error_code, $error_code, $file, $line);

    // -- FATAL ERROR
    // throw an Error Exception, to be handled by whatever Exception handling logic is available in this context
    if (in_array($error_code, array(E_USER_ERROR, E_RECOVERABLE_ERROR))) {
      throw $e;
    }
    // -- NON-FATAL ERROR/WARNING/NOTICE
    else {
      $logger->log_error($e);
      return FALSE;
    }
}

// function shutdown() {
//   // This is our shutdown function, in
//   // here we can do any last operations
//   // before the script is complete.
//   $error = error_get_last();
//
//   if ($error["type"] == E_ERROR) {
//     global $logger;
//     $code = $error["type"];
//     $exception = new ErrorException($error["message"], ExceptionCode_Error + $error["type"]);
//     $exception instanceof CriticalDatabaseException ? $logger->log_error_simple($exception) : $logger->log_error($exception);
//     redirect_to("errorpage.php");
//   }
// }

function exception_handler(Throwable $exception) {
  if ($exception instanceof Error) {
    $exception = new ErrorException($exception->getMessage(), ExceptionCode_Error + $exception->getCode(), 0, $exception->getFile(), $exception->getLine(), $exception);
  }
  global $logger;
  $code = $exception->getCode();
  $exception instanceof CriticalDatabaseException ? $logger->log_error_simple($exception) : $logger->log_error($exception);

  $error_page = "errorpage.php";
  $error_form  = "<form action=\"{$error_page}\" method=\"POST\" id=\"errorForm\" >";
  $error_form .= "<input type=\"text\" name=\"ExceptionCode\" value=\"{$code}\" />";
  $error_form .= "<input type=\"submit\" />";
  $error_form .= "</form>";

  $submit  = "<script type=\"text/javascript\">";
  $submit .= "document.getElementById(\"errorForm\").submit()";
  $submit .= "</script>";

  echo $error_form;
  echo $submit;
}

set_error_handler("error_to_exception");
set_exception_handler("exception_handler");
// register_shutdown_function("shutdown");


// misc functions

function get_user_browser($user_agent = NULL) {

  if(!isset($user_agent) && isset($_SERVER['HTTP_USER_AGENT'])) {
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
  }
    $browser        =   "Unknown Browser";
    $browser_array  =   array(
                            '/msie|trident/i' =>  'Internet Explorer',
                            '/firefox/i'      =>  'Firefox',
                            '/safari/i'       =>  'Safari',
                            '/chrome/i'       =>  'Chrome',
                            '/edge/i'         =>  'Edge',
                            '/opera/i'        =>  'Opera',
                            '/netscape/i'     =>  'Netscape',
                            '/maxthon/i'      =>  'Maxthon',
                            '/konqueror/i'    =>  'Konqueror',
                            '/mobile/i'       =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }
    return $browser;
}

function get_user_OS($user_agent = NULL) {
    if(!isset($user_agent) && isset($_SERVER['HTTP_USER_AGENT'])) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    }

    // https://stackoverflow.com/questions/18070154/get-operating-system-info-with-php
    $os_array = [
        'windows nt 10'                              =>  'Windows 10',
        'windows nt 6.3'                             =>  'Windows 8.1',
        'windows nt 6.2'                             =>  'Windows 8',
        'windows nt 6.1|windows nt 7.0'              =>  'Windows 7',
        'windows nt 6.0'                             =>  'Windows Vista',
        'windows nt 5.2'                             =>  'Windows Server 2003/XP x64',
        'windows nt 5.1'                             =>  'Windows XP',
        'windows xp'                                 =>  'Windows XP',
        'windows nt 5.0|windows nt5.1|windows 2000'  =>  'Windows 2000',
        'windows me'                                 =>  'Windows ME',
        'windows nt 4.0|winnt4.0'                    =>  'Windows NT',
        'windows ce'                                 =>  'Windows CE',
        'windows 98|win98'                           =>  'Windows 98',
        'windows 95|win95'                           =>  'Windows 95',
        'win16'                                      =>  'Windows 3.11',
        'mac os x 10.1[^0-9]'                        =>  'Mac OS X Puma',
        'macintosh|mac os x'                         =>  'Mac OS X',
        'mac_powerpc'                                =>  'Mac OS 9',
        'linux'                                      =>  'Linux',
        'ubuntu'                                     =>  'Linux - Ubuntu',
        'iphone'                                     =>  'iPhone',
        'ipod'                                       =>  'iPod',
        'ipad'                                       =>  'iPad',
        'android'                                    =>  'Android',
        'blackberry'                                 =>  'BlackBerry',
        'webos'                                      =>  'Mobile',

        '(media center pc).([0-9]{1,2}\.[0-9]{1,2})' =>'Windows Media Center',
        '(win)([0-9]{1,2}\.[0-9x]{1,2})'             =>'Windows',
        '(win)([0-9]{2})'                            =>'Windows',
        '(windows)([0-9x]{2})'                       =>'Windows',

        // Doesn't seem like these are necessary...not totally sure though..
        //'(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'Windows NT',
        //'(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})'=>'Windows NT', // fix by bg

        'Win 9x 4.90'                                =>'Windows ME',
        '(windows)([0-9]{1,2}\.[0-9]{1,2})'          =>'Windows',
        'win32'                                      =>'Windows',
        '(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})' =>'Java',
        '(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}'    =>'Solaris',
        'dos x86'                                    =>'DOS',
        'Mac OS X'                                   =>'Mac OS X',
        'Mac_PowerPC'                                =>'Macintosh PowerPC',
        '(mac|Macintosh)'                            =>'Mac OS',
        '(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'       =>'SunOS',
        '(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'        =>'BeOS',
        '(risc os)([0-9]{1,2}\.[0-9]{1,2})'          =>'RISC OS',
        'unix'                                       =>'Unix',
        'os/2'                                       =>'OS/2',
        'freebsd'                                    =>'FreeBSD',
        'openbsd'                                    =>'OpenBSD',
        'netbsd'                                     =>'NetBSD',
        'irix'                                       =>'IRIX',
        'plan9'=>'Plan9',
        'osf'=>'OSF',
        'aix'=>'AIX',
        'GNU Hurd'=>'GNU Hurd',
        '(fedora)'=>'Linux - Fedora',
        '(kubuntu)'=>'Linux - Kubuntu',
        '(ubuntu)'=>'Linux - Ubuntu',
        '(debian)'=>'Linux - Debian',
        '(CentOS)'=>'Linux - CentOS',
        '(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - Mandriva',
        '(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - SUSE',
        '(Dropline)'=>'Linux - Slackware (Dropline GNOME)',
        '(ASPLinux)'=>'Linux - ASPLinux',
        '(Red Hat)'=>'Linux - Red Hat',
        // Loads of Linux machines will be detected as unix.
        // Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
        //'X11'=>'Unix',
        '(linux)'=>'Linux',
        '(amigaos)([0-9]{1,2}\.[0-9]{1,2})'=>'AmigaOS',
        'amiga-aweb'=>'AmigaOS',
        'amiga'=>'Amiga',
        'AvantGo'=>'PalmOS',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1}-([0-9]{1,2}) i([0-9]{1})86){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1} i([0-9]{1}86)){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1})'=>'Linux',
        '[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})'=>'Linux',
        '(webtv)/([0-9]{1,2}\.[0-9]{1,2})'=>'WebTV',
        'Dreamcast'=>'Dreamcast OS',
        'GetRight'=>'Windows',
        'go!zilla'=>'Windows',
        'gozilla'=>'Windows',
        'gulliver'=>'Windows',
        'ia archiver'=>'Windows',
        'NetPositive'=>'Windows',
        'mass downloader'=>'Windows',
        'microsoft'=>'Windows',
        'offline explorer'=>'Windows',
        'teleport'=>'Windows',
        'web downloader'=>'Windows',
        'webcapture'=>'Windows',
        'webcollage'=>'Windows',
        'webcopier'=>'Windows',
        'webstripper'=>'Windows',
        'webzip'=>'Windows',
        'wget'=>'Windows',
        'Java'=>'Unknown',
        'flashget'=>'Windows',

        // delete next line if the script show not the right OS
        //'(PHP)/([0-9]{1,2}.[0-9]{1,2})'=>'PHP',
        'MS FrontPage'=>'Windows',
        '(msproxy)/([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
        '(msie)([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
        'libwww-perl'=>'Unix',
        'UP.Browser'=>'Windows CE',
        'NetAnts'=>'Windows',
    ];

    // https://github.com/ahmad-sa3d/php-useragent/blob/master/core/user_agent.php
    $arch_regex = '/\b(x86_64|x86-64|Win64|WOW64|x64|ia64|amd64|ppc64|sparc64|IRIX64)\b/ix';
    $arch = preg_match($arch_regex, $user_agent) ? '64' : '32';

    foreach ($os_array as $regex => $value) {
        if (preg_match('{\b('.$regex.')\b}i', $user_agent)) {
            return $value.' (x'.$arch.')';
        }
    }

    return 'Unknown';
}

function sql_show_current_user() {
  // displays the logged in user

  global $database;
  $query = "SELECT CONVERT(varchar(32), SUSER_SNAME())";
  $params = array();
  $result_set = $database->query($query, $params);
  if ($result_set) {
    $sql_user = $database->fetch_array($result_set);
  }
  $output = "Currently logged in to SQL Server with SQL user: ".reset($sql_user);
  return $output;

}

function sql_show_current_database() {

  global $database;
  $query = "SELECT CONVERT(varchar(32), DB_NAME())";
  $params = array();
  $result_set = $database->query($query, $params);
  if ($result_set) {
    $sql_database = $database->fetch_array($result_set);
  }
  $output = "Currently using the SQL Server database: ".reset($sql_database);
  return $output;
}

function sql_show_sqlversion() {

  global $database;
  $query = "SELECT @@Version as SQL_VERSION";
  $params = array();
  $result_set = $database->query($query, $params);
  if ($result_set) {
    $sql_version = $database->fetch_array($result_set);
  }
  $output = "Currently using SQL Server version: ".reset($sql_version);
  return $output;
}


?>
