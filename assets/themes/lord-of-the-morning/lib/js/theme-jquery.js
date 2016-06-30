jQuery(document).ready(function($) {	
    $('*:first-child').addClass('first-child');
    $('*:last-child').addClass('last-child');
    $('*:nth-child(even)').addClass('even');
    $('*:nth-child(odd)').addClass('odd');
	
	var numwidgets = $('#footer-widgets div.widget').length;
	$('#footer-widgets').addClass('cols-'+numwidgets);
	$.each(['show', 'hide'], function (i, ev) {
        var el = $.fn[ev];
        $.fn[ev] = function () {
          this.trigger(ev);
          return el.apply(this, arguments);
        };
      });

	$('.nav-footer ul.menu>li').after(function(){
		if(!$(this).hasClass('last-child') && $(this).hasClass('menu-item') && $(this).css('display')!='none'){
			return '<li class="separator">|</li>';
		}
	});
	
	$('.section.expandable .expand').click(function(){
	    var target = $(this).parents('.section-body').find('.content');
	    console.log(target);
	    if(target.hasClass('open')){
            target.removeClass('open');
            $(this).html('MORE <i class="fa fa-angle-down"></i>');
	    } else {
	        target.addClass('open');
	        $(this).html('LESS <i class="fa fa-angle-up"></i>');
	    }
	});
	$('.page.coffee .col-md-6 img,.page.coffee .col-md-4 img,.page.coffee .col-md-3 img').wrap('<div class="imgwrap"></div>');
});

var currentTallest = 0;
var currentRowStart = 0;
var rowDivs = new Array();

function setConformingHeight(el, newHeight) {
 // set the height to something new, but remember the original height in case things change
 el.data("originalHeight", (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight")));
 el.height(newHeight);
}

function getOriginalHeight(el) {
 // if the height has changed, send the originalHeight
 return (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight"));
}

function columnConform() {

 // find the tallest DIV in the row, and set the heights of all of the DIVs to match it.
 var theElements = '.page.coffee .col-md-6,.page.coffee .col-md-4,.page.coffee .col-md-3';
 jQuery(theElements).each(function(index) {

  if(currentRowStart != jQuery(this).position().top) {

   // we just came to a new row.  Set all the heights on the completed row
   for(currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) setConformingHeight(rowDivs[currentDiv], currentTallest);

   // set the variables for the new row
   rowDivs.length = 0; // empty the array
   currentRowStart = jQuery(this).position().top;
   currentTallest = getOriginalHeight(jQuery(this));
   rowDivs.push(jQuery(this));

  } else {

   // another div on the current row.  Add it to the list and check if it's taller
   rowDivs.push(jQuery(this));
   currentTallest = (currentTallest < getOriginalHeight(jQuery(this))) ? (getOriginalHeight(jQuery(this))) : (currentTallest);

  }
  // do the last row
  for(currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) setConformingHeight(rowDivs[currentDiv], currentTallest);

 });

}


jQuery(window).resize(function($) {
    jQuery('.entry-content').imagesLoaded( function() {
    if(window.innerWidth > 768){
        columnConform();
    }
    });
});

jQuery(document).ready(function($) {
    jQuery('.entry-content').imagesLoaded( function() {
    if(window.innerWidth > 768){
        columnConform();
    }
    });
});