var img = document.getElementById('mapa');
document.getElementById("map-container").scrollLeft = 0.43*img.clientWidth;

var ratio = 1080 / (img.clientHeight);
var x = '{{row.loc_x}}';
var y = '{{row.loc_y}}';
var id = '{{row.id}}';
console.log("ratio: "+ ratio);
var marker = document.getElementById("marker"+String(id))
marker.style.left = (x/ratio) + "px";
marker.style.top = (y/ratio) + "px";


function showPopup(id) {
    var popup = document.getElementById("popup"+String(id));
    popup.style.display = "block";
}

function hidePopup(id) {
    var popup = document.getElementById("popup"+String(id));
    popup.style.display = "none";
}