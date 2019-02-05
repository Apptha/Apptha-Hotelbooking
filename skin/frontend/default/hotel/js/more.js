      var jqs= jQuery.noConflict();
      jqs(document).ready(function (){
      var str="";
      var sum=0;
      var i=1;
       jqs(".nav-container ul:not('.level0') > li").each(function(){
           if(jqs(this).width())
           {
               sum +=jqs(this).width();
               if(sum > 500)
               {
                   str += "<li>"+ jqs(this).html()+ "<span></span></li>";

                   if(i==1){
                       jqs(this).wrap("<li><var id='MoreDiv' /></li>")
                   }
                   else
                   {
                       jqs(this).wrap("<var class='ClearDiv' />")
                   }
                   i++;
               }
           }
       });
       str ="<li  class='more_li'><a href='javascript:void(0);'>More </a><span></span><ul>"+str+"</ul></li>";
       jqs("#MoreDiv").html(str);
       jqs(".ClearDiv").html("");
       jqs(".ClearDiv").remove();


      var str1="";
      var sum1=0;
      var m=1;
       jqs(".huf-header-category ul:not('.children') > li").each(function(){
           if(jqs(this).width())
           {
               sum1 +=jqs(this).width();
               if(sum1 > 700)
               {
                   str1 += "<li>"+ jqs(this).html()+ "</li>";

                   if(m==1){
                       jqs(this).wrap("<var id='MoreDivsec' />")
                   }
                   else
                   {
                       jqs(this).wrap("<var class='ClearDivsec' />")
                   }
                   m++;
               }
           }
       });
       //baseURL=baseURL+'/wp-content/themes/hufington-post/images/more-arrow.png';
       //str1 ="<li><a href='javascript:void(0);'><img src="+baseURL+" alt='more'/></a><ul>"+str1+"</ul></li>";
       jqs("#MoreDivsec").html(str1);
       jqs(".ClearDivsec").html("");
       jqs(".ClearDivsec").remove();

});