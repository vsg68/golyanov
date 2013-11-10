<?php	


function getFullPath($initpath, $filename) {
	
	$files = glob("$initpath/*");

	if( !is_array($files) ) return;

	// Если в директории есть файлы
	foreach ( $files as $file )
	{
		if( is_dir( $file ) )
		{
			$addpath = getFullPath($file, $filename);
			
			if( $addpath ) return $addpath;
			continue;
		}
		
		if( !$filename && stripos($file, '.jpg') )
		{
			if( file_exists($file) )  return $file;
		}
		else
		{
			if( basename($file) == $filename) return $file;
		}		
	}
}		
/*
$initpath = "/var/www/g2.ru/assets/images/gallery/_thumbnails/barzovka";
$filename ="";// "26_mod_80x80.jpg";

$mp = getFullPath($initpath, $filename);
echo $mp;
*/	

/* 
 * возвращаю полный путь до пункта с нужным ID, level нужно найти до вызова функции
 */
function getFullPathDB($id, $level_start, $level_end = 1) {	
	
	global $modx;
	$join		= "";
	$tbalias 	= " NT"; 
	$tb 		= $modx->db->config['table_prefix']."easy2_dirs";
	$path_field = $tbalias . $level_start .".cat_name";
	$from 		= "FROM ". $tb ." ". $tbalias . $level_start;
	$where		= " WHERE ".$tbalias.$level_start .".cat_visible=1 AND". $tbalias.$level_start .".cat_id='". $id ."'";

	for( $i = $level_start; $i >= $level_end; $i-- )
	{
		if( ($i-1) >= $level_end )
		{
			$n = $tbalias.$i; 
			$k = $tbalias.($i-1); 
			$join .= " LEFT JOIN ". $tb ." ". $k ." ON ".$k.".cat_id=".$n.".parent_id ";
			$path_field = $k .".cat_name,". $path_field;
		}	
	}	
	
	$sql = "SELECT CONCAT_WS('/',". $path_field .") AS path ". $from . $join . $where;
//echo $sql;
	$res = mysql_query($sql);
	$row = mysql_fetch_assoc($res);

	return $row['path'];
}
?>
