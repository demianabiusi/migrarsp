<?

	header("Content-type: text/plain");
	header("Cache-Control: no-store, no-cache");


 	require('servidores.php');


	//print_r($_GET);
	
	$idx=$_GET['srv'];
	$tabla=$_GET['tabla'];
	$servidor=$arraysrv[$idx];
	
	echo "-- SCRIPT EXTRAIDO DEL SERVIDOR ".$servidor->nombre."\n\n";

	$mylink=Conectar_Con($servidor);
	$sql='show create table '.$tabla;
	
	echo "-- ".$sql."\n\n";
	$qry=mysqli_query($mylink,$sql);
	echo mysqli_error($mylink);

	$row=@mysqli_fetch_assoc($qry);
	echo $row['Create Table'].";";
	
	
	$mylink=Conectar_Con($servidor);
	$sql='select count(*) cant from '.$tabla;
	$qry=mysqli_query($mylink,$sql);
	
	$row=@mysqli_fetch_assoc($qry);
	
	
	
	if ($row['cant']<5000) {
		echo "\n\n\n";
		echo "-- Como tiene menos de 5000 registros tiro el script por si los quieren insertar ahora\n\n"; 
		$mylink=Conectar_Con($servidor);
		$sql='select * from '.$tabla;
		$qry=mysqli_query($mylink,$sql);
		
		while($row=@mysqli_fetch_assoc($qry))
		{
			$sxx="insert into `$tabla` (";
			foreach ($row as $xn=>$xx)
			{
				$sxx.="`$xn`,";
			}
			$sxx=substr($sxx,0,-1);
			
			$sxx.= ") values (";
			foreach ($row as $xn=>$xx)
			{
				$sxx.="'$xx',";
			}
			$sxx=substr($sxx,0,-1);
			
			$sxx.=");\n";
			
			echo $sxx;
			
		}
		
		
		
	}
	

?>
