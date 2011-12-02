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
	var selyear = d3.select(this);
	d3.json(request_url+"&type=sales&start="+y+"&end="+ (y+1), function(json) {
		var data = d3.nest()
			.key(function(d) { return d.date; })
			.rollup(function(v) { return d3.sum(v.map(function(d) { return d.amount; })); })
			.map(json.sales);

		var color = d3.scale.quantize()
			.domain([0, maxsale*0.8])
			.range(d3.range(9));

		selyear.selectAll("rect.day")
			.attr("class", function(d) { return "day q" + color(data[format(d)]) + "-9"; })
			.append("svg:title")
			.text(function(d) { return (d = format(d)) + (d in data ? ": " + amount_format(data[d]) + amount_currency : ""); });

		selyear.transition().ease("cubic").delay(100).duration(500).style("opacity", "1.0")
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

}

function sunburst_sales(selector, r_start, r_end) {
var w = 560,
	h = 300,
	r = Math.min(w, h) / 2,
	color = d3.scale.category20c()
	format = d3.time.format("%Y-%m-%d %X");

var vis = d3.select(selector).append("svg:svg")
	.attr("width", w)
	.attr("height", h)
	.append("svg:g")
		.attr("transform", "translate(" + w / 2 + "," + h / 2 + ")");

var partition = d3.layout.partition()
	.sort(null)
	.size([2 * Math.PI, r * r])
	.value(function(d) { return 1; });

var arc = d3.svg.arc()
	.startAngle(function(d) { return d.x; })
	.endAngle(function(d) { return d.x + d.dx; })
	.innerRadius(function(d) { return Math.sqrt(d.y); })
	.outerRadius(function(d) { return Math.sqrt(d.y + d.dy); });

d3.json(request_url+"&type=sales&start="+encodeURI(r_start)+"&end="+encodeURI(r_end), function(json) {
	var pre = [d3.nest()
		.key(function(d) { return d.group; })
		.rollup(function(v) { return d3.nest()
										.key(function(d) { return d.plan; })
										.rollup(function(v) { return d3.sum(v.map(function(d) { return d.amount; })); })
										.entries(v);
							})
		.entries(json.sales)];

	var path = vis.data(pre).selectAll("path")
		.data(partition.nodes)
		.enter().append("svg:path")
			.attr("display", function(d) { return d.depth ? null : "none"; }) // hide inner ring
			.attr("d", arc)
			.attr("fill-rule", "evenodd")
			.style("stroke", "#fff")
			.style("fill", function(d) { return color((d.values ? d : d.parent).name); })
			.append("svg:title")
			.text(function(d) { return amount_format(d.amount) + amount_currency; });
			;

d3.select("#size").on("click", function() {
	path
		.data(partition.value(function(d) { return d.amount; }))
	  .transition()
		.duration(1500)
		.attrTween("d", arcTween);

	d3.select("#size").classed("active", true);
	d3.select("#count").classed("active", false);
  });

d3.select("#count").on("click", function() {
	path
		.data(partition.value(function(d) { return 1; }))
	  .transition()
		.duration(1500)
		.attrTween("d", arcTween);

	d3.select("#size").classed("active", false);
	d3.select("#count").classed("active", true);
  });
});

// Stash the old values for transition.
function stash(d) {
  d.x0 = d.x;
  d.dx0 = d.dx;
}

// Interpolate the arcs in data space.
function arcTween(a) {
  var i = d3.interpolate({x: a.x0, dx: a.dx0}, a);
  return function(t) {
	var b = i(t);
	a.x0 = b.x;
	a.dx0 = b.dx;
	return arc(b);
  };
}
}
