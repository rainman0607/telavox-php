<?php

/**
 * TELAVOX-PHP
 * Created by Aske Merci / RainMan
 * Last updated: 12/04/2019 - 12:12 AM
 *
 * LEGAL:
 * I have no other relationship with Telavox beside that I'm one of their client.
 *
 */
class Telavox
{
    const API_ENDPOINT = "https://api.telavox.se";

    private $_token;

    public function __construct($token)
    {
        $this->_token = $token;
    }

    /**
     * @param $path = F.x /extensions, /calls, /dial etc
     * @param string $method = F.x GET, POST, PUT, DELETE etc
     * @param string $params = F.x ?fromDate
     */
    private function _api_call($path, $method = "GET", $params = "")
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::API_ENDPOINT . $path . ($params ? "?" . $params : ''),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $this->_token,
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

    }


    /**
     * Retrieves a list of your last incoming, outgoing and missed calls.
     * You will receive the last 30 calls, limited to the last 4 months.
     * (Optional) use the parameters fromDate and toDate to list between two dates (YYYY-MM-DD format)
     *
     * @param string $fromDate = The start date of listing
     * @param string $toDate = The end date of listing
     * @param bool $withRecordings = Optional, find recordings from specific calls
     */

    public function getCalls($fromDate = "", $toDate = "", $withRecordings = false)
    {
        return $this->_api_call(
        	'/calls',
			'GET',
			"fromDate=" . $fromDate . "&toDate=" . $toDate . "?withRecordings=".$withRecordings
		);
    }


    /**
     * *** REQUIRES PAID TELEPHONY ***
     * Starts a call between the logged in user and another phone.
     * Calls are initiated by first dialing the logged in user, and upon pickup the number specified will be dialed.
     * This function is blocked for users without paid telephony.
     *
     * @param string $number = The number that should be dialed
     * @param bool $autoAnswer = Whether the initial incoming call should be answered automatically.
     *
     * The default setting is to answer the incoming call automatically.
     * This feature can be turned of by providing false in the function call (see the examples).
     * Important! Only applicable to certain phone types, e.g. Snom, Cisco and Desktop.
     * For other phone types this parameter will simply be ignored.
     */

    public function dial($number, $autoAnswer = true)
    {
        return $this->_api_call(
        	'/dial/' . $number,
			'GET',
			"?autoanswer=".$autoAnswer
		);
    }


    /**
     * *** REQUIRES PAID TELEPHONY ***
     * Sends an SMS text message to a phone number from the logged in user.
     * Blocked for users without paid telephony
     * @param $number = The target number to send the message to.
     * @param $msg = The message body.
     */

    public function sendSMS($number, $msg)
    {
		return $this->_api_call(
			'/sms/' . $number,
			'GET',
			"?message=" . $msg
		);
    }


    /**
     * Terminates an ongoing call for the logged in user, use carefully
     */

    public function hangupCall()
    {
        return $this->_api_call('/hangup', 'POST');
    }


    /**
     * Get recording(s) from call history
     * @param string $id = (optional) Id uniquely identifying a recorded call, found in call history
     */

    public function getRecordings($id = "")
    {
        return $this->_api_call('/recordings/' . $id, "GET");
    }


    /**
     * @param string $extension = (optional)
     * Retrieve information about a single extension only.
     * The response will contain only a single extension item, i.e. not a list containing a single item.
     * Specify an actual extension number, or use me for the current authenticated user's extension.
     * If the extension parameter is not isset, all accessible extensions will be returned.
     */

    public function getExtensions($extension = "")
    {
        return $this->_api_call('/extensions/' . $extension, "GET");
    }

}
