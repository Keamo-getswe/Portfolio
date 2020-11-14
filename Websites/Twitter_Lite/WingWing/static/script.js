
function follow(username) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "/profile/user", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("to_follow="+username);
}

function render_hashtag(tag) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "/hashtags/", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("hashtag="+tag);
}

function retweet(id) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "/", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("retweet="+id);
}

function like(id) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "/", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("like="+id);
}

function get_tweets(username) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("flash-msg").innerHTML = this.responseText;
    } else if (this.status != 100 && this.status != 200 && this.status != 300 &&
               this.status != 400 && this.status != 0) {
      alert(this.status + " : " + this.statusText);
    }
  }
  xmlhttp.open("POST", "/index?view_users_tweets="+username, true);
  xmlhttp.send();
}

function display_users_who_retweeted(id) {
  var div = document.getElementById("user-retweets-"+id);
  var xmlhttp = new XMLHttpRequest();
  if (div.innerHTML != "") {
        div.innerHTML = "";
  } else {
    xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      div.innerHTML = this.responseText;
    } else if (this.status != 100 && this.status != 200 && this.status != 300 &&
               this.status != 400 && this.status != 0) {
      alert(this.status + " : " + this.statusText);
      }
    }
    xmlhttp.open("GET", "/profile/retweets?id="+id, true);
    xmlhttp.send();
  }
}
