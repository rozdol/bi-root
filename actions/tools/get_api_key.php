<?php
if ($access['main_admin']){
	$password=$this->html->readRQ('password');
	echo $this->html->pre_display($password,"password");
	$is_client_id=$this->html->readRQn('is_client_id');
	$is_client=$this->data->get_row('is_clients',$is_client_id);
	$api_URL=$is_client[api_link];
	$api_key=$is_client[api_key];
	$authorization=$is_client[api_authorization];
	echo $this->html->pre_display($is_client,"is_client");
	$api_URL_arr=explode("&",$api_URL);
	//echo $this->html->pre_display($api_URL_arr,"api_URL_arr");
	$api_URL=$api_URL_arr[0];
	//echo $this->html->pre_display($api_URL,"api_URL");exit;
	$response=$this->comm->api_auth($api_URL, 'api', $password);
	echo $this->html->pre_display($response,"response $api_URL ($password)");
	if($response->access_token!=''){
		$vals=[
			'api_key'=>$response->api_key,
			'api_authorization'=>$response->access_token
		];
		$this->db->update_db('is_clients',$is_client_id,$vals);
	}

}else{
	$this->html->error('Not admin');
}