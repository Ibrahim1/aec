var sunburst_color = d3.scale.category20();

function vCharts() {
	this.charts  = [];
	this.p = 0;
	this.data = [];
	this.queue = [];
	this.queue_next = 0;
	this.datef = d3.time.format("%Y-%m-%d %X");
	var exstart, exend;

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

	this.getData = function(callback, start, end, target, p) {
		if ( !this.exstart ) {
			this.exstart = start;
			this.exend = end;
		}

		that = this;

		if ( ( this.exstart == start ) && ( this.exend == end ) ) {
			if ( this.data.length < 1 ) {
				this.pushData(function(data) {
					that.acquireData(data, callback, start, end, target, p);
				}, start, end);
			} else {
				this.doCallback(callback, start, end, target, p);
			}
		} else {
			if ( ( start < this.exstart ) || ( end > this.exend ) ) {
				if ( start < this.exstart ) {
					this.pushData(function(data) {
						that.acquireData(data, callback, start, end, target, p);
					}, start, this.exstart);
				} else if ( end > this.exend ) {
					this.pushData(function(data) {
						that.acquireData(data, callback, start, end, target, p);
					}, this.exend, end);
				}
			} else {
				this.doCallback(callback, start, end, target, p);
			}
		}
	}

	this.acquireData = function(json, callback, start, end, target, p) {
		this.data = this.data.concat(json);

		if ( start < this.exstart ) {
			this.exstart = start;
		}

		if ( end > this.exend ) {
			this.exend = end;
		}

		this.doCallback(callback, start, end, target, p);
	}

	this.doCallback = function(callback, start, end, target, p){
		var s = this.datef.parse(start),
		e = this.datef.parse(end);

		short = d3.time.format("%Y-%m-%d");

		filtered = this.data.filter( function(d){ ddate = short.parse(d.date); return (ddate >= s) && (ddate <= e); }); 

		callback(filtered, target, p);
	}

	this.pushData = function(callback, start, end) {
		this.json(this.request+"&start="+encodeURI(start)+"&end="+encodeURI(end), callback);
	}

	this.create = function(type, dim) {
		this.enqueue(function(data, target, p) {
			canvas = target
			.append("svg:g")
			.attr("class", type)
			.attr("id", type+"-"+p);

			chart = new window[type](canvas, dim, data);
		})
	}

	this.json = function(url, callback) {
		var req = new XMLHttpRequest;

		if (arguments.length < 2) callback = "application/json";
		else if ("application/json" && req.overrideMimeType) req.overrideMimeType("application/json");

		that = this;

		req.open("GET", url, true);
		req.onreadystatechange = function() {
			if (req.readyState === 4) {
				that.dequeue((req.status < 300 ? JSON.parse(req.responseText) : null), callback)
			}
		};

		req.send(null);
	};

	this.enqueue = function(callback) {
		this.queue_next++;
		this.queue.push({call:callback,start:this.start,end:this.end,target:this.svg,p:this.p});

		this.triggerqueue();
	}

	this.dequeue = function(data, callback) {
		this.queue_next--;

		callback(data);

		this.triggerqueue();
	}

	this.triggerqueue = function() {
		if ( this.queue.length ) {
			if ( this.queue.length == this.queue_next ) {
				item = this.queue.shift();
				this.getData(item.call, item.start, item.end, item.target, item.p);
			}
		}
	}

}

function Cellular(canvas, dim, dat) {
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
		.style("opacity", "1.0");

	year.append("svg:text")
		.attr("transform", "translate(-6," + z * 3.5 + ")rotate(-90)")
		.attr("text-anchor", "middle")
		.text(String);

	year.selectAll("rect.day")
		.data(d3.time.days(new Date(r_start), new Date(r_end)))
		.enter()
		.append("svg:rect")
		.attr("class", "day")
		.attr("width", 1).attr("height", 1)
		.attr("x", function(d) { return (week(d) * z)+z/2; })
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
			.attr("ry", z/3.33).attr("rx", z/3.33)
			.append("svg:title")
			.text(function(d) { return format(d) + (format(d) in nsales ? ": " + amount_format(nsales[format(d)]) + amount_currency : ""); })

		this.selectAll("rect.day")
			.transition().ease("bounce")
			.delay(function(d, i) { return (i * (8-ccolor(nsales[format(d)])))+(Math.random()*8); })
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
			.startAngle(function(d) { return 0; })
			.endAngle(function(d) { return 360; })
			.innerRadius(function(d) { return r+1; })
			.outerRadius(function(d) { return r+2; })
		)
		.attr("class", "sunburst-ring")
		.style("stroke", "#000")
		.style("opacity", "0.8");

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

