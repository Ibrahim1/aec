if (typeof d3.chart != "object") d3.chart = {};

d3.chart.factory = function () {
	var factory = {},
	p = 0,
	data = [],
	queue = [],
	charts = [],
	seeking = false,
	datef = d3.time.format("%Y-%m-%d %X"),
	s,e,
	exstart,
	exend,
	source,
	request,
	selector,
	w,h,d,m,
	svg;

	factory.source = function(s){
		request = request_url+"&type="+s;

		return factory;
	};

	factory.range = function(start,end){
		s = datef.parse(start);
		e = datef.parse(end);

		return factory;
	};

	factory.canvas = function(width, height, margin){
		w = width;
		h = height;
		m = margin;

		return factory;
	};

	factory.target = function(sel){
		selector = sel;
		svg = d3.select(sel)
		.append("svg:svg")
			.attr("width", w + m*2)
			.attr("height", h + m*2);

		charts.push(svg);

		p = charts.length-1;

		svg = charts[p];

		return factory;
	};

	factory.create = function(type, dim) {
		enqueue(function(start, end, target, p) {
			canvas = target
			.append("svg:g")
			.attr("class", type)
			.attr("id", type+"-"+p);

			chart = new window[type](canvas, dim, data.filter( function(e){ return (e.date >= start) && (e.date <= end); }));
		});

		return factory;
	};

	function getData(callback, start, end, target, p) {
		if ( !exstart ) {
			exstart = start;
			exend = end;
		}

		if ( ( exstart == start ) && ( exend == end ) ) {
			if ( data.length < 1 ) {
				pushData(function(data) {
					acquireData(data, callback, start, end, target, p);
				}, start, end);
			} else {
				doCallback(callback, start, end, target, p);
			}
		} else {
			if ( ( start < exstart ) || ( end > exend ) ) {
				if ( start < exstart ) {
					pushData(function(data) {
						acquireData(data, callback, start, end, target, p);
					}, start, exstart);
				} else if ( end > exend ) {
					pushData(function(data) {
						acquireData(data, callback, start, end, target, p);
					}, exend, end);
				}
			} else {
				doCallback(callback, start, end, target, p);
			}
		}
	}

	function acquireData(json, callback, start, end, target, p) {
		short = d3.time.format("%Y-%m-%d");

		test = [json.forEach(function(entry){
			if ( typeof entry != 'undefined' ) {
				entry.date = short.parse(entry.date);
				data = data.concat([entry]); }
			})];

		if ( test.length ) {
			data = data.concat([test]);
		}

		doCallback(callback, start, end, target, p);
	}

	function pushData(callback, start, end) {
		if ( start < exstart ) {
			exstart = start;
		}

		if ( end > exend ) {
			exend = end;
		}

		json(request+"&start="+encodeURI(datef(start))+"&end="+encodeURI(datef(end)), callback);
	}

	function doCallback(callback, start, end, target, p){
		callback(start, end, target, p);
	}

	function json(url, callback) {
		var req = new XMLHttpRequest;

		if (arguments.length < 2) callback = "application/json";
		else if ("application/json" && req.overrideMimeType) req.overrideMimeType("application/json");

		req.open("GET", url, true);
		req.onreadystatechange = function() {
			if (req.readyState === 4) {
				dequeue((req.status < 300 ? JSON.parse(req.responseText) : null), callback)
			}
		};

		req.send(null);
	}

	function enqueue(callback) {
		queue.push({call:callback,start:s,end:e,target:svg,p:p});

		if ( !seeking ) {
			seeking == true;

			triggerqueue();
		}
	}

	function dequeue(data, callback) {
		callback(data);

		seeking = false;

		triggerqueue();
	}

	function triggerqueue() {
		if ( queue.length ) {
			item = queue.shift();
			getData(item.call, item.start, item.end, item.target, item.p);
		}
	}

	return factory;
}

var sunburst_color = d3.scale.category20();

