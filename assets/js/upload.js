function file_upload() {

    if( $("#mediafile").val().length == 0 ){
      return false;
    }

    // フォームデータを取得
    var formdata = new FormData($('#media_form').get(0));

    $(".progress-box").removeClass("hidden");
    $(".file_upload_btn").addClass("hidden");

    // POSTでアップロード
    $.ajax({
        url  : "/member/user/profile/media_upload.php",
        type : "POST",
        data : formdata,
        cache       : false,
        contentType : false,
        processData : false,
        dataType    : "html",
        async: true,
        xhr : function(){
            var XHR = $.ajaxSettings.xhr();
            if(XHR.upload){
                XHR.upload.addEventListener('progress',function(e){
                    var progre = parseInt(e.loaded/e.total*100);
                    $(".progress-bar").css({width: progre+'%'});
                    $(".progress-data-num").text(progre);
                }, false);
            }
            return XHR;
        }
    })
    .done(function(data, textStatus, jqXHR){
        $(".progress-box").addClass("hidden");

        if( data != "nofile" && data != "upError" && data != "mimeError" ) {

          $("#logo_path").val(data);
          $("#company_logo").attr('src', '/member/user/data/upload/' + data);

          $("#media_form .heading-02 .check").removeClass("hidden");

        }

        if( data == "nofile" ) {
          alert('アップロードに失敗しました：[nofile]');
        }

        if( data == "upError" ) {
          alert('アップロードに失敗しました：[upError]');
        }

        if( data == "mimeError" ) {
          alert('アップロードに失敗しました：[mimeError]');
        }

        $(".file_upload_btn").removeClass("hidden");

    })
    .fail(function(jqXHR, textStatus, errorThrown){
        $(".progress-box").addClass("hidden");

        alert('アップロードに失敗しました：[fail]');

        $(".file_upload_btn").removeClass("hidden");
    });
}

function file_reset() {

  var noimg = "noimage.jpg";

  $("#logo_path").val(noimg);
  $("#company_logo").attr( 'src', '/member/user/data/upload/' + noimg );

}

