document.addEventListener("DOMContentLoaded", function() {
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
      offsetX = getPageX(event) - marker.getBoundingClientRect().left;
      offsetY = getPageY(event) - marker.getBoundingClientRect().top;
    }
  

  
    function stopDragging() {
      isDragging = false;
    }
  
    function moveMarker(event) {
        if (!buttonClicked && isDragging) {
          event.preventDefault();
          var containerRect = element.getBoundingClientRect();
          var x = getPageX(event) - containerRect.left - offsetX + scrollX;
          var y = getPageY(event) - containerRect.top - offsetY + scrollY;
      
          marker.style.left = x + "px";
          marker.style.top = y + "px";
        }
      }
      
      function placeMarker(event) {
        if (!buttonClicked) {
          var containerRect = element.getBoundingClientRect();
          var x = getPageX(event) - containerRect.left + scrollX;
          var y = getPageY(event) + scrollY;
      
          marker.style.left = x + "px";
          marker.style.top = y + "px";
          marker.style.display = "block";
      
          console.log("Coordinates: " + x + ", " + y);
        }
    }
  
    function getPageX(event) {
        if (event.touches && event.touches.length) {
          return event.touches[0].pageX;
        } else {
          return event.pageX;
        }
      }
      
      function getPageY(event) {
        if (event.touches && event.touches.length) {
          return event.touches[0].pageY;
        } else {
          return event.pageY;
        }
      }
      
  
    element.addEventListener("scroll", function() {
      scrollX = element.scrollLeft;
      scrollY = element.scrollTop;
  
      // Get the marker's position relative to the container
      var markerRect = marker.getBoundingClientRect();
      var containerRect = element.getBoundingClientRect();
      var markerX = markerRect.left - containerRect.left;
      var markerY = markerRect.top - containerRect.top;
  
      // Update the marker's position after scrolling
      marker.style.left = markerX + scrollX + "px";
      marker.style.top = markerY + scrollY + "px";
    });
  });
  