function popShoutbox() 
	{
		window.open("/shoutboxlive.php","shoutbox","width=300,height=400,resizable=yes,toolbar=no,status=yes,scrollbars=yes");
	}
function clearText(el)
	{
		if (el.defaultValue==el.value) el.value = ""
	}
function reloadshoutboxlive() 
	{
		window.location.reload(true);
	}
function bbcode(button)
	{
		var elem, text;
		elem = document.getElementById(button);
		text = document.getElementById('newposttext');
		text.value += '['+elem.value+']';
		if (elem.value.charAt(0) == '/')
		{
			elem.value = elem.value.substring(1);
			elem.style.backgroundColor = 'transparent';
		}
		else
		{
			elem.value = '/'+elem.value;
			elem.style.backgroundColor = '#DAA134';
		}
		text.focus();
	}
function youfail()
	{
		var text;
		text = document.getElementById('newposttext');
		text.value += '['+"fail"+']';
		text.focus();
	}
function over(button)
	{
		var elem;
		elem = document.getElementById(button);
		if (elem.value.charAt(0) == '/')
		{
			elem.style.backgroundColor = 'transparent';
		}
		else
		{
			elem.style.backgroundColor = '#DAA134';
		}
	}
function out(button)
	{
		var elem;
		elem = document.getElementById(button);
		if (elem.value.charAt(0) == '/')
		{
			elem.style.backgroundColor = '#DAA134';
		}
		else
		{
			elem.style.backgroundColor = 'transparent';
		}
	}
function showfull(image,w,h)
	{
		/*
		Borrowed and modified from Sliced Gaming - an Australian gaming site with accurate reviews and friendly forums.
		Visit them at www.slicedgaming.com
		Original function Copyright Gordon Craick 2002-2005
		*/
		image_window = window.open('about:blank','',"width=10,height=10,resizable=yes,toolbar=no,status=yes,scrollbars=no");
		with (image_window.document)
		{ 	
			writeln('<html><head><title>Loading Image...</title><style type="text/css">body{margin:0px;}</style>');
			writeln('</head><body bgcolor="000000" scroll="no" onLoad="reSizeToImage();self.focus()">');
			writeln('<sc'+'ript type="text/javascript">');
			writeln('function reSizeToImage(){');
			writeln('window.resizeTo(100,100);');
			writeln('width=document.image.width+40;');
			writeln('height=document.image.height+110;');
			writeln('window.resizeTo(width,height);');
			writeln('document.title="TWHL View Image";}');
			writeln('</sc'+'ript>');	
			writeln('<div style="text-align:center;"><img id="image" name="image" src="'+image+'" style="margin: 10px;"><br/>');	
			writeln('<a style="color: red;" href="javasc'+'ript:window.close();">[close]</a></div>');		
			writeln('</body></html>');
			close();
		}
	}	
function smilie(txt) 
	{
		var elem;
		if( document.getElementById ) // this is the way the standards work
			elem = document.getElementById( 'newposttext' );
		else if( document.all ) // this is the way old msie versions work
		  elem = document.all['newposttext'];
		else if( document.layers ) // this is the way nn4 works
			elem = document.layers['newposttext'];
			
		//IE support
		if (document.selection) {
			elem.focus();
			sel = document.selection.createRange();
			sel.text = ' '+txt+' ';
		}
		//MOZILLA/NETSCAPE support
		else if (elem.selectionStart || elem.selectionStart == '0') {
			var startPos = elem.selectionStart;
			var endPos = elem.selectionEnd;
			elem.value = elem.value.substring(0, startPos)
			+ ' '+txt+' '
			+ elem.value.substring(endPos, elem.value.length);
		} else {
			elem.value += ' '+txt+' ';
		}
			
		//elem.value += ((elem.value.charAt(elem.value.length-1) == ' ')?'':' ')+txt+' ';
		elem.focus();
	}
