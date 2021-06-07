
<?php

require_once "../controllers/controller.php";

?>



<!DOCTYPE html>
<meta charset="utf-8">

<style>

body {
  font: 10px sans-serif;
}
.back {
  width: 17.5vw;
  height: 4vh;
  border-radius: 25px;
  border: 2px solid #f4a261;
  background-color: #e9c46a;
  transition-duration: 0.4s;
}
.back:hover {
  background-color: #e76f51;
  border: 2px solid #bc6c25;
  color: white;
}
.axis path,
.axis line {
  fill: none;
  stroke: #000;
  shape-rendering: crispEdges;
}

.bar {
  fill: orange;
}

.bar:hover {
  fill: orangered ;
}

.x.axis path {
  display: none;
}

.d3-tip {
  line-height: 1;
  font-weight: bold;
  padding: 12px;
  background: rgba(0, 0, 0, 0.8);
  color: #fff;
  border-radius: 2px;
}

/* Creates a small triangle extender for the tooltip */
.d3-tip:after {
  box-sizing: border-box;
  display: inline;
  font-size: 10px;
  width: 100%;
  line-height: 1;
  color: rgba(0, 0, 0, 0.8);
  content: "\25BC";
  position: absolute;
  text-align: center;
}

/* Style northward tooltips differently */
.d3-tip.n:after {
  margin: -1px 0 0 0;
  top: 100%;
  left: 0;
}
</style>

<body>
  <h1><?php echo $_SESSION['name'] ?></h1>
  <form  method="post">
    <button class="back"  name="back">BACK</button>
  </form>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>
<script>
var dateInp = "<?php echo $_SESSION['date'] ?>";
var nameInp= "<?php echo $_SESSION['name'] ?>";
console.log(dateInp);
console.log(nameInp);

var margin = {top: 40, right: 20, bottom: 30, left: 40},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var formatPercent = d3.format("d");

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .tickFormat(formatPercent);

var tip = d3.tip()
  .attr('class', 'd3-tip')
  .offset([-10, 0])
  .html(function(d) {
    return "<strong>"+nameInp+":</strong> <span style='color:red'>" + d[dateInp] + "</span>";
  })

var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

svg.call(tip);

d3.json("../dboperations/getSpecificTables.php",function(error,data){
  x.domain(data.map(function(d) { return d.CountryCode; }));
  y.domain([0, d3.max(data, function(d) { return d[dateInp]; })]);

  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text(nameInp);

  svg.selectAll(".bar")
      .data(data)
    .enter().append("rect")
      .attr("class", "bar")
      .attr("x", function(d) { return x(d.CountryCode); })
      .attr("width", x.rangeBand())
      .attr("y", function(d) { return y(d[dateInp]); })
      .attr("height", function(d) { return height - y(d[dateInp]); })
      .on('mouseover', tip.show)
      .on('mouseout', tip.hide)

});

function type(d) {
    d[dateInp] = +d[dateInp];
  return d;
}
var body = document.getElementsByTagName("BODY")[0];
if(screen.width<760){
  body.style.fontSize ="22px";
}

</script>