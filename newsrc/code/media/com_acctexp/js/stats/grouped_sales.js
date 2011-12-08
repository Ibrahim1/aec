var sunburst_color = d3.scale.category20();

function vCharts() {
	this.charts  = [];
	this.p = 0;

	this.source = function(type) {
		this.request = request_url+"&type="+type;
	}

	this.range = function(start, end) {
		this.start = start;
		this.end = end;
	}

	this.canvas = function(w, h, m) {
		this.w = w;
		this.h = h;
		this.d = Math.min(w, h) / 2;
		this.m = m;
	}

	this.pushTarget = function(sel) {
		this.selector = sel;

		svg = d3.select(sel)
			.append("svg:svg")
				//.attr("class", "svg-crisp")
				.attr("width", this.w + this.m*2)
				.attr("height", this.h + this.m*2);

		this.charts.push(svg);

		this.p = this.charts.length-1;

		this.svg = this.charts[this.p];
	}

	this.create = function(type, dim) {
		var target = this.svg,
			p = this.p;

		d3.json(this.request+"&start="+encodeURI(this.start)+"&end="+encodeURI(this.end), function(json) {
			canvas = target.append("svg:g")
			.attr("class", type)
			.attr("id", type+"-"+p);

			chart = new window[type](canvas, dim, json);
		});
	}

}

Cellular = function(canvas, dim, data) {
	var m = 20,
		z = dim,
		h = z*6 - m[0] - m[2],
		day = d3.time.format("%w"),
		week = d3.time.format("%U"),
		mon = d3.time.format("%U"),
		format = d3.time.format("%Y-%m-%d");

	var year = this.svg.selectAll("g.year")
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
		});

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

		var nsales = d3.nest()
			.key(function(d) { return d.date; })
			.rollup(function(v) { return d3.sum(v.map(function(d) { return d.amount; })); })
			.map(data.sales)
			.sortValues(d3.descending);  // TODO

		var ccolor = d3.scale.quantize()
			.domain([0, maxsale*0.8])
			.range(d3.range(9));

		selyear.selectAll("rect.day")
			.attr("class", function(d) { return "day q" + ccolor(nsales[format(d)]) + "-9"; })
			.append("svg:title")
			.text(function(d) { return (d = format(d)) + (d in nsales ? ": " + amount_format(nsales) + amount_currency : ""); });

		selyear.transition().ease("cubic").delay(100).duration(500).style("opacity", "1.0")
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

Sunburst = function(canvas, dim, data) {
	var r = dim / 2,
		format = d3.time.format("%Y-%m-%d %X")
		selector = "#"+canvas.attr("id");

	canvas.attr("transform", "translate("+(r+3)+","+(r+3)+")")

	canvas.append("svg:path")
		.attr("d",d3.svg.arc()
			.startAngle(function(d) { return 0; })
			.endAngle(function(d) { return 360; })
			.innerRadius(function(d) { return r+1; })
			.outerRadius(function(d) { return r+2; })
		)
		.attr("class", "sunburst-ring")
		.style("stroke", "#000")
		.style("opacity", "0.5");

	var total = d3.sum(data.sales, function(v){ return v.amount; });

	var txt = canvas.append("svg:text")
		.attr("text-anchor", "middle")
		.attr("class", "console")
		.text("test")
		.attr("transform", "translate(0,-6)")
		.attr("class", "console-amt")
		.style("opacity", "0.5");

	var txt = canvas.append("svg:text")
		.attr("text-anchor", "middle")
		.attr("class", "console")
		.text("test")
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
		.entries(data.sales);

	var path = canvas.data([pre]).selectAll("path")
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

}
