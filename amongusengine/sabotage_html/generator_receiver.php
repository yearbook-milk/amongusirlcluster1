<script>
  function checkCode(code) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        if (xhr.responseText == code) {
          var xhr2 = new XMLHttpRequest();
          xhr2.onreadystatechange = function() {
            if (xhr2.readyState == 4) {
              alert("OK! Sabotage should be over: "+xhr2.responseText);
            }
          }
          xhr2.open("GET", "/amongusengine/event.php?issuer=__emergencybutton&event=sabotagestop&eventdata=");
          xhr2.send();
        } else {
          alert("Code incorrect!")
        }
      }
    }
    xhr.open("GET", "/amongusengine/sabotage_html/code", true)
    xhr.send()
  }
</script>



<h1>EMERGENCY GENERATOR SHUTDOWN</h1>

1. ESTABLISH RADIO CONTACT WITH THE PERSON AT THE OTHER “EMERGENCY SHUTDOWN” STATION<br>
2. INPUT THE CODE YOU ARE GIVEN: 

<input style='font-size: 37px;' id='codein'>
<button onclick='checkCode(document.getElementById(`codein`).value)'>INPUT</button>