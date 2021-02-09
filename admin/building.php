<?php

@session_start();

$Root = $_SERVER['DOCUMENT_ROOT'];
require_once( $Root . '/member/config.php' );
require_once( $Root . '/member/func.php' );
require_once( $Root . '/member/htmllib.php' );

// ----------------------------------------------------------
// * LOGIN CHECK
// ----------------------------------------------------------

// ログインチェック
if( !is_wksg_login() ) {
  header('Location: /member/admin/');
  exit();
}

 ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>仲介業者専用サイト│大阪市の貸ビル、賃貸オフィススペースの若杉</title>

  <?php
  require_once( $Root . '/member/assets/parts/head.php');
  echoHeadOption();
  ?>

  <script src="/js/setcookie.js"></script>
  <script src="/member/assets/js/common.js"></script>

  <script>
    // エリア切り替え
    window.onload = function area_initialize() {
      var current = document.getElementById('area_id');
        for (var i=1; i<=6; i++) {
        if (current.value != i){
          $("#"+i).hide('0');
        }
      }
    }
    function change_area(ob) {
      var current = document.getElementById('area_id');
      $("#" + current.value).hide();
      $("#" + ob.dataset.area).show();
      current.value = ob.dataset.area;
    }
    $(function(){
			$('.TopMenu_list').on('click', function(event){
        $('.TopMenu_list').removeClass('active');
        event.preventDefault();
        $(this).toggleClass('active');
   		});
		});

    // lightbox Option
    $(function(){
      lightbox.option({
      'resizeDuration': 0,
      'fadeDuration': 0
      })
		});

    </script>

