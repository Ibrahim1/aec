var sunburst_color = d3.scale.category20();

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

var cell = d3.select(selector)
	.style("width", w + m[1] + m[3])
	.style("height", h*(r_end-r_start)*3 + m[0] + m[2])
	.append("svg:svg")
		.attr("class", "svg-crisp")
		.attr("width", w + m[1] + m[3])
		.attr("height", h*(r_end-r_start)*2 + m[0]*3);

var year = cell.selectAll("g.year")
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
		.transition().ease("bounce").delay(100).duration(500)
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

		var ccolor = d3.scale.quantize()
			.domain([0, maxsale*0.8])
			.range(d3.range(9));

		selyear.selectAll("rect.day")
			.attr("class", function(d) { return "day q" + ccolor(data[format(d)]) + "-9"; })
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
var w = 200,
	h = 200,
	r = (Math.min(w, h) / 2)-6,
	format = d3.time.format("%Y-%m-%d %X"),
	sales;

var sun = d3.select(selector).append("svg:svg")
	.attr("width", w)
	.attr("height", h)
	.append("svg:g")
		.attr("transform", "translate(" + w / 2 + "," + h / 2 + ")");

sun.append("svg:path")
	.attr("d",d3.svg.arc()
		.startAngle(function(d) { return 0; })
		.endAngle(function(d) { return 360; })
		.innerRadius(function(d) { return r+1; })
		.outerRadius(function(d) { return r+2; })
	)
	.attr("class", "sunburst-ring")
	.style("stroke", "#000")
	.style("opacity", "0.5");

d3.json(request_url+"&type=sales&start="+encodeURI(r_start)+"&end="+encodeURI(r_end), function(json) {
sales = json;

d3.select(selector+" .sunburst-ring")
.transition().ease("cubic-out").duration(500)
.style("opacity", "1.0");

var total = d3.sum(sales.sales, function(v){ return v.amount; });

var txt = sun.append("svg:text")
	.attr("text-anchor", "middle")
	.attr("class", "console")
	.text("")
	.attr("transform", "translate(0,-6)")
	.attr("class", "console-amt")
	.style("opacity", "0.5");

var txt = sun.append("svg:text")
	.attr("text-anchor", "middle")
	.attr("class", "console")
	.text("")
	.attr("transform", "translate(0,10)")
	.attr("class", "console-val")
	.style("opacity", "0.5");

var partition = d3.layout.partition()
	.sort(null)
	.size([2 * Math.PI, r * r])
	.value(function(d) { return d.values; })
	.children(function (d) { return d.values; });

var arc = d3.svg.arc()
	.startAngle(function(d) { return d.x; })
	.endAngle(function(d) { return (d.x + d.dx)-0.01; })
	.innerRadius(function(d) { return Math.sqrt(d.y)+1; })
	.outerRadius(function(d) { return Math.sqrt(d.y + d.dy+1); });

var pre = new Object;
pre.key = 0;
pre.values = d3.nest()
	.key(function(d) { return d.group; })
	.rollup(function(v) { return d3.nest()
									.key(function(d) { return d.plan; })
									.rollup(function(v) { return d3.sum(v.map(function(d) { return d.amount; })); })
									.entries(v);
						})
	.entries(sales.sales);

var path = sun.data([pre]).selectAll("path")
	.data(partition.nodes).enter()
	.append("svg:path")
		.attr("display", function(d) { return d.depth ? null : "none"; }) // hide inner ring
		.attr("d", arc)
		.attr("fill-rule", "evenodd")
		.style("opacity", "0.8")
		.style("stroke", "#fff")
		.style("stroke-width", "0")
		.style("fill", function(d) { return sunburst_color(( (typeof d.values != 'object') ? d.parent : d).key); })
		.on("mouseover", function(d){
			if (typeof d.values != 'object') {
				name = "Plan " + d.key + ": ";
				amount = amount_format(d.values) + amount_currency; 
			} else {
				name = "Group " + d.key + ": ";
				amount = amount_format(d3.sum(d.values.map(function(v) { return v.values; }))) + amount_currency;
			}
			d3.select(this)
				.transition().ease("cubic-out").duration(500)
				.style("opacity", "1.0")
				.style("stroke-width", "1");
			d3.select(selector+" .console-amt")
				.style("opacity", "0.2")
				.text(name)
				.transition().ease("cubic-out").duration(500)
				.style("opacity", "1.0");
			d3.select(selector+" .console-val")
				.style("opacity", "0.2")
				.text(amount)
				.transition().ease("cubic-out").duration(500)
				.style("opacity", "1.0");
		})
		.on("mouseout", function(){
			d3.select(this)
				.transition().ease("cubic-out").duration(500)
				.style("opacity", "0.8")
				.style("stroke-width", "0");

			center_console();
		})
		;

var center_console = function() {
	d3.select(selector+" .console-amt")
	.style("opacity", "0.2")
	.text("Total:")
	.transition().ease("cubic-out").duration(500)
	.style("opacity", "1.0");

	d3.select(selector+" .console-val")
	.style("opacity", "0.2")
	.text(amount_format(total) + amount_currency)
	.transition().ease("cubic-out").duration(500)
	.style("opacity", "1.0");
}

center_console();

});

}
