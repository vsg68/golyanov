<?php
header("Content-type: text/html; charset=utf-8", true);
include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
$modx = new DocumentParser();

$modx->getSettings();

$content = 'No data';
$params=array();

	$id = $_REQUEST['id'];
	$rid = $_REQUEST['rid'];

 	if ( ! is_numeric($id) && isset($id) ) { exit; }

	if ( $id == '23' )
	{
		$params['subject']	= '��������� � ����a';
		$params['tpl'] 		= 'mailform-tpl';
		$params['formid']	= 'contact-form';
		$params['report']	= 'report-tpl';
		$params['thankyou']	= 'thank-tpl';
		$params['vericode']	= '1';

		echo $modx->runSnippet('eForm', $params);
		return;
	}
	else
	{
		$params['tpl']		= 'tpl-gallery';
		$params['dir_tpl'] 	= 'tpl-gallery-series';
		$params['thumb_tpl'] 	= 'tpl-gallery-thumb';
		$params['gid']		= $id;
		$params['limit']	= 20;
		$params['gpn']		= ( isset($page) && $page > 0) ? $page : 1;

		echo $modx->runSnippet('easy2',$params);

		return;
	}

	if ( ! is_numeric($rid) && isset($rid) ) { exit; }

		$tvname = "reportID";
		$ids = array();
		$year = $_REQUEST['id'];

		if(! is_numeric($year) ) return;

		$sql = "SELECT b.`cat_id` FROM `modx_easy2_dirs` a LEFT JOIN `modx_easy2_dirs` b ON a.cat_id = b.parent_id WHERE a.`cat_name`='".$year."'";
		$res = $modx->db->query($sql);

		while( $row = $modx->db->getRow( $res ) )
		{
		  array_push( $ids, $row[cat_id] );
		}

		$childs = $modx->getDocumentChildrenTVarOutput(4, $tvname);
		foreach ( $childs as $key=>$value )
		{
		  if( ! in_array($value[$tvname], $ids) ) unset($childs[$key]);
		}

		$content = $modx->runSnippet('Ditto', array('tpl' => 'tpl-newsItem',
											  'dateFormat' => '%d.%m.%Y',
											  'documents' => implode(",", array_keys ($childs))
							   ));


	echo $content;

?>
