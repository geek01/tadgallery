<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "tadgallery_view.tpl";
include_once XOOPS_ROOT_PATH . "/header.php";
/*-----------function區--------------*/

//觀看某一張照片
function view_pic($sn = "")
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $xoopsTpl, $xoTheme;

    $tadgallery = new tadgallery();

    //判斷是否對該模組有管理權限，  若空白
    if ($xoopsUser) {
        $nowuid    = $xoopsUser->getVar('uid');
        $module_id = $xoopsModule->getVar('mid');
        $isAdmin   = $xoopsUser->isAdmin($module_id);
    } else {
        $isAdmin = false;
        $nowuid  = "";
    }

    $sql    = "select * from " . $xoopsDB->prefix("tad_gallery") . " where sn='{$sn}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);
    //$csn,$title,$description,$filename,$size,$type,$width,$height,$dir,$uid,$post_date,$counter,$exif,$good,$tag,$photo_sort
    foreach ($all as $k => $v) {
        $$k = $v;
        $xoopsTpl->assign($k, $v);
    }

    $photo_s = $tadgallery->get_pic_url($dir, $sn, $filename, "s");
    $photo_m = $tadgallery->get_pic_url($dir, $sn, $filename, "m");
    $photo_l = $tadgallery->get_pic_url($dir, $sn, $filename);

    $xoopsTpl->assign("photo_s", $photo_s);
    $xoopsTpl->assign("photo_m", $photo_m);
    $xoopsTpl->assign("photo_l", $photo_l);

    if (!empty($csn)) {
        $ok_cat = $tadgallery->chk_cate_power();
        $cate   = $tadgallery->get_tad_gallery_cate($csn);
        if (!in_array($csn, $ok_cat)) {
            redirect_header("index.php?csn={$csn}&op=passwd_form", 3, sprintf(_TADGAL_NO_PASSWD_CONTENT, $cate['title']));
            exit;
        }

        $sql     = "select * from " . $xoopsDB->prefix("tad_gallery") . " where csn='{$csn}' order by photo_sort , post_date";
        $result  = $xoopsDB->query($sql) or web_error($sql);
        $slides1 = $slides2 = "";
        $i       = 0;
        $start   = false;
        while ($all = $xoopsDB->fetchArray($result)) {
            if ($sn == $all['sn']) {
                $start = true;
                $i     = 0;
            }

            if ($start) {
                $slides1[$i]['sn']          = $all['sn'];
                $slides1[$i]['photo']       = $tadgallery->get_pic_url($all['dir'], $all['sn'], $all['filename']);
                $slides1[$i]['description'] = strip_tags($all['description']);
                $slides1[$i]['thumb']       = ($all['is360']) ? $tadgallery->get_pic_url($all['dir'], $all['sn'], $all['filename'], 'm') : $tadgallery->get_pic_url($all['dir'], $all['sn'], $all['filename'], 's');
            } else {
                $slides2[$i]['sn']          = $all['sn'];
                $slides2[$i]['photo']       = $tadgallery->get_pic_url($all['dir'], $all['sn'], $all['filename']);
                $slides2[$i]['description'] = strip_tags($all['description']);
                $slides2[$i]['thumb']       = ($all['is360']) ? $tadgallery->get_pic_url($all['dir'], $all['sn'], $all['filename'], 'm') : $tadgallery->get_pic_url($all['dir'], $all['sn'], $all['filename'], 's');
            }
            $i++;
        }

    }

    $xoopsTpl->assign("slides1", $slides1);
    $xoopsTpl->assign("slides2", $slides2);

    //找出上一張或下一張
    $pnp = get_pre_next($csn, $sn);
    $xoopsTpl->assign("next", $pnp['next']);
    $xoopsTpl->assign("back", $pnp['pre']);

    $arr             = get_tadgallery_cate_path($csn);
    $jBreadCrumbPath = breadcrumb($csn, $arr);
    $xoopsTpl->assign("path", $jBreadCrumbPath);

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/fancybox.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/fancybox.php";
    $fancybox      = new fancybox('.fancybox');
    $fancybox_code = $fancybox->render(false);
    $xoopsTpl->assign('fancybox_code', $fancybox_code);

    $title     = (empty($title)) ? $filename : $title;
    $div_width = $xoopsModuleConfig['thumbnail_m_width'] + 30;
    $size_txt  = sizef($size);

    if ($uid == $nowuid or $isAdmin) {
        $xoopsTpl->assign('show_del', 1);
        //$del_btn="<a src='javascript:delete_tad_gallery_func($sn)' title='"._TADGAL_DEL_PIC."' class='btn btn-danger'>"._TAD_DEL."</a>";
        $xoopsTpl->assign('good', $good);

        $del_js = "
    <script>
    function delete_tad_gallery_func(sn){
      var sure = window.confirm('" . _TAD_DEL_CONFIRM . "');
      if (!sure)  return;
      location.href=\"{$_SERVER['PHP_SELF']}?op=delete_tad_gallery&sn=\" + sn;
    }
    </script>";

    } else {
        $del_btn = $admin_tool = $del_js = "";
    }

    $xoopsTpl->assign('del_btn', $del_btn);

    //秀出各種尺寸圖示
    if ($xoopsModuleConfig['show_copy_pic']) {
        $xoopsTpl->assign("photo_s", $photo_s);
        $xoopsTpl->assign("photo_m", $photo_m);
        $xoopsTpl->assign("photo_l", $photo_l);
        $xoopsTpl->assign("description", $description);
        $xoopsTpl->assign("sel_size", 1);
    } else {
        $xoopsTpl->assign("sel_size", 0);
    }

    //推文工具
    $push = push_url($xoopsModuleConfig['use_social_tools']);
    $xoopsTpl->assign("push", $push);
    $xoopsTpl->assign("pic_toolbar", $xoopsModuleConfig['pic_toolbar']);
    $xoopsTpl->assign("thumb_slider", $xoopsModuleConfig['thumb_slider']);

    //計數器
    add_tad_gallery_counter($sn);

    //地圖部份
    $info = explode("||", $exif);
    foreach ($info as $v) {
        $exif_arr    = explode("=", $v);
        $exif_arr[1] = str_replace("&#65533;", "", $exif_arr[1]);
        $bb          = "\$photoexif{$exif_arr[0]}=\"{$exif_arr[1]}\";";
        if (empty($exif_arr[0])) {
            continue;
        }

        @eval($bb);
    }
    // die(var_export($photoexif));

    // $Model360 = get360_arr();
    // $is360    = in_array($photoexif['IFD0']['Model'], $Model360) ? true : false;
    $xoopsTpl->assign("is360", $is360);

    $latitude  = $photoexif['GPS']['latitude'];
    $longitude = $photoexif['GPS']['longitude'];
    $xoopsTpl->assign("latitude", $latitude);
    $xoopsTpl->assign("longitude", $longitude);

    $jquery_path = get_jquery(true);
    $xoopsTpl->assign("jquery", $jquery_path);

    $xoopsTpl->assign("path", $jBreadCrumbPath);
    $xoopsTpl->assign("del_js", $del_js);

    $xoopsTpl->assign("div_width", $div_width);

    $facebook_comments = facebook_comments($xoopsModuleConfig['facebook_comments_width'], 'tadgallery', 'view.php', 'sn', $sn);
    $xoopsTpl->assign("facebook_comments", $facebook_comments);

    $fb_tag = "
      <meta property=\"og:title\" content=\"{$title}\" />
      <meta property=\"og:description\" content=\"{$description}\" />
      <meta property=\"og:image\" content=\"" . $tadgallery->get_pic_url($dir, $sn, $filename, "m") . "\" />
      ";
    $xoopsTpl->assign("xoops_module_header", $fb_tag);
    $xoopsTpl->assign("xoops_pagetitle", $title);
    if (is_object($xoTheme)) {
        $xoTheme->addMeta('meta', 'keywords', $title);
        $xoTheme->addMeta('meta', 'description', $description);
    } else {
        $xoopsTpl->assign('xoops_meta_keywords', 'keywords', $title);
        $xoopsTpl->assign('xoops_meta_description', $description);
    }

}

//更新人氣資料到tad_gallery中
function add_tad_gallery_counter($sn = "")
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_gallery") . " set `counter`=`counter`+1 where sn='{$sn}'";
    $xoopsDB->queryF($sql) or web_error($sql);

}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op  = system_CleanVars($_REQUEST, 'op', '', 'string');
$sn  = system_CleanVars($_REQUEST, 'sn', 0, 'int');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');

switch ($op) {
    case "good":
        update_tad_gallery_good($sn, '1');
        header("location: view.php?sn={$sn}#photo{$sn}");
        break;

    case "good_del":
        update_tad_gallery_good($sn, '0');
        header("location: view.php?sn={$sn}#photo{$sn}");
        break;

    case "delete_tad_gallery":
        $csn = delete_tad_gallery($sn);
        mk_rss_xml();
        mk_rss_xml($csn);
        header("location: index.php?csn=$csn");
        break;

    default:
        view_pic($sn);
        break;
}

/*-----------秀出結果區--------------*/

$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
$xoopsTpl->assign("bootstrap", get_bootstrap());

include_once XOOPS_ROOT_PATH . '/include/comment_view.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
