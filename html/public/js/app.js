AOS.init();
let navBar = document.querySelector("header");
let h1 = document.querySelector("header .container h1");
let listItems = document.querySelectorAll("header .container nav ul li a");
let bars = document.querySelector("header .container nav a i");
$("#menu-btn").on("click", function() {
  if (document.querySelector(".mobile-nav").style.display == "none") {
    document.querySelector(".mobile-nav").style.display = "block";
  } else {
    document.querySelector(".mobile-nav").style.display = "none";
  }
});
$(".mobile-nav ul li a").on("click", function() {
  document.querySelector(".mobile-nav").style.display = "none";
});
let thisWindow = $(window);
let windowHeight = thisWindow.height();

$(".loadmore").on("click", function() {
  $(this).remove();
  $("table").append(fadeInItems);
  $(fadeInItems).show("slow");
});
$(".closeMe").on("click", function() {
  $(".flagSign").fadeOut();
});
var nevershowAgain = false;

$(".closeMe-buynow").on("click", function() {
  $(".buy-now").fadeOut();
  nevershowAgain = true;
});
$("table").on("click", ".clickItem", function() {
  if (
    $(this)
      .find(".more-info")
      .css("display") == "none"
  ) {
    $(this)
      .find(".more-info")
      .slideToggle("slow");
    $(this)
      .find("i")
      .css({ transform: "rotate(180deg)" });
  } else {
    $(this)
      .find(".more-info")
      .slideToggle("slow");
    $(this)
      .find("i")
      .css({ transform: "rotate(360deg)" });
    // if($(this).find('i').hasClass('ianimf')){
    //     $(this).find('i').removeClass('ianimf');
    // }
    // $(this).find('i').addClass('ianimb');
  }
});
$(".pricelist").on("inview", function(event, isInView) {
  if (isInView) {
    if (nevershowAgain == false) {
      $(".buy-now").fadeIn();
    }
  } else {
    $(".buy-now").fadeOut();
  }
});
thisWindow
  .on("scroll", () => {
    if ($(this).scrollTop() > windowHeight - 700) {
    } else {
    }
    if ($(this).scrollTop() > windowHeight - 100) {
      navBar.style.backgroundColor = "white";
      navBar.style.boxShadow = "0px 0px 2px rgba(0, 0, 0, .1)";
      navBar.style.height = "75px";
      h1.style.color = "orange";
      bars.style.color = "black";
      for (var i = 0; i < listItems.length; i++) {
        listItems[i].style.color = "black";
      }
      navBar.classList.add("test");
    } else {
      h1.style.color = "white";
      navBar.style.backgroundColor = "transparent";
      navBar.style.boxShadow = "0px 0px 0px rgba(0, 0, 0, 0)";
      navBar.style.height = "100px";
      bars.style.color = "white";
      for (var i = 0; i < listItems.length; i++) {
        listItems[i].style.color = "white";
      }
      navBar.classList.remove("test");
    }
  })
  .on("resize", () => {
    windowHeight = $(this).height();
  });
$(".scrollCta").click(function() {
  let scrollTo = $($(this).attr("href"));
  if ($(this).attr("href") == "#services") {
    $("html, body").animate(
      {
        scrollTop: scrollTo.offset().top
      },
      1500
    );
  } else {
    $("html, body").animate(
      {
        scrollTop: scrollTo.offset().top
      },
      1500
    );
  }
});
function filterTable() {
  var input = document.getElementById("service-search");
  var filter = input.value.toUpperCase();
  var table = document.getElementById("pricelist-table");
  var tr = table.getElementsByTagName("tr");
  if (filter == "") {
    $(".heading").fadeIn();
  } else {
    $(".heading").fadeOut();
  }
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      var txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        $(tr[i]).fadeIn(250);
      } else {
        $(tr[i]).fadeOut(250);
      }
    }
  }
}

new Glider(document.querySelector(".testimonials"), {
  slidesToShow: 1,
  dots: "#dots",
  rewind: true,
  draggable: true,
  arrows: {
    prev: ".glider-prev",
    next: ".glider-next"
  },
  responsive: [
    {
      breakpoint: 800,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    }
  ]
});
