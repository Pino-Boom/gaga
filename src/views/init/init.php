<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>初始化站点</title>
    <!-- Latest compiled and minified CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="../../public/css/init.css?_version=<?php echo $versionCode?>">
    <script type="text/javascript" src="../../../public/js/jquery.min.js"></script>
    <script src="../../public/js/template-web.js?_version=<?php echo $versionCode?>"></script>
    <script src="../../public/js/zalyjsHelper.js?_version=<?php echo $versionCode?>"></script>
    <script src="../../public/js/jquery.i18n.properties.min.js?_version=<?php echo $versionCode?>"></script>
    <script type="text/javascript">
        var latestVersion="0";
        function setLasteVersion(lasteVersion) {
            latestVersion = lasteVersion;
        }
    </script>
    <script src="https://duckchat.akaxin.com/static/checkVersion.js?_version=<?php echo $versionCode?>"></script>

</head>
<body>
    <div class="container">

        <div class="zaly_container">
           <div style="padding-top: 14rem;">
               <div class="zaly_init">

               </div>
           </div>
        </div>

        <div class="zaly_window">

        </div>
    </div>
<input type="hidden" value="<?php echo $siteVersion?>" class="site_version">

    <input type="hidden" value="<?php echo $isPhpVersionValid;?>" class="isPhpVersionValid">
    <input type="hidden" value="<?php echo $isLoadOpenssl;?>" class="isLoadOpenssl">
    <input type="hidden" value="<?php echo $isLoadPDOSqlite;?>" class="isLoadPDOSqlite">
    <input type="hidden" value="<?php echo $isLoadPDOMysql; ?>" class="isLoadPDOMysql">
    <input type="hidden" value="<?php echo $isLoadCurl;?>" class="isLoadCurl">
    <input type="hidden" value="<?php echo $isWritePermission;?>" class="isWritePermission">
    <input type="hidden" value='<?php echo $dbFiles;?>' class="dbFiles">

    <?php include (dirname(__DIR__) . '/init/template_init.php');?>

