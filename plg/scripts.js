$(function () {
  var windowOpen;
  $("a.tw").on("click", function () {
    if ("undefined" !== typeof windowOpen) {
      // If there's another sharing window open, close it.
      windowOpen.close();
    }
    windowOpen = window.open(
      "http://twitter.com/share?url=" +
        encodeURIComponent($(this).attr("href")),
      "wpcomtwitter",
      "menubar=1,resizable=1,width=600,height=350"
    );
    return false;
  });

  $("a.fb").on("click", function () {
    if ("undefined" !== typeof windowOpen) {
      // If there's another sharing window open, close it.
      windowOpen.close();
    }
    windowOpen = window.open(
      "http://www.facebook.com/sharer.php?u=" +
        encodeURIComponent($(this).attr("href")),
      "wpcomfacebook",
      "menubar=1,resizable=1,width=600,height=400"
    );
    return false;
  });
});
