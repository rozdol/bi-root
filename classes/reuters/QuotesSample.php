<?php
//The TRKD API sample code is provided for informational purposes only
//and without knowledge or assumptions of the end users development environment.
//We offer this code to provide developers practical and useful guidance while developing their own code.
//However, we do not offer support and troubleshooting of issues that are related to the use of this code
//in a particular environment; it is offered solely as sample code for guidance.
//Please see the Thomson Reuters Knowledge Direct product page at http://customers.thomsonreuters.com
//for additional information regarding the TRKD API.

/**
 * Provide the generation of HTML for RetrieveItem_3 response.
 */
class RetrieveItem3Response
{
	private $response;

	/**
	 * Initializes a new instance of <tt>Views_Quotes1_RetrieveItem3Response</tt> with the secified parameters.
	 *
	 * @param $response the response object.
	 */
	public function __construct($response)
	{
		$this->response = $response;
	}

	/**
	 * Gets the generated HTML.
	 *
	 * @return the string containing HTML markup.
	 */
	public function getHTML()
	{
		$retval = '';
		foreach ($this->response->ItemResponse as $items)
		{
			if (is_array($items))
			{
				foreach ($items as $item)
				{
					$retval .=  $this->getItemContent($item);
				}
			}
			else
			{
				$retval .= $this->getItemContent($items);
			}
		}
		
		return $retval;
	}

	private function getItemContent($item)
	{
		$retval = '<b>' . $item->RequestKey->Name . '</b> - ' . $item->Status->StatusMsg .
					'<br/>Timeliness:&nbsp;' . $item->QoS->TimelinessInfo->Timeliness . ',&nbsp;' . $item->QoS->TimelinessInfo->TimeInfo .
					'&nbsp;|&nbsp;Rate:&nbsp;' . $item->QoS->RateInfo->Rate . ',&nbsp;' . $item->QoS->RateInfo->TimeInfo .
					'<table border="1"><tr><th width="20%">Field</th><th>Value</th><th width="20%">Data type</th></tr>';
		foreach ($item->Fields as $fields)
		{
			foreach ($fields as $field)
			{
				$retval .= '<tr><td>' . $field->Name . '</td><td>';
				switch ($field->DataType)
				{
					case 'Binary':
						{
							$retval .= $field->Binary;
							break;
						}
					case 'Date':
						{
							$retval .= $field->Date;
							break;
						}
					case 'DateTime':
						{
							$retval .= $field->DateTime;
							break;
						}
					case 'Float':
						{
							$retval .= $field->Float;
							break;
						}
					case 'Double':
						{
							$retval .= $field->Double;
							break;
						}
					case 'Time':
						{
							$retval .= $field->Time;
							break;
						}
					case 'Int8':
						{
							$retval .= $field->Int8;
							break;
						}
					case 'Int16':
						{
							$retval .= $field->Int16;
							break;
						}
					case 'Int32':
						{
							$retval .= $field->Int32;
							break;
						}
					case 'Int64':
						{
							$retval .= $field->Int64;
							break;
						}
					case 'UInt8':
						{
							$retval .= $field->UInt8;
							break;
						}
					case 'UInt16':
						{
							$retval .= $field->UInt16;
							break;
						}
					case 'UInt32':
						{
							$retval .= $field->UInt32;
							break;
						}
					case 'UInt64':
						{
							$retval .= $field->UInt64;
							break;
						}
					case 'Utf8String':
						{
							$retval .= $field->Utf8String;
							break;
						}
				}
				$retval .= '</td><td>' . $field->DataType . '</td></tr>';
			}
		}
		$retval .= '</table><br/>';
		
		return $retval;
	}
}

/**
 * Provide the generation of RetrieveItem_3 request.
 */
