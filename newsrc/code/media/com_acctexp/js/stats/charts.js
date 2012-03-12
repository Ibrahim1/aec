if (typeof d3.chart != "object") d3.chart = {};
	
d3.chart.sunburst = function () {

	var chart = {},
	data= [],
	label= [],
	parent,
	group,
	w, h,
	x, y,
	chartW, chartH,
	duration = 1000,
	margin = [20, 20, 20, 20],
	gap = 30,
	variant = "standard",
	console,chead,cbody;

	chart.parent = function(p,id) {
		if (!arguments.length) return parent.node();	   
		parent = d3.select(p);
		w = parent.node().clientWidth;
		h = parent.node().clientHeight;
		x = -w/2;
		y = -h/2;
		if(d3.ns.prefix.xhtml == parent.node().namespaceURI) {
			parent = parent.append("svg:svg")
				.attr("viewBox", "0 0 "+w+" "+h)
				.attr("preserveAspectRatio", "none");					  
		}
		group = parent.append("svg:g")
			.attr("class", "group")
			.attr("id", "group"+id);
		group.append("svg:rect")
		.attr("class", "panel")
		.call(resize);

		chart.console();

		return chart;
	};

	chart.console = function() {
		var r = w / 2;

		console = group
			.append("svg:path")
			.attr("d",d3.svg.arc()
				.startAngle(0)
				.endAngle(360)
				.innerRadius(0)
				.outerRadius(2*r/3)
			)
			.attr("class", "sunburst-middle")
			.style("fill", "#fff")
			.style("opacity", "1.0");

		chead = group
			.append("svg:text")
			.attr("class", "console-amt")
			.attr("text-anchor", "middle")
			.attr("transform", "translate(0,-6)")
			.text("---")
			.style("opacity", "0.5");

		cbody = group
			.append("svg:text")
			.attr("class", "console-val")
			.attr("text-anchor", "middle")
			.attr("transform", "translate(0,10)")
			.text("-")
			.style("opacity", "0.5");
	}

	chart.margin = function(m) {
		if (!arguments.length) return margin;
		margin = m;
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};

	chart.size = function(s) {
		if (!arguments.length) return [w, h];
		w = s[0];
		h = s[1];
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};

	chart.position = function(p, other) {
		if (!arguments.length) return [x, y];
		if(typeof p == "string") {
			var otherPos = other.position();
			var otherSize = other.size();	
			if(p == "after") {
				x = otherPos[0]+otherSize[0];
				y = otherPos[1]+otherSize[1]-h;
			}else if(p == "under") {
				x = otherPos[0];
				y = otherPos[1]+otherSize[1];
			}
		}else{
			x = p[0];
			y = p[1];
		}
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};

	chart.transition = function(d) {
		if (!arguments.length) return duration;
		duration = d;
		return redraw();
	};

	chart.data = function(d) {
		if (!arguments.length) return data;
		data = d;
		return redraw();
	};

	chart.gap = function(g) {
		if (!arguments.length) return gap;
		gap = g;
		return redraw();
	};

	chart.variant = function(v) {
		if (!arguments.length) return variant;
		variant = v;
		return redraw();
	};

	function resize() {
		chartW = w - margin[1] - margin[3];
		chartH = h - margin[0] - margin[2];
		this.attr("width", chartW)
			.attr("height", chartH)
			.attr("x", x+margin[3])
			.attr("y", y+margin[0]);
		return chart;
	}

	function redraw() {
		chart.tochart();		  
		return chart;
	};

	chart.tochart = function() {
		var yScale = d3.scale.linear().domain([0, d3.max(data)+h/100]).range([chartH, 0]);
		var xScale = d3.scale.linear().domain([0, data.length]).range([0, chartW]);
		var gapW = chartW/data.length*(gap/100);
		
		var markX = function(d, i) {return x+xScale(i)+margin[3]+gapW/2;};
		var markY = function(d, i) {return y+yScale(d)+margin[0];};
		var markW = chartW/data.length-gapW;
		var markH = function(d, i) {return chartH-yScale(d);};
		drawSVG(markX, markY, markW, markH);

		return chart;
	};

	function drawSVG(markX, markY, markW, markH) {
		var r = w / 2 - margin[0],
		format = d3.time.format("%Y-%m-%d %X"),
		selector = "#"+group.attr("id");

		group.attr("transform", "translate("+(r+margin[0])+","+(r+margin[1])+")");

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
			.entries(data);

		var path = group.data([pre]).selectAll("path")
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

		path.on("mouseover", function(d) {
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
			.on("mouseout", function() {
				d3.select(this)
					.transition().ease("cubic-out").duration(500)
					.style("opacity", function(d) { return (typeof d.values != 'object') ? "0.6" : "0.9"; })
					.style("stroke-width", "0");

				center_console();
			})
			;

		var total = d3.sum(data, function(v) { return v.amount; });

		var center_console = function() {
			chead
			.style("opacity", "0.2")
			.text("Total:")
			.transition().ease("cubic-out").duration(500)
			.style("opacity", "1.0");

			cbody
			.style("opacity", "0.2")
			.text(amount_format(total) + amount_currency)
			.transition().ease("cubic-out").duration(500)
			.style("opacity", "1.0");
			}

		center_console();
	}

	return chart;
};

d3.chart.cellular = function () {

	var chart = {},
	data= [],
	label= [],
	parent,
	group,
	w, h,
	x, y,
	chartW, chartH,
	duration = 1000,
	margin = [20, 20, 20, 20],
	gap = 30,
	variant = "standard";

	chart.parent = function(p,id) {
		if (!arguments.length) return parent.node();	   
		parent = d3.select(p);
		w = parent.node().clientWidth;
		h = parent.node().clientHeight;
		x = 0;
		y = 0;
		if(d3.ns.prefix.xhtml == parent.node().namespaceURI) {
			parent = parent.append("svg:svg")
				.attr("viewBox", "0 0 "+w+" "+h)
				.attr("preserveAspectRatio", "none");					  
		}
		group = parent.append("svg:g")
			.attr("class", "group svg-crisp")
			.attr("id", "group"+id);
		group.append("svg:rect")
		.attr("class", "panel")
		.call(resize);

		return chart;
	};

	chart.margin = function(m) {
		if (!arguments.length) return margin;
		margin = m;
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};

	chart.size = function(s) {
		if (!arguments.length) return [w, h];
		w = s[0];
		h = s[1];
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};

	chart.position = function(p, other) {
		if (!arguments.length) return [x, y];
		if(typeof p == "string") {
			var otherPos = other.position();
			var otherSize = other.size();	
			if(p == "after") {
				x = otherPos[0]+otherSize[0];
				y = otherPos[1]+otherSize[1]-h;
			}else if(p == "under") {
				x = otherPos[0];
				y = otherPos[1]+otherSize[1];
			}
		}else{
			x = p[0];
			y = p[1];
		}
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};

	chart.transition = function(d) {
		if (!arguments.length) return duration;
		duration = d;
		return redraw();
	};

	chart.data = function(d) {
		if (!arguments.length) return data;
		data = d;
		return redraw();
	};

	chart.gap = function(g) {
		if (!arguments.length) return gap;
		gap = g;
		return redraw();
	};

	chart.variant = function(v) {
		if (!arguments.length) return variant;
		variant = v;
		return redraw();
	};

	function resize() {
		chartW = w - margin[1] - margin[3];
		chartH = h - margin[0] - margin[2];
		this.attr("width", chartW)
			.attr("height", chartH)
			.attr("x", x+margin[3])
			.attr("y", y+margin[0]);
		return chart;
	}

	function redraw() {
		chart.tochart();		  
		return chart;
	};

	chart.tochart = function() {
		var yScale = d3.scale.linear().domain([0, d3.max(data)+h/100]).range([chartH, 0]);
		var xScale = d3.scale.linear().domain([0, data.length]).range([0, chartW]);
		var gapW = chartW/data.length*(gap/100);
		
		var markX = function(d, i) {return x+xScale(i)+margin[3]+gapW/2;};
		var markY = function(d, i) {return y+yScale(d)+margin[0];};
		var markW = chartW/data.length-gapW;
		var markH = function(d, i) {return chartH-yScale(d);};
		drawSVG(markX, markY, markW, markH);

		return chart;
	};

	function drawSVG(markX, markY, markW, markH) {
		z = 14,
		day = d3.time.format("%w"),
		week = d3.time.format("%U"),
		format = d3.time.format("%Y-%m-%d");

		var numyear = 2012;

		var year = group.selectAll("g.year")
			.data([numyear])
			.enter()
			.append("svg:g")
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
				.map(data);

			this.selectAll("rect.day")
				.attr("class", function(d) { return "day q" + ccolor(nsales[d]) + "-9"; })
				.attr("class", "bstooltip")
				.attr("ry", z/3.33).attr("rx", z/3.33)
				.attr("rel", "tooltip")
				.attr("data-original-title", function(d) { return format(d) + (d in nsales ? ": " + amount_format(nsales[d]) + amount_currency : ""); });

			this.selectAll("rect.day")
				.transition().ease("bounce")
				.delay(function(d, i) { return nsales[d] ? (i * (8-ccolor(nsales[d])))+(Math.random()*8) : 0; })
				.duration(600)
				.attr("width", z).attr("height", z)
				.attr("ry", 0).attr("rx", 0)
				.attr("x", function(d) { return week(d) * z; })
				.attr("y", function(d) { return day(d) * z; });
		});
	}

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

	function monthPathMonday(t0) {
		  var t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0),
		      d0 = +day(t0), w0 = +week(t0),
		      d1 = +day(t1), w1 = +week(t1);
		  return "M" + (w0 + 1) * size + "," + d0 * size
		      + "H" + w0 * size + "V" + 7 * size
		      + "H" + w1 * size + "V" + (d1 + 1) * size
		      + "H" + (w1 + 1) * size + "V" + 0
		      + "H" + (w0 + 1) * size + "Z";
	}

	return chart;
};

