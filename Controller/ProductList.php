
<?php
$url=(@dirname($_POST['url']));
$item=$output[]='';
if (!$url){
//	echo '';
	exit('لطفا لینک لیست محصولات را وارد کنید');
}else {
	include('../Plugins/simple_html_dom.php');
	$html = file_get_html($url);
	$i = 1;
	foreach ($html->find('div.detail_container') as $e) {
		if ($i == 1)# Request Limit
			# لیست محصول
		$item['title'] = trim($e->find('span.product_listing_name', 0)->innertext);
		$item['link'] = dirname($e->find('a.pro_name', 0)->href);
		$item['price'] = trim($e->find('span.productPrice,span.productStatus', 0)->innertext);
		# مشخصات محصول
		$inner_html = file_get_html($item['link']);
		foreach ($inner_html->find('table.middle_content') as $o) {
			$item['product']['description'] = trim($o->find('div.products_description_container', 0)->innertext);
			#ویژگی های محصول
			foreach ($o->find('table.pef_group_container_table') as $value) {
				foreach ($value->find('tr.pef_inner_container_box_group_container_row_odd,tr.pef_inner_container_box_group_container_row_even') as $tr) {
					$item['product']['feature']['row_title'][] = trim($tr->find('td.pef_inner_container_box_group_container_row_title', 0)->innertext);
					$item['product']['feature']['row_desc'][] = trim($tr->find('td.pef_inner_container_box_group_container_row_value span', 0)->innertext);
				}
			}
			$cama = $item['tags'] = '';
			foreach ($o->find('ul.product_cloud_tags li') as $tag) {
				$item['tags'] .= $cama . trim($tag->find('a', 0)->innertext);
				$cama = ',';
			}
		}
		$ret[] = $item;
		$output[] = $ret;
		$ret = $item = '';
		$i++;
	}
	print_r($output);
}