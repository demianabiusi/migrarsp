<?php
	
	function eliminar_comentarios($str)
	{
		$inh=false;
		$sal='';
	
		for($i=0;$i<strlen($str);$i++)
		{

	
			
			if ((!$inh) && (substr($str,$i,3)=='-- ')) 
			{
				$inh=true;
				$desinh="\n";
				$i+=2;
			}

			if ((!$inh) && (substr($str,$i,2)=='/*')) 
			{
				$inh=true;
				$desinh="*/";
				$i+=1;
			}

			
			if ($inh)
			{
			
				if (substr($str,$i,strlen($desinh))==$desinh)
				{
					$inh=false;
					$i+=strlen($desinh)-1;
				}
			} else
			{  
				$sal.=substr($str,$i,1); 
			}
		
		}
		
		return $sal;
	}
	
	function extraer_palabra_despues($str,$antes,$del=array(" ","\n",";","\t",")","(","@"))
	{
		$str=strtolower($str);
		$str=str_replace("\n"," ",$str);
		$str=str_replace("\t"," ",$str);
		
		$antes=strtolower($antes);
		
		$sal=array();
		
		$inh=true;
		
		for($i=0;$i<strlen($str);$i++)
		{
			if ( substr($str,$i,strlen($antes))==$antes  )
			{
			
				$x="";
				$i+=strlen($antes);

				while ((substr($str,$i,1)==' ') && $i<strlen($str)) { $i++; }
				
				do {
					$ch=substr($str,$i,1);
					if (!(in_array($ch,$del))) { $x.=$ch; }
					$i++;
					
				} while (  ($i<strlen($str))  && (!(in_array($ch,$del)))  );
				
				
				$x=trim($x);
				
				if ( ($x!='') && !(in_array($x,$sal)) ) { $sal[count($sal)]=$x; }

			}
		}
		
		return $sal;
	}
	
	
	function comparador($sp,$sd,&$rp,&$rd,$tabla)
	{
		$l=Conectar_Con($sp);


		
		$st=mysqli_query($l,"SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, NUMERIC_PRECISION, NUMERIC_SCALE, CHARACTER_SET_NAME, COLLATION_NAME, ".
								  "COLUMN_TYPE, COLUMN_KEY, EXTRA FROM information_schema.COLUMNS	WHERE TABLE_SCHEMA='".$sp->base."'	AND TABLE_NAME='$tabla'");
			
		$rp=array();
		$cp=array();
		while($r=mysqli_fetch_assoc($st))
		{
			$rp[$r['COLUMN_NAME']]=$r;
			$cp[count($cp)]=$r['COLUMN_NAME'];
		}	
	
	
		$l=Conectar_Con($sd);
		$st=mysqli_query($l,"SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, NUMERIC_PRECISION, NUMERIC_SCALE, CHARACTER_SET_NAME, COLLATION_NAME, ".
								  "COLUMN_TYPE, COLUMN_KEY, EXTRA FROM information_schema.COLUMNS	WHERE TABLE_SCHEMA='".$sd->base."'	AND TABLE_NAME='$tabla'");

		
		$rd=array();
		$cd=array();
		while($r=mysqli_fetch_assoc($st))
		{
			$rd[$r['COLUMN_NAME']]=$r;
			$cd[count($cd)]=$r['COLUMN_NAME'];
		}	
		
		if (count($rd)!=count($rp))
		{
			return false;
		}
			else
		{
			$sal=true;
			foreach($rd as $r)
			{
				if (!in_array($r,$rp))
				{
					$sal=false;
				}
			}
			
			return $sal;
		}

	}

?>