d3.chart.bump = function () {

	var chart = {},
	data= [],
	label= [],
	parent,
	group,
	w, h,
	x, y,
	chartW, chartH,
	duration = 1000,
	margin = [20, 20, 20, 20],
	gap = 30,
	variant = "standard";

	chart.parent = function(p,id) {
		if (!arguments.length) return parent.node();	   
		parent = d3.select(p);
		w = parent.node().clientWidth;
		h = parent.node().clientHeight;
		x = 0;
		y = 0;
		if(d3.ns.prefix.xhtml == parent.node().namespaceURI) {
			parent = parent.append("svg:svg")
				.attr("viewBox", "0 0 "+w+" "+h)
				.attr("preserveAspectRatio", "none");					  
		}
		group = parent.append("svg:g")
			.attr("class", "group svg-crisp")
			.attr("id", "group"+id);
		group.append("svg:rect")
			.attr("class", "panel")
			.call(resize);
		return chart;
	};

	chart.margin = function(m) {
		if (!arguments.length) return margin;
		margin = m;
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};

	chart.size = function(s) {
		if (!arguments.length) return [w, h];
		w = s[0];
		h = s[1];
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};

	chart.position = function(p, other) {
		if (!arguments.length) return [x, y];
		if(typeof p == "string") {
			var otherPos = other.position();
			var otherSize = other.size();	
			if(p == "after") {
				x = otherPos[0]+otherSize[0];
				y = otherPos[1]+otherSize[1]-h;
			}else if(p == "under") {
				x = otherPos[0];
				y = otherPos[1]+otherSize[1];
			}
		}else{
			x = p[0];
			y = p[1];
		}
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};

	chart.transition = function(d) {
		if (!arguments.length) return duration;
		duration = d;
		return redraw();
	};

	chart.data = function(d) {
		if (!arguments.length) return data;
		data = d;
		return redraw();
	};

	chart.gap = function(g) {
		if (!arguments.length) return gap;
		gap = g;
		return redraw();
	};

	chart.variant = function(v) {
		if (!arguments.length) return variant;
		variant = v;
		return redraw();
	};

	function resize() {
		chartW = w - margin[1] - margin[3];
		chartH = h - margin[0] - margin[2];
		this.attr("width", chartW)
			.attr("height", chartH)
			.attr("x", x+margin[3])
			.attr("y", y+margin[0]);
		return chart;
	}

	function redraw() {
		chart.tochart();		  
		return chart;
	};

	chart.tochart = function() {
		var yScale = d3.scale.linear().domain([0, d3.max(data)+h/100]).range([chartH, 0]);
		var xScale = d3.scale.linear().domain([0, data.length]).range([0, chartW]);
		var gapW = chartW/data.length*(gap/100);
		
		var markX = function(d, i) {return x+xScale(i)+margin[3]+gapW/2;};
		var markY = function(d, i) {return y+yScale(d)+margin[0];};
		var markW = chartW/data.length-gapW;
		var markH = function(d, i) {return chartH-yScale(d);};
		drawSVG(markX, markY, markW, markH);

		return chart;
	};

	function drawSVG(markX, markY, markW, markH) {
		var xScale = d3.scale.linear().domain([0, data.length]).range([0, chartW]);

		group.append("svg:g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + h + ")")
		.call(xScale);



	}

	return chart;
};

