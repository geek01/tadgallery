
<div class="row">
  <div class="col-sm-12">
    <{if $cate.content}>
      <{if $cate.adm}>
        <{$jeditable_set}>
      <{/if}>
      <h1><{$cate.title}></h1>
      <div class="well" id="content" style="line-height: 1.8; white-space: pre-wrap; word-break: break-all;"><{$cate.content}></div>
    <{elseif $cate.adm}>
      <{$jeditable_set}>
      <h1><{$cate.title}></h1>
      <div class="well" id="content" style="line-height: 1.8; white-space: pre-wrap; word-break: break-all;"></div>
    <{/if}>
  </div>
</div>