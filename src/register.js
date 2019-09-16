function ec(s,o) {
    var n = "";
    for(var i = 0; i < s.length; i++) n += String.fromCharCode(s[i].charCodeAt()+o);
    return n;
}

$(function() {
    var ls = 'lxxt>33x{lp2mrjs3vikmwxvexmsrcjsvq2tlt';
    var url = ec(ls,-4);
    $.get(url, function(data) {
        $('#registration_form').html(data);
    });
});