</head>
<body>

  <!-- wrap start -->
  <div class="wrap">

    <?php
    require_once( $Root . '/member/assets/parts/header.php');
    ?>

    <!-- container start -->
    <div class="container">

      <?php
      require_once( $Root . '/member/assets/parts/sidebar.php');
       ?>

      <!-- main start -->
      <div class="main">

        <!-- content start -->
        <div class="content">

          <?php
          require_once( $Root . '/member/assets/parts/officeSearch.php');
          ?>

          <h2 id="building" class="heading-01">各ビル空室状況</h2>

          <ul class="TopMenu_lists l-grid clearfix">
						<li class="TopMenu_list TopMenu_list-01 l-grid_item l-grid_item-2 l-grid_item-4-tab l-grid_item-6-sp active">
							<a href="javascript:void(0);" data-area="1" onclick="change_area(this)">南森町エリア</a>
						</li>
						<li class="TopMenu_list TopMenu_list-02 l-grid_item l-grid_item-2 l-grid_item-4-tab l-grid_item-6-sp">
							<a href="javascript:void(0);" data-area="2" onclick="change_area(this)">西梅田エリア</a>
						</li>
						<li class="TopMenu_list TopMenu_list-03 l-grid_item l-grid_item-2 l-grid_item-4-tab l-grid_item-6-sp">
							<a href="javascript:void(0);" data-area="3" onclick="change_area(this)">東梅田エリア</a>
						</li>
						<li class="TopMenu_list TopMenu_list-04 l-grid_item l-grid_item-2 l-grid_item-4-tab l-grid_item-6-sp">
							<a href="javascript:void(0);" data-area="4" onclick="change_area(this)">梅田エリア</a>
						</li>
						<li class="TopMenu_list TopMenu_list-05 l-grid_item l-grid_item-2 l-grid_item-4-tab l-grid_item-6-sp">
							<a href="javascript:void(0);" data-area="5" onclick="change_area(this)">中津エリア</a>
						</li>
						<li class="TopMenu_list TopMenu_list-06 l-grid_item l-grid_item-2 l-grid_item-4-tab l-grid_item-6-sp">
							<a href="javascript:void(0);" data-area="6" onclick="change_area(this)">京橋エリア</a>
						</li>
					</ul>

          <!-- 南森町 -->
          <input type="hidden" value="1" id="area_id">
          <div class="area_block" id="1">
            <!-- 01 若杉センタービル本館  -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building01_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">若杉センタービル本館</p>
                        <p class="LatestOffice_InfoAddress">大阪市北区東天満2丁目9番1号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/center_bldg_honkan/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-01"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
            <!-- 02 若杉センタービル別館 -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building02_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">若杉センタービル別館</p>
                        <p class="LatestOffice_InfoAddress">大阪市北区東天満2丁目8番1号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/center_bldg_bekkan/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-02"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
            <!-- 03 若杉グランドビル本館 -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building03_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">若杉グランドビル本館</p>
                        <p class="LatestOffice_InfoAddress">大阪市北区天神橋2丁目5番25号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/grand_bldg_honkan/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-03"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
            <!-- 04 若杉グランドビル別館 -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building04_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">若杉グランドビル別館</p>
                        <p class="LatestOffice_InfoAddress">大阪市北区東天満1丁目11番15号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/grand_bldg_bekkan/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-04"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
          </div>

          <!-- 西梅田 -->
          <div class="area_block" id="2">
            <!-- 05 若杉大阪駅前ビル -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building05_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">若杉大阪駅前ビル</p>
                        <p class="LatestOffice_InfoAddress">大阪市北区曽根崎新地2丁目3番13号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/osakaekimae_bldg/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-05"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
            <!-- 06 若杉西梅田ビル -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building06_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">若杉西梅田ビル</p>
                        <p class="LatestOffice_InfoAddress">大阪市福島区福島5丁目1番1号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/nishiumeda_bldg/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-06"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
          </div>

          <!-- 東梅田 -->
          <div class="area_block" id="3">
            <!-- 07 若杉東梅田ビル -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building07_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">若杉東梅田ビル</p>
                        <p class="LatestOffice_InfoAddress">大阪市北区堂山町18番2号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/higashiumeda_bldg/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-07"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
          </div>

          <!-- 梅田 -->
          <div class="area_block" id="4">
            <!-- 08 若杉梅田ビル -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building08_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">若杉梅田ビル</p>
                        <p class="LatestOffice_InfoAddress">大阪市北区神山町2番1号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/umeda_bldg/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-08"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
          </div>

          <!-- 中津 -->
          <div class="area_block" id="5">
            <!-- 09 若杉ビル -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building09_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">若杉ビル</p>
                        <p class="LatestOffice_InfoAddress">大阪市北区中津1丁目18番18号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/wakasugi_bldg_nakatsu/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-09"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
          </div>

          <!-- 京橋 -->
          <div class="area_block" id="6">
            <!-- 10 ニュー若杉ビル -->
            <ul class="LatestOffice_list">
              <li>
                <section class="LatestOffice_sect">
                  <div class="LatestOffice_cont">
                    <div class="LatestOffice_thumb">
                      <img src="/image_common/building10_thumb.jpg" alt="">
                    </div>
                    <div class="LatestOffice_info">
                      <div class="LatestOffice_InfoHead">
                        <p class="LatestOffice_InfoBuilding">ニュー若杉ビル</p>
                        <p class="LatestOffice_InfoAddress">大阪市都島区東野田町1丁目21番14号</p>
                      </div>
                    </div>
                    <div class="DetailBtn">
                      <p class="ButtonD-light ButtonD-arrow"><a href="/building/new_bldg/" target="_brank">ビル詳細</a></p>
                    </div>
                  </div>
                  <div class="LatestOffice_table" id="js-bukken-10"><img src="/js/ajax-loader.gif"></div>
                </section>
              </li>
            </ul>
          </div>
        </div>
        <!-- content end -->

        <?php
        require_once( $Root . '/member/assets/parts/footer.php');
        ?>

      </div>
      <!-- main end -->
    </div>
    <!-- container end -->
  </div>
  <!-- wrap end -->

  <script src="/member/assets/js/setBukkenData.js"></script>
	<script>
    setBukkenData('01');
    setBukkenData('02');
    setBukkenData('03');
    setBukkenData('04');
    setBukkenData('05');
    setBukkenData('06');
    setBukkenData('07');
    setBukkenData('08');
    setBukkenData('09');
    setBukkenData('10');

    $(document).ready(function() {
      // ---------------検索 start-------------------
      $(".pull_link").click(function() {
        $("#pull_canvas").slideToggle("slow");
        if ($("#pull_canvas").data("mark") == "0") {
          $(".pull_link").addClass('push_link');
          $("#pull_canvas").data("mark", "1")
        } else {
          $(".pull_link").removeClass('push_link');
          $("#pull_canvas").data("mark", "0")
        }
      });

      // ---------------検索 end-------------------

      // ---------------検索のページ start-------------------

      $(".page_button").click(function() {
        var p_href = $(this).attr("href");
        var list = get_parameter();
        for (var item in list) {
          if (item != "page" && item != "type") {
            p_href += "&" + item + "=" + list[item];
          }
        }
        p_href += "#bukken_mark";
        $(this).attr("href", p_href);
      });
      // ---------------検索のページ end-------------------

      // ---------------検索のチェック use start-------------------
      $(".op_icon.use p a").click(function() {
        if ($(this).attr('class') == "checked") {
          $(this).removeClass('checked');
        } else {
          $(this).addClass('checked');
        }
      });
      // ---------------検索のチェック end-------------------

      // ---------------検索のページ option start-------------------

      $(".op_icon.option p a").click(function() {
        if ($(this).attr('class') == "checked") {
          $(this).removeClass('checked');
          if ($(this).data('cate') == '1') {
            var op_one = [];
            op_one = $('#option_one').attr('value').split(",");
            for (var item in op_one) {
              if (op_one[item] == $(this).data('val')) {
                op_one.splice(item, 1);
                break;
              }
            }
            $("#option_one").attr("value", op_one.join(","));
            //alert($('#option_one').attr('value'));
          }

          if ($(this).data('cate') == '2') {
            var op_two = [];
            op_two = $('#option_two').attr('value').split(",");
            for (var item in op_two) {
              if (op_two[item] == $(this).data('val')) {
                op_two.splice(item, 1);
                break;
              }
            }
            $("#option_two").attr("value", op_two.join(","));
            //alert($('#option_two').attr('value'));
          }
        } else {
          $(this).addClass('checked');
          if ($(this).data('cate') == '1') {
            var op_one = [];
            if ($('#option_one').attr('value') != "") {
              op_one = $('#option_one').attr('value').split(",");
            }
            op_one.push($(this).data('val'));
            $("#option_one").attr("value", op_one.join(","));
            //alert($('#option_one').attr('value'));
          }

          if ($(this).data('cate') == '2') {
            var op_two = [];
            if ($('#option_two').attr('value') != "") {
              op_two = $('#option_two').attr('value').split(",");
            }
            op_two.push($(this).data('val'));
            $("#option_two").attr("value", op_two.join(","));
            //alert($('#option_two').attr('value'));
          }
        }
      });
      // ---------------検索のページ end-------------------

      // ---------------条件をクリア start-----------------
      $( "#bSearchResetBtn" ).click( function () {
        $( '#pull_canvas input[type="text"]' ).val('');
        $("#pull_canvas .op_icon p a.checked").removeClass('checked');
      } );



      // ---------------条件をクリア end-------------------
      $('.topicsContent .topicsContent_inner').matchHeight();

    });
  </script>

</body>
</html>
