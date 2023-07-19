<script>
  code = Math.floor(Math.random() * 888888) + 100000;
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4) {
      document.getElementById("code").innerHTML = code;
    }
  }
  xhr.open("GET", "generator_set_code.php?code="+code, true);
  xhr.send();
</script>

<h1>EMERGENCY GENERATOR SHUTDOWN</h1>

1. ESTABLISH RADIO CONTACT WITH THE PERSON AT THE OTHER “EMERGENCY SHUTDOWN” STATION<br>
2. GIVE THEM THE FOLLOWING CODE: 
<h2 style='color:red; border: 4px dotted red; text-align: center; font-size: 37px;' id='code'>Waiting for XMLHttp to go through...</h2>