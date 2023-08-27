<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>SURAT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="shortcut icon" href="https://simaster.ugm.ac.id/ugmfw-assets/images/favicon.ico" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,800,300&subset=latin" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script>
        function _pxDemo_loadStylesheet(a, b, c) {
            var c = c || decodeURIComponent((new RegExp(";\\s*" + encodeURIComponent("px-demo-theme") + "\\s*=\\s*([^;]+)\\s*;", "g").exec(";" + document.cookie + ";") || [])[1] || "default"),
                d = "1" === decodeURIComponent((new RegExp(";\\s*" + encodeURIComponent("px-demo-rtl") + "\\s*=\\s*([^;]+)\\s*;", "g").exec(";" + document.cookie + ";") || [])[1] || "0");
            document.write(a.replace(/^(.*?)((?:\.min)?\.css)$/, '<link href="$1' + (c.indexOf("dark") !== -1 && a.indexOf("/css/") !== -1 && a.indexOf("/themes/") === -1 ? "-dark" : "") + (d && a.indexOf("assets/") === -1 ? ".rtl" : "") + '$2" rel="stylesheet" type="text/css"' + (b ? 'class="' + b + '"' : "") + ">"))
        }
    </script>
    <script>
        "1" === decodeURIComponent((new RegExp(";\\s*" + encodeURIComponent("px-demo-rtl") + "\\s*=\\s*([^;]+)\\s*;", "g").exec(";" + document.cookie + ";") || [])[1] || "0") && document.getElementsByTagName("html")[0].setAttribute("dir", "rtl");
    </script>
    <script>
        _pxDemo_loadStylesheet('https://simaster.ugm.ac.id/ugmfw-assets/css/bootstrap.min.css', 'px-demo-stylesheet-core');
        _pxDemo_loadStylesheet('https://simaster.ugm.ac.id/ugmfw-assets/css/pixeladmin.min.css', 'px-demo-stylesheet-bs');
    </script>
    <script>
        function _pxDemo_loadTheme(a) {
            var b = decodeURIComponent((new RegExp(";\\s*" + encodeURIComponent("px-demo-theme") + "\\s*=\\s*([^;]+)\\s*;", "g").exec(";" + document.cookie + ";") || [])[1] || "default");
            _pxDemo_loadStylesheet(a + b + ".min.css", "px-demo-stylesheet-theme", b)
        }
        _pxDemo_loadTheme('https://simaster.ugm.ac.id/ugmfw-assets/css/themes/');
    </script>
    <script>
        _pxDemo_loadStylesheet('https://simaster.ugm.ac.id/ugmfw-assets/css/demo.css');
    </script>

    <!--[if !IE]> -->
    <script src="https://simaster.ugm.ac.id/ugmfw-assets/js/jquery-2.2.0.min.js"></script>

</head>

