<div class="searchSection">
    <div class="searchSection_btn ButtonC pull_list">
        <a class="pull_link" href="javascript:void(0)">条件・仕様で絞り込むにはこちら</a>

        <div id="pull_canvas" class="list_canvas" data-mark="0">

        <div class="table_list">
            <table>
            <tr>
                <td>
                <div class="left_div l-clearfix">
                    <form id="form_table_key" action="/office_search/index.php#bukken_mark" method="get" target="_brank" enctype="multipart/form-data">
                    <div class="search_box left">
                        <dl>
                        <dt class="tit_tr"><span class="normal">キーワード検索</span></dt>
                        <dd class="input_area l-clearfix">
                            <div class="key_left key_left-keyword"><input type="text" name="key_word" placeholder="　ご希望の条件を入力して下さい">
                            </div>
                            <input type="hidden" name="type" value="11">
                            <div class="key_right"><input class="SearchBtn-02" type="submit" value="" />
                            </div>
                        </dd>
                        </dl>
                    </div>
                    </form>
                    <!-- <form id="" action="/office_search/index.php#bukken_mark" method="get" target="_brank" enctype="multipart/form-data"> -->
                    <div class="search_box right">
                        <dl>
                        <dt class="tit_tr"><span class="normal">エリアで検索</span>
                        </dt>
                        <dd class="l-clearfix">
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="/office_search/?type=14#bukken_mark" target="_brank">
                                <span class="op_way">南森町</span>
                                </a>
                            </p>
                            </div>
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="/office_search/?type=15#bukken_mark" target="_brank">
                                <span class="op_way">西梅田</span>
                                </a>
                            </p>
                            </div>
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="/office_search/?type=16#bukken_mark" target="_brank">
                                <span class="op_way">東梅田</span>
                                </a>
                            </p>
                            </div>
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="/office_search/?type=17#bukken_mark" target="_brank">
                                <span class="op_way">梅田</span>
                                </a>
                            </p>
                            </div>
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="/office_search/?type=18#bukken_mark" target="_brank">
                                <span class="op_way">中津</span>
                                </a>
                            </p>
                            </div>
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="/office_search/?type=19#bukken_mark" target="_brank">
                                <span class="op_way">京橋</span>
                                </a>
                            </p>
                            </div>
                        </dd>
                        </dl>
                    </div>
                    <!-- </form> -->
                    <form id="form_table_people" action="/office_search/index.php#bukken_mark" method="get" target="_brank" enctype="multipart/form-data">
                    <div class="search_box right hide-i">
                        <dl>
                        <dt class="tit_tr"><span class="normal">用途で検索</span></dt>
                        <dd class="l-clearfix">
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="javascript:void(0)">
                                <input type="hidden" name="IJ" value="0">
                                <span class="op_way">SOHO・創業オフィス</span>
                                </a>

                            </p>
                            </div>
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="javascript:void(0)">
                                <input type="hidden" name="D" value="0">
                                <span class="op_way">士業</span>
                                </a>

                            </p>
                            </div>
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="javascript:void(0)">
                                <input type="hidden" name="E" value="0">
                                <span class="op_way">支店・営業拠点</span>
                                </a>

                            </p>
                            </div>
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="javascript:void(0)">
                                <input type="hidden" name="F" value="0">
                                <span class="op_way">IT関連</span>
                                </a>

                            </p>
                            </div>
                            <div class="search_opt">
                            <p class="ButtonA_op ButtonA-theme_op">
                                <a href="javascript:void(0)">
                                <input type="hidden" name="L" value="0">
                                <span class="op_way">飲食店</span>
                                </a>

                            </p>
                            </div>
                        </dd>
                        </dl>
                    </div>
                    </form>
                </div>
                </td>
            </tr>

            <tr>
                <td>
                <div class="left_div l-clearfix">
                    <form id="form_table_size" action="/office_search/index.php#bukken_mark" method="get" target="_brank" enctype="multipart/form-data">
                    <div class="search_box left">
                        <dl>
                        <dt class="tit_tr">
                            <span class="normal">広さで検索</span>
                            <span><a href="/image_uniq/graph_img.png" class="search_size_lightbox" data-lightbox="areaimg" rel="lightbox">業種ごとに1人当たりの目安坪数</a></span>
                        </dt>
                        <dd class="input_area l-clearfix">
                            <input type="text" class="search_size" name="rs_min" value=""><span> 坪 ～ </span>
                            <input type="text" class="search_size" name="rs_max" value=""><span> 坪 </span>
                            <input type="hidden" name="type" value="9">
                            <div class="key_right"><input class="SearchBtn-02" type="submit" value=" " />
                            </div>
                        </dd>
                        </dl>
                    </div>
                    </form>
                    <form id="form_table_people" action="/office_search/index.php#bukken_mark" method="get" target="_brank" enctype="multipart/form-data">
                    <div class="search_box right">
                        <dl>
                        <dt class="tit_tr"><span class="normal">従業員数で検索</span><span class="small">　※1人あたり3坪計算</span></dt>
                        <dd class="input_area l-clearfix">
                            <input type="text" class="search_people" name="rp_min" value=""><span> 人 ～ </span>
                            <input type="text" class="search_people" name="rp_max" value=""><span> 人 </span>
                            <input type="hidden" name="type" value="10">
                            <div class="key_right"><input class="SearchBtn-02" type="submit" value="" />
                            </div>
                        </dd>
                        </dl>
                    </div>
                    </form>
                </div>
                </td>
            </tr>
            <!-- <tr>
                <td>
                <div class="form_buttons center_div">
                    <li><input class="ResetBtn" type="reset" value="条件をクリア" />
                    </li>
                    <li><button type="submit" class="SearchBtn-01">検索する</button>
                    </li>
                </div>
                </td>
            </tr> -->
            <form id="form_table_option" action="/office_search/index.php#bukken_mark" method="get" target="_brank" enctype="multipart/form-data">
                <tr class="tit_tr">
                <td class="u-align-l">
                    <span class="normal">こだわり検索</span><span class="small">　※複数選択可</span>
                </td>
                </tr>
                <tr>
                <td style="padding: 0">
                    <div class="center_div fix_position">
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="J" data-cate="1">
                            <span class="op_way">駅スグ</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="I" data-cate="1">
                            <span class="op_way">駅直結</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="D" data-cate="1">
                            <span class="op_way">貸会議室</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="E" data-cate="1">
                            <span class="op_way">貸駐車場</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="1FK" data-cate="2">
                            <span class="op_way">1Fコンビニ</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="P" data-cate="1">
                            <span class="op_way">EVリニューアル</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="O" data-cate="1">
                            <span class="op_way">空調リニューアル</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="N" data-cate="1">
                            <span class="op_way">給湯室リニューアル</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="M" data-cate="1">
                            <span class="op_way">トイレリニューアル</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="B" data-cate="1">
                            <span class="op_way">個別空調</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option hide-i">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="">
                            <span class="op_way">スケルトン</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option hide-i">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="">
                            <span class="op_way">内装工事可</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="C" data-cate="1">
                            <span class="op_way">光回線</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="L" data-cate="1">
                            <span class="op_way">OAフロア対応</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="F" data-cate="1">
                            <span class="op_way">ビル前ポスト</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="G" data-cate="1">
                            <span class="op_way">管理人常駐</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option hide-i">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="">
                            <span class="op_way">機械警備</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="Q" data-cate="1">
                            <span class="op_way">防犯カメラ</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="A" data-cate="1">
                            <span class="op_way">24時間利用</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option hide-i">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="">
                            <span class="op_way">新耐震基準</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="A" data-cate="2">
                            <span class="op_way">高階層(10階以上)</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="B" data-cate="2">
                            <span class="op_way">低階層(3階以上)</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="C" data-cate="2">
                            <span class="op_way">最上階</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="D" data-cate="2">
                            <span class="op_way">1F店舗空物件</span>
                        </a>

                        </p>
                    </div>
                    <div class="op_icon option hide-i">
                        <p class="ButtonA_op ButtonA-theme_op">
                        <a href="javascript:void(0)" data-val="E" data-cate="2">
                            <span class="op_way">ワンフロア</span>
                        </a>

                        </p>
                    </div>
                    </div>
                </td>
                </tr>
                <tr>
                <td>
                    <div class="center_div">
                    <input type="hidden" name="type" value="13">
                    <input type="hidden" id="option_one" name="option_one" value="">
                    <input type="hidden" id="option_two" name="option_two" value="">
                    <input type="hidden" id="option_three" name="option_three" value="">
                    <div class="form_buttons center_div">
                        <li><input id="bSearchResetBtn" class="ResetBtn" type="reset" value="条件をクリア" />
                        </li>
                        <li><button type="submit" class="SearchBtn-01">検索する</button>
                        </li>
                    </div>
                    </div>
                </td>
                </tr>
            </form>
            </table>
        </div>
        </div>

    </div>
</div>