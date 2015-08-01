 jQuery(document).ready(function(){
            setContainerWidth();
             jQuery('.tbgrd_autocenter > .griditemleft').css('visibility', 'visible');
 });

jQuery(window).resize(function(){
    setContainerWidth();
          
});

function setContainerWidth()
{
    var container = jQuery('.tbgrd_autocenter')
    var windowWidth = container.parent().width();
    var blockWidth = jQuery('.tbgrd_autocenter > .griditemleft').outerWidth(true);
    var maxBoxPerRow = Math.floor(windowWidth / blockWidth);
    var children = container.children().length;
    if (maxBoxPerRow <= 0)
        maxBoxPerRow = 1;
    if (maxBoxPerRow > children)
        maxBoxPerRow = children;
  //  jQuery('.tbgrd_autocenter').effect( "size", {    to: { 'width': maxBoxPerRow * blockWidth }  }, 1000 );;
    container.width(maxBoxPerRow * blockWidth);
    //*This can happen when the window goes below 380 on crappy devices*/
    if (container.width() > container.parent().width()) { 
   
        container.parent().width(container.width);
    }
}