if (typeof d3.chart != "object") d3.chart = {};

d3.chart.barchart = function (){

    var barchart = {},
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

    barchart.parent = function(p){
        if (!arguments.length) return parent.node();       
        parent = d3.select(p);
        w = parent.node().clientWidth;
        h = parent.node().clientHeight;
        x = 0;
        y = 0;
        if(d3.ns.prefix.xhtml == parent.node().namespaceURI){
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
        return barchart;
    };
    
    barchart.margin = function(m){
        if (!arguments.length) return margin;
        margin = m;
        group.select("rect.panel")
            .call(resize);
        return redraw();
    };
    
    barchart.size = function(s){
        if (!arguments.length) return [w, h];
        w = s[0];
        h = s[1];
        group.select("rect.panel")
            .call(resize);
        return redraw();
    };
    
    barchart.position = function(p, other){
        if (!arguments.length) return [x, y];
        if(typeof p == "string"){
            var otherPos = other.position();
            var otherSize = other.size();    
            if(p == "after"){
                x = otherPos[0]+otherSize[0];
                y = otherPos[1]+otherSize[1]-h;
            }else if(p == "under"){
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
    
    barchart.transition = function(d){
        if (!arguments.length) return duration;
        duration = d;
        return redraw();
    };
    
    barchart.data = function(d){
        if (!arguments.length) return data;
        data = d;
        return redraw();
    };
   
    barchart.gap = function(g){
        if (!arguments.length) return gap;
        gap = g;
        return redraw();
    };
    
    barchart.variant = function(v){
        if (!arguments.length) return variant;
        variant = v;
        return redraw();
    };
    
    function resize(){
        chartW = w - margin[1] - margin[3];
        chartH = h - margin[0] - margin[2];
        this.attr("width", chartW)
            .attr("height", chartH)
            .attr("x", x+margin[3])
            .attr("y", y+margin[0]);
        return barchart;
    }

    function redraw(){
        if(variant == "stacked") barchart.toStackedBarchart();  
        else barchart.toBarchart();          
        return barchart;
    };
    
    barchart.toPCP = function(){
        var yScale = d3.scale.linear().domain([0, d3.max(data)+h/100]).range([chartH, 0]);
        
        var markX = x+margin[3];
        var markY = function(d, i){return y+yScale(d)+margin[0];};
        var markW = 5;
        var markH = 5;
        drawSVG(markX, markY, markW, markH);
        return barchart;
    };
    
    barchart.toBarchart = function(){
        var yScale = d3.scale.linear().domain([0, d3.max(data)+h/100]).range([chartH, 0]);
        var xScale = d3.scale.linear().domain([0, data.length]).range([0, chartW]);
        var gapW = chartW/data.length*(gap/100);
        
        var markX = function(d, i){return x+xScale(i)+margin[3]+gapW/2;};
        var markY = function(d, i){return y+yScale(d)+margin[0];};
        var markW = chartW/data.length-gapW;
        var markH = function(d, i){return chartH-yScale(d);};
        drawSVG(markX, markY, markW, markH);
        return barchart;
    };
    
    barchart.toStackedBarchart = function(){
        var yScale = d3.scale.linear().domain([0, d3.sum(data)+h/100]).range([chartH, 0]);        
        var stackH = [];
        var stackTopH = 0;
        for(var i=0; i<data.length; i++){
            stackTopH += chartH-yScale(data[i]);
            stackH.push(stackTopH)
        }
        var gapW = chartW*(gap/100);
        
        var markX = x+margin[3]+gapW/2;
        var markY = function(d, i){return y+h-stackH[i]-margin[2];};
        var markW = chartW-gapW;
        var markH = function(d, i){return chartH-yScale(d);};
        drawSVG(markX, markY, markW, markH);
        return barchart;
    };
    
    function drawSVG(markX, markY, markW, markH){
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

return barchart;
};