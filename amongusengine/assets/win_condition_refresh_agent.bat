@echo OFF
:start
  curl https://amongusirlcluster1.charleshu.repl.co/amongusengine/conditions_refresh.php
  timeout -t 5
goto start