function cellular_years(selector, r_start, r_end) {
var m = [19, 20, 15, 19], // top right bottom left margin
	z = 14, // cell size
	w = z*60 - m[1] - m[3], // width
	h = z*6 - m[0] - m[2] // height
	day = d3.time.format("%w"),
	week = d3.time.format("%U"),
	mon = d3.time.format("%U"),
	percent = d3.format(".1%"),
	format = d3.time.format("%Y-%m-%d");

var svg = d3.select(selector)
	.style("width", w + m[1] + m[3])
	.style("height", h*(r_end-r_start)*3 + m[0] + m[2])
	.append("svg:svg")
		.attr("width", w + m[1] + m[3])
		.attr("height", h*(r_end-r_start)*3 + m[0] + m[2]);

var year = svg.selectAll("g.year")
	.data(d3.range(r_start, r_end))
	.enter().append("svg:g")
		.attr("class", function(d) { return "year y-"+d+" RdYlGn"; })
		.attr("transform", function(d) { return "translate(42," + ( 1 + ( d - r_start ) * h*2.2 ) + ")"; })
		.style("opacity", "0.1");

year.append("svg:text")
	.attr("transform", "translate(-6," + z * 3.5 + ")rotate(-90)")
	.attr("text-anchor", "middle")
	.text(String);

year.selectAll("rect.day")
	.data(function(d) { return d3.time.days(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
	.enter()
	.append("svg:rect")
	.attr("class", "day")
	.attr("width", z)
	.attr("height", z)
	.attr("x", function(d) { return week(d) * z; })
	.attr("y", function(d) { return day(d) * z; })
	.on("mouseover", function(){
		d3.select(this)
		.transition().ease("elastic").duration(500)
			.attr("ry", z/3.33).attr("rx", z/3.33).attr("width", z-2).attr("height", z-2)
			.attr("x", function(d) { return (week(d) * z)+1; })
			.attr("y", function(d) { return (day(d) * z)+1; });
	})
	.on("mouseout", function(){
		d3.select(this)
		.transition()
		.ease("bounce")
		.delay(100)
		.duration(500)
			.attr("ry", 0).attr("rx", 0).attr("width", z).attr("height", z)
			.attr("x", function(d) { return week(d) * z; })
			.attr("y", function(d) { return day(d) * z; });
	})
;

var month = year.selectAll("path.month")
	.data(function(d) { return d3.time.months(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
.enter().append("svg:path")
	.attr("class", "month")
	.attr("d", monthPath);

var maxsale = 0;

d3.json("index.php?option=com_acctexp&task=statrequest&type=max_sale", function(json) {
maxsale = json.amount;
});

year.each( function(y) {
d3.json("index.php?option=com_acctexp&task=statrequest&type=sales&start="+format(new Date(y, 0, 1))+"&end="+format(new Date(y + 1, 0, 0)), function(json) {
var data = d3.nest()
	.key(function(d) { return d.date; })
	.rollup(function(v) { return d3.sum(v.map(function(d) { return d.amount; })); })
	.map(json.sales);

d3.selectAll("g.y-"+y+" rect.day")
	.append("svg:title")
	.text(function(d) { return (d = format(d)) + (d in data ? ": " + amount_format(data[d]) + amount_currency : ""); })
	;

var color = d3.scale.quantize()
	.domain([0, maxsale*0.8])
	.range(d3.range(9));

d3.selectAll("g.y-"+y+" rect.day")
	.attr("class", function(d) { return "day q" + color(data[format(d)]) + "-9"; })
	;

d3.selectAll("g.y-"+y+"")
	.transition().ease("cubic").delay(100).duration(500)
	.style("opacity", "1.0")
})
})
;

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

}
