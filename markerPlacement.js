document.addEventListener("DOMContentLoaded", function() {
  
            //jak sie srolluje( w wersji na kompa to sie cos psuje)
            //w wersji na tel tw sumie tak samo ale tam jest jeszcze gorzej tak naprawde(bo jest wiecej scrollowania xd)

            var element = document.getElementById("mapa");
            var marker = document.getElementById("marker");

            var isDragging = false;
            var offsetX = 0;
            var offsetY = 0;
            var scrollX = 0;
            var scrollY = 0;


            var buttonClicked = false;
    
            $(document).ready(function() {
             
              $('#button').click(function(event) {
                event.preventDefault();
                buttonClicked = true;
                
              });
          
            });
            //powinno dzialac jak przesuwasz palcem po telefonie

            marker.addEventListener("mousedown", startDragging);
            marker.addEventListener("touchstart", startDragging);
            


            document.addEventListener("mousemove", moveMarker);
            document.addEventListener("touchmove", moveMarker, { passive: false });





            document.addEventListener("mouseup", stopDragging);
            document.addEventListener("touchend", stopDragging);

            element.addEventListener("click", placeMarker);
            element.addEventListener("touchstart", placeMarker);


            function startDragging(event) {
                isDragging = true;
                offsetX = getClientX(event) - marker.getBoundingClientRect().left;
                offsetY = getClientY(event) - marker.getBoundingClientRect().top;
            }

            
            function moveMarker(event) {
              if (!buttonClicked && isDragging) {
                console.log(buttonClicked);
                event.preventDefault();
                var x = getClientX(event) - offsetX;
                var y = getClientY(event) - offsetY;
            
                marker.style.left = x + "px";
                marker.style.top = y + "px";
              }
            }
           

            function stopDragging() {
                isDragging = false;
            }

            function placeMarker(event) {
                if(!buttonClicked){
                    var x = getClientX(event);
                    var y = getClientY(event);

                    marker.style.left = x + "px";
                    marker.style.top = y + "px";
                    marker.style.display = "block";

                    console.log("Coordinates: " + x + ", " + y);
            }
            }

            function getClientX(event) {
                if (event.touches && event.touches.length) {
                    return event.touches[0].clientX;
                } else {
                    return event.clientX;
                }
            }

            function getClientY(event) {
                if (event.touches && event.touches.length) {
                    return event.touches[0].clientY;
                } else {
                    return event.clientY;
                }
            }

            element.addEventListener("scroll", function() {
                scrollX = element.scrollLeft;
                scrollY = element.scrollTop;
            });
        
        
        });