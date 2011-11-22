var m = [19, 20, 15, 19], // top right bottom left margin
	w = 960 - m[1] - m[3], // width
	h = 136 - m[0] - m[2], // height
	z = 12; // cell size

var day = d3.time.format("%w"),
	week = d3.time.format("%U"),
	mon = d3.time.format("%U"),
	percent = d3.format(".1%"),
	format = d3.time.format("%Y-%m-%d");

var svg = d3.select("div#chart")
   .append("svg:svg")
     .attr("width", w + m[1] + m[3])
     .attr("height", (h*(range_end-range_start)) + m[0] + m[2]);

var year = svg.selectAll("g.year")
    .data(d3.range(range_start, range_end))
   .enter().append("svg:g")
    .attr("class", function(d) { return "year y-"+d+" RdYlGn"; })
	.attr("transform", function(d) { return "translate(42," + ( 1 + (m[0] + (h - z * 7) / 2) * ( d - range_start ) * 3.33 ) + ")"; });

year.append("svg:text")
	.attr("transform", "translate(-6," + z * 3.5 + ")rotate(-90)")
	.attr("text-anchor", "middle")
	.text(String);

year.selectAll("rect.day")
	.data(function(d) { return d3.time.days(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
  .enter().append("svg:rect")
	.attr("class", "day")
	.attr("width", z)
	.attr("height", z)
	.attr("x", function(d) { return week(d) * z; })
	.attr("y", function(d) { return day(d) * z; });

var month = year.selectAll("path.month")
	.data(function(d) { return d3.time.months(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
  .enter().append("svg:path")
	.attr("class", "month")
	.attr("d", monthPath);

year.each( function(y) {
d3.json("index.php?option=com_acctexp&task=statrequest&type=sales&start="+format(new Date(y, 0, 1))+"&end="+format(new Date(y + 1, 0, 0)), function(json) {
var data = d3.nest()
	.key(function(d) { return d.date; })
	.rollup(function(v) { return d3.sum(v.map(function(d) { return d.amount; })); })
	.map(json.sales);

var color = d3.scale.quantize()
	.domain([0, d3.max(d3.values(data))*0.66])
	.range(d3.range(9));

d3.selectAll("g.y-"+y+" rect.day")
	  .attr("class", function(d) { return "day q" + color(data[format(d)]) + "-9"; })
	.append("svg:title")
	  .text(function(d) { return (d = format(d)) + (d in data ? ": " + amount_format(data[d]) + amount_currency : ""); });
})
});

function monthPath(t0) {
  var t1 = new Date(t0.getUTCFullYear(), t0.getUTCMonth() + 1, 0),
	  d0 = +day(t0), w0 = +week(t0),
	  d1 = +day(t1), w1 = +week(t1);
  return "M" + (w0 + 1) * z + "," + d0 * z
	  + "H" + w0 * z + "V" + 7 * z
	  + "H" + w1 * z + "V" + (d1 + 1) * z
	  + "H" + (w1 + 1) * z + "V" + 0
	  + "H" + (w0 + 1) * z + "Z";
}
