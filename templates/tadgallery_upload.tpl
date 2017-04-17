<{$toolbar}>

<script type="text/javascript">

  $(document).ready(function(){
    make_option('csn_menu',0,0,<{if $def_csn}><{$def_csn}><{else}>0<{/if}>);
    make_option('m_csn_menu',0,0,<{if $def_csn}><{$def_csn}><{else}>0<{/if}>);
  });

  function make_option(menu_name , num , of_csn , def_csn){
    $('#'+menu_name+num).show();
    $.post('ajax_menu.php',  {'of_csn': of_csn , 'def_csn': def_csn} , function(data) {
      $('#'+menu_name+num).html("<option value=''>/</option>"+data);
    });

    $('.'+menu_name).change(function(){
    var menu_id= $(this).attr('id');
    var len=menu_id.length-1;
    var next_num = Number(menu_id.charAt(len))+1
      var next_menu = menu_name + next_num;
      $.post('ajax_menu.php',  {'of_csn': $('#'+menu_id).val()} , function(data) {
        if(data==""){
          $('#'+next_menu).hide();
        }else{
          $('#'+next_menu).show();
          $('#'+next_menu).html("<option value=''>/</option>"+data);
        }

      });
    });
  }

  function chk_csn(csn, new_csn){
    if(csn=="0" && new_csn==""){
      alert("<{$smarty.const._MD_TADGAL_NEED_CATE}>");
      return false;
    }else{
      return true;
    }
  }
</script>

<div id="jquery_tabs_tg_<{$now}>">
  <ul>
    <li><a href="#upload_one_pic"><span><{$smarty.const._MD_INPUT_FORM}></span></a></li>
    <li><a href="#upload_pics"><span><{$smarty.const._MD_TADGAL_MUTI_INPUT_FORM}></span></a></li>
    <li><a href="#upload_zip_pics"><span><{$smarty.const._MD_TADGAL_ZIP_IMPORT_FORM}></span></a></li>
    <li><a href="import.php#import" id="import"><span><{$smarty.const._MD_TADGAL_PATCH_IMPORT_FORM}></span></a></li>
  </ul>


  <div id="upload_one_pic">

    <form action="uploads.php" method="post" id="myForm" enctype="multipart/form-data" onsubmit="return chk_csn(this.csn.value,this.new_csn.value);" class="form-horizontal" role="form">

      <div class="form-group">
        <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_CSN}></label>
        <div class="col-sm-10 controls form-inline">
          <select name="csn_menu[0]" id="csn_menu0" class="csn_menu form-control"><option value=''></option></select>
          <select name="csn_menu[1]" id="csn_menu1" class="csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[2]" id="csn_menu2" class="csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[3]" id="csn_menu3" class="csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[4]" id="csn_menu4" class="csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[5]" id="csn_menu5" class="csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[6]" id="csn_menu6" class="csn_menu form-control" style="display: none;"></select>
          <input type="text" name="new_csn" class="form-control" placeholder="<{$smarty.const._MD_TADGAL_NEW_CSN}>" style="width: 200px;">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_PHOTO}></label>
        <div class="col-sm-10 controls">
          <input type="file" name="image">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_TITLE}></label>
        <div class="col-sm-10 controls">
          <input type="text" name="title" class="form-control" value="<{$title}>">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_DESCRIPTION}></label>
        <div class="col-sm-10 controls">
          <textarea style="min-height: 64px;font-size:12px;" name="description" class="form-control"></textarea>
        </div>
      </div>

      <!--<div class="form-group">
        <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_TAG}></label>
        <div class="col-sm-10 controls">
          <input type="text" name="new_tag" class="form-control" placeholder="<{$smarty.const._MD_TADGAL_TAG_TXT}>">
        </div>
      </div>-->

      <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10 controls">
          <{$tag_select}>
          <input type="hidden" name="sn" value="<{$sn}>">
          <input type="hidden" name="op" value="<{$op}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._MD_SAVE}></button>
        </div>
      </div>
    </form>
  </div>



  <div id="upload_pics">

    <form action="uploads.php" method="post" id="myForm_upload_pics" enctype="multipart/form-data" onsubmit="return chk_csn(this.csn.value,this.new_csn.value);" class="form-horizontal" role="form">

      <div class="form-group">
        <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_CSN}></label>
        <div class="col-sm-10 controls form-inline">
          <select name="csn_menu[0]" id="m_csn_menu0" class="m_csn_menu form-control"><option value=''></option></select>
          <select name="csn_menu[1]" id="m_csn_menu1" class="m_csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[2]" id="m_csn_menu2" class="m_csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[3]" id="m_csn_menu3" class="m_csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[4]" id="m_csn_menu4" class="m_csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[5]" id="m_csn_menu5" class="m_csn_menu form-control" style="display: none;"></select>
          <select name="csn_menu[6]" id="m_csn_menu6" class="m_csn_menu form-control" style="display: none;"></select>
          <input type="text" name="new_csn" class="form-control" placeholder="<{$smarty.const._MD_TADGAL_NEW_CSN}>" style="width: 200px;">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_PHOTO}></label>
        <div class="col-sm-10 controls">
          <input type="file" name="upfile[]" multiple="multiple" class="multi">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10 controls">
          <input type="hidden" name="op" value="upload_muti_file">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>

    </form>
  </div>



  <div id="upload_zip_pics">
    <form action="uploads.php" method="post" id="myForm_upload_pics" enctype="multipart/form-data" class="form-horizontal" role="form">
      <div class="form-group">
        <label class="col-sm-3 control-label"><{$smarty.const._MD_TADGAL_ZIP}></label>
        <div class="col-sm-9 controls">
          <input type="file" name="zipfile">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10 controls">
          <input type="hidden" name="op" value="upload_zip_file">
          <button type="submit" class="btn btn-primary"><{$smarty.const._MD_SAVE}></button>
        </div>
      </div>

    </form>
  </div>
</div>