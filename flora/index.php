<?php
	require '../vendor/autoload.php';
	include('bd.php');

	header('Content-Type: application/json');

	$app = new \Slim\App();


	$app->get('/', function(){

  		$app->render('index.html');

	});


	$app->get('/families', function($request, $response) {

	    $r = new StdClass;
		$r->success = true;
		$r->result = null;

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query="select distinct(family) from ipt_lista_brasil.v_taxonomia_hierarquia order by 1";

		$result = $clConnection->perform($query);

		$r->result = $clConnection->listR($result);

		$families = $r->result;

		foreach($families as $valor) {
        		$arr[] =$valor['family'];
        }

        $r->result = $arr;

		$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    	return $response->write($json);
	});

	$app->get('/genus/{family}', function($request, $response, $args) {

    	$family = $args['family'];

	    $r = new StdClass;
		$r->success = true;
		$r->result = null;

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = "select distinct(genus) from ipt_lista_brasil.v_taxonomia_hierarquia where family ilike '$family' order by genus";

		$result = $clConnection->perform($query);

		if (pg_num_rows($result) == 0) {

        	  $json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        }else {

        	$r->result = $clConnection->listR($result);

			$genus = $r->result;

			foreach($genus as $valor) {
        			$arr[] = $valor['genus'];


       		}

		    $r->result = $arr;

			$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

		}

    	return $response->write($json);
	});


	$app->get('/species/{family}', function($request, $response, $args) {

    	$family = $args['family'];

    	$r = new StdClass;
		$r->success = true;
		$r->result = null;

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = 'select taxonid, family, genus, scientificname, specificepithet, infraspecificepithet, scientificnameauthorship, taxonomicstatus, acceptednameusage, higherclassification, source, "references" from ipt_lista_brasil.v_taxonomia_hierarquia where family ilike '."'".$family."'"." and taxonrank like 'ESPECIE' or taxonrank like 'SUB ESPECIE'";

		$result = $clConnection->perform($query);

		if (pg_num_rows($result) == 0) {

          		$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        	}else {

        		$r->result = $clConnection->listR($result);

			$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

		}

    	return $response->write($json);
	});

	$app->get('/taxon/{scientificname}', function($request, $response, $args) {

    	$scientificname = $args['scientificname'];

    	$r = new StdClass;
		$r->success = true;
		$r->result = null;

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = 'select ipt_lista_brasil.v_taxonomia_hierarquia.taxonid, family, genus, scientificname, specificepithet, infraspecificepithet, scientificnameauthorship,
		taxonomicstatus, acceptednameusage, higherclassification, source, "references",acceptednameusageid, modified, ipt_lista_brasil.v_distribuicao.endemism
		from ipt_lista_brasil.v_taxonomia_hierarquia
		join ipt_lista_brasil.v_distribuicao
		on (ipt_lista_brasil.v_taxonomia_hierarquia.taxonid = ipt_lista_brasil.v_distribuicao.taxonid)
		where scientificname ilike '."'".$scientificname."%'";
		// ipt_lista_brasil.v_distribuicao.endemism
		//join ipt_lista_brasil.v_distribuicao ON (ipt_lista_brasil.v_taxonomia_hierarquia.taxonid = ipt_lista_brasil.v_distribuicao.taxonid)
		$result = $clConnection->perform($query);

		if (pg_num_rows($result) == 0) {

	          	$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        	}else {

        		$r->result = $clConnection->listResult($result);

			$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

		}

    	return $response->write($json);
	});

	$app->get('/endemism/{scientificname}', function($request, $response, $args) {

			$scientificname = $args['scientificname'];

			$r = new StdClass;
		$r->success = true;
		$r->result = null;

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = 'select endemism
		from ipt_lista_brasil.v_distribuicao
		join ipt_lista_brasil.v_taxonomia_hierarquia
		on (ipt_lista_brasil.v_distribuicao.taxonid = ipt_lista_brasil.v_taxonomia_hierarquia.taxonid)
		where ipt_lista_brasil.v_taxonomia_hierarquia.scientificname ilike '."'".$scientificname."%'";
		// ipt_lista_brasil.v_distribuicao.endemism
		//join ipt_lista_brasil.v_distribuicao ON (ipt_lista_brasil.v_taxonomia_hierarquia.taxonid = ipt_lista_brasil.v_distribuicao.taxonid)
		$result = $clConnection->perform($query);

		if (pg_num_rows($result) == 0) {

							$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

					}else {

						$r->result = $clConnection->listResult($result);

			$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

		}

			return $response->write($json);
	});

	$app->get('/url/{specie}', function ($request, $response, $args) {

 	   	$specie = $args['specie'];

    	$r = new StdClass;
		$r->success = true;
		$r->result = null;

		$clConnection = new Connection;
		$conn = $clConnection->connect();

		$query = 'select mv_taxonomia_hierarquia."references" from ipt_lista_brasil.mv_taxonomia_hierarquia where scientificname ilike '."'%".$specie."%'";

		$result = $clConnection->perform($query);

		if (pg_num_rows($result) == 0) {

		          $json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

	        }else {

	        	$r->result = $clConnection->listR($result);

			$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

		}


    	return $response->write($json);
	});

	$app->get('/search/{scientificname}', function ($request, $response, $args) {

	    $info = $args['scientificname'];

	    $under = substr_count($info, '_');

        $space = substr_count($info, ' ');

        if ($under == 0){ 	//Se a busca for feita com espaço no scientific name

        	$r = new StdClass;
			$r->success = true;
			$r->result = null;

			$clConnection = new Connection;
			$conn = $clConnection->connect();

			if ($space == 0) {

				$query = 'select mv_taxonomia_hierarquia."references" from ipt_lista_brasil.mv_taxonomia_hierarquia where family ilike '."'".$info."'";

		    	$result = $clConnection->perform($query);

		    	if (pg_num_rows($result) == 0) {

		    			$query = 'select mv_taxonomia_hierarquia."references" from ipt_lista_brasil.mv_taxonomia_hierarquia where genus ilike '."'".$info."'";

						$result = $clConnection->perform($query);

						if (pg_num_rows($result) == 0) {

							$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

							header('Location: http://servicos.jbrj.gov.br/flora/error/notfound.php');
				        	exit;



		            	} else {

				    		$r->result = $clConnection->listResult($result);

				        	$print = $r->result[0]['references'];
				        	header('Location: '.$print.'');
				        	exit;

		            	}

		    	} else {

					  $r->result = $clConnection->listResult($result);

		        	  $print = $r->result[0]['references'];
		        	  header('Location: '.$print.'');
		        	  exit;

		    	}


        	} elseif ($space == 1) {

        		$var = explode(" ", $info);

				$query = 'select mv_taxonomia_hierarquia."references" from ipt_lista_brasil.mv_taxonomia_hierarquia where genus ilike '."'".$var[0]."'".'  and specificepithet ilike '."'".$var[1]."'";

				$result = $clConnection->perform($query);

				if (pg_num_rows($result) == 0) {


				$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

				header('Location: http://servicos.jbrj.gov.br/flora/error/notfound.php');
		        exit;


				} else {

			    	$r->result = $clConnection->listResult($result);

		        	$print = $r->result[0]['references'];
		        	header('Location: '.$print.'');
		        	exit;

		   		 }

			} elseif ($space == 2) {

				$var = explode(" ", $info);

				$query = 'select mv_taxonomia_hierarquia."references" from ipt_lista_brasil.mv_taxonomia_hierarquia where genus ilike '."'".$var[0]."'".' and specificepithet ilike '."'".$var[1]."'".' and infraspecificepithet ilike '."'".$var[2]."'";

				$result = $clConnection->perform($query);


				if (pg_num_rows($result) == 0) {

					$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

					header('Location: http://servicos.jbrj.gov.br/flora/error/notfound.php');
		        	exit;

				} else {

			    	$r->result = $clConnection->listResult($result);

		        	$print = $r->result[0]['references'];
		        	header('Location: '.$print.'');
		        	exit;

		    	}

			}

    } if ($under == 1) { //Se a busca for por underline

    	    $r = new StdClass;
			$r->success = true;
			$r->result = null;

			$clConnection = new Connection;
			$conn = $clConnection->connect();

			$var = explode("_", $info);

			$query = 'select mv_taxonomia_hierarquia."references" from ipt_lista_brasil.mv_taxonomia_hierarquia where genus ilike '."'".$var[0]."'".'  and specificepithet ilike '."'".$var[1]."'";

			$result = $clConnection->perform($query);

			if (pg_num_rows($result) == 0) {

				$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

				header('Location: http://servicos.jbrj.gov.br/flora/error/notfound.php');
		        exit;


			} else {

			    $r->result = $clConnection->listResult($result);

		        $print = $r->result[0]['references'];
		        header('Location: '.$print.'');
		        exit;

		    }

	} if ($under == 2) {

		    $r = new StdClass;
			$r->success = true;
			$r->result = null;

			$clConnection = new Connection;
			$conn = $clConnection->connect();

			$var = explode("_", $info);

			$query = 'select mv_taxonomia_hierarquia."references" from ipt_lista_brasil.mv_taxonomia_hierarquia where genus ilike '."'".$var[0]."'".' and specificepithet ilike '."'".$var[1]."'".' and infraspecificepithet ilike '."'".$var[2]."'";

			$result = $clConnection->perform($query);


			if (pg_num_rows($result) == 0) {

				$json = json_encode($r,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

				header('Location: http://servicos.jbrj.gov.br/flora/error/notfound.php');
		        exit;

			} else {

			    $r->result = $clConnection->listResult($result);

		        $print = $r->result[0]['references'];
		        header('Location: '.$print.'');
		        exit;

		    }

	}

		return $response->write($json);

	});

	$app->run();
?>
