String.prototype.ucfirst = function() {
    return this.charAt(0).toUpperCase() + this.substring(1).toLowerCase();
};

function roundNumber(num, dec) {
  var result = String(Math.round(num*Math.pow(10,dec))/Math.pow(10,dec));
  if(result.indexOf('.')<0) {result+= '.';}
  while(result.length- result.indexOf('.')<=dec) {result+= '0';}
  return result;
};