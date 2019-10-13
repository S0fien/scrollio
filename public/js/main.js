var wordmark = document.querySelector('.rainbow-wordmark');

var wordmarkImg = wordmark.querySelector('.rainbow-wordmark__image');
var canvas = wordmark.querySelector('.rainbow-wordmark__canvas');
var ctx = canvas.getContext('2d');
var trailCount = 100;
var canvasWidth = canvas.width;
var canvasHeight = canvas.height;

// ----- load image ----- //

var whiteImg = new Image();
var twigInfo = document.querySelector('#useless');
var imgPath = twigInfo.dataset.header;
whiteImg.onload = onWhiteImgLoad;
whiteImg.src = imgPath;

class colorManager {
    constructor() {
        this.self = this;
    }
    render() {
        ctx.clearRect( 0, 0, canvasWidth, canvasHeight );
        // iterate backwards through rainbow
        for ( var i = rainbow.length-1; i >= 0; i-- ) {
            var color = rainbow[i];
            if ( color ) {
                ctx.drawImage( colorCanvases[ color ], i+1, i+1 );
            }
        }
    }
    setColorCanvas( name, color ) {
        var colorCanvas = document.createElement('canvas');
        colorCanvas.width = whiteImg.width;
        colorCanvas.height = whiteImg.height;
        var colorCtx = colorCanvas.getContext('2d');
        colorCtx.drawImage( whiteImg, 0, 0 );
        colorCtx.globalCompositeOperation = 'source-in';
        colorCtx.fillStyle = color;
        colorCtx.fillRect( 0, 0, whiteImg.width, whiteImg.height );
        colorCanvases[ name ] = colorCanvas;
    }
}
var manager = new colorManager();

var colorCanvases = {};
// ----- animate rainbow ----- //

var isHovering = true;
var t = 0;
var rainbow = [];

(function() {
    for ( var i=0; i < trailCount; i++ ) {
        rainbow.push(null);
    }
})();

var colorCycle = [ 'blue', 'gold', 'orange', 'magenta' ];

function onWhiteImgLoad() {
    manager.setColorCanvas( 'blue', '#19F' );
    manager.setColorCanvas( 'gold', '#EA0' );
    manager.setColorCanvas( 'orange', '#E62' );
    manager.setColorCanvas( 'magenta', '#C25' );
    animate();
}
function animate() {
    update();
    manager.render();
    requestAnimationFrame( animate );
}

function update() {
    t++;

    var colorCycleIndex = Math.floor( t / 8 ) % 4;
    var nextColor = isHovering ? colorCycle[ colorCycleIndex ] : null;

    rainbow.pop();
    rainbow.pop();
    rainbow.pop();
    rainbow.unshift( nextColor );
    rainbow.unshift( nextColor );
    rainbow.unshift( nextColor );
}

//PAGINATION PART
var btnLoad = document.getElementById("loader");
if (btnLoad) btnLoad.onclick = function(){ showMore()};

var loader = document.getElementById("spinLoad");
var arrow = document.getElementById("arrowLoad");
if(loader) loader.style.visibility = "hidden";

function showMore() {

    let url = window.location.pathname;
    if(url.includes('c/')) {
        arrow.style.display = "none";
        loader.style.visibility = "visible";
        setTimeout(function(){
            loader.style.visibility = "hidden";
            arrow.style.display = "initial";
        }, 2000);
        btnLoad.innerHTML = 'No more posts.';
        return false;
    }

    arrow.style.display = "none";
    loader.style.visibility = "visible";
    var xhttp;

    var lastCol = document.getElementsByClassName("grid-item");
    datId = Number.MAX_SAFE_INTEGER;
    for (i = 0; i < lastCol.length; i++) {
        if(lastCol[i].id < datId) datId = lastCol[i].id;
    }

    var last = lastCol[lastCol.length-1];
    console.log(last.id);
    console.log(datId);
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var responsePlace = document.getElementById("response");
            responsePlace.innerHTML = this.responseText;
            var arr = responsePlace.getElementsByTagName('script');
            for (var n = 0; n < arr.length; n++)
                eval(arr[n].innerHTML);//run script inside div
            responsePlace.innerHTML = "";
            loader.style.visibility = "hidden";
            arrow.style.display = "initial";
        }
    };
    //xhttp.open("GET", "load/"+last.id, true);
    xhttp.open("GET", "load/"+datId, true);
    xhttp.send();
}

//category
var datPath = window.location.pathname.split("/").pop()
if (datPath == "Gaming") {
    document.getElementById("Gaming").className = "catBtn selected";
}
else if (datPath == "Animals") {
    document.getElementById("Animals").className = "catBtn selected";
}
else if (datPath == "Entertainment") {
    document.getElementById("Entertainment").className = "catBtn selected";
}
else if (datPath == "Meme") {
    document.getElementById("Meme").className = "catBtn selected";
}

var deleteBtn = document.querySelectorAll(".deleteBtn");
for (let i=0; i < deleteBtn.length; i++) {
    deleteBtn[i].onclick =  function(){
        var r = confirm("Are you sure to delete that?");
        if (!r == true) {
            return false
        }
    }
}