<body style="background-image: url('<?= base_url('mipa.jpg'); ?>');">
    <script type="text/javascript">
        var pxInit = [];
        var checked = [];
        var url = '';
        var env = "production";
        var base = "https://simaster.ugm.ac.id/";
        var app = "";
        var cs = "https://wa.me/628112826546?text=Akun UGM: %0AMenu: %0AURL: -%0ALapor: ";
        var xhr = null;
        if (typeof XMLHttpRequest === "undefined") {
            xhr = function() {
                try {
                    return new ActiveXObject("Msxml2.XMLHTTP.6.0");
                } catch (e) {}
                try {
                    return new ActiveXObject("Msxml2.XMLHTTP.3.0");
                } catch (e) {}
                try {
                    return new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
            };
        } else {
            xhr = new XMLHttpRequest();
        }
        var to, to_10, to_20;
        localStorage['ugmfwCache'] = '';
    </script>
    <style>
        .page-signin-modal {
            position: relative;
            top: auto;
            right: auto;
            bottom: auto;
            left: auto;
            z-index: 1;
            display: block;
        }

        .page-signin-form-group {
            position: relative;
        }

        .page-signin-icon {
            position: absolute;
            line-height: 21px;
            width: 36px;
            border-color: rgba(0, 0, 0, .14);
            border-right-width: 1px;
            border-right-style: solid;
            left: 1px;
            top: 9px;
            text-align: center;
            font-size: 15px;
        }

        html[dir="rtl"] .page-signin-icon {
            border-right: 0;
            border-left-width: 1px;
            border-left-style: solid;
            left: auto;
            right: 1px;
        }

        html:not([dir="rtl"]) .page-signin-icon+.page-signin-form-control {
            padding-left: 50px;
        }

        html[dir="rtl"] .page-signin-icon+.page-signin-form-control {
            padding-right: 50px;
        }

        #page-signin-forgot-form {
            display: none;
        }

        .page-signin-modal>.modal-dialog {
            margin: 30px 10px;
        }

        @media (min-width: 544px) {
            .page-signin-modal>.modal-dialog {
                margin: 60px auto;
            }
        }
    </style>
    <script>
        pxInit.push(function() {
            $(function() {
                // pxDemo.initializeBgsDemo('body', 1, '#000', function(isBgSet) {
                // $('#px-demo-signup-link, #px-demo-signup-link a')
                //         .addClass(isBgSet ? 'text-white' : 'text-muted')
                //         .removeClass(isBgSet ? 'text-muted' : 'text-white');
                // });
                $('#page-signin-forgot-link').on('click', function(e) {
                    e.preventDefault();
                    $('#page-signin-form, #page-signin-social')
                        .css({
                            opacity: '1'
                        })
                        .animate({
                            opacity: '0'
                        }, 200, function() {
                            $(this).hide();
                            $('#page-signin-forgot-form')
                                .css({
                                    opacity: '0',
                                    display: 'block'
                                })
                                .animate({
                                    opacity: '1'
                                }, 200)
                                .find('.form-control').first().focus();
                            $(window).trigger('resize');
                        });
                });
                $('#page-signin-forgot-back').on('click', function(e) {
                    e.preventDefault();
                    $('#page-signin-forgot-form')
                        .animate({
                            opacity: '0'
                        }, 200, function() {
                            $(this).css({
                                display: 'none'
                            });
                            $('#page-signin-form, #page-signin-social')
                                .show()
                                .animate({
                                    opacity: '1'
                                }, 200)
                                .find('.form-control').first().focus();
                            $(window).trigger('resize');
                        });
                });
            });
        });
    </script>

    <noscript>
        <style>
            .page-signin-modal {
                display: none
            }
        </style>
        <div class="page-header">
            <h1><i class="page-header-icon fa fa-unlink"></i>&nbsp;Terjadi Galat</h1>
        </div>
        <div class="note note-danger"><b>Mohon maaf</b>, javascript tidak aktif pada peramban yang Anda gunakan.</div>
    </noscript>

    <?= $this->renderSection('content') ?>

    <script src="https://simaster.ugm.ac.id/ugmfw-assets/js/bootstrap.min.js"></script>
    <script src="https://simaster.ugm.ac.id/ugmfw-assets/js/pixeladmin.min.js"></script>
    <script src="https://simaster.ugm.ac.id/ugmfw-assets/js/demo.js"></script>
    <script src="https://simaster.ugm.ac.id/ugmfw-assets/js/ajax.js"></script>
    <script>
        function menu_selected() {}
        pxDemo.initializeDemoSidebar();
        pxInit.push(function() {
            $(function() {
                $('#px-demo-sidebar').pxSidebar();
            });
        });
    </script>
    <script type="text/javascript">
        pxInit.unshift(function() {
            $(function() {
                pxDemo.initializeDemo();
            });
        });
        for (var i = 0, len = pxInit.length; i < len; i++) {
            pxInit[i].call(null);
        }
    </script>
</body>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-B3TESR985X"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-B3TESR985X');
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-165289732-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-165289732-1');
</script>
<script>
    $(document).ready(function() {
        $('body').css({
            'background': 'url("<?= base_url('mipa.jpg'); ?>") no-repeat',
            'background-size': '100% 100%',
            'backdrop-filter': 'brightness(60%) blur(5px)',
        });
    });
</script>
<?= $this->renderSection('js') ?>

</html>