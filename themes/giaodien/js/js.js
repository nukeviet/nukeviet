// JavaScript Document

//function run1(event)
//{
//	 //alert(event.clientX + "px")
//	 var left=event.clientX + "px";
//	 //alert(this.);
//	// document.getElementById("run").style.transition="all 0.31s linear";
////	 document.getElementById("run").style.left=left;
//
//document.getElementById("run").style.transition="all 0.1s linear";
//	 document.getElementById("run").style.left=left;
//	 document.getElementById("run").style.opacity="0.3";
//}
//
//function run2()
//{
//	alert(this.name);	
//}

$(document).ready(function() {
	
 	$("nav li").hover(function() 
		{
			//alert($(this).text());
			$("#run").css({left:$(this).position().left+20,width:$(this).width()});
			$("#run1").css({left:$(this).position().left+20,width:$(this).width()});
			$("#run2").css({left:$(this).position().left+25});
			$("#run3").css({left:$(this).position().left+5+$(this).width()});
			$(this).css("animation", "mymove 0.2s infinite");
			//$(this).css("transform","rotate(720deg)");
			//alert($("#run").text());
    	}
	,function() 
		{
			//alert($(this).text());
			$("#run").css({left:0,width:$(this).width()});
			$("#run1").css({left:0,width:$(this).width()});
			$("#run2").css({left:0});
			$("#run3").css({left:0});
			$(this).css("animation", "none");
			
		}
	);
 
 
 
// function ball1(feild)
// {	
 //$("#k1")
 //alert(feild);
   var kt=Math.round(Math.random()*4),kt1=0,n=1;

	$("#k1").click(function(e) {
		//alert($("#k1").position().left);
		
        var ball1=setInterval(
			function()
			{ //alert(Math.random());
			
				if ($("#k2").position().top==$("#k1").position().top && $("#k2").position().left==$("#k1").position().left)
				{
					clearInterval(ball1)	
				}
				document.getElementById("sp1").innerHTML=$("#k1").position().top;
				
				
				if ( parseInt($("#k1").position().top)>=450) {kt=3;n=Math.round(Math.random());}
				if (parseInt($("#k1").position().left)<=0 ) {kt=4;n=Math.round(Math.random());}
				if (parseInt($("#k1").position().top)<=0) {kt=1;n=Math.round(Math.random());}
				if (parseInt($("#k1").position().left)>=650 ) {kt=2;n=Math.round(Math.random());}
				
				if (kt==1) 
					{
						$("#k1").css({left:$("#k1").position().left+1*n});
						$("#k1").css({top:$("#k1").position().top+2*n});
					}	
					
				if (kt==2) 
					{
						$("#k1").css({left:$("#k1").position().left-3*n});
						$("#k1").css({top:$("#k1").position().top+4*n});
					}
					
				if (kt==3) 
					{
						$("#k1").css({left:$("#k1").position().left-4*n});
						$("#k1").css({top:$("#k1").position().top-3*n});
					}
					
				if (kt==4) 
					{
						$("#k1").css({left:$("#k1").position().left+2*n});
						$("#k1").css({top:$("#k1").position().top-1*n});
					}
				
			},10
		);
		

		//setInterval(function(){if (parseInt($("#k1").position().left)==40) $("#k1").css({top:$("#k1").position().top+1})},10);
		
		
    });
	

var kt2=0,kt21=0;
	$("#k2").click(function(e) {
		//alert($("#k1").position().left);
		
		
		
		if ($("#k2").position().top!=$("#k1").position().top)
	{var ball2=setInterval(
			function()
			{ 	
				if ($("#k2").position().top==$("#k1").position().top && $("#k2").position().left==$("#k1").position().left)
				{
					clearInterval(ball2);
					$("#k2").css({left:$("#k2").position().left-55});	
				}
				document.getElementById("sp2").innerHTML=$("#k2").position().top;
				
				if (parseInt($("#k2").position().left)<=450 && kt2!=1) 
					{
						$("#k2").css({left:$("#k2").position().left+1})
					}
				else
					{
						if (parseInt($("#k2").position().top)<=450 && kt21!=1) 
						{
							$("#k2").css({top:$("#k2").position().top+1})
						}
						else
						{	kt2=1;
							
							if (parseInt($("#k2").position().left)>=0)	
							{
								$("#k2").css({left:$("#k2").position().left-1});	
							}
							else
							{
								kt21=1;
									
								if (parseInt($("#k2").position().top)==0 ) 
								{
									kt2=0;
									kt21=0;	
									//alert("dung");
								}
								else
								{
									$("#k2").css({top:$("#k2").position().top-1});	
								}
							}
						}
						
						
					}
			},10
		);}
		
		else{clearInterval(ball2)}
		//setInterval(function(){if (parseInt($("#k1").position().left)==40) $("#k1").css({top:$("#k1").position().top+1})},10);
		
		
    });
});