<script>

    var isPhpVersionValid = $(".isPhpVersionValid").val();
    var isLoadOpenssl = $(".isLoadOpenssl").val();
    var isLoadPDOMysql = $(".isLoadPDOMysql").val();
    var isLoadPDOSqlite = $(".isLoadPDOSqlite").val();
    var isWritePermission = $(".isWritePermission").val();
    var isLoadCurl = $(".isLoadCurl").val();
    var isCanLoadPropertites = false;
    var dbFiles = $(".dbFiles").val();
    var isAvaliableSiteEnv = true;

    var dbHost = "";
    var dbPort = "";
    var dbUserName = "";
    var dbPassword = "";
    var dbName = "";
    var sqliteFileName = "";
    var dbType = "sqlite";
    var upgradeUrl='https://github.com/duckchat/gaga/releases';

    function testCanLoadPropertites()
    {
        $.ajax({
            method: "GET",
            url: "./public/js/config/lang_init_"+languageName+".properties?_"+Date.now(),
            success: function () {
                isCanLoadPropertites = true;
            }
        });
    }

    testCanLoadPropertites();


    jQuery.i18n.properties({
        name: "lang_init",
        path: '../../public/js/config/',
        mode: 'map',
        language: languageName,
        callback: function () {
            try {
                //初始化页面元素
                $('[data-local]').each(function () {
                    var changeData = $(this).attr("data-local");
                    var changeDatas = changeData.split(":");
                    var changeDataName = changeDatas[0];
                    var changeDataValue = changeDatas[1];
                    $(this).attr(changeDataName, $.i18n.map[changeDataValue]);
                });
                $('[data-local-value]').each(function () {
                    var changeHtmlValue = $(this).attr("data-local-value");
                    $(this).html($.i18n.map[changeHtmlValue]);
                });
                $('[data-local-placeholder]').each(function () {
                    var placeholderValue = $(this).attr("data-local-placeholder");
                    $(this).attr("placeholder", $.i18n.map[placeholderValue]);
                });
            }
            catch(ex){
                console.log(ex.message);
            }
        }
    });

    function initProtocol()
    {
        var protocolHtml = template("tpl-protocol-init", {});
        protocolHtml = handleHtmlLanguage(protocolHtml);
        $(".zaly_init")[0].style.background="rgba(243,243,243,1)";
        $(".zaly_init").html(protocolHtml)
    }
    initProtocol();
    $(document).on("click", ".zaly_protocol_sure",function () {
        var siteVersion = $(".site_version").val();
        if(Number(latestVersion) > Number(siteVersion)) {
            $(".zaly_window")[0].style.display = "flex";
            var upgradeHtml = template("tpl-upgrade-tip", {
                siteVersion:$(".site_version").val(),
            });
            upgradeHtml = handleHtmlLanguage(upgradeHtml)
            $(".zaly_window").html(upgradeHtml);
            return;
        }
        newStepForCheckEnv("check_site_env");
    });

    $(document).on("click", ".zaly_site_upgrade_sure", function () {
        event.preventDefault();
        window.open(upgradeUrl);
        newStepForCheckEnv('upgrade_site');
    });

    function newStepForCheckEnv(type)
    {
        $(".zaly_init")[0].style.background="rgba(255,255,255,1)";

        if(type == 'data_init') {
            if(dbType == "mysql") {
                dbHost = $(".sql-dbHost").val();
                dbPort = $(".sql-dbPort").val();
                dbUserName = $(".sql-dbUserName").val();
                dbPassword = $(".sql-dbPassword").val();
                dbName = $(".sql-dbName").val();
            } else {
                var selector = document.getElementById('sqlite-file');
                sqliteFileName = $(selector[selector.selectedIndex]).attr("fileName");
            }
            $(".initDiv")[0].style.display="none";
        } else {
            $(".zaly_protocol_init")[0].style.display ="none";
            $(".zaly_window")[0].style.display = "none";
        }

        var html = template("tpl-check-site-environment", {
            isPhpVersionValid:isPhpVersionValid,
            isLoadOpenssl:isLoadOpenssl,
            isLoadPDOSqlite:isLoadPDOSqlite,
            isLoadPDOMysql:isLoadPDOMysql,
            isLoadCurl:isLoadCurl,
            isWritePermission:isWritePermission,
            isLoadProperties:isCanLoadPropertites,
        });
        html = handleHtmlLanguage(html);
        $(".zaly_init").html(html);

        testCurl();

        if(!isPhpVersionValid) {
            $(".isPhpVersionValid")[0].style.color="#F44336";
            isAvaliableSiteEnv = false;
        }
        if(!isLoadOpenssl) {
            $(".isLoadOpenssl")[0].style.color="#F44336";
            isAvaliableSiteEnv = false;
        }
        if(!isLoadPDOSqlite) {
            $(".isLoadPDOSqlite")[0].style.color="#F44336";
            isAvaliableSiteEnv = false;
        }
        if(!isLoadPDOMysql) {
            $(".isLoadPDOMysql")[0].style.color="#F44336";
            isAvaliableSiteEnv = false;
        }
        if(!isLoadCurl) {
            $(".isLoadCurl")[0].style.color="#F44336";
            isAvaliableSiteEnv = false;
        }
        if(!isWritePermission) {
            $(".isWritePermission")[0].style.color="#F44336";
            isAvaliableSiteEnv = false;
        }
        if(!isCanLoadPropertites) {
            $(".isLoadProperties")[0].style.color="#F44336";
            isAvaliableSiteEnv = false;
        }

        if(isAvaliableSiteEnv == false) {
            $(".next_init_data")[0].style.background="rgba(201,201,201,1)";
            $(".next_init_data").attr("disabled", "disabled");
        }
    }
    
    $(document).on("click", ".previte_init_protocol", function () {
            initProtocol();
    });
    
    $(document).on("click", ".next_init_data", function () {
        if(isAvaliableSiteEnv == false) {
            return;
        }
        try{
            if(dbFiles == undefined || !dbFiles) {
                var sqliteFiles = new Array();
            } else {
                var sqliteFiles = JSON.parse(dbFiles);
            }
        }catch (error){
            var sqliteFiles = new Array();
        }

        var initDataHtml = template("tpl-init-data", {
            dbFiles:sqliteFiles
        });
        initDataHtml = handleHtmlLanguage(initDataHtml);
        $(".zaly_init").html(initDataHtml);

        if(dbType == "mysql") {
            $(".sql-dbHost").val(dbHost);
            $(".sql-dbPort").val(dbPort);
            $(".sql-dbUserName").val(dbUserName);
            $(".sql-dbPassword").val(dbPassword);
            $(".sql-dbName").val(dbName);
        } else {
            if(sqliteFileName.length>0) {
                var selector = document.getElementById('sqlite-file');
                for(var i=0; i<selector.options.length; i++){
                    if(selector.options[i].innerHTML == sqliteFileName){
                        selector.options[i].selected = true;
                        break;
                    }
                }
            }
        }
        initDBType(dbType);
    });


    function clickRadio(radioValue) {
        var className = "." + radioValue + "Radio";
        var isSelected = $(className).attr("isSelected");
        var src;
        var unSelectSrc = "../../public/img/init/un_select.png";
        if (radioValue == "mysql") {
            $(".sqliteRadio").attr("isSelected", "0");
            $(".sqliteRadio").attr("src", unSelectSrc);
            $(".mysql-div")[0].style.display = "block";
            $(".sqlite-div")[0].style.display = "none";

            $(".ext_pdo_sqlite").hide();
            $(".ext_pdo_mysql").show();
        } else {
            $(".mysqlRadio").attr("isSelected", "0");
            $(".mysqlRadio").attr("src", unSelectSrc);
            $(".sqlite-div")[0].style.display = "block";
            $(".mysql-div")[0].style.display = "none";

            $(".ext_pdo_sqlite").show();
            $(".ext_pdo_mysql").hide();
        }

        if (isSelected == "1") {
            src = unSelectSrc;
            $(className).attr("isSelected", "0");
            dbType = ""
        } else {
            src = "../../public/img/init/select.png";
            $(className).attr("isSelected", "1");
            dbType = radioValue
        }
        $(className).attr("src", src);
    }

    function initDBType()
    {
        var unSelectSrc = "../../public/img/init/un_select.png";

        if(dbType == "mysql") {
            $(".mysql-div")[0].style.display = "block";
            $(".sqlite-div")[0].style.display = "none";
            $(".ext_pdo_sqlite").hide();
            $(".ext_pdo_mysql").show();
        } else {
            $(".mysqlRadio").attr("isSelected", "0");
            $(".mysqlRadio").attr("src", unSelectSrc);
            $(".sqlite-div")[0].style.display = "block";
            $(".mysql-div")[0].style.display = "none";

            $(".ext_pdo_sqlite").show();
            $(".ext_pdo_mysql").hide();
        }

        var className = "." + dbType + "Radio";
        src = "../../public/img/init/select.png";
        $(className).attr("isSelected", "1");
    }

    $(document).on("click", ".login_button", function () {
        if (!isLoadOpenssl) {
            alert("请先安装openssl");
            return false;
        }

        if (!isLoadPDOSqlite) {
            alert("请先安装pdo_sqlite");
            return false;
        }

        if (!isLoadCurl) {
            alert("请先安装is_curl");
            return false;
        }

        if(!isLoadPDOMysql) {
            alert("请先安装pdo_mysql");
            return false;
        }

        if (!isWritePermission) {
            alert("当前目录不可写");
            return false;
        }

        var selector = document.getElementById('verifyPluginId');
        var pluginId = $(selector[selector.selectedIndex]).attr("pluginId");
        if (dbType == "") {
            alert("请选择数据库类型");
            return;
        }

        var uic = $(".uic-input").val();
        if (dbType == 'mysql') {
             dbHost = $(".sql-dbHost").val();
             dbPort = $(".sql-dbPort").val();
             dbUserName = $(".sql-dbUserName").val();
             dbPassword = $(".sql-dbPassword").val();
             dbName = $(".sql-dbName").val();

            var isFocus = false;
            if (dbName == "" || dbName.length < 1) {
                $(".dbNameFailed")[0].style.display = "block";
                isFocus = true;
                $(".sql-dbName").focus();
            }

            if (dbUserName == "" || dbUserName.length < 1) {
                $(".dbUserNameFailed")[0].style.display = "block";
                if (isFocus == false) {
                    $(".sql-dbUserName").focus();
                    isFocus = true;
                    $(".dbNameFailed")[0].style.display = "none";
                }
            }

            if (dbPassword == "" || dbPassword.length < 1) {
                $(".dbPasswordFailed")[0].style.display = "block";
                if (isFocus == false) {
                    $(".sql-dbPassword").focus();
                    isFocus = true;
                    $(".dbNameFailed")[0].style.display = "none";
                    $(".dbUserNameFailed")[0].style.display = "none";
                }
            }

            if (dbPort == "" || dbPort.length < 1) {
                dbPort = 3306;
            }

            if (dbHost == "" || dbHost.length < 1) {
                $(".dbHostFailed")[0].style.display = "block";
                if (isFocus == false) {
                    $(".sql-dbHost").focus();
                    isFocus = true;
                    $(".dbPortFailed")[0].style.display = "none";
                    $(".dbNameFailed")[0].style.display = "none";
                    $(".dbHostFailed")[0].style.display = "none";
                    $(".dbPasswordFailed")[0].style.display = "none";
                }
            }

            if (isFocus == true) {
                return;
            }
            $(".dbPasswordFailed")[0].style.display = "none";

            var data = {
                pluginId: pluginId,
                dbHost: dbHost,
                dbPort: dbPort,
                dbUserName: dbUserName,
                dbPassword: dbPassword,
                dbName: dbName,
                dbType: dbType,
                uic: uic
            };
            testConnectMysql(data);
            return;
        }

        var selector = document.getElementById('sqlite-file');
        sqliteFileName = $(selector[selector.selectedIndex]).attr("fileName");
        var data = {
            pluginId: pluginId,
            dbType: dbType,
            sqliteDbFile: sqliteFileName,
            uic: uic
        };
        initSite(data);
    });

    function initSite(data)
    {
        $.ajax({
            method: "POST",
            url: "./index.php?action=installDB",
            data: data,
            success: function (resp) {
                if (resp == "success") {
                    window.location.href = "./index.php?action=page.logout";
                } else {
                    var html = template("tpl-error-info", {
                        errorInfo:resp
                    })
                    $(".errorInfo").html(html);
                }
            }
        });
    }

    function testConnectMysql(data)
    {
        $.ajax({
            method: "POST",
            url: "./index.php?action=installDB&for=test_connect_mysql",
            data: data,
            success: function (resp) {
                if (resp == "success") {
                    initSite(data);
                } else {
                    var html = template("tpl-error-info", {
                        errorInfo:resp
                    })

                    $(".errorInfo").html(html);
                }
            }
        });
    }

    function testCurl()
    {
        console.log("lll");
        $.ajax({
            method: "GET",
            url: "./index.php?action=installDB&for=test_curl_result",
            success: function (resp) {
                if (resp == "success") {
                    $(".isCanUseCurlImg").attr("src", "../../public/img/init/check_success.png");
                    $(".isCanUseCurl")[0].style.color="rgba(102,102,102,1)";
                    $(".next_init_data")[0].style.background="rgba(76,59,177,1)";
                    $(".next_init_data").attr("disabled", false);
                    return;
                }
                isAvaliableSiteEnv = false;
                $(".next_init_data")[0].style.background="rgba(201,201,201,1)";
                $(".next_init_data").attr("disabled", "disabled");
            },
        });
    }

</script>
</body>
</html>
