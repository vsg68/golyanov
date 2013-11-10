<?php
header("Content-type: text/html; charset=utf-8", true);
define('MODX_API_MODE', true);
include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
include_once(MODX_BASE_PATH."assets/snippets/ajax/getFullPath.inc.php");
$modx = new DocumentParser();
  
$modx->db->connect();
$modx->getSettings();

	
	$id = $_REQUEST['id'];
	$content = '';
    $thumb_default = 'assets/images/folder_pictures.png';
 //echo $id;   
	if( ! is_numeric($id) || ! $id ) return;
	
	/* 
	 * берем конфигурационные параметры в виде
	 * $dir = '/easy2/sss/qqq'
	 * $mod_w = '80'
	 * $mod_y = '50'
	*/
	
	$res = mysql_query('SELECT `cfg_key`,`cfg_val` FROM '.$modx->db->config['table_prefix'].'easy2_configs '.
					   'WHERE cfg_key IN ("dir","mod_w","mod_h")' );

	while( $l = mysql_fetch_assoc($res) ) 
    {
           ${$l[cfg_key]} = $l[cfg_val];
    }

	/*
	 * Нужно определить, является ли данная тема контейнером, если ДА, то выводим потомков и выходим
	 * Если "нет", то находим директорию с тумбами и выводим галерею.
	*/	
	// Ищем сразу потомков
	$sql = "SELECT A.cat_level,".
				" COALESCE(B.`cat_id`, 0) AS chid, ".
				" COALESCE(B.`cat_alias`,'') AS chalias, ".
				" COALESCE(B.`cat_name`,'') AS chname, ".
				" COALESCE(B.`cat_thumb_id`,0) AS tid, ".
				" COALESCE(F.filename,'') AS filename ".
				" FROM `". $modx->db->config['table_prefix'] ."easy2_dirs` A ".
				" LEFT JOIN `". $modx->db->config['table_prefix'] ."easy2_dirs` B ON A.cat_id = B.parent_id ".
				" LEFT JOIN `". $modx->db->config['table_prefix'] ."easy2_files` F ON F.id = B.cat_thumb_id ". 
				" WHERE A.cat_visible = '1' AND A.cat_id = '". $id ."' ORDER BY chid ";
			
	$res = mysql_query($sql);
//echo 	$sql;
	
	while( $row = mysql_fetch_assoc($res) ) 
	{
		$rows[] = $row;
	}	
	
	$path_to_id = getFullPathDB($id, $rows[0]['cat_level']);

//echo 	$path_to_id;

	$thumb_dir	= $dir."_thumbnails/". $path_to_id;  // путь до тумб
	$dir 		= $dir. $path_to_id;   // путь до галереи

	foreach( $rows as $row ) 
	{
		
		if( ! $row['chid'] )  break;  // без потомков - выходим из цикла
		
       // Ищем имя файла
		if( $row['filename'] )  
		{
			$f = explode('.', $row['filename']);
			$row['filename'] = $row['tid'].'_mod_'.$mod_w.'x'.$mod_h.'.'.$f[1];
		}

        $thumb = getFullPath( $thumb_dir ."/". $row['chname'], $row['filename']);	
        	
        if( !$thumb ) 
        { 
           $thumb = $thumb_default;
        }

		$content .= "<div class='series'><a href='#' class='series-tip' onclick='show_gallery(this)' title='". $row['chalias'] ."' rel='". $row['chid'] ."'>".
					"<img src='". $thumb ."'/></a></div>";
	}

	if( $content )
	{
		echo $content;
		return;
	}
	

	$sql = 'SELECT filename, description FROM '.$modx->db->config['table_prefix'].'easy2_files WHERE status=1 AND dir_id='.$id;

	// берем данные о картинках
	$res = mysql_query($sql);
	while( $l = mysql_fetch_array($res, MYSQL_ASSOC))
	{
		$f = explode('.', $l['filename']);
		$img = $dir.'/'.$l['filename'];
		$thumb = $thumb_dir.'/'.$f[0].'_mod_'.$mod_w.'x'.$mod_h.'.'.$f[1];
		// надо знать разрешения тумбы из конфига и путь до директории - тоже из конфига

		// Проверка на существование тумбы
		if( file_exists(MODX_BASE_PATH.'/'.$thumb) )
		{
			$content .= "<a class='highslide' href='".$img."' onclick='return hs.expand(this,GalleryOptions)'><img src='".$thumb.
						"'></a><div class='highslide-heading'>".$l['description']."</div>";
		}
	}
		
	echo "<div class='hidden-container'>".$content."</div>";
?>
