<?php
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/function_block.php";

//區塊主函式 (縮圖列表)
function tadgallery_list($options)
{
    global $xoopsDB;

    $order_array = array('post_date', 'counter', 'rand', 'photo_sort');
    $limit       = empty($options[0]) ? 12 : intval($options[0]);
    $view_csn    = empty($options[1]) ? '' : intval($options[1]);
    $include_sub = empty($options[2]) ? "0" : "1";
    $order_by    = in_array($options[3], $order_array) ? $options[3] : "post_date";
    $desc        = empty($options[4]) ? "" : "desc";
    $size        = (!empty($options[5]) and $options[5] == "s") ? "s" : "m";
    $only_good   = $options[6] != '1' ? "0" : "1";

    $options[7] = intval($options[7]);
    $width      = empty($options[7]) ? 120 : $options[7];
    $options[8] = intval($options[8]);
    $height     = empty($options[8]) ? 120 : $options[8];

    $options[9] = intval($options[9]);
    $margin     = empty($options[9]) ? 0 : $options[9];

    $show_txt = ($options[10] == "1") ? "1" : "0";

    $style = (empty($options[11]) or strrpos(';', $options[11]) === false) ? 'font-size:11px;font-weight:normal;overflow:hidden;' : $options[11];

    $tadgallery = new tadgallery();
    $tadgallery->set_limit($limit);
    if ($view_csn) {
        $tadgallery->set_view_csn($view_csn);
    }

    $tadgallery->set_orderby($order_by);
    $tadgallery->set_order_desc($desc);
    $tadgallery->set_view_good($only_good);
    $photos = $tadgallery->get_photos($include_sub);

    $pics = "";
    $i    = 0;
    foreach ($photos as $photo) {
        $pp                   = 'photo_' . $size;
        $pic_url              = $photo[$pp];
        $pics[$i]['pic_url']  = $pic_url;
        $pics[$i]['photo_sn'] = $photo['sn'];
        $pics[$i]['pic_txt']  = (empty($photo['title'])) ? $photo['filename'] : $photo['title'];

        $i++;
    }
    //die(var_export($pics));
    $block['view_csn'] = $view_csn;
    $block['width']    = $width;
    $block['height']   = $height;
    $block['margin']   = $margin;
    $block['style']    = $style;
    $block['pics']     = $pics;
    $block['show_txt'] = $show_txt;

    return $block;
}

//區塊編輯函式
function tadgallery_list_edit($options)
{
    //die(var_export($options));
    //$option0~6
    $common_setup = common_setup($options);

    $options[7] = intval($options[7]);
    if (empty($options[7])) {
        $options[7] = 100;
    }

    $options[8] = intval($options[8]);
    if (empty($options[8])) {
        $options[8] = 100;
    }

    $options[9] = intval($options[9]);
    if (empty($options[9])) {
        $options[9] = 0;
    }

    $show_txt_0 = ($options[10] != "1") ? "checked" : "";
    $show_txt_1 = ($options[10] == "1") ? "checked" : "";

    if (empty($options[11])) {
        $options[11] = 'font-size:11px;font-weight:normal;overflow:hidden;';
    }

    //$opt0_show_photo_num=opt0_show_photo_num($options[0]);
    $form = "
  {$common_setup}

  <div>
    " . _MB_TADGAL_BLOCK_THUMB_WIDTH . "
    <input type='text' name='options[7]' value='{$options[7]}' size=3> x
    " . _MB_TADGAL_BLOCK_THUMB_HEIGHT . "
    <input type='text' name='options[8]' value='{$options[8]}' size=3> px
  </div>


  <div>
    " . _MB_TADGAL_BLOCK_THUMB_SPACE . "
    <input type='text' name='options[9]' value='{$options[9]}' size=2> px
  </div>

  <div>
      " . _MB_TADGAL_BLOCK_SHOW_TEXT . "
    <label for='show_txt_1'>
      <input type='radio' name='options[10]' value=1 $show_txt_1 id='show_txt_1'>
      " . _MB_TADGAL_BLOCK_SHOW_TEXT_Y . "
    </label>
    <label for='show_txt_0'>
      <input type='radio' name='options[10]' value=0 $show_txt_0 id='show_txt_0'>
      " . _MB_TADGAL_BLOCK_SHOW_TEXT_N . "
    </label>
  </div>

  <div>
    " . _MB_TADGAL_BLOCK_TEXT_CSS . "
    <input type='text' name='options[11]' value='{$options[11]}' size=100>
  </div>
  ";
    return $form;
}
