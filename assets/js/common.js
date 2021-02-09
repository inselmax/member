(function () {

    function createPdf(obj) {

        var $_this = obj;
        var oid = $_this.data("oid");
        var otype = $_this.data("otype");

        var ssurl = "";

        switch ( otype ) {
            case "b":
                ssurl = "/member/user/data/pdf/createpdf.php";
                break;

            case "b_1_01":
                ssurl = "/member/user/data/pdf/createpdf_1_01.php";
                break;

            case "b_1_02":
                ssurl = "/member/user/data/pdf/createpdf_1_02.php";
                break;

            case "b_1_03":
                ssurl = "/member/user/data/pdf/createpdf_1_03.php";
                break;

            case "b_1_04":
                ssurl = "/member/user/data/pdf/createpdf_1_04.php";
                break;

            case "b_1_05":
                ssurl = "/member/user/data/pdf/createpdf_1_05.php";
                break;

            case "b_1_06":
                ssurl = "/member/user/data/pdf/createpdf_1_06.php";
                break;

            case "b_1_07":
                ssurl = "/member/user/data/pdf/createpdf_1_07.php";
                break;

            case "b_1_08":
                ssurl = "/member/user/data/pdf/createpdf_1_08.php";
                break;

            case "b_1_09":
                ssurl = "/member/user/data/pdf/createpdf_1_09.php";
                break;

            case "b_1_10":
                ssurl = "/member/user/data/pdf/createpdf_1_10.php";
                break;

            case "m":
                ssurl = "/member/user/data/pdf/createpdfM.php";
                break;

            case "m_1":
                ssurl = "/member/user/data/pdf/createpdfM_1.php";
                break;
        }

        var $_loading = $(".loading", $_this);

        $_this.prop("disabled", true);
        $_loading.removeClass("hidden");

        $.post({
            url: ssurl,
            cache: false,
            async: true,
            data: {
            office_id: oid,
        },
        })
        .done(function (data) {
            if (data) {
                window.open(data, "_blank");
            }

            $_loading.addClass("hidden");
            $_this.prop("disabled", false);
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown) {
            // console.log(errorThrown);
        });
    }

    $(function () {
        $(document).on("click", ".pdfFormSubmit", function () {
            createPdf($(this));
        });
    });

})();