<?php
if (!$access['main_admin'])$this->html->error('Honey pot');
//copypaste from http://forums.phpfreaks.com/topic/260016-make-excel-solver-in-php/ to keep some curl,stats::covariance and fgetcsv examples
include('assets/init.inc.php');
include('assets/header.php');

// Sets up todays market conditions
$risk_free_rate		= .0014; //The risk free rate taken from 6-Month U.S. T-Bills
$market_return		= .11; //Normal stock market return is 11% annually
$weighting_sum 		= 0;

$stocks 				= array();
$quote_info 			= array();
$log_info				= array();
$weights				= array();
$portfolio_contribution = array();
	
$stocks[0] = 'YHOO';
$stocks[1] = 'MSFT';
$stocks[2] = 'GOOG';

$weights[0] = .2;
$weights[1] = .3;
$weights[2] = .5;

// Yahoo Query Language dates 
$start_date 	= date('Y-m-d', strtotime("-5 years"));
$end_date 		= date('Y-m-d');

// CSV dates broken out 
$start_year 	= date('Y', strtotime("-5 years"));
$start_month 	= date('m', strtotime("-5 years")) - 1;
$start_day 		= date('d', strtotime("-5 years"));
$end_year 		= date('Y');
$end_month 		= date('m') - 1;
$end_day 		= date('d');

foreach($stocks as $s => $stock){

	// Outline the query to get historical stock pricing information
	$query = 'select Close from yahoo.finance.historicaldata where symbol = "'.$stock.'" and startDate = "'.$start_date.'" and endDate = "'.$end_date.'"';
	
	// Insert the query into the full URL
	$url = 'http://query.yahooapis.com/v1/public/yql?format=json&q=' . urlencode($query). '&env=http://datatables.org/alltables.env';
	
	// Set up the cURL
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);

	// Execute the cURL
	$rawdata = curl_exec($c);
	curl_close($c);

	// Convert the returned JSON to a PHP object
	$data = json_decode($rawdata);
	
	// Download the data from Yahoo Finance to scrap the page for the beta value of each stock
	$content = file_get_contents('http://finance.yahoo.com/q?s='.$stock);
	preg_match('#<tr><th scope="row" width="48%">Beta:</th><td class="yfnc_tabledata1">(.*?)</td></tr>#', $content, $match);

	// Set the beta value from the page scrape
	$beta = $match[1];

	// Calculate the capital asset pricing model
	$capm = $risk_free_rate	+ ($beta * ($market_return - $risk_free_rate));
	
	// Convert the stock close prices to an array of prices
	// Try and get the data from Yahoo Query Language first
	// If that fails, download the CSV file to pull the data
	if(isset($data->query->results->quote)){
		$stock_data = $data->query->results->quote;
		
		foreach ($stock_data as $k => $price) {
			$quote_info[$s][$k]['price'] = $price->Close;
		}
	}else{
		$i = 0;
		
		if(($handle = fopen('http://ichart.finance.yahoo.com/table.csv?s='.$stock.'&a='.$start_month.'&b='.$start_day.'&c='.$start_year.'&d='.$end_month.'&e='.$end_day.'&f='.$end_year.'&g=d&ignore=.csv', 'r')) !== FALSE) {
			while (($data = fgetcsv($handle, 1500, ',', '"')) !== FALSE) {
				$quote_info[$s][$i]['price'] = $data[4];
				
				$i++;
			}
			fclose($handle);
		}
		array_shift($quote_info[$s]);
	}
	
	// Find the log values for the price differences
	foreach ($quote_info[$s] as $j => $log) {
		$m = $j + 1;
		
		if(array_key_exists($m, $quote_info[$s])) {
			$log_info[$s][$j] = log($quote_info[$s][$m]['price']) - log($quote_info[$s][$j]['price']);
		}
	}
	
	// NOT CURRENTLY USING THIS! Set the weight for the stock equal to zero
	//$weights[$s] = 0;
	
	$portfolio_contribution[$s] = $weights[$s] * $capm;
}

// Add the covariance between stocks to the portfolio weights
foreach($log_info as $k => $item){
	$count = count($log_info);
	
	for($i = 0; $i < $count; $i++){
		$weighting_sum += stats::covariance ($log_info[$k],  $log_info[$i]) * $weights[$k] * $weights[$i];
	}
}

// Calculate the annual volatility of the portfolio
$annual_volatility = sqrt($weighting_sum)*sqrt(252); //252 is the number of trading days in a year

// Calculate the return of the portfolio
$portfolio_return = array_sum($portfolio_contribution);

// Calculate the sharpe ratio for the portfolio
$sharpe_ratio = ($portfolio_return - $risk_free_rate) / $annual_volatility;

include('assets/footer.php');

?>