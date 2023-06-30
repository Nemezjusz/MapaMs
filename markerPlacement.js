document.addEventListener("DOMContentLoaded", function() {
    var element = document.getElementById("mapa");
    var marker = document.getElementById("marker");
    var ratio = 1080 / (element.clientHeight);

    var isDragging = false;
    var offsetX = 0;
    var offsetY = 0;
    var scrollX = 0;
    var scrollY = 0;
  
    var buttonClicked = false;
  
    $(document).ready(function() {
      $('#button').click(function(event) {
        event.preventDefault();
        console.log("button clicked")
        if (buttonClicked = false){
          buttonClicked = true;
        } else {
          buttonClicked = false;
        }
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

    function setCookie(cname, cvalue, exdays) {
      const d = new Date();
      d.setTime(d.getTime() + (exdays*24*60*60*1000));
      let expires = "expires="+ d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function startDragging(event) {
      isDragging = true;
      offsetX = getPageX(event) - marker.getBoundingClientRect().left;
      offsetY = getPageY(event) - marker.getBoundingClientRect().top;
    }
  
    function stopDragging() {
      isDragging = false;
      console.log(window.screen.availHeight, window.screen.availWidth);

      console.log("Coordinates: " + x + ", " + y);
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
        //zeby oprazek pojawial sie w srodku klikniecia
        x -= 10;
        y -= 10;
        marker.style.left = x + "px";
        marker.style.top = y + "px";
        marker.style.display = "block";

        console.log(window.screen.availHeight, window.screen.availWidth);

        var x_alterd = x * ratio
        var y_alterd = y * ratio

        setCookie("loc_x", x_alterd, 1)
        setCookie("loc_y", y_alterd, 1)

        console.log("Coordinates: " + x + ", " + y);
        console.log("Uni Coordinates: " + x_alterd + ", " + y_alterd);
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
