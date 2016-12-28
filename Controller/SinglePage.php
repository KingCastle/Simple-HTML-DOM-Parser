<?php
$url=(@dirname($_POST['url']));
$ret[]=$output[]='';
if (!$url){
	exit('لطفا لینک  محصول را وارد کنید');
}else {
	include('../Plugins/simple_html_dom.php');
	$Single_product_html = file_get_html($url);
	foreach ($Single_product_html->find('table.edman') as $o) {
		$item['title'] = trim($o->find('span#product_info_name', 0)->innertext);
		$item['price'] = trim($o->find('span.productPrice,span.productStatus', 0)->innertext);

		$item['product']['description'] = trim($o->find('div.products_description_container', 0)->innertext);
		# Product feature
		foreach ($o->find('table.pef_group_container_table') as $value) {
			foreach ($value->find('tr.pef_inner_container_box_group_container_row_odd,tr.pef_inner_container_box_group_container_row_even') as $tr) {
				$item['product']['feature']['row_title'][] = trim($tr->find('td.pef_inner_container_box_group_container_row_title', 0)->innertext);
                 $ACT = trim($tr->find('td.pef_inner_container_box_group_container_row_value span', 0)->innertext);
                if($ACT=='<img src="./images/icons/yes.png" />'){
                    $item['product']['feature']['row_desc'][]='بله';
                }elseif ($ACT=='<img src="./images/icons/no.png" />'){
                    $item['product']['feature']['row_desc'][]='خیر';
                }else{
                  $item['product']['feature']['row_desc'][] = trim($tr->find('td.pef_inner_container_box_group_container_row_value span', 0)->innertext);
                }
			}
		}
		$cama = $item['tags'] = '';
		foreach ($o->find('ul.product_cloud_tags li') as $tag) {
			$item['tags'] .= $cama . trim($tag->find('a', 0)->innertext);
			$cama = ',';
		}
	}
	$ret = $item;
	$output = $ret;
	$ret = $item = '';
	print_r($output);
}