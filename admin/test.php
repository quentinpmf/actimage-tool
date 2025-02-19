<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Featherlight – The ultra slim jQuery lightbox.</title>
    <?php include('../includes/admin/top_page.php') ?>

</head>
<body class="fl-page">
<div class="container">
    <div class="jumbotron">
        <h1>Featherlight<i>.js</i><span> The ultra slim lightbox.</span></h1>
        <p class="lead">Featherlight is a very lightweight jQuery lightbox.</p>
        <div class="btn-group btn-download">
            <a class="btn btn-lg btn-info" href="https://github.com/noelboss/featherlight/">
                <i class="glyphicon glyphicon-eye-open"></i>
                github
            </a>
            <a class="btn btn-lg btn-success" href="https://github.com/noelboss/featherlight/archive/1.7.13.zip">
                <i class="glyphicon glyphicon-arrow-down"></i>
                Download <span>(1.7.13)</span>
            </a>
        </div>
        <div class="btn-group">
            <a class="btn btn-default" href="#" data-featherlight="#fl1">Default</a>
            <a class="btn btn-default" href="#" data-featherlight="#fl2" data-featherlight-variant="fixwidth">Custom Styles</a>
            <a class="btn btn-default" href="../assets/featherlight-1.7.13/assets/images/droplets.jpg" data-featherlight="image">Image</a>
            <a class="btn btn-default" href="https://player.vimeo.com/video/33110953" data-featherlight="iframe" data-featherlight-iframe-allowfullscreen="true" data-featherlight-iframe-width="500" data-featherlight-iframe-height="281">iFrame</a>

            <a class="btn btn-default" href="../assets/featherlight-1.7.13/index.html .ajaxcontent" data-featherlight="ajax">Ajax</a>
        </div>
    </div>
    <div class="row github text-center">
        <a href="https://twitter.com/share" class="twitter-share-button" data-counturl="noelboss.github.io/featherlight/" data-url="http://bit.ly/1hsLGpp" data-text="Featherlight – The ultra slim, responsive jQuery lightbox. It handles images, iFrames, inline- and ajax-content!
> " data-hashtags="#lightbox,#featherlight,#jQuery,#gallery,#responsive" data-dnt="true">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    </div>
    <div class="row marketing text-center">
        <div class="col-lg-4">
            <h4>Lightweight</h4>
            <p>
                Featherlight is very, very lightweight. 400 lines of JavaScript, 100 of CSS, less than 6kB combined.
            </p>
            <p>
                Don't be fooled by Featherlight's small footprint! It's smart, responsive, supports images, ajax and iframes out of the box and <a href="https://github.com/noelboss/featherlight/#configuration">you can adapt it to your needs</a>.
            </p>
            <p>
                To get started, simply add the <em>"data-featherlight"</em> with a selector, an image or an ajax-url. <a href="https://github.com/noelboss/featherlight/#usage">It's that simple.</a>
            </p>
        </div>

        <div class="col-lg-4">
            <h4>For the Pro</h4>
            <p>
                Many lightbox plugins try to handle everything for you. Even the ones called «simple» or «lightweight». Featherlight is different. It's for the pro who knows what he’s doing and just needs a barebones plugin.
            </p>
            <p>
                Thanks to very low specific css selectors and little code, it's easy to customize and to understand.
            </p>
            <p>
                It's meant to be extended, like our <a href="../assets/featherlight-1.7.13/gallery.html">gallery extension</a>.
            </p>
        </div>
        <div class="col-lg-4">
            <h4>Battle tested</h4>
            <p>
                Featherlight works on IE8+, all modern browsers and on mobile platforms.
            </p>
            <p>
                Featherlight has an extensive <a href="https://travis-ci.org/noelboss/featherlight">test suite</a>.
            </p>
            <p>
                Featherlight runs on hundreds of thousands of websites. That we know of.
            </p>
        </div>
    </div>

    <div class="row marketing text-center">
        <div class="col-lg-3">
        </div>
        <div class="col-lg-6">
            <a class="doc btn btn-lg btn-default" href="https://github.com/noelboss/featherlight/#installation">
                View Documentation
            </a>
        </div>
        <div class="col-lg-3">
        </div>
    </div>
    <div class="footer text-right">
        <p><a href="//noelboss.com">&copy; Noël Bossart.</a> Made in Switzerland.</p>
    </div>
</div>

<div class="lightbox" id="fl1">
    <h2>Featherlight Default</h2>
    <p>
        This is a default featherlight lightbox.<br>
        It's flexible in height and width.<br>
        Everything that is used to display and style the box can be found in the <a href="https://github.com/noelboss/featherlight/blob/master/src/featherlight.css">featherlight.css</a> file which is pretty simple.</p>
</div>

<div class="lightbox" id="fl2">
    <h2>Featherlight with custom styles</h2>
    <p>It's easy to override the styling of Featherlight. All you need to do is specify an additional class in the data-featherlight-variant of the triggering element. This class will be added and you can then override everything. You can also reset all CSS: <em>$('.special').featherlight({ resetCss: true });</em>
    </p>
</div>

<div class="ajaxcontent lightbox">
    <h2>This Ligthbox was loaded using ajax</h2>
    <p>With <a href="https://github.com/noelboss/featherlight/#installation">little code</a>, you can build lightboxes that use custom content loaded with ajax...</p>
</div>


<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//stats.g.doubleclick.net/dc.js','ga');

    ga('create', 'UA-5342062-6', 'noelboss.github.io');
    ga('send', 'pageview');
</script>
</body>
</html>