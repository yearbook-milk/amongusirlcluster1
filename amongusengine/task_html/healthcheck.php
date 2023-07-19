<?php
error_reporting(E_ALL);
if (empty($_GET['playerID']) || file_exists("../$_GET[playerID].txt") == FALSE ) {
	$_GET['playerID'] = '';
  die("INVALID OR MISSING PLAYER ID FIELD (HTTP GET, 'playerID'). This page must be loaded in the context of a player.");
}


?>
<style>
body {
	background-color: #AAA;
	font-family: Times New Roman;
	color: black;
}

button {
	background-color: #0000;
}

</style>


<h1 style='text-align:center; color: #333'>MyMIRA Health Passport</h1>

<p style='color: #333;text-align: center;'>Please complete the following routine health check up. For legal reasons,
please note that this form does not 
actually save or record any data inputted. If you don't know or don't want to answer a question, it is acceptable to make something up.</p>

<fieldset>
  <legend>1. Vitals</legend>
  <p>Please take the following measurements or observations and report them accurately.</p><br><br>
  <table>
    <tr><td>Pulse (beats/min, and quality. Any abnormalities?)</td><td><input></td></tr>
    <tr><td>Respirations (number/min, and quality. Any abnormalities?)</td><td><input></td></tr>
    <tr><td>Blood Pressure (two numbers, i.e. 120/80)</td><td><input></td></tr>
    <tr><td>Temperature (in farenheit)</td><td><input></td></tr>
    <tr><td>Blood Oxygen (0-100%)</td><td><input></td></tr>
    <tr><td><br></td></tr>
    <tr><td>Pupils (any abnormalities?)</td><td><input></td></tr>
    <tr><td>Skin (any abnormalities?)</td><td><input></td></tr>
    <tr><td>Height (in)</td><td><input></td></tr>
    <tr><td>Weight (lb)</td><td><input></td></tr>
    <tr><td><br></td></tr>
    <tr><td>How do you feel right now, physically?</td><td><textarea></textarea></td></tr>
    <tr><td>How do you feel right now, mentally and emotionally?</td><td><textarea></textarea></td></tr>
    <tr><td>Have any incidents related to your physical, mental or emotional health <br>taken place since the last screening? Do you have any concerns?</td><td><textarea></textarea></td></tr>
    <tr><td><br></td></tr>
  </table>

</fieldset>


<fieldset>
  <legend>2. Questions</legend>
  <p>Please check if any of the following apply to you or have applied to you in the last 7 days:</p>

  <b>Physical Signs and Symptoms</b><hr><div style='white-space:pre;'>
  <input type='checkbox'>Malaise (general uncomfort)
  <input type='checkbox'>Fever
  <input type='checkbox'>Nausea
  <input type='checkbox'>Vomiting
  <input type='checkbox'>Diarrhea
  <input type='checkbox'>Pain in the abdomen
  <input type='checkbox'>Pain in the chest
  <input type='checkbox'>Pain in the head
  <input type='checkbox'>Pain in the extremities or limbs
  <input type='checkbox'>Discoloration of any part of the body
  <input type='checkbox'>Swelling of any part of the body
  <input type='checkbox'>Death
  <input type='checkbox'>Hot sensation anywhere in the body
  <input type='checkbox'>Burning sensation anywhere on the body
  <input type='checkbox'>Cold sensation anywhere in the body
  <input type='checkbox'>Numbness (physical)
  <input type='checkbox'>Lost voice / sore throat
  <input type='checkbox'>Bleeding from body orifices
  <input type='checkbox'>Symptoms mimicking those of exposure to asbestos (this question is now required following a serious incident in 2011)
  <input type='checkbox'>Nasal discharge
  <input type='checkbox'>Coughing 
  <input type='checkbox'>Sneezing
  <input type='checkbox'>Other abnormalities on the skin
  <input type='checkbox'>Watery eyes 

  <b>Mental or Behavioral Signs and Symptoms</b>
  <input type='checkbox'>Persistent sleeplessness
  <input type='checkbox'>Persistent sleepiness
  <input type='checkbox'>Inability to focus or remember
  <input type='checkbox'>Inability to unfocus or forget
  <input type='checkbox'>Inability to feel (mentally)
  <input type='checkbox'>Inability to not feel (mentally)
  <input type='checkbox'>The sense that you're going to die
  <input type='checkbox'>Paniccc (with three cs)
  <input type='checkbox'>Lack of panic in situations that would warrant panic
  <input type='checkbox'>Pain in the extremities or limbs
  <input type='checkbox'>A sense that you've let yourself go or are letting yourself go
  <input type='checkbox'>A sense that everyone around you is judging the actual fuck out of you
  <input type='checkbox'>A sense that everything has changed and things were better at some previous point in time, ESPECIALLY your childhood
  <input type='checkbox'>( we are no longer allowed to ask this question )
  <input type='checkbox'>A sense that this job isn't everything in life and that in working here you have neglected other parts of your life 
  <input type='checkbox'>A sense that everything went by too quickly, or that certain memories or events were not real
  <input type='checkbox'>Other "a sense that" (please elaborate)
  <input type='checkbox'>Any recreational drug or alcohol use since the last screening
  <input type='checkbox'>Any other drug or alcohol use since the last screening
  <input type='checkbox'>Sense of humor that feels like it shouldn't be possible AND/OR clearly shows to you that you have a problem
  <input type='checkbox'>Not hydrating or eating
  <input type='checkbox'>Hydrating or eating too much
  <input type='checkbox'>Detachment
  <input type='checkbox'>Excessive resentment or anger against an intangible, indescribable object
  <input type='checkbox'>Crying about what feels like nothing but is actually the culmination of 28 years worth of issues
  <input type='checkbox'>Wack ass dreams
  <input type='checkbox'>Fatigue
  <input type='checkbox'>Lack of interest
  <input type='checkbox'>Excessive exposure to asbestos 

  <b>Final Question</b>
  <input type='checkbox'>Did you die?
  <input type='checkbox'>Other, not listed
  <input type='checkbox'>Health insurance?
  <input type='checkbox'>Are you in support of Medicare For All?





  </div>
</fieldset>

<button id='ok'>Click here to submit your health screening to the crew's medical officer.</button>


<script>
  document.getElementById("ok").onclick = function() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      if (xhr.responseText.includes("OK")) {
        alert("OK! Task complete.")
      } else {
        alert("Error in sending complete task signal: "+xhr.responseText)
      }
    } else if (xhr.readyState == 4) {
      alert("HTTP error on sending complete task signal: "+xhr.responseText)
    }
  }
  xhr.open("GET", `/amongusengine/event.php?issuer=<?= $_GET['playerID']; ?>&eventdata=taskID=healthcheck&event=task`, true)
  xhr.send();
}
</script>
