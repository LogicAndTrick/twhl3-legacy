<!--<h1>Game Servers <a href="javascript:toggleLayer('server-div');"><img id="server-div-hide" src="images/arrow_up.gif" alt="show/hide" /></a></h1>
<?/*<div id="server-div">
    <iframe src="http://cache.www.gametracker.com/components/html0/?host=000.000.000.000:27015&bgColor=FBF6ED&fontColor=32361C&titleBgColor=FBE8C8&titleColor=1F2112&borderColor=FADFB0&linkColor=183396&borderLinkColor=183396&showMap=0&showCurrPlayers=0&showTopPlayers=0&showBlogs=0&width=202" frameborder="0" scrolling="no" width="202" height="164"></iframe>
</div>*/?>
<div id="server-div">
    <script type="text/javascript">
        //<![CDATA[
        function serverCallback(data) {
            var el = $('#server-div');
            var first = true;
            for (var key in data) {
                var s = data[key];
                var div = $('<div style="text-align:center;font-size:11px;">' +
                '<p style="background-color: #FBE8C8;border:1px solid #888;border-width:' + (first ? '0' : '1px') + ' 0 1px 0;">' + s.Game + '<\/p>' +
                '<p style="font-weight:bold;padding:0 10px">' + s.Name + '<\/p>' +
                '<p>Map: ' + s.Map + ' | Players: ' + s.NumPlayers + '/' + s.MaxPlayers + '<\/p>' +
                '<p style="margin: 5px;"><a href="' + s.Link + '" style="border:1px solid; padding:0 3px;">JOIN<\/a><\/p>' +
                '<\/div>');
                el.append(div);
                first = false;
            }
        }
        //]]>
    </script>
    <script type="text/javascript" src="http://servers.twhl.info/Home/Json/serverCallback"></script>
</div>-->