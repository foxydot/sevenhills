jQuery(document).ready(function($) {
    var numwidgets = $('#homepage-widgets section.widget').length;
    $('#homepage-widgets').addClass('cols-'+numwidgets);
    var cols = 12/numwidgets;
    $('#homepage-widgets section.widget').addClass('col-sm-'+cols);
    $('#homepage-widgets section.widget').addClass('col-xs-12');
    
    $('.section-testimonials').prepend(
        '<div class="parallax level1"></div>' +
        '<div class="parallax level2"></div>' +
        '<div class="parallax level3"></div>' +
        '<div class="parallax level4"></div>'
    );
    
    //do some little stuff for parralaxing
    // init controller
    var vw;
    if($(window).width()<1500){
        vw = $(window).width()/100;
    } else {
        vw = 15;
    }
    console.log(vw);
    var testimonial_controller = new ScrollMagic({globalSceneOptions: {triggerHook: "onEnter",triggerElement:".section-testimonials",duration:$(window).height()*1.2}});
    var testimonial_tween = new TimelineMax()
        .add([
            TweenMax.fromTo("#testimonials .level1", 1, {css:{'background-position':"-2% 200%",'background-size':vw*0.7+'%'}, ease: Linear.easeNone}, {css:{'background-position':"-2% 10%"}, ease: Linear.easeNone}),
            TweenMax.fromTo("#testimonials .level2", 1, {css:{'background-position':"90% 70%",'background-size':vw*0.5+'%'}, ease: Linear.easeNone}, {css:{'background-position':"90% -10%"}, ease: Linear.easeNone}),
            TweenMax.fromTo("#testimonials .level3", 1, {css:{'background-position':"100% 0%",'background-size':vw*0.8+'%'}, ease: Linear.easeNone}, {css:{'background-position':"100% 60%"}, ease: Linear.easeNone}),
            TweenMax.fromTo("#testimonials .level4", 1, {css:{'background-position':"4% -150%",'background-size':vw+'%'}, ease: Linear.easeNone}, {css:{'background-position':"4% 100%"}, ease: Linear.easeNone})
        ]);
    // build scenes
    new ScrollScene()
    .setTween(testimonial_tween)
    .addTo(testimonial_controller);
});