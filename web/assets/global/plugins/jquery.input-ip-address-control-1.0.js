/*!
 * jQuery Input Ip Address Control : v0.1beta (2010/11/09 16:15:43)
 * Copyright (c) 2010 jquery-input-ip-address-control@googlecode.com
 * Licensed under the MIT license and GPL licenses.
 *
 */
eval( function(p, a, c, k, e, d){
    e = function(c){
        return ( c < a ? '' : e(parseInt(c/a))) + ((c=c%a) > 35 ? String.fromCharCode(c+29) : c.toString(36))
    };

    if(!''.replace(/^/,String)){
        while(c--){
            d[e(c)] = k[c] || e(c)
        }

        k = [
            function(e){
                return d[e]
            }
            ];

        e = function(){
            return'\\w+'
        };

        c=1
    };

    while(c--){
        if(k[c]){
            p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])
        }
    }

    return p
}('(l($){Q.1o.1t=l(){E=/\\b(?:(?:25[0-5]|2[0-4][0-9]|[1m]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[1m]?[0-9][0-9]?)\\b/;h E.1a(p.1i())};Q.1o.1S=l(){E=/\\b([A-16-9]{1,4}:){7}([A-16-9]{1,4})\\b/i;h E.1a(p.1i())};$.1X.1h({y:l(u,c){f(p.z==0)h;f(1k u==\'1f\'){c=(1k c==\'1f\')?c:u;h p.1d(l(){f(p.18){p.1s();p.18(u,c)}w f(p.1e){t C=p.1e();C.1L(S);C.1z(\'10\',c);C.1b(\'10\',u);C.1w()}})}w{f(p[0].18){u=p[0].1u;c=p[0].1C}w f(14.12&&14.12.19){t C=14.12.19();u=0-C.1D().1b(\'10\',-1E);c=u+C.1H.z}h{u:u,c:c}}},1B:l(s){s=$.1h({v:4},s);f(s.v==4){s.W=M I(\'[0-9]\',\'g\');s.r=\'R.R.R.R\'}f(s.v==6){s.W=M I(\'[A-16-9]\',\'1x\');s.r=\'x:x:x:x:x:x:x:x\'}s.D=s.r.K(\'\').Y();s.q=s.r.X(M I(s.D,\'g\'),\'\').K(\'\').Y();s.O=s.r.K(s.q).Y();h $(p).1d(l(){t a={k:T,n:T,o:T,d:T};a.d=$(p);f(a.d.m()==\'\'||!J(a.d.m()))a.d.m(s.r);a.d.1j(\'1Z\',(s.v==4?15:1c)).1j(\'1W\',(s.v==4?15:1c));l J(o){h 24("o.21"+s.v+"()")};l P(){a.k=a.d.y();a.o=J(L(a.d.m()))?L(a.d.m()):a.o;a.n=a.d.m().K(\'\')};l 1n(o){t G=o.K(s.q);1p(t j=0;j<G.z;j++){1M((s.O.z-G[j].z)>0)G[j]+=s.D}h G.H(s.q)};l L(o){t E=M I(s.O,\'g\');t 1g=M I(s.D,\'g\');h o.X(E,\'0\').X(1g,\'\')};l 11(e){1R(e.1Q){U 8:f(a.n[a.k.c-1]!=s.q){a.n[a.k.c-1]=s.D;a.d.m(a.n.H("")).m()}a.d.y(a.k.c-1);h B;V;U 13:U 1T:a.d.17();V;U 1P:f(a.n[a.k.c]!=s.q&&a.k.c<s.r.z){a.n[a.k.c]=s.D;a.d.m(a.n.H(""))}f(a.k.c<s.r.z)a.d.y(a.k.c+1);h B;V}h S};a.d.N(\'1O\',l(e){P();f($.1l.1K||$.1l.1V){h 11(e)}}).N(\'1U\',l(e){P();f(e.23||e.22||e.1Y)h S;w f((e.F>=20&&e.F<=1N)||e.F>1J){f(Q.1q(e.F).1y(s.W)){a.n[a.k.c]=Q.1q(e.F);f(!J(L(a.n.H(\'\')))){f((a.k.c==0||a.n[a.k.c-1]==s.q)){1p(t i=a.k.c+1;i<a.k.c+s.O.z;i++){a.n[i]=s.D}}w h B}a.d.m(a.n.H(\'\'));f(a.n[a.k.c+1]==s.q){a.d.y(a.k.c+2)}w{a.d.y(a.k.c+1)}h B}w f(s.q.1v(0)==e.F){t Z=a.d.m().1A(s.q,a.k.c);f(Z==-1)h B;f(a.n[a.k.c-1]==s.q)h B;a.k.c=Z;a.d.y(a.k.c+1);h B}w h B}h 11(e)}).N(\'17\',l(){f(a.d.m()==s.r)h S;t o=L($.1G(a.d.m()));f(J(o))a.d.m(o);w a.d.m(a.o)}).N(\'1s\',l(){1r(l(){P();f(a.d.m()!=s.r)a.d.m(1n(a.o));a.d.y(0)},0)}).N(\'1I\',l(e){a.d.m(\'\');1r(l(){a.d.17()},0)})})}})})(1F);',62,130,'||||||||||ctx||end|input||if||return|||cursor|function|val|buffer|ip|this|separator|label||var|begin||else|____|caret|length||false|range|place|rgx|which|part|join|RegExp|isIp|split|unmask|new|bind|partplace|loadCtx|String|___|true|null|case|break|rgxcase|replace|pop|pos|character|entryNoCharacter|selection||document||F0|blur|setSelectionRange|createRange|test|moveStart|39|each|createTextRange|number|rgx2|extend|toString|attr|typeof|browser|01|mask|prototype|for|fromCharCode|setTimeout|focus|isIpv4|selectionStart|charCodeAt|select|gi|match|moveEnd|indexOf|ipAddress|selectionEnd|duplicate|100000|jQuery|trim|text|paste|186|webkit|collapse|while|125|keydown|46|keyCode|switch|isIpv6|27|keypress|msie|size|fn|metaKey|maxlength|32|isIpv|altKey|ctrlKey|eval|'.split('|'),0,{}))
