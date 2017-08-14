<?php
	require '../vendor/autoload.php';
	include('bd.php');

	header('Accept: application/json');
	header('Content-Type: application/json');

	$app = new \Slim\App();


	$app->get('/', function($request, $response){
  		
  		header('Location: index.html');

       return $response; 
	});

	$app->get('/collections', function($request, $response) {
	
	    	$r = new StdClass;
		$r->success = true;
		$r->result = null;

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select distinct(siglacolecao) as collection from publicacao.extracao_jabot order by siglacolecao";

		$result = $clConnection->perform($query);

		$r->result = $clConnection->listResult($result);

		$collections = $r->result;

		foreach($collections as $valor) {
        		$arr[] =$valor['collection'];
        		

                }

                $r->result = $arr;
		
		$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    	return $response->write($json);
	});


	$app->get('/families', function($request, $response) {
    	 
	    	$r = new StdClass;
		$r->success = true;
		$r->result = null;
		 
		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select distinct(familia) as family from publicacao.extracao_jabot order by familia";

		$result = $clConnection->perform($query);
		
		$r->result = $clConnection->listResult($result);

		$families = $r->result;

		foreach($families as $valor) {
        		$arr[] =$valor['family'];
        		

                }

         	$r->result = $arr;

		
		$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    	return $response->write($json);
	});

	$app->get('/genus', function($request, $response) {
    	 
		$r = new StdClass;
		$r->success = true;
		$r->result = null;

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select distinct(genero) as genus from publicacao.extracao_jabot order by genero";

		$result = $clConnection->perform($query);

		$r->result = $clConnection->listResult($result);

		$genus = $r->result;

		foreach($genus as $valor) {
        		$arr[] =$valor['genus'];
        		

               }

	        $r->result = $arr;

		$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		
    	return $response->write($json);
	});


     $app->get('/genus/{family}', function ($request, $response, $args) {
    	 
    	        $r = new StdClass;
		$r->success = true;
		$r->result = null;
		 
    	        $f = $args['family'];
      	        $family = strtoupper($f);

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select distinct(genero) as genus from publicacao.extracao_jabot where familia like '$family' order by genero";

		$result = $clConnection->perform($query);
		
		if (pg_num_rows($result) == 0) {

                $json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        }else {

        	       $r->result = $clConnection->listResult($result);
		      
                       $genus = $r->result;

		       foreach($genus as $valor) {
        		$arr[] =$valor['genus'];
        		

                       }

			$r->result = $arr;

			$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

		}  

    	return $response->write($json);
	});
	

	$app->get('/occurrence/city/{city}', function ($request, $response, $args) {
    	 
    		$r = new StdClass;
		$r->success = true;
		$r->result = null;
		 
    		$location = $args['city'];
    	 
		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select * from publicacao.extracao_jabot where cidade ilike '$location' limit 1000";
		
		$result = $clConnection->perform($query);

		if (pg_num_rows($result) == 0) {

          	$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

       		 }else {

        	$r->result = $clConnection->listResult($result);

			$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

		}  

    	return $response->write($json);
	});

	$app->get('/occurrence/{family}', function ($request, $response, $args) {
    	
    	$r = new StdClass;
		$r->success = true;
		$r->result = null;
		 
    	$f = $args['family'];
    	$family = strtoupper($f);
    	 
		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select * from publicacao.extracao_jabot where familia like '$family' order by 1";

		$result = $clConnection->perform($query);
		
		if (pg_num_rows($result) == 0) {

          $json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        }else {

        	$r->result = $clConnection->listResult($result);

			$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);;

		}  

    	return $response->write($json);
	});

	$app->get('/occurrence/{genus}/{family}', function ($request, $response, $args) {
    	 
    	$r = new StdClass;
		$r->success = true;
		$r->result = null;
		 
    	$f = $args['family'];
    	$family = strtoupper($f);
    	$genus = $args['genus'];
    	 
		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select * from publicacao.extracao_jabot where familia like '$family' and genero like '$genus'";

		$result = $clConnection->perform($query);
		
		if (pg_num_rows($result) == 0) {

          $json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        }else {

        	$r->result = $clConnection->listResult($result);

			$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);;

		}  

    	return $response->write($json);
	});


	$app->get('/species', function($request, $response) {
    	
	    	$r = new StdClass;
		$r->success = true;
		$r->result = null;

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select distinct(taxoncompleto) as specie, familia as family from publicacao.extracao_jabot order by taxoncompleto";
		
		$result = $clConnection->perform($query);

		$r->result = $clConnection->listResult($result);

		$species = $r->result;

		foreach($species as $valor) {
        		$arr[] =$valor['specie'];
        		

                }

         	$r->result = $arr;

		$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    	return $response->write($json);
	});

	
	$app->get('/specie/{genus}/{family}', function ($request, $response, $args) {
    	 
	    	$r = new StdClass;
		$r->success = true;
		$r->result = null;
		 
		$f = $args['family'];
    		$family = strtoupper($f);
	    	$genus = $args['genus'];
    	 
		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select distinct(taxoncompleto) as specie from publicacao.extracao_jabot where genero like '$genus' and familia like '$family' group by taxoncompleto";
		
		$result = $clConnection->perform($query);

		if (pg_num_rows($result) == 0) {

        	  $json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        	}else {

        	$r->result = $clConnection->listResult($result);

		$species = $r->result;

		foreach($species as $valor) {
        		$arr[] =$valor['specie'];
        		

        	}

         	$r->result = $arr;

		$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);;

		}  

    	return $response->write($json);
	});

	$app->get('/occurrence/{specie}/{genus}/{family}', function ($request, $response, $args) {
    	 
    	$r = new StdClass;
		$r->success = true;
		$r->result = null;
		 
    	$f = $args['family'];
    	$family = strtoupper($f);
    	$genus = $args['genus'];
    	$specie = $args['specie'];
    	 
		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select * from publicacao.extracao_jabot where familia like '$family' and genero like '$genus' and sp like '$specie'";
		
		$result = $clConnection->perform($query);

		if (pg_num_rows($result) == 0) {

          $json = json_encode(array($r),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        }else {

        	$r->result = $clConnection->listResult($result);

			$json = json_encode(array($r),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);;

		}  

    	return $response->write($json);
	});


	$app->run();
?>