function showloginbox() 
	{
		var elem, vis;
		if( document.getElementById ) // this is the way the standards work
			elem = document.getElementById( 'header-login' );
		else if( document.all ) // this is the way old msie versions work
		  elem = document.all['header-login'];
		else if( document.layers ) // this is the way nn4 works
			elem = document.layers['header-login'];
		vis = elem.style;
		// if the style.display value is blank we try to figure it out here
		vis.display = 'none';
		
		if( document.getElementById ) // this is the way the standards work
			elem = document.getElementById( 'header-login-form' );
		else if( document.all ) // this is the way old msie versions work
		  elem = document.all['header-login-form'];
		else if( document.layers ) // this is the way nn4 works
			elem = document.layers['header-login-form'];
		vis = elem.style;
		// if the style.display value is blank we try to figure it out here
		vis.display = 'inline';
	}
function togglesmilies() 
	{
		var elem, vis;
		var hide;
		if( document.getElementById ) // this is the way the standards work
			elem = document.getElementById( 'smiley-content' );
		else if( document.all ) // this is the way old msie versions work
		  elem = document.all['smiley-content'];
		else if( document.layers ) // this is the way nn4 works
			elem = document.layers['smiley-content'];
		vis = elem.style;
		// if the style.display value is blank we try to figure it out here
		if ( vis.display == 'block' ) {
			vis.display = 'none';
			hide = 1;
		}
		else {
			vis.display = 'block';
			hide = 0;
		}
		
		if( document.getElementById ) // this is the way the standards work
			elem = document.getElementById( 'smiley-toggle' );
		else if( document.all ) // this is the way old msie versions work
		  elem = document.all['smiley-toggle'];
		else if( document.layers ) // this is the way nn4 works
			elem = document.layers['smiley-toggle'];
		// if the style.display value is blank we try to figure it out here
		if ( hide == 0 ) elem.innerHTML = '<a href="javascript:togglesmilies()">[Hide Smilies]</a>';
		else elem.innerHTML = '<a href="javascript:togglesmilies()">[Show Smilies]</a>';
	}
function toggle(tdiv) 
	{
		var elem, vis;
		var hide;
		elem = document.getElementById( tdiv+'-content' );
		vis = elem.style;
		if ( vis.display == 'block' ) {
			vis.display = 'none';
			hide = 1;
		}
		else {
			vis.display = 'block';
			hide = 0;
		}
		
		elem = document.getElementById( tdiv+'-toggle' );
		if (elem != null)
		{
			if ( hide == 0 ) elem.innerHTML = elem.innerHTML.replace(/Show/i, "Hide");
			else elem.innerHTML = elem.innerHTML.replace(/Hide/i, "Show");
		}
	}
function mapsubtoggle(onoff) 
	{
		var elem, vis;
		elem = document.getElementById( 'upload-div' );
		vis = elem.style;
		if ( onoff == 1 )
			vis.display = 'block';
		else
			vis.display = 'none';
			
		elem = document.getElementById( 'link-div' );
		vis = elem.style;
		if ( onoff == 0 )
			vis.display = 'block';
		else
			vis.display = 'none';
	}
function tabswitcher(entities)
	{
		var elem, vis;
		for (i = 0; i < entities.length; i++)
		{
			elem = document.getElementById( entities[i] );
			vis = elem.style;
			if (i == 0)
				vis.display = 'block';
			else
				vis.display = 'none';
		}
	}
function Right(str, n)
	{
	    if (n <= 0)
	       return "";
	    else if (n > String(str).length)
	       return str;
	    else {
	       var iLen = String(str).length;
	       return String(str).substring(iLen, iLen - n);
	    }
	}
