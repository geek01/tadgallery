<{includeq file="db:tadgallery_cate_fancybox.tpl"}>

<!--工具列-->
<{$toolbar}>

<!--下拉選單及目前路徑-->
<div class="row">
  <div class="col-sm-12">
    <{$path}>
  </div>
</div>

<!--相簿-->
<{if $only_thumb!="1"}>
  <{includeq file="db:tadgallery_albums.tpl"}>
<{/if}>
