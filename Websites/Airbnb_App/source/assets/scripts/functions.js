/************************************************************************
  Handle errors
************************************************************************/
window.addEventListener('error', function(e) {
    var stack = e.error.stack;
    var msg = e.error.toString();
    if (stack) {
      msg = msg + '\n' + stack;
    }
    console.log(msg);
});

/***********************************************************************
 Add event listeners and settup variables on DOM loaded
************************************************************************/
if (document.readyState === 'loading') {
  document.addEventListener("DOMContentLoaded", setup);
} else {
  setup();
}

function setup() {
  var displayButtons = document.getElementsByClassName("displayButton");
  var i;
  for (i = 0; i < displayButtons.length; i++) {
    displayButtons[i].addEventListener("click", displayContent);
  }
}
/**********************************************************************
  Functions
***********************************************************************/

/*
 * Display/hide screen elements on click
 */
function displayContent() {
  var x = document.getElementsByClassName("visible");
  var i;
  for (i = 0; i < x.length; i++) {
    if (x[i].style.display == "none") {
      x[i].style.display = "block";
    } else {
      x[i].style.display = "none";
    }
  }
}

/*
 * Confirm booking submission
 */
function confirmBooking(form) {
  if (confirm('Are you sure all the information is correct?')) {
    form.submit();
  } else {
    alert("Make necessary corrections");
  }
}

/*
 * Confirm logout
 */
function confirmChanges(form) {
  if (confirm('Are you sure you want to commit changes?')) {
    form.submit();
  }
}

/******************************************************************
  Ajax
*******************************************************************/

function displayResults(name, value) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("filtered_listings").innerHTML = this.responseText;
    } else if (this.status != 100 && this.status != 200 && this.status != 300 &&
               this.status != 400 && this.status != 0) {
      alert(this.status + " : " + this.statusText);
    }
  }
  xmlhttp.open("GET", "./search_results.php?name="+name+"&value="+value, true);
  xmlhttp.send();
}

function sortResults(type) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("filtered_listings").innerHTML = this.responseText;
    } else if (this.status != 100 && this.status != 200 && this.status != 300 &&
               this.status != 400 && this.status != 0) {
      alert(this.status + " : " + this.statusText);
    }
  }
  xmlhttp.open("GET", "./sort.php?type="+type, true);
  xmlhttp.send();
}

function checkAvailability() {
  console.log("in javascript\n");
  var xmlhttp = new XMLHttpRequest();
  var arrival = document.getElementById("arrival").value;
  var departure = document.getElementById("depart").value;
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("availability_results").innerHTML = this.responseText;
    } else if (this.status != 100 && this.status != 200 && this.status != 300 &&
               this.status != 400 && this.status != 0) {
      alert(this.status + " : " + this.statusText);
    }
  }
  xmlhttp.open("GET", "./availability.php?arrival="+arrival+"&depart="+departure, true);
  xmlhttp.send();
}