d3.chart.stacked = function () {

	var chart = {},
	data= [],
	label= [],
	parent,
	group,
	w, h,
	x, y,
	chartW, chartH,
	duration = 1000,
	margin = [20, 20, 20, 20],
	gap = 30
	variant = "standard";

	chart.parent = function(p) {
		if (!arguments.length) return parent.node();	   
		parent = d3.select(p);
		w = parent.node().clientWidth;
		h = parent.node().clientHeight;
		x = 0;
		y = 0;
		if(d3.ns.prefix.xhtml == parent.node().namespaceURI) {
			parent = parent.append("svg:svg")
				.attr("viewBox", "0 0 "+w+" "+h)
				.attr("preserveAspectRatio", "none");					  
		}
		group = parent.append("svg:g")
			.attr("class", "group")
			.attr("id", "group"+parent.node().childElementCount);
		group.append("svg:rect")
			.attr("class", "panel")
			.call(resize);
		return chart;
	};
	
	chart.margin = function(m) {
		if (!arguments.length) return margin;
		margin = m;
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};
	
	chart.size = function(s) {
		if (!arguments.length) return [w, h];
		w = s[0];
		h = s[1];
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};
	
	chart.position = function(p, other) {
		if (!arguments.length) return [x, y];
		if(typeof p == "string") {
			var otherPos = other.position();
			var otherSize = other.size();	
			if(p == "after") {
				x = otherPos[0]+otherSize[0];
				y = otherPos[1]+otherSize[1]-h;
			}else if(p == "under") {
				x = otherPos[0];
				y = otherPos[1]+otherSize[1];
			}
		}else{
			x = p[0];
			y = p[1];
		}
		group.select("rect.panel")
			.call(resize);
		return redraw();
	};
	
	chart.transition = function(d) {
		if (!arguments.length) return duration;
		duration = d;
		return redraw();
	};
	
	chart.data = function(d) {
		if (!arguments.length) return data;
		data = d;
		return redraw();
	};
   
	chart.gap = function(g) {
		if (!arguments.length) return gap;
		gap = g;
		return redraw();
	};
	
	chart.variant = function(v) {
		if (!arguments.length) return variant;
		variant = v;
		return redraw();
	};
	
	function resize() {
		chartW = w - margin[1] - margin[3];
		chartH = h - margin[0] - margin[2];
		this.attr("width", chartW)
			.attr("height", chartH)
			.attr("x", x+margin[3])
			.attr("y", y+margin[0]);
		return chart;
	}

	function redraw() {
		if(variant == "stacked") chart.toStackedBarchart();  
		else chart.toBarchart();		  
		return chart;
	};
	
	chart.toPCP = function() {
		var yScale = d3.scale.linear().domain([0, d3.max(data)+h/100]).range([chartH, 0]);
		
		var markX = x+margin[3];
		var markY = function(d, i) {return y+yScale(d)+margin[0];};
		var markW = 5;
		var markH = 5;
		drawSVG(markX, markY, markW, markH);
		return chart;
	};
	
	chart.toBarchart = function() {
		var yScale = d3.scale.linear().domain([0, d3.max(data)+h/100]).range([chartH, 0]);
		var xScale = d3.scale.linear().domain([0, data.length]).range([0, chartW]);
		var gapW = chartW/data.length*(gap/100);
		
		var markX = function(d, i) {return x+xScale(i)+margin[3]+gapW/2;};
		var markY = function(d, i) {return y+yScale(d)+margin[0];};
		var markW = chartW/data.length-gapW;
		var markH = function(d, i) {return chartH-yScale(d);};
		drawSVG(markX, markY, markW, markH);
		return chart;
	};
	
	chart.toStackedBarchart = function() {
		var yScale = d3.scale.linear().domain([0, d3.sum(data)+h/100]).range([chartH, 0]);		
		var stackH = [];
		var stackTopH = 0;
		for(var i=0; i<data.length; i++) {
			stackTopH += chartH-yScale(data[i]);
			stackH.push(stackTopH)
		}
		var gapW = chartW*(gap/100);
		
		var markX = x+margin[3]+gapW/2;
		var markY = function(d, i) {return y+h-stackH[i]-margin[2];};
		var markW = chartW-gapW;
		var markH = function(d, i) {return chartH-yScale(d);};
		drawSVG(markX, markY, markW, markH);
		return chart;
	};
	
	function drawSVG(markX, markY, markW, markH) {
		var marks = group.selectAll("rect.mark")
			.data(data);
			
			marks.enter().append("svg:rect")
				.attr("class", "mark")
				.attr("x", chartW)
				.attr("y", markY)
				.attr("width", 0)
				.attr("height", markH)
				.attr("opacity", 1);
				
			marks.transition()		   
				.attr("x", markX)
				.attr("y", markY)
				.attr("width", markW)
				.attr("height", markH);
				
			marks.exit()
				.transition()
				.duration(duration/5)
				.attr("opacity", 0)
				.remove();
	}

	return chart;
};
