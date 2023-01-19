var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active2");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}



var isRunning = false;

function addFavorite(event) {
  if (isRunning) {
    return;
  }
  isRunning = true;

    // Get the button element that was clicked
    var button = event.target;
  
    // Get the parent tr element of the button
    var tr = button.parentNode.parentNode;
  
    // Get the td elements in the tr element
    var tds = tr.getElementsByTagName('td');
 console.log(tds); 
    // Get the values of the td elements
    var id = tds[tds.length - 1].innerHTML;
  
    console.log(id);
    // Make an AJAX request to the PHP file
    $.ajax({
      url: 'SQLproject.php',
      type: 'POST',
      data: {id: id},
      success: function(result) {
        console.log(result);
      }
    });

  setTimeout(function() {
    isRunning = false;
    window.location.reload();
  }, 200);
}

function openOverlay() {
  document.getElementById("overlay").classList.add("show");
  console.log('hij opent');
}

function closeOverlay() {
  document.getElementById("overlay").classList.remove("show");
}

document.getElementById("overlay").addEventListener("click", function(event) {
  if (event.target === this) {
      closeOverlay();
  }
});