function Stacked(canvas, dim, dat) {
	var n = 2, // number of layers
    m = 64, // number of samples per layer
    color = d3.interpolateRgb("#aad", "#556");
/*
	var data = new Object;
	data.key = 0;
	data.values = d3.nest()
		.key(function(d) { return d.date; })
		.rollup(function(v) {
			return d3.nest()
			.key(function(d) { return d.group; })
			.rollup(function(w) { return d3.nest()
				.key(function(d) { return d.plan; })
				.rollup(function(v) { return Math.max(d3.sum(w.map(function(d) { return d.amount; })), 0); })
				.entries(w);
				})
			.entries(v);
		})
		.entries(dat);


	var data = d3.layout.stack()(d3.range(n).map(function(dat) {
	var a = [], i;
	for (i = 0; i < m; i++) a[i] = .2 * Math.random();
	for (i = 0; i < 5; i++) bump(a);
	return a.map(stream_index);
	}););
*/
	function stream_layers(n, m, o) {
		  if (arguments.length < 3) o = 0;
		  function bump(a) {
		    var x = 1 / (.1 + Math.random()),
		        y = 2 * Math.random() - .5,
		        z = 10 / (.1 + Math.random());
		    for (var i = 0; i < m; i++) {
		      var w = (i / m - y) * z;
		      a[i] += x * Math.exp(-w * w);
		    }
		  }
		  return d3.range(n).map(function() {
		      var a = [], i;
		      for (i = 0; i < m; i++) a[i] = o + o * Math.random();
		      for (i = 0; i < 5; i++) bump(a);
		      return a.map(stream_index);
		    });
		}
	function stream_index(d, i) {
		  return {x: i, y: Math.max(0, d)};
		}
test = stream_layers(n,m);
	var p = 20,
    w = canvas.attr("width"),
    h = canvas.attr("height") - .5 - p,
    mx = m,
    my = d3.max(data, function(d) {
      return d3.max(d, function(d) {
        return d.y0 + d.y;
      });
    }),
    mz = d3.max(data, function(d) {
      return d3.max(d, function(d) {
        return d.y;
      });
    }),
    x = function(d) { return d.x * w / mx; },
    y0 = function(d) { return h - d.y0 * h / my; },
    y1 = function(d) { return h - (d.y + d.y0) * h / my; },
    y2 = function(d) { return d.y * h / mz; }; // or `my` to not rescale

var layers = canvas.selectAll("g.layer")
    .data(data)
  .enter().append("g")
    .style("fill", function(d, i) { return sunburst_color(i / (n - 1)); })
    .attr("class", "layer");

var bars = layers.selectAll("g.bar")
    .data(function(d) { return d; })
  .enter().append("g")
    .attr("class", "bar")
    .attr("transform", function(d) { return "translate(" + x(d) + ",0)"; });

bars.append("rect")
    .attr("width", x({x: .9}))
    .attr("x", 0)
    .attr("y", h)
    .attr("height", 0)
  .transition()
    .delay(function(d, i) { return i * 10; })
    .attr("y", y1)
    .attr("height", function(d) { return y0(d) - y1(d); });
/*
var labels = canvas.selectAll("text.label")
    .data(data[0])
  .enter().append("text")
    .attr("class", "label")
    .attr("x", x)
    .attr("y", h + 6)
    .attr("dx", x({x: .45}))
    .attr("dy", ".71em")
    .attr("text-anchor", "middle")
    .text(function(d, i) { return i; });
*/
canvas.append("line")
    .attr("x1", 0)
    .attr("x2", w - x({x: .1}))
    .attr("y1", h)
    .attr("y2", h);

function transitionGroup() {
  var group = d3.selectAll("#chart");

  group.select("#group")
      .attr("class", "first active");

  group.select("#stack")
      .attr("class", "last");

  group.selectAll("g.layer rect")
    .transition()
      .duration(500)
      .delay(function(d, i) { return (i % m) * 10; })
      .attr("x", function(d, i) { return x({x: .9 * ~~(i / m) / n}); })
      .attr("width", x({x: .9 / n}))
      .each("end", transitionEnd);

  function transitionEnd() {
    d3.select(this)
      .transition()
        .duration(500)
        .attr("y", function(d) { return h - y2(d); })
        .attr("height", y2);
  }
}

function transitionStack() {
  var stack = d3.select("#chart");

  stack.select("#group")
      .attr("class", "first");

  stack.select("#stack")
      .attr("class", "last active");

  stack.selectAll("g.layer rect")
    .transition()
      .duration(500)
      .delay(function(d, i) { return (i % m) * 10; })
      .attr("y", y1)
      .attr("height", function(d) { return y0(d) - y1(d); })
      .each("end", transitionEnd);

  function transitionEnd() {
    d3.select(this)
      .transition()
        .duration(500)
        .attr("x", 0)
        .attr("width", x({x: .9}));
  }
}

}