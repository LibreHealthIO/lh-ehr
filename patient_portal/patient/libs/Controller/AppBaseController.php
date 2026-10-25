<?php
/** @package    Patient Portal::Controller */

/** import supporting libraries */
require_once("verysimple/Phreeze/Controller.php");
require_once (dirname( __FILE__ )."/../../../lib/appsql.class.php" );
/**
 * AppBaseController is a base class Controller class from which
 * the front controllers inherit.  it is not necessary to use this
 * class or any code, however you may use if for application-wide
 * functions such as authentication
 *
 * From phreeze package
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 *
 * @package Patient Portal::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class AppBaseController extends Controller
{

    static $DEFAULT_PAGE_SIZE = 20;

    /**
     * Init is called by the base controller before the action method
     * is called.  This provided an oportunity to hook into the system
     * for all application actions.  This is a good place for authentication
     * code.
     */
    /**
     * Init is called by the base controller before the action method
     * is called. Enforces authentication for all controller actions.
     * HIPAA 45 CFR 164.312(a)(1) Access Control
     */
    protected function Init()
    {
        parent::Init();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $isPortalUser = (isset($_SESSION['pid']) && !empty($_SESSION['patient_portal_onsite']));
        $isEHRUser    = (isset($_SESSION['authUserID']) && !empty($_SESSION['authUserID']));

        // Exempt public login endpoints if router is available
        $uri = '';
        if ($this->GetRouter()) {
            $uri = strtolower($this->GetRouter()->GetUri());
        }
        $exemptUris = array('login', 'loginform', 'logout', 'default.login', 'default.loginform');

        if (!in_array($uri, $exemptUris)) {
            if (!$isPortalUser && !$isEHRUser) {
                $landingpage = (isset($GLOBALS['web_root']) ? $GLOBALS['web_root'] : '') . "/patient_portal/index.php";
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('HTTP/1.1 401 Unauthorized');
                    echo json_encode(array('success' => false, 'message' => 'Authentication required to access PHI.'));
                    exit;
                }
                header('Location: ' . $landingpage);
                exit;
            }
        }
    }

    /**
     * Enforce patient-level authorization for PHI access.
     * Prevents Insecure Direct Object References (IDOR) where a patient could access another patient's records.
     * HIPAA 45 CFR 164.312(a)(1) Access Control
     *
     * @param int $requestedPid
     * @return int Authorized patient ID
     */
    public function EnforcePatientAccess($requestedPid = 0)
    {
        $isPortalUser = (isset($_SESSION['pid']) && !empty($_SESSION['patient_portal_onsite']));
        if ($isPortalUser) {
            $sessionPid = (int)$_SESSION['pid'];
            if (!empty($requestedPid) && (int)$requestedPid !== $sessionPid) {
                header('HTTP/1.1 403 Forbidden');
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    echo json_encode(array('success' => false, 'message' => 'Access denied: unauthorized access to patient record.'));
                    exit;
                }
                die('Access denied: unauthorized access to patient record.');
            }
            return $sessionPid;
        }
        return (int)$requestedPid;
    }

    /**
     * Check if the current active session is a patient portal user.
     *
     * @return bool
     */
    public function IsPortalUser()
    {
        return (isset($_SESSION['pid']) && !empty($_SESSION['patient_portal_onsite']));
    }

    /**
     * Returns the number of records to return per page
     * when pagination is used
     */
    protected function GetDefaultPageSize()
    {
        return self::$DEFAULT_PAGE_SIZE;
    }

    /**
     * Returns the name of the JSONP callback function (if allowed)
     */
    protected function JSONPCallback()
    {
        // TODO: uncomment to allow JSONP
        // return RequestUtil::Get('callback','');

        return '';
    }

    /**
     * Return the default SimpleObject params used when rendering objects as JSON
     * @return array
     */
    protected function SimpleObjectParams()
    {
        return array('camelCase'=>true);
    }

    /**
     * Helper method to get values from stdClass without throwing errors
     * @param stdClass $json
     * @param string $prop
     * @param string $default
     */
    protected function SafeGetVal($json, $prop, $default='')
    {
        return (property_exists($json,$prop))
            ? $json->$prop
            : $default;
    }

    /**
     * Helper utility that calls RenderErrorJSON
     * @param Exception
     */
    protected function RenderExceptionJSON(Exception $exception)
    {
        $this->RenderErrorJSON($exception->getMessage(),null,$exception);
    }

    /**
     * Output a Json error message to the browser
     * @param string $message
     * @param array key/value pairs where the key is the fieldname and the value is the error
     */
    protected function RenderErrorJSON($message, $errors = null, $exception = null)
    {
        $err = new stdClass();
        $err->success = false;
        $err->message = $message;
        $err->errors = array();

        if ($errors != null)
        {
            foreach ($errors as $key=>$val)
            {
                $err->errors[lcfirst($key)] = $val;
            }
        }

        if ($exception)
        {
            $err->stackTrace = explode("\n#", substr($exception->getTraceAsString(),1) );
        }

        @header('HTTP/1.1 401 Unauthorized');
        $this->RenderJSON($err,RequestUtil::Get('callback'));
    }

}
?>