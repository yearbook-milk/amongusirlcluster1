function read(input) {
  input = input.replace(/(\r\n|\n|\r)/gm, "");
  // thank you to https://stackoverflow.com/questions/10805125/how-to-remove-all-line-breaks-from-a-string for the regex
  lines = input.split(";");
  output = {}
  for (i = 0; i < lines.length; i++) {
    if ( lines[i].length == 0 || lines[i].split("=").length != 2 ) {
      // do nothing if the line is empty or malformed
    } else {
      if (lines[i].split("=")[0].slice(-2) == "[]") {
        line_divided = lines[i].split("=")[1].split(",")
        toAdd = []
        for (j = 0; j < line_divided.length; j++) {
          toAdd.push(line_divided[j]);
        }
        output[ lines[i].split("=")[0] ] = toAdd;
      } else {
        output[ lines[i].split("=")[0] ] = lines[i].split("=")[1];
      }
    }
  }
  return output
}