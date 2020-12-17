//pie
function updatePieChartValues(x,y) {
  var ctxP = document.getElementById("pieChart").getContext("2d");
  var myPieChart = new Chart(ctxP, {
    type: "pie",
    data: {
      labels: ["Solved", "Unsolved"],
      datasets: [
        {
          data: [x, y],
          backgroundColor: ["#F7464A", "#7CFC00"],
          hoverBackgroundColor: ["#FF5A5E", "#7CFC00"],
        },
      ],
    },
      options: {
        responsive: false,
      },
  });
}


function forumDropdown(node) {
  var x = node.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.nextElementSibling;//element because if not the next sibling is the enter/line change
  if (x.style.display === "none") {
    x.style.display = "flex";
  } else {
    x.style.display = "none";
  }
}

function displaySidebar() {
  var x = document.getElementById("sidebarID");
  if (x.style.zIndex === "0") {
    x.style.zIndex = "1";
  } else {
    x.style.zIndex = "0";
  }
}