function Cellular(canvas, dim, dat) {
	var z = dim,
		day = d3.time.format("%w"),
		week = d3.time.format("%U"),
		format = d3.time.format("%Y-%m-%d");

	canvas.attr("class", "svg-crisp")
		.attr("transform", "translate("+z*2+","+z+")");

	var r_start = d3.min(dat, function(v){ return v.date; }),
		r_end = d3.max(dat, function(v){ return v.date; });
	var numyear = r_start.getFullYear();

	var year = canvas.append("svg:g")
		.data([numyear])
		.attr("class", "year y-"+numyear+" RdYlGn")
		.style("opacity", "1.0");

	year.append("svg:text")
		.attr("transform", "translate(-6," + z * 3.5 + ")rotate(-90)")
		.attr("text-anchor", "middle")
		.text(String);

	year.selectAll("rect.day")
		.data(d3.time.days(new Date(numyear, 0, 1), new Date(numyear + 1, 0, 1)))
		.enter()
		.append("svg:rect")
		.attr("class", "day")
		.attr("width", 1).attr("height", 1)
		.attr("x", function(d) {
			return (week(d) * z)+z/2;
			})
		.attr("y", function(d) { return (day(d) * z); })
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

	year.selectAll("path.month")
		.data(d3.time.months(new Date(numyear, 0, 1), new Date(numyear+1, 0, 1)))
		.enter()
		.append("svg:path")
			.attr("class", "month")
			.attr("d", monthPath)
			.style("stroke", "#555");

	var ccolor = d3.scale.quantize()
	.domain([0, max_sale*0.8])
	.range(d3.range(9));

	year.call( function(y) {
		var nsales = d3.nest()
			.key(function(d) { return d.date; })
			.rollup(function(v) { return d3.sum(v.map(function(d) { return d.amount; })); })
			.map(dat);

		this.selectAll("rect.day")
			.attr("class", function(d) { return "day q" + ccolor(nsales[d]) + "-9"; })
			.attr("ry", z/3.33).attr("rx", z/3.33)
			.append("svg:title")
			.text(function(d) { return format(d) + (d in nsales ? ": " + amount_format(nsales[d]) + amount_currency : ""); })

		this.selectAll("rect.day")
			.transition().ease("bounce")
			.delay(function(d, i) { return nsales[d] ? (i * (8-ccolor(nsales[d])))+(Math.random()*8) : 0; })
			.duration(600)
			.attr("width", z).attr("height", z)
			.attr("ry", 0).attr("rx", 0)
			.attr("x", function(d) { return week(d) * z; })
			.attr("y", function(d) { return day(d) * z; });
	});

	function monthPath(t0) {
	var t1 = new Date(t0.getUTCFullYear(), t0.getUTCMonth() + 1, 0),
		d0 = +day(t0), w0 = +week(t0),
		d1 = +day(t1), w1 = +week(t1);
	return "M" + (w0 + 1) * z + "," + d0 * z
		+ "H" + w0 * z + "V" + 7 * z
		+ "H" + w1 * z + "V" + (d1 + 1) * z
		+ "H" + (w1 + 1) * z + "V" + 0
		+ "H" + (w0 + 1) * z + "z";
	}
}

function Sunburst(canvas, dim, dat) {
	var r = dim / 2,
		format = d3.time.format("%Y-%m-%d %X"),
		selector = "#"+canvas.attr("id");

	canvas.attr("transform", "translate("+(r+3)+","+(r+3)+")");

	canvas.append("svg:path")
		.attr("d",d3.svg.arc()
			.startAngle(0)
			.endAngle(360)
			.innerRadius(0)
			.outerRadius(2*r/3)
		)
		.attr("class", "sunburst-middle")
		.style("fill", "#fff")
		.style("opacity", "1.0");

	var total = d3.sum(dat, function(v){ return v.amount; });

	var txt = canvas.append("svg:text")
		.attr("text-anchor", "middle")
		.attr("class", "console")
		.text("---")
		.attr("transform", "translate(0,-6)")
		.attr("class", "console-amt")
		.style("opacity", "0.5");

	var txt = canvas.append("svg:text")
		.attr("text-anchor", "middle")
		.attr("class", "console")
		.text("-")
		.attr("transform", "translate(0,10)")
		.attr("class", "console-val")
		.style("opacity", "0.5");

	var partition = d3.layout.partition()
		.sort(function(a,b) { return b.values-a.values; })
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
		.rollup(function(v) {
			return d3.nest()
				.key(function(d) { return d.plan; })
				.rollup(function(v) { return Math.max(d3.sum(v.map(function(d) { return d.amount; })), 0); })
				.entries(v);
		})
		.entries(dat);

	var path = canvas.data([pre]).selectAll("path")
		.data(partition.nodes).enter()
		.append("svg:path")
			.attr("display", function(d) { return d.depth ? null : "none"; }) // hide inner ring
			.attr("d", arc)
			.attr("fill-rule", "evenodd")
			.style("opacity", "0.3")
			.style("stroke", "#fff")
			.style("stroke-width", "0")
			.style("fill", function(d) { return sunburst_color(( (typeof d.values != 'object') ? d.parent : d).key); });

	path.transition().ease("bounce")
			.delay(function(d, i) { return (i * 50); })
			.duration(300)
			.style("opacity", function(d) { return (typeof d.values != 'object') ? "0.6" : "0.9"; });

/*
	path.transition()
		.ease("bounce")
		.duration(1000)
		.attrTween("d", pathTween);

	function pathTween(b){
		var i = d3.interpolate(
					{startAngle: function(d) { return d.x; }, endAngle: function(d) { return d.x; }},
					{startAngle:function(d) { return d.x; }, endAngle:function(d) { return (d.x + d.dx)-0.01; }}
					);

		return function(t) {
			return arc(i(t));
		}
	}
*/
	path.on("mouseover", function(d){
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
				.style("opacity", function(d) { return (typeof d.values != 'object') ? "0.6" : "0.9"; })
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
