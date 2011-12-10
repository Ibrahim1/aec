var sunburst_color = d3.scale.category20();

function vCharts() {
	this.charts  = [];
	this.p = 0;
	this.data = [];
	this.queue = [];
	this.quen = 0;
	this.datef = d3.time.format("%Y-%m-%d %X");

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
				.attr("width", this.w + this.m*2)
				.attr("height", this.h + this.m*2);

		this.charts.push(svg);

		this.p = this.charts.length-1;

		this.svg = this.charts[this.p];
	}

	this.getData = function(callback, start, end) {
		if ( this.exstart == undefined ) {
			this.exstart = start;
			this.exend = end;
		}

		that = this;

		if ( ( this.exstart == start ) && ( this.exend == end ) ) {
			if ( this.data.length < 1 ) {
				this.pushData(function(data, start, end) {
					that.acquireData(data, callback, start, end);
				}, start, end);
			} else {
				callback(this.data);
			}
		} else {
			if ( ( start < this.exstart ) || ( end > this.exend ) ) {
				if ( start < this.exstart ) {
					this.pushData(function(data, start, end) {
						that.acquireData(data, callback, start, end);
					}, start, this.exstart);
				}

				if ( end > this.exend ) {
					this.pushData(function(data, start, end) {
						that.acquireData(data, callback, start, end);
					}, this.exend, end);
				}
			} else {
				callback(this.data.filter( function(d){ return (d.date <= start) && (d.end >= end); }));
			}
		}
	}

	this.acquireData = function(json, callback, start, end) {
		this.data = this.data.concat(json);

		var s = this.datef.parse(start),
			e = this.datef.parse(end);

		short = d3.time.format("%Y-%m-%d");

		filtered = this.data.filter( function(d){ ddate = short.parse(d.date); return (ddate >= s) && (ddate <= e); }); 

		callback(filtered);
	}
	
	this.pushData = function(callback, start, end) {
		if ( start < this.exstart ) {
			this.exstart = start;
		}

		if ( end > this.exend ) {
			this.exend = end;
		}

		d3.json(this.request+"&start="+encodeURI(start)+"&end="+encodeURI(end), callback);
	}

	this.chain = function(type, dim) {
		
	}

	this.create = function(type, dim) {
		var target = this.svg,
			p = this.p,
			start = this.start,
			end = this.end;

		this.getData(function(data, target, p) {
			canvas = target.append("svg:g")
				.attr("class", type)
				.attr("id", type+"-"+p);

			chart = new window[type](canvas, dim, data);
		}, start, end);
	}

	this.json = function(url, callback) {
		var req = new XMLHttpRequest;

		if (arguments.length < 3) callback = "application/json";
		else if ("application/json" && req.overrideMimeType) req.overrideMimeType("application/json");

		req.open("GET", url, true);
		req.onreadystatechange = function() {
			if (req.readyState === 0) callback(req.status < 300 ? req : null);
			else if (req.readyState === 4) callback(req.status < 300 ? req : null);
		};

		req.send(null);
	};

	this.enqueue = function(type, dim) {
		queuer = function() {
				var type = type,
					dim = dim,
					start = this.start,
					end = this.end,
					target = this.p,
					selector = this.selector;
		}

		//this.queue[] = queuer;
	}

	this.createold = function(type, dim) {
		var target = this.svg,
			p = this.p;

		d3.json(this.request+"&start="+encodeURI(this.start)+"&end="+encodeURI(this.end), function(data) {
			canvas = target.append("svg:g")
				.attr("class", type)
				.attr("id", type+"-"+p);

			chart = new window[type](canvas, dim, data);
		});
	}

	this.multi = function(unit) {
		d3.json(this.request+"&start="+encodeURI(this.start)+"&end="+encodeURI(this.end), function(json) {
			canvas = target.append("svg:g")
				.attr("class", type)
				.attr("id", type+"-"+p);

			chart = new window[type](canvas, dim, json);
		});		
	}

}

Cellular = function(canvas, dim, dat) {
	var z = dim,
		day = d3.time.format("%w"),
		week = d3.time.format("%U"),
		mon = d3.time.format("%U"),
		format = d3.time.format("%Y-%m-%d"),
		dat = dat;

	canvas.attr("class", "svg-crisp")
		.attr("transform", "translate("+z*2+","+z+")");

	var r_start = d3.min(dat, function(v){ return v.date; }),
		r_end = d3.max(dat, function(v){ return v.date; })
		numyear = Number(r_start.slice(0, 4));

	var year = canvas.append("svg:g")
		.data([numyear])
		.attr("class", "year y-"+r_start.slice(0, 4)+" RdYlGn")
		//.attr("transform", "translate(42," + ( 1 + ( d - r_start ) * h*2.2 ) + ")")
		.style("opacity", "0.5");

	year.append("svg:text")
		.attr("transform", "translate(-6," + z * 3.5 + ")rotate(-90)")
		.attr("text-anchor", "middle")
		.text(String);

	year.selectAll("rect.day")
		.data(d3.time.days(new Date(r_start), new Date(r_end)))
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

	year.selectAll("path.month")
		.data(d3.time.months(new Date(numyear, 0, 1), new Date(numyear+1, 0, 1)))
		.enter()
		.append("svg:path")
			.attr("class", "month")
			.attr("d", monthPath);

	var ccolor = d3.scale.quantize()
	.domain([0, max_sale*0.8])
	.range(d3.range(9));

	year.call( function(y) {
		var nsales = d3.nest()
			.key(function(d) { return d.date; })
			.rollup(function(v) { return d3.sum(v.map(function(d) { return d.amount; })); })
			.map(dat);

		this.selectAll("rect.day")
			.attr("class", function(d) { dd = format(d); test = nsales[format(d)];return "day q" + ccolor(nsales[format(d)]) + "-9"; })
			.append("svg:title")
			.text(function(d) { return format(d) + (format(d) in nsales ? ": " + amount_format(nsales[format(d)]) + amount_currency : ""); });

		this.transition().ease("cubic").delay(100).duration(500).style("opacity", "1.0")
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

Sunburst = function(canvas, dim, dat) {
	var r = dim / 2,
		format = d3.time.format("%Y-%m-%d %X"),
		selector = "#"+canvas.attr("id");

	canvas.attr("transform", "translate("+(r+3)+","+(r+3)+")");

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

	var total = d3.sum(dat, function(v){ return v.amount; });

	var txt = canvas.append("svg:text")
		.attr("text-anchor", "middle")
		.attr("class", "console")
		.text("-")
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
		.rollup(function(v) { return d3.nest()
										.key(function(d) { return d.plan; })
										.rollup(function(v) { return d3.sum(v.map(function(d) { return d.amount; })); })
										.entries(v);
							})
		.entries(dat);

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