class RequestBuilder
{
  public static function createRequest()
  {
    return array(
        'ItemRequest' => array(
            'Scope' => 'List',
            'Fields' => 'CF_ASK:CF_BID:CF_CLOSE:CF_DATE:CF_EXCHNG:CF_HIGH:CF_LAST:CF_LOW:CF_NETCHNG:CF_SOURCE:CF_TICK:CF_TIME:CF_VOLUME:CF_YIELD:CF_CURRENCY:CF_NAME',
            'RequestKey'=>array(
                array(
                    'Name' => 'FX=X',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => 'WX=X',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => 'INR=X',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => 'INRX=X',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => 'INRF=X',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => 'ECON',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => '0#USBMK=XX',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => '0#INBMK=XX',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => 'XAU=X',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => 'XAG=X',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => 'XPT=X',
                    'NameType' => 'RIC'
                ),
                array(
                    'Name' => 'XPD=X',
                    'NameType' => 'RIC'
                )
            )
        )
    );
  }
}

$client = new SoapClient("http://api.rkd.reuters.com/schemas/wsdl/TokenManagement/TokenManagement_1_HttpsAndAnonymous.wsdl", array('soap_version' => SOAP_1_2));

$applicationId = '';
$createTokenRequest = array(
   'ApplicationID' => $applicationId,
   'Username' => '',
   'Password' => ''
); //make sure credentials are initialized here

$wsAddressingHeaders = array(
  new SoapHeader('http://www.w3.org/2005/08/addressing', 'To', 'https://api.rkd.reuters.com/api/2006/05/01/TokenManagement_1.svc/Anonymous'),
  new SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', 'http://www.reuters.com/ns/2006/05/01/webservices/rkd/TokenManagement_1/CreateServiceToken_1')
);
try
{
	$createTokenResponse = $client->__soapCall('CreateServiceToken_1', array('parameters' => $createTokenRequest), null, $wsAddressingHeaders);
	echo 'Token received<br/>Token:&nbsp;' . bin2hex($createTokenResponse->Token) .
	'<br/>Expiration:&nbsp;' . $createTokenResponse->Expiration . '<br/>';

} catch (SoapFault $e) {
	echo "<span style='color:red'>Error occured: " . $e->getMessage() . "</span>";
}

$client2 = new SoapClient("http://api.rkd.reuters.com/schemas/wsdl/Quotes/Quotes_1_HttpAndRKDToken.wsdl", array('soap_version' => SOAP_1_2, 'trace' => true)); //in order to get the XML response enable trace

$auth = array('ApplicationID' => $applicationId, 'Token' => $createTokenResponse->Token );  //make sure the app ID is initialized here
$authvar = new SoapVar($auth, SOAP_ENC_OBJECT, "AuthorizationType", 'http://www.reuters.com/ns/2006/05/01/webservices/rkd/Common_1' );

$quotesRequest = RequestBuilder::createRequest();

$wsAddressingHeaders2 = array(
	new SoapHeader('http://www.w3.org/2005/08/addressing', 'To', 'http://api.rkd.reuters.com/api/2006/05/01/Quotes_1.svc'),
	new SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', 'http://www.reuters.com/ns/2006/05/01/webservices/rkd/Quotes_1/RetrieveItem_3'),
	new SoapHeader('http://www.reuters.com/ns/2006/05/01/webservices/rkd/Common_1', 'Authorization', $authvar)
);

try
{
	$quotesResponse = $client2->__soapCall('RetrieveItem_3', array('parameters' => $quotesRequest), null, $wsAddressingHeaders2);
	$view = new RetrieveItem3Response($quotesResponse);
    echo '<hr/><a href="#formatted">Go to formatted ouput.</a>';
	echo '<hr/><h2>XML Response:</h2><br/>' . htmlspecialchars($client2->__getLastResponse()); //get XML response
	echo '<hr/><h2><a name="formatted">Formatted output:</a></h2><br/>' . $view->getHTML(); //creaing HTML from the response object
} catch (SoapFault $e) {
	echo "<span style='color:red'>Error occured: " . $e->getMessage() . "</span>";
}