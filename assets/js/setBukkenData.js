/**
 * 物件情報を取得して表示する（table）
 * @parm {bldgId} ビルID -> 01 ～ 10
 * @parm {bcount} 最大表示件数
 */
function setBukkenData( bldgId, bcount, btype ) {

  if(typeof bcount === 'undefined') bcount = 20;

  var ssurl = '/member/assets/js/ajax_getBukken.php';
  var bldg_type = '';

  if( btype === 'm') {
    ssurl = '/member/assets/js/ajax_getMsBukken.php';
    bldg_type = btype;
  }

  $.post({
      url: ssurl,
      cache: false,
      async: true,
      data:{
          bldg_id : bldgId,
          b_count : bcount
      },
      dataType: 'html'
  }).done(function(data){

    var htmlData = data;

    // 描画
    $( "#js-bukken-" + bldg_type + bldgId ).html(htmlData);

    // クッキーを再設定
    cookieInit();

  }).fail(function(XMLHttpRequest, textStatus, errorThrown){
      console.log(errorThrown);
  })
}
