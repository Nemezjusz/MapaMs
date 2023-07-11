function copyToClipboard() {
    var copyText = document.getElementById("myLink");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */
  
    /* Copy the text inside the text field to the clipboard */
    document.execCommand("copy");
  
}