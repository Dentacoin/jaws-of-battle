console.log("Don't touch the code. Or do ... ¯\\_(ツ)_/¯"),window.scrollTo(0,0),$(document).ready(function(){}),$(window).on("load",function(){}),$(window).on("load",function(){}),$(window).on("resize",function(){}),$(window).on("scroll",function(){});var mobile_os={Android:function(){return navigator.userAgent.match(/Android/i)},iOS:function(){return navigator.userAgent.match(/iPhone|iPad|iPod/i)}};mobile_os.iOS()?$(".android-btn").remove():mobile_os.Android()&&$(".ios-btn").remove();var pagesData={homepage:function(){function e(){$(".below-absolute-content").css({top:$(".moving-image").height()+"px"}).height($(".section-intro").height()-$(".moving-image").height()).fadeIn(),0==$(document).scrollTop()?($(".moving-image").removeClass("second-sprite third-sprite fourth-sprite not-fixed"),$(".frame-figure").removeClass("not-fixed"),$(".absolute-content").removeClass("not-fixed"),$(".moving-image").addClass("first-sprite"),$(".hidden-frame").show(),$(".below-absolute-content").css({top:$(".moving-image").height()+"px",position:"fixed"})):$(document).scrollTop()<=100?($(".moving-image").removeClass("first-sprite third-sprite fourth-sprite not-fixed"),$(".frame-figure").removeClass("not-fixed"),$(".absolute-content").removeClass("not-fixed"),$(".moving-image").addClass("second-sprite"),$(".hidden-frame").show(),$(".below-absolute-content").css({top:$(".moving-image").height()+"px",position:"fixed"})):$(document).scrollTop()<=200?($(".moving-image").removeClass("first-sprite second-sprite fourth-sprite not-fixed"),$(".frame-figure").removeClass("not-fixed"),$(".absolute-content").removeClass("not-fixed"),$(".section-intro").removeClass("no-overflow-hidden"),$(".moving-image").addClass("third-sprite"),$(".hidden-frame").show(),$(".below-absolute-content").css({top:$(".moving-image").height()+"px",position:"fixed"})):$(document).scrollTop()<=300?($(".moving-image").removeClass("first-sprite second-sprite third-sprite"),$(".frame-figure").removeClass("not-fixed"),$(".absolute-content").removeClass("not-fixed"),$(".moving-image").addClass("fourth-sprite"),$(".hidden-frame").show(),$(".below-absolute-content").css({top:$(".moving-image").height()+"px",position:"fixed"})):($(".moving-image").removeClass("first-sprite second-sprite third-sprite"),$(".absolute-content").addClass("not-fixed"),$(".moving-image").addClass("fourth-sprite"),$(".frame-figure").addClass("not-fixed"),$(".hidden-frame").hide(),$(".below-absolute-content").css({top:$(".moving-image").height()+300+"px",position:"absolute"}))}$(window).on("scroll",function(){e()}),e(),$(".card-types-slider").length>0&&$(".card-types-slider").slick({slidesToShow:3,slidesToScroll:1,autoplay:!0,autoplaySpeed:8e3,adaptiveHeight:!0,responsive:[{breakpoint:992,settings:{slidesToShow:2}},{breakpoint:600,settings:{slidesToShow:1}}]}),$(".featured-cards-slider").length>0&&$(".featured-cards-slider").slick({slidesToShow:1,slidesToScroll:1,autoplay:!0,autoplaySpeed:8e3,adaptiveHeight:!0}),$("body").addClass("over-hidden"),$(window).width()<1200&&$(".featured-cards-slider .single-slide").each(function(){$(this).css({"background-image":"url("+$(this).attr("data-mobile-background")+")"})}),$("body").removeClass("over-hidden")}};function router(){$(".homepage").length&&pagesData.homepage()}router();