function toggleLayer( whichLayer )
	{	
		/*if (Right(document[whichLayer+'-hide'].src,8)=='down.png')
		document[whichLayer+'-hide'].src='images/arrow_up.png';
		else
		document[whichLayer+'-hide'].src='images/arrow_down.png';*/
		
		if (Right(document.getElementById(whichLayer+'-hide').src,8)=='down.gif')
		document.getElementById(whichLayer+'-hide').src='images/arrow_up.gif';
		else
		document.getElementById(whichLayer+'-hide').src='images/arrow_down.gif';
		
		var elem, vis;
		if( document.getElementById ) // this is the way the standards work
			elem = document.getElementById( whichLayer );
		else if( document.all ) // this is the way old msie versions work
		  elem = document.all[whichLayer];
		else if( document.layers ) // this is the way nn4 works
			elem = document.layers[whichLayer];
			vis = elem.style;
		// if the style.display value is blank we try to figure it out here
		if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
			vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
			vis.display = (vis.display==''||vis.display=='block')?'none':'block';
	}
function newcaptcha()
	{
		var xmlHttp;
		try
			{
				// Firefox, Opera 8.0+, Safari
				xmlHttp=new XMLHttpRequest();
			}
			catch (e)
			{
			// Internet Explorer
			try
				{
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e)
				{
				try
					{
						xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (e)
						{
							alert("Your browser does not support AJAX!");
							return false;
						}
				}
			}
			xmlHttp.onreadystatechange=function()
			{
				if(xmlHttp.readyState==4)
				{
					//document.myForm.time.value=xmlHttp.responseText;
					document.getElementById('cap').innerHTML=xmlHttp.responseText;
				}
			}
		xmlHttp.open("GET","/getcaptcha.php",true);
		xmlHttp.send(null);
	}
//--------------------------------------------------------------------------------------------------------------------------------------//
//-------------------------------------------------FIX--------------------------------------------------------------------------------//
//--------------------------------------------------------ABOVE---------------------------------------------------------------------//
//------------------------------------------------------------------HERE-------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------------//
/* Insert into a textbox */
function insert_text(textbox,text,end)
{
	var elem;
	elem = document.getElementById(textbox);
	if (!elem) return;
	if (!end) end = false;
	if (end) elem.value += text;
	else
	{
		var pos = 0;
		//IE support
		if (document.selection)
		{
			elem.focus();
			sel = document.selection.createRange();
			sel.text = text;
		}
		//Everything else
		else if (elem.selectionStart || elem.selectionStart == '0')
		{
			pos = elem.selectionStart+text.length;
			elem.value = elem.value.substring(0,elem.selectionStart)+text+elem.value.substring(elem.selectionEnd,elem.value.length);
		}
		//Default if it doesn't work
		else
		{
			elem.value += text;
		}
	}
	elem.focus();
	if (pos > 0) elem.setSelectionRange(pos, pos); 
}
function bbcode_button(buttonid,textbox,notoggle)
{
	var elem;
	elem = document.getElementById(buttonid);
	if (!elem) return;
	if (!notoggle) notoggle = false;
	insert_text(textbox,'['+elem.value+']')
	if (!notoggle)
	{
		if (elem.value.charAt(0) == '/')
		{
			elem.value = elem.value.substring(1);
			elem.style.backgroundColor = 'transparent';
		}
		else
		{
			elem.value = '/'+elem.value;
			elem.style.backgroundColor = '#DAA134';
		}
	}
}
/* Open in new window links */
var DOMReadytouse = 0;
function externalLinks() {
 if (!document.getElementsByTagName) return;
 var anchors = document.getElementsByTagName("a");
 for (var i=0; i<anchors.length; i++) {
   var anchor = anchors[i];
   if (anchor.getAttribute("href") &&
       anchor.getAttribute("rel") == "external")
     anchor.target = "_blank";
 }
}
// Introduce our custom event onDOMReady 
window.onDOMReady = DOMReady 
function DOMReady(fn) 
{ 
    // According to standard implementation 
     if(document.addEventListener) 
        document.addEventListener("DOMContentLoaded", fn, false);  
    // IE 
    else 
        document.onreadystatechange = function() 
            { 
                checkReadyState(fn); 
            } 
}
function checkReadyState(fn) 
{ 
    if(document.readyState == "interactive") 
        fn(); 
}
window.onDOMReady( 
    function() 
    { 
        externalLinks();
		DOMReadytouse = 1;
    } 
);
/* Hiding and showing elements (simple) */
/* referenced from http://javascript-array.com */
var close_delay = 0;
function show_id(idnum)
{
	var elem, vis;
	elem = document.getElementById(idnum);
	vis = elem.style;
	vis.display = 'block';
}
function hide_id(idnum)
{
	var elem, vis;
	elem = document.getElementById(idnum);
	vis = elem.style;
	vis.display = 'none';
}
function hide_id_delay(idnum)
{
	close_delay = window.setTimeout("hide_id('"+idnum+"')", 300);
}
function stop_hide_delay()
{
	if(close_delay)
	{
		window.clearTimeout(close_delay);
		close_delay = null;
	}
}
/* Countdown to 0, time. */
function countdown(seed,element)
{
	var d,h,m,s,r,e,c;
	e=document.getElementById(element);
	if (!e) return;
	if (seed==0)
	{
		e.innerHTML="Time's up!";
		return;
	}
	c=":";
	r=seed;
	d=("0"+Math.floor(r/86400)).slice(-2);
	r=r-(d*86400);
	h=("0"+Math.floor(r/3600)).slice(-2);
	r=r-(h*3600);
	m=("0"+Math.floor(r/60)).slice(-2);
	r=r-(m*60);
	s=("0"+r).slice(-2);
	e.innerHTML=d+c+h+c+m+c+s;
	setTimeout("countdown("+(seed-1)+",'"+element+"')",1000);
}
/*
http://blog.paranoidferret.com/index.php/2007/12/20/javascript-tutorial-simple-fade-animation/
*/
/* fades an element which is initially hidden */
function fade(eid,fadetime)
{
	var element = document.getElementById(eid);
	if(element == null) return;
	
	if (element.style.visibility != 'visible')
	{
		element.style.opacity = '0';
		element.style.filter = 'alpha(opacity = 0)';
		element.style.visibility = 'visible';
	}

	if(element.FadeState == null)
	{
		if(element.style.opacity == null || element.style.opacity == '' || element.style.opacity == '1')
		{
			element.FadeState = 2;
		}
		else
		{
			element.FadeState = -2;
		}
	}

	if(element.FadeState == 1 || element.FadeState == -1)
	{
		element.FadeState = element.FadeState == 1 ? -1 : 1;
		element.FadeTimeLeft = fadetime - element.FadeTimeLeft;
	}
	else
	{
		element.FadeState = element.FadeState == 2 ? -1 : 1;
		element.FadeTimeLeft = fadetime;
		setTimeout("animateFade(" + new Date().getTime() + ",'" + eid + "',"+fadetime+")", 33);
	} 
}
function animateFade(lastTick, eid,fadetime)
{ 
	var curTick = new Date().getTime();
	var elapsedTicks = curTick - lastTick;

	var element = document.getElementById(eid);

	if(element.FadeTimeLeft <= elapsedTicks)
	{
		element.style.opacity = element.FadeState == 1 ? '1' : '0';
		element.style.filter = 'alpha(opacity = ' + (element.FadeState == 1 ? '100' : '0') + ')';
		element.FadeState = element.FadeState == 1 ? 2 : -2;
		return;
	}

	element.FadeTimeLeft -= elapsedTicks;
	var newOpVal = element.FadeTimeLeft/fadetime;
	if(element.FadeState == 1) newOpVal = 1 - newOpVal;

	element.style.opacity = newOpVal;
	element.style.filter = 'alpha(opacity = ' + (newOpVal*100) + ')';

	setTimeout("animateFade(" + curTick + ",'" + eid + "',"+fadetime+")", 33);
}
/* returns the value of a radio button group */
/* http://www.somacon.com/p143.php */
function get_radio_checked(radioObj) {
	if(!radioObj) return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}
/* Official TWHL3 Ajax functions */
/* Debugging */
var debug = false;
function set_debug()
{
	debug = true;
}
/* Random Loading Text */
var ajax_loading = new Array();
ajax_loading[0] = "Loading...";
ajax_loading[1] = "Rekerchiggering...";
ajax_loading[2] = "Haxing...";
ajax_loading[3] = "Countdown to doom...";
ajax_loading[4] = "Oh, the humanity...";
ajax_loading[5] = "Ajaxificating...";
ajax_loading[6] = "Ninja hax...";
ajax_loading[7] = "Processing...";
ajax_loading[8] = "Calibrating monkeys...";
ajax_loading[9] = "brb...";
ajax_loading[10] = "Informatification...";
ajax_loading[11] = "TWHLifying...";
/* borrowed from prototype.js */
function try_these()
{
	var returnValue;
	for (var i = 0, length = arguments.length; i < length; i++) {
		var lambda = arguments[i];
		try {
			returnValue = lambda();
			break;
		}
		catch (e) { }
	}
	return returnValue;
}
/* Returns an AJAX object */
function ajax_object()
{
	/* borrowed from prototype.js */
	return try_these(
      function() {return new XMLHttpRequest()},
      function() {return new ActiveXObject('Msxml2.XMLHTTP')},
      function() {return new ActiveXObject('Microsoft.XMLHTTP')}
    ) || false;
}
/* act = function to do when ready (takes the response text as an argument), url = request url */
function ajax_request_get(act,url)
{
	var ajaxobj = ajax_object();
	if (!ajaxobj) return;
	
	ajaxobj.onreadystatechange=function()
	{
		if(ajaxobj.readyState==4)
		{
			act(ajaxobj.responseText);
			delete ajaxobj;
		}
	}
	ajaxobj.open("GET",url,true);
	ajaxobj.send(null);
}
function ajax_request_post(act,url,param)
{
	var ajaxobj = ajax_object();
	if (!ajaxobj) return;
	
	ajaxobj.onreadystatechange=function()
	{
		if(ajaxobj.readyState==4)
		{
			act(ajaxobj.responseText);
			delete ajaxobj;
		}
	}
	ajaxobj.open('POST',url,true);
	ajaxobj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxobj.setRequestHeader("Content-length", param.length);
	ajaxobj.setRequestHeader("Connection", "close");
	ajaxobj.send(param);
	
}
/* End AJAX general functions */
/* Begin specific AJAX functions */
/* Shoutbox Functions */
function ajax_shoutbox_mod(editdel)
{
	var modsht=function(resp)
	{
		document.getElementById('shoutedit').style.display='block';
		document.getElementById('shoutedit').innerHTML=resp;
		delete modsht;
	}
	ajax_request_get(modsht,"/shoutmod.php?"+editdel)
}
function ajax_shoutbox_refresh()
{
	document.getElementById('sidebar_shoutbox').innerHTML=document.getElementById('sidebar_shoutbox').innerHTML+'<h2>'+ajax_loading[Math.floor(Math.random()*12)]+'</h2>';
	var refsht=function(resp)
	{
		document.getElementById('sidebar_shoutbox').innerHTML=resp;
		delete refsht;
	}
	ajax_request_get(refsht,"/shoutboxreload.php")
}
function ajax_shoutbox_refresh_live()
{
	ajax_shoutbox_refresh();
	livereload = window.setTimeout("ajax_shoutbox_refresh_live()", 10000);
}
function ajax_shout_postedit(shoutid)
{
	var parameters = "shout="+escape(encodeURIComponent(document.getElementById('edit_shout_box').value.replace(/\\/mg, "\\\\")));
	
	var edtsht=function(resp)
	{
		document.getElementById('shoutedit').style.display='none';
		document.getElementById('shoutedit').innerHTML='';
		ajax_shoutbox_refresh();
		delete edtsht;
	}
	ajax_request_post(edtsht,"/shoutedit.php?id="+shoutid,parameters)
}
function ajax_shout_postdelete(shoutid)
{
	var delsht=function(resp)
	{
		document.getElementById('shoutedit').style.display='none';
		document.getElementById('shoutedit').innerHTML='';
		ajax_shoutbox_refresh();
		delete delsht;
	}
	ajax_request_get(delsht,"/shoutdelete.php?id="+shoutid)
}
function ajax_shout_post()
{
	var parameters = "shout="+escape(encodeURIComponent(document.getElementById('shout_box').value.replace(/\\/mg, "\\\\")));
	document.getElementById('shout_box').value='Type here';
	document.getElementById('shout_box').disabled=true;
	
	var pstsht=function(resp)
	{
		ajax_shoutbox_refresh()
		document.getElementById('shout_box').disabled=false;
		delete pstsht;
	}
	
	ajax_request_post(pstsht,"/shoutpost.php",parameters)
}
/* Captcha refresh */
function ajax_reload_captcha()
{
	var refcap=function(resp)
	{
		document.getElementById('captcha').innerHTML=resp;
		delete refcap;
	}
	ajax_request_get(refcap,"/getcaptcha.php")
}
/* Wiki Functions */
function ajax_wiki_preview()
{
	var parameters = "cont="+escape(encodeURIComponent(document.getElementById('newposttext').value.replace(/\\/mg, "\\\\")));
	fade('wikiprevfade',300);
	setTimeout("fade('wikiprevfade',300)", 1300);
	document.getElementById('wikiprevdiv').style.display="block";
	document.getElementById('wikiprevcont').innerHTML="Loading...";
	
	var wikpre=function(resp)
	{
		document.getElementById('wikiprevcont').innerHTML=resp;
		delete wikpre;
	}
	
	ajax_request_post(wikpre,"/wikipreview.php",parameters)
}
function ajax_wiki_newpreview()
{
	document.getElementById('wikiprevname').innerHTML=document.getElementById('wikinewname').value;
	ajax_wiki_preview();
}
function ajax_wiki_compare()
{
	var oldid = get_radio_checked(document.forms['wikicompfrm'].elements['compold']);
	var newid = get_radio_checked(document.forms['wikicompfrm'].elements['compnew']);
	fade('wikicompfade',300);
	setTimeout("fade('wikicompfade',300)", 1300);
	document.getElementById('wikicompdiv').style.display="block";
	document.getElementById('wikicompcont').innerHTML='<p class="single-center-content">Loading...</p>';
	
	var wikcom=function(resp)
	{
		document.getElementById('wikicompcont').innerHTML=resp;
		delete wikcom;
	}
	
	ajax_request_get(wikcom,"/wikicompare.php?old="+oldid+"&new="+newid)
}
function ajax_name_table(in_id,out_id)
{
	var uidtext = document.getElementById(in_id).value
	var tblref=function(resp)
	{
		document.getElementById(out_id).innerHTML=x=resp;
		delete tblref;
	}
	
	ajax_request_get(tblref,"/userlivelist.php?part="+uidtext)
}
var nametext = '';
function nametable(input,output)
{
	if ( DOMReadytouse == 0 ) return;
	if ( document.getElementById(input).value != nametext ) 
	{
		nametext = document.getElementById(input).value
		document.getElementById(output).innerHTML='<p class="single-center-content-center">Reloading...</p>'
		ajax_name_table(input,output)
	}
	t=setTimeout("nametable('"+input+"','"+output+"')",100)
}
function ajax_motm_votes(year,month)
{
	document.getElementById('votecont').innerHTML='<p class="single-center-content">Loading...</p>';
	
	var votget=function(resp)
	{
		document.getElementById('votecont').innerHTML=resp;
		delete votget;
	}
	
	ajax_request_get(votget,"/motmadminvotes.php?m="+month+"&y="+year)
}